<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\Raffle\Model\ResourceModel\Ticket\Grid;

use Angel\Raffle\Model\Raffle;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

/**
 * Order grid collection
 */
class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * @var Raffle
     */
    private $raffle;
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Initialize dependencies.
     *
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param string $mainTable
     * @param string $resourceModel
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        Raffle $raffle,
        RequestInterface $request,
        $mainTable = 'angel_raffle_ticket',
        $resourceModel = \Angel\Raffle\Model\ResourceModel\Ticket::class
    ) {
        $this->raffle = $raffle;
        $this->request = $request;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);

    }

    /**
     * Initialize select
     *
     * @return $this
     */
    protected function _initSelect()
    {
        $this->addFilterToMap('customer_email', 'customer.email');
        $this->addFilterToMap('product_name', 'product.name');
        parent::_initSelect();
        $this->_joinFields();
        $this->_addFilters();
        return $this;
    }

    protected function _joinFields()
    {
        $this->raffle->joinTotalWinningNumbersToTicketsCollection($this);
        $this->raffle->joinCustomerEmailToTicketsCollection($this);
        $this->raffle->joinProductNameToTicketsCollection($this);
    }

    protected function _addFilters()
    {
        if ($this->request->getParam('current_product_id'))
            $this->addFieldToFilter('main_table.product_id', $this->request->getParam('current_product_id'));
    }


}
