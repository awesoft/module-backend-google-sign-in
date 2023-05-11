<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Helper;

use Awesoft\GoogleSignIn\Api\Helper\AdminUserInterface;
use Google\Service\Oauth2\Userinfo;
use Magento\Authorization\Model\Role;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;
use Magento\User\Model\User;
use Magento\User\Model\ResourceModel\User as UserResource;
use Magento\User\Model\ResourceModel\User\CollectionFactory;
use Magento\User\Model\UserFactory;

class AdminUser implements AdminUserInterface
{
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly UserResource $userResource,
        private readonly UserFactory $userFactory,
        private readonly Random $random,
    ) {
    }

    public function findByUserInfo(Userinfo $userinfo): ?User
    {
        $user = $this->findByUsername($userinfo->getEmail());
        if ($user) {
            return $user;
        }

        return $this->findByUsername($userinfo->getEmail());
    }

    public function findByUsername(string $username): ?User
    {
        /** @var User $user */
        $user = $this->collectionFactory->create()
            ->addFieldToFilter(self::USERNAME_COLUMN, $username)
            ->setPageSize(self::PAGE_SIZE)
            ->getFirstItem();

        return $user->isEmpty() ? null : $user;
    }

    public function findByEmail(string $email): ?User
    {
        /** @var User $user */
        $user = $this->collectionFactory->create()
            ->addFieldToFilter(self::EMAIL_COLUMN, $email)
            ->setPageSize(self::PAGE_SIZE)
            ->getFirstItem();

        return $user->isEmpty() ? null : $user;
    }

    /** @throws LocalizedException */
    public function createUser(Userinfo $userinfo, Role $role): int
    {
        $user = $this->userFactory->create();
        $user->setData([
            'is_active' => true,
            'role_id' => $role->getId(),
            'email' => $userinfo->getEmail(),
            'username' => $userinfo->getEmail(),
            'firstname' => $userinfo->getGivenName(),
            'lastname' => $userinfo->getFamilyName(),
            'password' => $this->random->getRandomString(self::PASSWORD_LENGTH),
        ]);

        $this->userResource->save($user);
        $this->userResource->recordLogin($user);

        return $user->getId();
    }

    public function findById(int $id): ?User
    {
        /** @var User $user */
        $user = $this->collectionFactory->create()
            ->addFieldToFilter(self::USER_ID_COLUMN, $id)
            ->setPageSize(self::PAGE_SIZE)
            ->getFirstItem();

        return $user->isEmpty() ? null : $user;
    }
}
