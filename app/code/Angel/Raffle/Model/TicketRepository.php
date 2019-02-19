<?php


namespace Angel\Raffle\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Angel\Raffle\Api\Data\TicketSearchResultsInterfaceFactory;
use Angel\Raffle\Api\Data\TicketInterfaceFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Angel\Raffle\Model\ResourceModel\Ticket as ResourceTicket;
use Magento\Framework\Api\DataObjectHelper;
use Angel\Raffle\Model\ResourceModel\Ticket\CollectionFactory as TicketCollectionFactory;
use Angel\Raffle\Api\TicketRepositoryInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Store\Model\StoreManagerInterface;

class TicketRepository implements TicketRepositoryInterface
{

    protected $extensibleDataObjectConverter;
    protected $dataObjectProcessor;

    protected $dataTicketFactory;

    private $collectionProcessor;

    protected $resource;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $extensionAttributesJoinProcessor;

    protected $ticketCollectionFactory;

    private $storeManager;

    protected $ticketFactory;


    /**
     * @param ResourceTicket $resource
     * @param TicketFactory $ticketFactory
     * @param TicketInterfaceFactory $dataTicketFactory
     * @param TicketCollectionFactory $ticketCollectionFactory
     * @param TicketSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceTicket $resource,
        TicketFactory $ticketFactory,
        TicketInterfaceFactory $dataTicketFactory,
        TicketCollectionFactory $ticketCollectionFactory,
        TicketSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->ticketFactory = $ticketFactory;
        $this->ticketCollectionFactory = $ticketCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTicketFactory = $dataTicketFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Angel\Raffle\Api\Data\TicketInterface $ticket
    ) {
        /* if (empty($ticket->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $ticket->setStoreId($storeId);
        } */
        
        $ticketData = $this->extensibleDataObjectConverter->toNestedArray(
            $ticket,
            [],
            \Angel\Raffle\Api\Data\TicketInterface::class
        );
        
        $ticketModel = $this->ticketFactory->create()->setData($ticketData);
        try {
            $this->resource->save($ticketModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the ticket: %1',
                $exception->getMessage()
            ));
        }
        return $ticketModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($ticketId)
    {
        $ticket = $this->ticketFactory->create();
        $this->resource->load($ticket, $ticketId);
        if (!$ticket->getId()) {
            throw new NoSuchEntityException(__('Ticket with id "%1" does not exist.', $ticketId));
        }
        return $ticket->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->ticketCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Angel\Raffle\Api\Data\TicketInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Angel\Raffle\Api\Data\TicketInterface $ticket
    ) {
        try {
            $ticketModel = $this->ticketFactory->create();
            $this->resource->load($ticketModel, $ticket->getTicketId());
            $this->resource->delete($ticketModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Ticket: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($ticketId)
    {
        return $this->delete($this->getById($ticketId));
    }
}
