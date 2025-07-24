<?php

namespace AvantLink\Tracking\Model\Session;

class Storage extends \Magento\Framework\Session\Storage
{
    /**
     * @param \Magento\Checkout\Model\Config\Share $configShare
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param string $namespace
     * @param array $data
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $namespace = 'sessionname',
        array $data = []
    ) {
        parent::__construct($namespace, $data);
    }
}