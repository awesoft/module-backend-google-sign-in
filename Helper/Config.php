<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Helper;

use Awesoft\GoogleSignIn\Api\Helper\ConfigInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Config implements ConfigInterface
{

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly SerializerInterface $serializer,
        private readonly EncryptorInterface $encryptor,
        private readonly UrlInterface $url,
    ) {
    }

    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_ENABLED);
    }

    public function getClientId(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_CLIENT_ID);
    }

    public function getClientSecret(): string
    {
        return $this->encryptor->decrypt(
            (string)$this->scopeConfig->getValue(self::XML_PATH_CLIENT_SECRET)
        );
    }

    public function getHostedDomains(): array
    {
        $domains = [];
        $hostedDomains = $this->serializer->unserialize(
            $this->scopeConfig->getValue(self::XML_PATH_HOSTED_DOMAINS)
        );

        foreach ($hostedDomains as $hostedDomain) {
            $domains[] = $hostedDomain[self::DOMAIN_COLUMN] ?? null;
        }

        return array_filter($domains);
    }

    public function getRedirectUrl(): string
    {
        return $this->url->turnOffSecretKey()->getRouteUrl(self::VERIFY_ROUTE);
    }

    public function isUserCreationEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_USER_CREATION);
    }

    public function getUserRole(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_USER_ROLE);
    }
}
