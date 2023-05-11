<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Block\Adminhtml\Button;

use Awesoft\GoogleSignIn\Api\Helper\ConfigInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class SignIn extends Template
{
    private const VIEW_FILE_URL = 'Awesoft_GoogleSignIn::images/btn-google-signin.png';

    public function __construct(
        private readonly ConfigInterface $config,
        private readonly UrlInterface $url,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }

    public function getLoginUrl(): string
    {
        return $this->url->turnOffSecretKey()->getRouteUrl(ConfigInterface::INDEX_ROUTE);
    }

    public function getImageUrl(): string
    {
        return $this->getViewFileUrl(self::VIEW_FILE_URL);
    }
}
