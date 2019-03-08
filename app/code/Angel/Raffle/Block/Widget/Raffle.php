<?php
/**
 * Angel Fifty Raffles
 * Copyright (C) 2018 Mark Wolf
 *
 * This file included in Angel/Fifty is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Angel\Raffle\Block\Widget;

use Angel\Raffle\Model\Product\Attribute\Source\RaffleStatus;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Url\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Raffle extends \Magento\Catalog\Block\Product\ListProduct
{
    protected $collectionFactory;
    protected $_productCollection;
    private $priceCurrency;

    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        CollectionFactory $collectionFactory,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ){
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
        $this->collectionFactory = $collectionFactory;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return PriceCurrencyInterface
     */
    public function getPriceCurrency(){
        return $this->priceCurrency;
    }

    protected function _getProductCollection()
    {
        return $this->getProducts();
    }

    public function getLoadedProductCollection() {
        return $this->getProducts();
    }

    protected function getProducts(){
        if (!$this->_productCollection) {
            $collection = $this->collectionFactory->create();
            $collection->addAttributeToSelect('*');
            $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
            $collection->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
            $collection->addFieldToFilter('type_id', \Angel\Raffle\Model\Product\Type\Raffle::TYPE_ID);
            $collection->addAttributeToFilter('raffle_status', RaffleStatus::PROCESSING);
            $collection->addStoreFilter($this->_storeManager->getStore()->getId());
            $collection->setCurPage(1)->setPageSize($this->getProductCount());
            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    protected $_template = "widget/raffle.phtml";

}
