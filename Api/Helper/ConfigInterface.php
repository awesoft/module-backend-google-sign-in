<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Api\Helper;

interface ConfigInterface
{
    public const XML_PATH_ENABLED = 'awesoft/google_signin/enabled';
    public const XML_PATH_CLIENT_ID = 'awesoft/google_signin/client_id';
    public const XML_PATH_CLIENT_SECRET = 'awesoft/google_signin/client_secret';
    public const XML_PATH_HOSTED_DOMAINS = 'awesoft/google_signin/hosted_domains';
    public const XML_PATH_USER_CREATION = 'awesoft/google_signin/enable_user_creation';
    public const XML_PATH_USER_ROLE = 'awesoft/google_signin/user_role';

    public const DOMAIN_COLUMN = 'domain';
    public const INDEX_ROUTE = 'awesoft_google_signin';
    public const VERIFY_ROUTE = 'awesoft_google_signin/verify';

    public function isEnabled(): bool;

    public function getClientId(): string;

    public function getClientSecret(): string;

    public function getRedirectUrl(): string;

    public function getHostedDomains(): array;

    public function isUserCreationEnabled(): bool;

    public function getUserRole(): int;
}
