<?php
namespace AHT\PaymentFee\Block\Cart;

class Sidebar extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \AHT\PaymentFee\Helper\Data
     */
    private $helper;

    /**
     * Sidebar constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \AHT\PaymentFee\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \AHT\PaymentFee\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    public function getConfigForShippingBar()
    {
        return $this->helper->getPriceForShippingBar();
    }
}
