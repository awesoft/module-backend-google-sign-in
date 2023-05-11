<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Api\Helper;

use Google\Service\Oauth2\Userinfo;
use Magento\Authorization\Model\Role;
use Magento\User\Model\User;

interface AdminUserInterface
{
    public const USER_ID_COLUMN = 'id';
    public const EMAIL_COLUMN = 'email';
    public const USERNAME_COLUMN = 'username';
    public const PASSWORD_LENGTH = 40;
    public const PAGE_SIZE = 1;

    public function findById(int $id): ?User;

    public function findByUserInfo(Userinfo $userinfo): ?User;

    public function findByUsername(string $username): ?User;

    public function findByEmail(string $email): ?User;

    public function createUser(Userinfo $userinfo, Role $role): int;
}
