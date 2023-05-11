<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Helper;

use Awesoft\GoogleSignIn\Api\Helper\ConfigInterface;
use Awesoft\GoogleSignIn\Api\Helper\UserRoleInterface;
use Awesoft\GoogleSignIn\Api\Service\ExceptionManagerInterface;
use Magento\Authorization\Model\Acl\Role\Group;
use Magento\Authorization\Model\ResourceModel\Role\CollectionFactory;
use Magento\Authorization\Model\Role;
use Magento\Framework\Exception\AuthenticationException;
use Psr\Log\LoggerInterface;

class UserRole implements UserRoleInterface
{
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly LoggerInterface $logger,
        private readonly ConfigInterface $config,
    ) {
    }

    /** @throws AuthenticationException */
    public function getRole(): Role
    {
        /** @var Role $role */
        $role = $this->collectionFactory
            ->create()
            ->addFieldToFilter('role_id', $this->config->getUserRole())
            ->setPageSize(1)
            ->getFirstItem();

        if ($role->isEmpty()) {
            $this->logger->error('Admin user role not found', ['roleId' => $this->config->getUserRole()]);
            throw new AuthenticationException(__(ExceptionManagerInterface::NO_PERMISSION_ERROR));
        }

        return $role;
    }

    public function getGroupOptions(): array
    {
        return $this->collectionFactory
            ->create()
            ->addFieldToFilter('role_type', Group::ROLE_TYPE)
            ->toOptionArray();
    }
}
