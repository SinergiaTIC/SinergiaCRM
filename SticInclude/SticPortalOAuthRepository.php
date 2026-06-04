<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'SticInclude/SticPortalAuthUtils.php';

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\OAuth2\Entity\UserEntity;
use Api\V8\OAuth2\Entity\ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use Api\V8\OAuth2\Entity\AccessTokenEntity;
use Api\V8\OAuth2\Repository\ClientRepository;
use Api\V8\OAuth2\Repository\AccessTokenRepository;
use OAuth2Tokens;

// ── AuthCodeEntity ──────────────────────────────────
class SticPortalAuthCodeEntity implements AuthCodeEntityInterface
{
    use EntityTrait, TokenEntityTrait, AuthCodeTrait;
}

// ── PortalUserRepository ───────────────────────────
class SticPortalUserRepository implements UserRepositoryInterface
{
    private $beanManager;
    public function __construct(BeanManager $beanManager) { $this->beanManager = $beanManager; }
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $result = SticPortalAuthUtils::authenticate($username, $password, false, '');
        if (!$result['success']) throw new \InvalidArgumentException($result['error_code'] === 'locked' ? 'Account locked' : 'Invalid credentials');
        return new UserEntity('portal:' . $result['type'] . ':' . $result['bean']->id);
    }
}

// ── PortalPasswordGrant ────────────────────────────
class SticPortalPasswordGrant extends \League\OAuth2\Server\Grant\PasswordGrant
{
    public function getIdentifier() { return 'portal_password'; }
}

// ── PortalClientRepository ─────────────────────────
class SticPortalClientRepository implements ClientRepositoryInterface
{
    private $clientEntity;
    private $beanManager;
    private $parentRepo;
    public function __construct(ClientEntity $e, BeanManager $b) { $this->clientEntity = $e; $this->beanManager = $b; $this->parentRepo = new ClientRepository($e, $b); }
    public function getClientEntity($cid) { return $this->parentRepo->getClientEntity($cid); }
    public function validateClient($cid, $secret, $grant)
    {
        if ($grant === 'portal_password' || $grant === 'authorization_code') {
            $c = $this->beanManager->getBeanSafe(\OAuth2Clients::class, $cid);
            return hash('sha256', $secret) === $c->secret;
        }
        return $this->parentRepo->validateClient($cid, $secret, $grant);
    }
}

// ── PortalAccessTokenRepository ────────────────────
class SticPortalAccessTokenRepository implements AccessTokenRepositoryInterface
{
    private $parentRepo;
    private $beanManager;
    private $accessTokenEntity;
    public function __construct(AccessTokenEntity $e, BeanManager $b) { $this->accessTokenEntity = $e; $this->beanManager = $b; $this->parentRepo = new AccessTokenRepository($e, $b); }
    public function getNewToken(ClientEntityInterface $c, array $s, $u = null) { return $this->parentRepo->getNewToken($c, $s, $u); }
    public function persistNewAccessToken(AccessTokenEntityInterface $at)
    {
        $this->parentRepo->persistNewAccessToken($at);
        $uid = $at->getUserIdentifier();
        if ($uid && strpos($uid, 'portal:') === 0) {
            $parts = explode(':', $uid);
            $portalType = count($parts) >= 2 ? $parts[1] : null;
            if ($portalType) {
                $token = $this->beanManager->newBeanSafe(OAuth2Tokens::class);
                $token->retrieve_by_string_fields(['access_token' => $at->getIdentifier()]);
                if ($token->id) {
                    $GLOBALS['db']->query("UPDATE oauth2tokens SET portal_type=" . $GLOBALS['db']->quoted($portalType) . " WHERE id=" . $GLOBALS['db']->quoted($token->id));
                }
            }
        }
    }
    public function revokeAccessToken($tid) { return $this->parentRepo->revokeAccessToken($tid); }
    public function isAccessTokenRevoked($tid) { return $this->parentRepo->isAccessTokenRevoked($tid); }
}

// ── PortalAuthCodeRepository ───────────────────────
class SticPortalAuthCodeRepository implements AuthCodeRepositoryInterface
{
    public function getNewAuthCode(): AuthCodeEntityInterface
    {
        return new \SticPortalAuthCodeEntity();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        global $db;
        $now = date('Y-m-d H:i:s');
        $expires = $authCodeEntity->getExpiryDateTime()->format('Y-m-d H:i:s');
        $userIdentifier = $authCodeEntity->getUserIdentifier();
        $portalType = null;
        $portalId = null;
        if ($userIdentifier && strpos($userIdentifier, 'portal:') === 0) {
            $parts = explode(':', $userIdentifier);
            $portalType = $parts[1] ?? null;
            $portalId = $parts[2] ?? null;
        }
        $db->query("INSERT INTO stic_portal_auth_codes (id, auth_code, client_id, portal_id, portal_type, redirect_uri, expires_at, date_entered, date_modified, deleted) VALUES ("
            . $db->quoted(create_guid()) . ", "
            . $db->quoted($authCodeEntity->getIdentifier()) . ", "
            . $db->quoted($authCodeEntity->getClient()->getIdentifier()) . ", "
            . $db->quoted($portalId ?? '') . ", "
            . $db->quoted($portalType ?? '') . ", "
            . $db->quoted('') . ", "
            . $db->quoted($expires) . ", "
            . $db->quoted($now) . ", "
            . $db->quoted($now) . ", 0)"
        );
    }

    public function revokeAuthCode($codeId): void
    {
        global $db;
        $db->query("UPDATE stic_portal_auth_codes SET is_revoked=1 WHERE auth_code=" . $db->quoted($codeId));
    }

    public function isAuthCodeRevoked($codeId): bool
    {
        global $db;
        $row = $db->fetchByAssoc($db->limitQuery("SELECT is_revoked, expires_at FROM stic_portal_auth_codes WHERE auth_code=" . $db->quoted($codeId) . " AND deleted=0", 0, 1));
        if (!$row) return true;
        if ($row['is_revoked'] == '1') return true;
        if (strtotime($row['expires_at']) < time()) return true;
        return false;
    }
}

// ── Helper for portal login page ───────────────────
class SticPortalAuthCodeGenerator
{
    public static function generateAndRedirect($portalId, $portalType, $clientId, $redirectUri, $state = '')
    {
        global $db;
        $code = bin2hex(random_bytes(32));
        $now = date('Y-m-d H:i:s');
        $expires = date('Y-m-d H:i:s', time() + 600);
        $db->query("INSERT INTO stic_portal_auth_codes (id, auth_code, client_id, portal_id, portal_type, redirect_uri, expires_at, date_entered, date_modified, deleted) VALUES ("
            . $db->quoted(create_guid()) . ", "
            . $db->quoted($code) . ", "
            . $db->quoted($clientId) . ", "
            . $db->quoted($portalId) . ", "
            . $db->quoted($portalType) . ", "
            . $db->quoted($redirectUri) . ", "
            . $db->quoted($expires) . ", "
            . $db->quoted($now) . ", "
            . $db->quoted($now) . ", 0)"
        );
        $sep = (strpos($redirectUri, '?') === false) ? '?' : '&';
        $url = $redirectUri . $sep . 'code=' . urlencode($code) . (!empty($state) ? '&state=' . urlencode($state) : '');
        header('Location: ' . $url);
        exit;
    }
}
