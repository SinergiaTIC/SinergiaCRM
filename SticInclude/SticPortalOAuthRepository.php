<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'SticInclude/SticPortalAuthUtils.php';

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\OAuth2\Entity\UserEntity;
use Api\V8\OAuth2\Entity\ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use Api\V8\OAuth2\Entity\AccessTokenEntity;
use Api\V8\OAuth2\Repository\ClientRepository;
use Api\V8\OAuth2\Repository\AccessTokenRepository;
use OAuth2Tokens;

// PortalUserRepository - authenticates Contacts/Accounts
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

// PortalPasswordGrant - custom grant with identifier 'portal_password'
class SticPortalPasswordGrant extends \League\OAuth2\Server\Grant\PasswordGrant
{
    public function getIdentifier() { return 'portal_password'; }
}

// PortalClientRepository - wraps ClientRepository, accepts portal_password grant
class SticPortalClientRepository implements ClientRepositoryInterface
{
    private $clientEntity;
    private $beanManager;
    private $parentRepo;
    public function __construct(ClientEntity $e, BeanManager $b) { $this->clientEntity = $e; $this->beanManager = $b; $this->parentRepo = new ClientRepository($e, $b); }
    public function getClientEntity($cid) { return $this->parentRepo->getClientEntity($cid); }
    public function validateClient($cid, $secret, $grant)
    {
        if ($grant === 'portal_password') { $c = $this->beanManager->getBeanSafe(\OAuth2Clients::class, $cid); return hash('sha256', $secret) === $c->secret; }
        return $this->parentRepo->validateClient($cid, $secret, $grant);
    }
}

// PortalAccessTokenRepository - wraps AccessTokenRepository, stores portal_type
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
