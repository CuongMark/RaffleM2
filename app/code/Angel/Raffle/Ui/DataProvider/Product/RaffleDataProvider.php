<?php

namespace Angel\Raffle\Ui\DataProvider\Product;


use Angel\Raffle\Model\PrizeManagement;
use Angel\Raffle\Model\TicketManagement;
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
    /**
     * @var Raffle
     */
    private $raffle;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        \Angel\Raffle\Model\Raffle $raffle,
        $addFieldStrategies = [],
        $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ){
        parent::__construct($name, $primaryFieldName, $requestFieldName, $collectionFactory, $addFieldStrategies, $addFilterStrategies, $meta, $data);
        $this->raffle = $raffle;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $this->getCollection()->addAttributeToFilter('type_id', ['in' => [Raffle::TYPE_ID]]);
//        $this->getCollection()->addAttributeToSelect(['raffle_status', 'total_tickets' ,'prefix']);
        $this->getCollection()->joinAttribute('total_tickets', 'catalog_product/total_tickets', 'entity_id', null, 'inner');
        $this->getCollection()->joinAttribute('raffle_status', 'catalog_product/raffle_status', 'entity_id', null, 'inner');

        $this->raffle->joinTotalPrizeToProductCollection($this->getCollection());
        $this->raffle->joinTotalPrizeWonToProductCollection($this->getCollection());
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
