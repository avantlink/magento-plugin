<?php
namespace AvantLink\Tracking\Block\Checkout;

use Magento\Checkout\Block\Onepage\Success as MagentoSuccess;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session;
use Magento\Sales\Model\Order\Config;
use Magento\Framework\App\Http\Context as HttpContext;

class Success extends MagentoSuccess
{
    /**
     * Get Order details
     *
     * @return mixed
     */
    public function getLastOrder()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
        return $order;
    }

    /**
     * Get the items from the order
     *
     * @return mixed
     */
    public function getItems()
    {
        $order = $this->getLastOrder();
        return $order->getAllVisibleItems();
    }

    /**
     * Builds an array with the items from the order
     *
     * @return array
     */
    public function buildItemsArray()
    {
        $itemsArray = [];

        $items = $this->getItems();
        foreach ($items as $item) {
            $itemsArray[] = [
                'itemQty' => $item->getQtyOrdered(),
                'itemPrice' => $item->getPrice(),
                'itemSku' => $item->getSku()
            ];
        }

        return $itemsArray;
    }

    /**
     * Get billing address info from order
     *
     * @return mixed
     */
    public function getBillingAddress()
    {
        $order = $this->getLastOrder();
        return $order->getBillingAddress();
    }
}
