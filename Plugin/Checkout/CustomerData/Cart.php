<?php

namespace AHT\PaymentFee\Plugin\Checkout\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;

class Cart implements SectionSourceInterface
{

    public $scopeConfig;


    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getSectionData()
    {
        $valueFromConfig = $this->scopeConfig->getValue(
            'carriers/freeshipping/free_shipping_subtotal',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
        );
        $parent = $this->getParentBlock();
        $subtotal = $parent->getSubtotal();
        if ($valueFromConfig > $subtotal) {
            $feeToShip = $valueFromConfig - $subtotal;
        } else {
            $feeToShip = 0;
        }
        $additionalVariables['fee_toship'] = $feeToShip;
        return $additionalVariables;
    }
}
