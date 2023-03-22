<?php declare(strict_types=1);

namespace AHT\PaymentFee\Helper;

use InvalidArgumentException;
use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Unserialize\Unserialize;
use Magento\Quote\Model\Quote;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * Recipient fixed amount of custom payment config path
     */
    const CONFIG_PAYMENT_FEE = 'paymentfee/config/';
    /**
     * Total Code
     */
    const PRICE_SHIPPING_BAR = 'carriers/freeshipping/free_shipping_subtotal';
    const TOTAL_CODE = 'fee_amount';
    /**
     * @var array
     */
    public $methodFee = null;
    /**
     * Constructor
     */

    /**
     * @var SerializerInterface
     */
    protected $serializer;
    /**
     * @var Data
     */
    protected $pricingHelper;
    /**
     * @var PriceCurrency
     */
    protected $priceCurrency;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Data constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param PriceCurrency $priceCurrency
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        PriceCurrency $priceCurrency
    ) {
        parent::__construct($context);
        if (interface_exists(SerializerInterface::class)) {
            // >= Magento 2.2
            $this->serializer = $objectManager->get(SerializerInterface::class);
        } else {
            // < Magento 2.2
            $this->serializer = $objectManager->get(Unserialize::class);
        }
        $this->scopeConfig = $scopeConfig;
        $this->pricingHelper = $pricingHelper;
        $this->priceCurrency = $priceCurrency;
        $this->logger = $context->getLogger();
    }

    /**
     * Retrieve Payment Method Fees from Store Config
     * @return array
     */

    /**
     * Retrieve Store Config
     * @param string $field
     * @return mixed|null
     */
    public function getConfig($field = '')
    {
        if ($field) {
            $storeScope = ScopeInterface::SCOPE_STORE;
            return $this->scopeConfig->getValue(self::CONFIG_PAYMENT_FEE . $field, $storeScope);
        }
        return null;
    }

    /**
     * Check if Extension is Enabled config
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getConfig('enabled');
    }
    /**
     * @param Quote $quote
     * @return bool
     */
    public function canApply(Quote $quote)
    {

        /**@TODO check module or config**/
        if ($this->isEnabled()) {
            if ($method = $quote->getPayment()->getMethod()) {
                    return true;
            }
        }
        return false;
    }

    /**
     * @param Quote $quote
     * @return float|int
     */
    public function getFee(Quote $quote)
    {
        $method  = $quote->getPayment();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $yourvalue = $this->scopeConfig->getValue('paymentfee/config/fee', $storeScope);
        $fee = $this->priceCurrency->round($yourvalue);

        return $fee;
    }

    /**
     * Retrieve Fee type from Store config (Percent or Fixed)
     * @return string
     */

    public function getFeeName()
    {
        return $this->getConfig('fee_name');
    }
    public function getPriceForShippingBar()
    {
        return $this->scopeConfig->getValue(
            self::PRICE_SHIPPING_BAR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}


