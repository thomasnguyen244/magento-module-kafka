<?php

namespace Thomas\Kafka\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var ScopeConfigInterface
     */
    protected $_config;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->_config = $context->getScopeConfig();

        parent::__construct($context);
    }

    /**
     * Is enabled ?
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->_config->isSetFlag('thomas/connection/enabled', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getConnectionSettings()
    {
        return $this->_config->getValue('thomas/connection', ScopeInterface::SCOPE_STORE);
    }

    /**
     * Is cron startup enabled?
     *
     * @return bool
     */
    public function isConsumersCronEnabled()
    {
        return $this->_config->isSetFlag('thomas/consumers/cron_enabled', ScopeInterface::SCOPE_STORE);
    }
}
