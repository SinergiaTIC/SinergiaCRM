<?php
use Api\V8\BeanDecorator\BeanManager;
use Api\V8\OAuth2\Entity\AccessTokenEntity;
use Api\V8\OAuth2\Entity\ClientEntity;
use Api\V8\OAuth2\Repository\PortalAccessTokenRepository;
use Api\V8\OAuth2\Repository\PortalClientRepository;
use Api\V8\OAuth2\Repository\RefreshTokenRepository;
use Api\V8\OAuth2\Repository\ScopeRepository;
use Api\V8\OAuth2\Repository\UserRepository;
use Api\V8\OAuth2\Repository\PortalUserRepository;
use Api\V8\OAuth2\Grant\PortalPasswordGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use Psr\Container\ContainerInterface as Container;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Api\Core\Config\ApiConfig;
use Api\V8\Helper\OsHelper;
use League\OAuth2\Server\CryptKey;

return [
    AuthorizationServer::class => static function (Container $container) {
        $baseDir = $GLOBALS['BASE_DIR'];
        $shouldCheckPermissions = OsHelper::getOS() !== OsHelper::OS_WINDOWS;
        $oauth2EncKey = $GLOBALS['sugar_config']['oauth2_encryption_key'] ?? 'SCRM-DEFK';
        $server = new AuthorizationServer(
            new PortalClientRepository(new ClientEntity(), $container->get(BeanManager::class)),
            new PortalAccessTokenRepository(new AccessTokenEntity(), $container->get(BeanManager::class)),
            new ScopeRepository(),
            new CryptKey(sprintf('file://%s/%s', $baseDir, ApiConfig::OAUTH2_PRIVATE_KEY), null, $shouldCheckPermissions),
            $oauth2EncKey
        );
        $server->enableGrantType(new ClientCredentialsGrant(), new DateInterval('PT1H'));
        $server->enableGrantType(
            new PasswordGrant(new UserRepository($container->get(BeanManager::class)), new RefreshTokenRepository($container->get(BeanManager::class))),
            new DateInterval('PT1H')
        );
        $server->enableGrantType(
            new PortalPasswordGrant(new PortalUserRepository($container->get(BeanManager::class)), new RefreshTokenRepository($container->get(BeanManager::class))),
            new DateInterval('PT1H')
        );
        $refreshGrant = new RefreshTokenGrant(new RefreshTokenRepository($container->get(BeanManager::class)));
        $refreshGrant->setRefreshTokenTTL(new DateInterval('P1M'));
        $server->enableGrantType($refreshGrant, new DateInterval('PT1H'));
        return $server;
    },
];
