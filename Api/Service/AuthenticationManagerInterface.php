<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Api\Service;

use Google\Service\Oauth2\Userinfo;

interface AuthenticationManagerInterface
{
    public function getUrl(): string;

    public function getUserinfo(string $code): Userinfo;

    public function authenticate(string $code, string $state): void;
}
