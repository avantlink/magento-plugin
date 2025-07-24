<?php

namespace AvantLink\Tracking\Helper;

class Data extends \Magento\FrameWork\App\Helper\AbstractHelper
{
    /**
     * Get configuration files for $config_path
     *
     * @param String $config_path
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue($config_path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get the merchant id in the store configuration
     *
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->getConfig('avantlink_tracking/general/merchant_id');
    }
}
