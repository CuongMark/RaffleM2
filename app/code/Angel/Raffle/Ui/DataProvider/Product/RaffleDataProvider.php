<?php

namespace Angel\Raffle\Ui\DataProvider\Product;


use Angel\Fifty\Model\PrizeManagement;
use Angel\Fifty\Model\TicketManagement;
use Angel\Raffle\Model\Product\Type\Raffle;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\ReportingInterface;

/**
 * Class ReviewDataProvider
 *
 * @api
 *
 * @method \Magento\Catalog\Model\ResourceModel\Product\Collection getCollection
 * @since 100.1.0
 */
class RaffleDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        $addFieldStrategies = [],
        $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ){
        parent::__construct($name, $primaryFieldName, $requestFieldName, $collectionFactory, $addFieldStrategies, $addFilterStrategies, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $this->getCollection()->addAttributeToFilter('type_id', ['in' => [Raffle::TYPE_ID]]);
        $this->getCollection()->addAttributeToSelect(['raffle_status', 'total_ticket' ,'prefix']);
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }
        $items = $this->getCollection()->toArray();

        return [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($items),
        ];
    }
}
