<?php

declare(strict_types=1);

namespace AHT\PaymentFee\Model\Quote\Address\Total;

use AHT\PaymentFee\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\Phrase;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\QuoteValidator;
use Psr\Log\LoggerInterface;

class Fee extends AbstractTotal
{
    public $scopeConfig;
    /**
     * @var string
     */
    protected $code = 'fee';
    /**
     * @var Data
     */
    protected $helperData;
    /**
     * @var Session
     */
    protected $checkoutSession;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Collect grand total address amount
     *
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    protected $quoteValidator = null;

    /**
     * Payment Fee constructor.
     * @param QuoteValidator $quoteValidator
     * @param Session $checkoutSession
     * @param PaymentInterface $payment
     * @param Data $helperData
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(
        QuoteValidator $quoteValidator,
        Session $checkoutSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        PaymentInterface $payment,
        Data $helperData,
        LoggerInterface $loggerInterface
    ) {
        $this->quoteValidator = $quoteValidator;
        $this->helperData = $helperData;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $loggerInterface;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Collect totals process.
     *
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return Fee|int
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $valueFromConfig = $this->scopeConfig->getValue(
            'paymentfee/config/fee_extra',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,

        );

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $fee = $this->scopeConfig->getValue(
            'paymentfee/config/fee',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
        );
        if ($valueFromConfig < $total->getData('subtotal')) {
            if ($this->helperData->canApply($quote)) {
                $fee = $this->helperData->getFee($quote);
            }
            $total->setFeeAmount($fee);
            $total->setBaseFeeAmount($fee);

            $total->setTotalAmount('fee_amount', $fee);
            $total->setBaseTotalAmount('base_fee_amount', $fee);

            $total->setGrandTotal($total->getGrandTotal());
            $total->setBaseGrandTotal($total->getBaseGrandTotal());

            // Make sure that quote is also updated

            $quote->setFeeAmount($fee);
            $quote->setBaseFeeAmount($fee);

            return $this;
        }
        return false;
    }
    protected function clearValues($total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }

    /**
     * Assign subtotal amount and label to address object
     *
     * @param Quote $quote
     * @param Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(
        Quote $quote,
        Total $total
    ) {
        $result = [
            'code' => $this->getCode(),
            'title' => __('Payment Fee'),
            'value' => $total->getFeeAmount()
        ];
        return $result;
    }

    /**
     * Get Subtotal label
     *
     * @return Phrase
     */
    public function getLabel()
    {
        return __('Payment Fee');
    }
}
