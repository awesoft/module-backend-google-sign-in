<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Model\Config\Source;

use Awesoft\GoogleSignIn\Api\Helper\UserRoleInterface;
use Magento\Framework\Data\OptionSourceInterface;

class UserRole implements OptionSourceInterface
{
    public function __construct(private readonly UserRoleInterface $userRole)
    {
    }

    public function toOptionArray(): array
    {
        return $this->userRole->getGroupOptions();
    }
}
