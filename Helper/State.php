<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Helper;

use Awesoft\GoogleSignIn\Api\Helper\StateInterface;
use Awesoft\GoogleSignIn\Api\Service\ExceptionManagerInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Math\Random;
use Psr\Log\LoggerInterface;

class State implements StateInterface
{
    private const LENGTH = 20;
    private const KEY = 'awesoft.google_sign.state';
    
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Session $session,
        private readonly Random $random,
    ) {
    }

    /**
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function create(): string
    {
        $state = $this->random->getRandomString(self::LENGTH);
        $this->session->setData(self::KEY, $state);

        return $state;
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function get(): string
    {
        $state = $this->session->getData(self::KEY);
        $this->session->unsData(self::KEY);

        return $state;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function validate(string $state): void
    {
        if ($this->get() !== $state) {
            $this->logger->error('Google sign-in authentication state validation failed', ['state' => $state]);
            throw new AuthenticationException(__(ExceptionManagerInterface::SECURITY_ERROR));
        }
    }
}
