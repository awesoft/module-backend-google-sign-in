<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Block\Adminhtml\System\Config\Field;

use Awesoft\GoogleSignIn\Api\Helper\ConfigInterface;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class HostedDomainsField extends AbstractFieldArray
{
    protected function _prepareToRender(): void
    {
        $this->addColumn(ConfigInterface::DOMAIN_COLUMN, ['label' => __('Domain')]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
