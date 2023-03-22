<?php

namespace AHT\PaymentFee\Block\Sales;

use Magento\Sales\Model\Order;
use AHT\PaymentFee\Helper\Data;

class Totals extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Order
     */

    protected $order;
    /**
     * @var \Magento\Framework\DataObject
     */
    protected $source;

    public $scopeConfig;

    protected $itemFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\Order\ItemFactory $itemFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->itemFactory = $itemFactory;
        parent::__construct($context, $data);
    }

    public function getSource()
    {
        return $this->source;
    }

    public function displayFullSummary()
    {
        return true;
    }

    public function initTotals()
    {
        $valueConFigFeeName = $this->scopeConfig->getValue(
            'paymentfee/config/fee_name',
            \Magento\Store\Model\ScopeInterface::SCOPESTORE,
        );
        $parent = $this->getParentBlock();
        $this->order = $parent->getOrder();
        $this->source = $parent->getSource();
        $store = $this->getStore();


        $customAmount = new \Magento\Framework\DataObject(
            [
                'code' => 'fee_amount',
                'strong' => false,
                'value' => $this->order->getFeeAmount(),
                'label' => __($valueConFigFeeName),
            ]
        );
        $parent->addTotal($customAmount, 'fee_amount');
        return $this;
    }

    /**
     * Get order store object
     *
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        return $this->order->getStore();
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }
}


