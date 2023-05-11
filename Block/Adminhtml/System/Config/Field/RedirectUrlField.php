<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Block\Adminhtml\System\Config\Field;

use Awesoft\GoogleSignIn\Api\Helper\ConfigInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class RedirectUrlField extends Field
{
    public function __construct(
        private readonly ConfigInterface $config,
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        parent::__construct($context, $data, $secureRenderer);
    }

    protected function _getElementHtml(AbstractElement $element): string
    {
        $redirectUrl = $this->config->getRedirectUrl();

        return '<a target="_blank" href="' . $redirectUrl . '">' . $redirectUrl . '</a>';
    }
}
