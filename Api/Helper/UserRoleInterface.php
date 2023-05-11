<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Api\Helper;

use Magento\Authorization\Model\Role;

interface UserRoleInterface
{
    public function getRole(): Role;

    public function getGroupOptions(): array;
}
