<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Api\Service;

use Magento\Framework\Exception\AuthenticationException;

interface ExceptionManagerInterface
{
    public const NO_PERMISSION_ERROR = 'More permissions are needed to access this.';
    public const SECURITY_ERROR = 'Invalid security or form key. Please refresh the page.';
    public const AUTHENTICATION_ERROR = 'An authentication error occurred. Verify and try again.';
    public const INCORRECT_ACCOUNT_ERROR = 'The account sign-in was incorrect or your account is disabled temporarily.'
    . ' Please wait and try again later.';

    public function createAuthenticationException(): AuthenticationException;

    public function createIncorrectAccountAuthenticationException(): AuthenticationException;

    public function createSecurityAuthenticationException(): AuthenticationException;

    public function createNoPermissionAuthenticationException(): AuthenticationException;
}
