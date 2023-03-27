<?php
namespace AHT\PaymentFee\Block\Wishlist;

class WishList extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
    public function showCategoryWishList(){
        $valueConfig = \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)
            ->getValue(
                'brand/showbrand/showbrand_enable',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            );
        return $valueConfig == 1 ? $this->collectionFactory->create()->addFieldToFilter('status', 1) : false;
    }
}


