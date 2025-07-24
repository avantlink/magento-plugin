<?php

namespace AvantLink\Tracking\ViewModel;

use Magento\Framework\App\Http\Context;
use Magento\Framework\Session\SessionManagerInterface;

class SuccessBlockHelper implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected $_httpContext;
    protected $_sessionManager;
    private $helperData;

    public function __construct(
        Context $httpContext,
        SessionManagerInterface $session,
        \AvantLink\Tracking\Helper\Data $helperData
    )
    {
        $this->_httpContext = $httpContext;
        $this->_sessionManager = $session;
        $this->helperData = $helperData;
    }

    /**
     * Calls the helper to get the merchant id value from store config
     *
     * @return mixed
     */
    public function getSettingValue()
    {
        return $this->helperData->getMerchantId();
    }

    public function getSessionData()
    {
        return $this->_sessionManager->getData();
    }
}