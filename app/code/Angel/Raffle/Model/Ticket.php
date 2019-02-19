<?php


namespace Angel\Raffle\Model;

use Angel\Raffle\Api\Data\TicketInterface;
use Angel\Raffle\Api\Data\TicketInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Ticket extends \Magento\Framework\Model\AbstractModel
{

    protected $_eventPrefix = 'angel_raffle_ticket';
    protected $dataObjectHelper;

    protected $ticketDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param TicketInterfaceFactory $ticketDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Angel\Raffle\Model\ResourceModel\Ticket $resource
     * @param \Angel\Raffle\Model\ResourceModel\Ticket\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        TicketInterfaceFactory $ticketDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Angel\Raffle\Model\ResourceModel\Ticket $resource,
        \Angel\Raffle\Model\ResourceModel\Ticket\Collection $resourceCollection,
        array $data = []
    ) {
        $this->ticketDataFactory = $ticketDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve ticket model with ticket data
     * @return TicketInterface
     */
    public function getDataModel()
    {
        $ticketData = $this->getData();
        
        $ticketDataObject = $this->ticketDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $ticketDataObject,
            $ticketData,
            TicketInterface::class
        );
        
        return $ticketDataObject;
    }
}
