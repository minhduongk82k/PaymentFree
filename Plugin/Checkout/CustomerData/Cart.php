<?php

namespace AHT\PaymentFee\Plugin\Checkout\CustomerData;

use Magento\Checkout\Model\Session;

class Cart
{

    public $scopeConfig;


    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
    }

    public function aftergetSectionData(\Magento\Checkout\CustomerData\Cart $subject, $result)
    {
        $valueFromConfig = $this->scopeConfig->getValue(
            'carriers/freeshipping/free_shipping_subtotal',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
        );
        $quote = $this->checkoutSession->getQuote();
        $totals = $quote->getTotals();
        $subtotalAmount = $totals['subtotal']->getValue();
        if ($valueFromConfig < $subtotalAmount) {
            $feeToShip = $valueFromConfig - $subtotalAmount;
        } else {
            $feeToShip = 0;
        }
        $result['fee_toship'] = $feeToShip;
        return $result;
    }
}
