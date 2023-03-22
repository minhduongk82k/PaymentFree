<?php

namespace AHT\PaymentFee\Model;

use Magento\Checkout\Model\ConfigProviderInterface;


class AdditionalConfigVars implements ConfigProviderInterface
{
    public $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfig()
    {
        $valueFromConfig = $this->scopeConfig->getValue(
            'paymentfee/config/fee_name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
        );

        $additionalVariables['fee_name'] = $valueFromConfig;
        return $additionalVariables;
    }
}
