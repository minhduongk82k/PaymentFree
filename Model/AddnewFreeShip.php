<?php

namespace AHT\PaymentFee\Model;

use Magento\Checkout\Model\ConfigProviderInterface;


class AddnewFreeShip implements ConfigProviderInterface
{
    public $scopeConfig;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfig()
    {
        $valueFromConfigFee = $this->scopeConfig->getValue(
            'carriers/freeshipping/free_shipping_subtotal',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
        );
        $quote = $this->checkoutSession->getQuote();
        $totals = $quote->getTotals();
        $subtotalAmount = $totals['subtotal']->getValue();
        if ($valueFromConfigFee > $subtotalAmount) {
            $feeToShip = $valueFromConfigFee - $subtotalAmount;
        } else {
            $feeToShip = 0;
        }
        $result['fee_toship'] = $feeToShip;
        return $result;
    }
}
