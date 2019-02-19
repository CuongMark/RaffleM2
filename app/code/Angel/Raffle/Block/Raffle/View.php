<?php


namespace Angel\Raffle\Block\Raffle;

use Magento\Catalog\Api\ProductRepositoryInterface;

class View extends \Magento\Catalog\Block\Product\View
{
    /**
     * @return bool
     */
    public function isLoggedIn(){
        return $this->customerSession->isLoggedIn();
    }
}
