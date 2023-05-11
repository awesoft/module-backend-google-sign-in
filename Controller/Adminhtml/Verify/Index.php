<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Controller\Adminhtml\Verify;

use Awesoft\GoogleSignIn\Api\Controller\Adminhtml\RedirectPathInterface;
use Awesoft\GoogleSignIn\Api\Service\AuthenticationManagerInterface;
use Awesoft\GoogleSignIn\Api\Service\ExceptionManagerInterface;
use Magento\Backend\Model\Auth;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class Index implements HttpGetActionInterface
{
    private const QUERY_KEY_STATE = 'state';
    private const QUERY_KEY_CODE = 'code';
    private const HEADERS = [
        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        'Pragma' => 'no-cache',
    ];

    public function __construct(
        private readonly AuthenticationManagerInterface $authenticationManager,
        private readonly RedirectFactory $redirectFactory,
        private readonly ManagerInterface $manager,
        private readonly RequestInterface $request,
        private readonly LoggerInterface $logger,
        private readonly Auth $auth,
    ) {
    }

    public function execute(): Redirect
    {
        $redirect = $this->redirectFactory->create();
        foreach (self::HEADERS as $key => $value) {
            $redirect->setHeader($key, $value, true);
        }

        if ($this->auth->isLoggedIn()) {
            return $redirect->setPath(RedirectPathInterface::DASHBOARD);
        }

        $code = (string)$this->request->getParam(self::QUERY_KEY_CODE);
        $state = (string)$this->request->getParam(self::QUERY_KEY_STATE);

        try {
            $this->authenticationManager->authenticate($code, $state);
        } catch (AuthenticationException $exception) {
            $this->manager->addErrorMessage($exception->getMessage());

            return $redirect->setPath(RedirectPathInterface::INDEX);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
            $this->manager->addErrorMessage(__(ExceptionManagerInterface::AUTHENTICATION_ERROR));

            return $redirect->setPath(RedirectPathInterface::INDEX);
        }

        return $redirect->setPath(RedirectPathInterface::DASHBOARD);
    }
}
