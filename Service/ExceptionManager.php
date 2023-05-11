<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Service;

use Awesoft\GoogleSignIn\Api\Service\ExceptionManagerInterface;
use Magento\Framework\Exception\AuthenticationException;

class ExceptionManager implements ExceptionManagerInterface
{
    public function createIncorrectAccountAuthenticationException(): AuthenticationException
    {
        return new AuthenticationException(__(ExceptionManagerInterface::INCORRECT_ACCOUNT_ERROR));
    }

    public function createAuthenticationException(): AuthenticationException
    {
        return new AuthenticationException(__(ExceptionManagerInterface::AUTHENTICATION_ERROR));
    }

    public function createSecurityAuthenticationException(): AuthenticationException
    {
        return new AuthenticationException(__(ExceptionManagerInterface::SECURITY_ERROR));
    }

    public function createNoPermissionAuthenticationException(): AuthenticationException
    {
        return new AuthenticationException(__(ExceptionManagerInterface::NO_PERMISSION_ERROR));
    }
}
