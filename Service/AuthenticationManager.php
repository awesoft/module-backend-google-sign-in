<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Service;

use Awesoft\GoogleSignIn\Api\Helper\AdminUserInterface;
use Awesoft\GoogleSignIn\Api\Helper\ConfigInterface;
use Awesoft\GoogleSignIn\Api\Helper\StateInterface;
use Awesoft\GoogleSignIn\Api\Helper\UserRoleInterface;
use Awesoft\GoogleSignIn\Api\Service\AuthenticationManagerInterface;
use Awesoft\GoogleSignIn\Api\Service\ExceptionManagerInterface;
use Google\Client;
use Google\Service\Oauth2;
use Google\Service\Oauth2\Userinfo;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\AuthenticationException;
use Psr\Log\LoggerInterface;

class AuthenticationManager implements AuthenticationManagerInterface
{
    private const SCOPES = ['email', 'profile', 'openid'];

    private Client $client;

    public function __construct(
        private readonly ExceptionManagerInterface $exceptionManager,
        private readonly AdminUserInterface $adminUser,
        private readonly UserRoleInterface $userRole,
        private readonly ConfigInterface $config,
        private readonly LoggerInterface $logger,
        private readonly StateInterface $state,
        private readonly Session $session,
    ) {
        $this->client = new Client();
        $this->client->setClientId($this->config->getClientId());
        $this->client->setClientSecret($this->config->getClientSecret());
        $this->client->setRedirectUri($this->config->getRedirectUrl());
    }

    public function getUrl(): string
    {
        $state = $this->state->create();
        $this->client->setState($state);

        return $this->client->createAuthUrl(self::SCOPES);
    }

    /** @throws AuthenticationException */
    public function authenticate(string $code, string $state): void
    {
        $this->state->validate($state);
        $userInfo = $this->getUserinfo($code);
        $user = $this->adminUser->findByUserInfo($userInfo);

        if (empty($user) && $this->config->isUserCreationEnabled()) {
            $role = $this->userRole->getRole();
            $userId = $this->adminUser->createUser($userInfo, $role);
            $user = $this->adminUser->findById($userId);
        }

        if (empty($user)) {
            $this->logger->error('Admin user not found', ['email' => $userInfo->getEmail()]);
            throw $this->exceptionManager->createIncorrectAccountAuthenticationException();
        }

        $this->session->setUser($user);
        $this->session->processLogin();
    }

    /** @throws AuthenticationException */
    public function getUserinfo(string $code): Userinfo
    {
        $this->client->fetchAccessTokenWithAuthCode($code);
        $userInfo = (new Oauth2($this->client))->userinfo_v2_me->get();

        if ($userInfo instanceof Userinfo === false) {
            $this->logger->error('Google user info fetch failed');
            throw $this->exceptionManager->createIncorrectAccountAuthenticationException();
        }

        $hostedDomains = $this->config->getHostedDomains();
        if ($hostedDomains && in_array($userInfo->getHd(), $hostedDomains) === false) {
            $this->logger->error('Hosted domain not allowed', ['hd' => $userInfo->getHd()]);
            throw $this->exceptionManager->createNoPermissionAuthenticationException();
        }

        return $userInfo;
    }
}
