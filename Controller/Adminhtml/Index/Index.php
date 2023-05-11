<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Controller\Adminhtml\Index;

use Awesoft\GoogleSignIn\Api\Controller\Adminhtml\RedirectPathInterface;
use Awesoft\GoogleSignIn\Api\Service\AuthenticationManagerInterface;
use Magento\Backend\Model\Auth;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;

class Index implements HttpGetActionInterface
{
    public function __construct(
        private readonly AuthenticationManagerInterface $authenticationManager,
        private readonly RedirectFactory $redirectFactory,
        private readonly Auth $auth,
    ) {
    }

    public function execute(): Redirect
    {
        return $this->redirectFactory
            ->create()
            ->setPath(
                $this->auth->isLoggedIn()
                    ? RedirectPathInterface::DASHBOARD
                    : $this->authenticationManager->getUrl()
            );
    }
}
