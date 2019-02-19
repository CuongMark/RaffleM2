<?php
/**
 * Angel Raffle Raffles
 * Copyright (C) 2018 Mark Wolf
 *
 * This file included in Angel/Raffle is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Angel\Raffle\Block\Tickets;

use Angel\Raffle\Model\Raffle;
use Angel\Raffle\Model\ResourceModel\Ticket\Collection;
use Angel\Raffle\Model\ResourceModel\Ticket\CollectionFactory;
use Angel\Raffle\Model\Ticket\Status;
use Magento\Customer\Model\Session;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Index extends \Magento\Framework\View\Element\Template
{

    /**
     * @var CollectionFactory
     */
    protected $ticketCollectionFactory;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var Collection
     */
    protected $ticketCollection;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    protected $ticketManagement;
    /**
     * @var Raffle
     */
    private $raffle;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CollectionFactory $ticketCollectionFactory,
        Session $customerSession,
        PriceCurrencyInterface $priceCurrency,
        Raffle $raffle,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->ticketCollectionFactory = $ticketCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->priceCurrency = $priceCurrency;
        $this->raffle = $raffle;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getTickets()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.tickets.pager'
            )->setCollection(
                $this->getTickets()
            );
            $this->setChild('pager', $pager);
            $this->getTickets()->load();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return Collection
     */
    public function getTickets()
    {
        if (!$this->ticketCollection) {
            /** @var Collection $ticketCollection */
            $ticketCollection = $this->ticketCollectionFactory->create();
            $ticketCollection->addFieldToFilter('customer_id', $this->_customerSession->getCustomerId());
            $ticketCollection->addFieldToFilter('main_table.status', ['neq' => \Angel\Raffle\Model\Ticket\Status::STATUS_CANCELED]);
            $ticketCollection->setOrder('ticket_id');
            $this->raffle->joinTotalWinningNumbersToTicketsCollection($ticketCollection);
            $this->ticketCollection = $ticketCollection;
        }
        return $this->ticketCollection;
    }


    /**
     * @param object $ticket
     * @return string
     */
    public function getViewUrl($ticket)
    {
        return $this->getUrl('Raffle/index/view', ['id' => $ticket->getId()]);
    }

    /**
     * @param object $ticket
     * @return string
     */
    public function getTrashUrl($ticket)
    {
        return $this->getUrl('Raffle/index/trash', ['id' => $ticket->getId()]);
    }

    /**
     * Retrieve formated price
     *
     * @param float $value
     * @return string
     */
    public function formatPrice($value, $isHtml = true)
    {
        return $this->priceCurrency->format(
            $value,
            $isHtml,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            1 //Todo getStore
        );
    }

    public function getStatusLabel($ticket){
        $options = Status::getOptionArray();
        return $options[$ticket->getStatus()];
    }
}
