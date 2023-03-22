<?php declare(strict_types=1);

namespace AHT\PaymentFee\Observer;

use AHT\PaymentFee\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class AddFeeToOrderObserver implements ObserverInterface
{
    /**
     * @var Session
     */
    protected $checkoutSession;

    /** @var Data  */
    protected $helper;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AddFeeToOrderObserver constructor.
     * @param Session $checkoutSession
     */
    public function __construct(
        Session $checkoutSession,
        Data $helper,
        LoggerInterface $loggerInterface
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->helper = $helper;
        $this->logger = $loggerInterface;
    }

    /**
     * Set payment fee to order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        $quote = $observer->getQuote();
        if ($this->helper->canApply($quote)) {
            $feeAmount = $this->helper->getFee($quote);

            //Set fee data to order
            $order = $observer->getOrder();
            $order->setData('fee_amount', $feeAmount);
            $order->setData('base_fee_amount', $feeAmount);
        }

        return $this;
    }
}
