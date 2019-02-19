<?php


namespace Angel\Raffle\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Angel\Raffle\Api\Data\NumberSearchResultsInterfaceFactory;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Angel\Raffle\Model\ResourceModel\Number\CollectionFactory as NumberCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Angel\Raffle\Api\Data\NumberInterfaceFactory;
use Angel\Raffle\Api\NumberRepositoryInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Angel\Raffle\Model\ResourceModel\Number as ResourceNumber;
use Magento\Store\Model\StoreManagerInterface;

class NumberRepository implements NumberRepositoryInterface
{

    protected $extensibleDataObjectConverter;
    protected $dataObjectProcessor;

    protected $numberCollectionFactory;

    private $collectionProcessor;

    protected $resource;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataNumberFactory;

    protected $extensionAttributesJoinProcessor;

    protected $numberFactory;

    private $storeManager;


    /**
     * @param ResourceNumber $resource
     * @param NumberFactory $numberFactory
     * @param NumberInterfaceFactory $dataNumberFactory
     * @param NumberCollectionFactory $numberCollectionFactory
     * @param NumberSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceNumber $resource,
        NumberFactory $numberFactory,
        NumberInterfaceFactory $dataNumberFactory,
        NumberCollectionFactory $numberCollectionFactory,
        NumberSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->numberFactory = $numberFactory;
        $this->numberCollectionFactory = $numberCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataNumberFactory = $dataNumberFactory;
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
        \Angel\Raffle\Api\Data\NumberInterface $number
    ) {
        /* if (empty($number->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $number->setStoreId($storeId);
        } */
        
        $numberData = $this->extensibleDataObjectConverter->toNestedArray(
            $number,
            [],
            \Angel\Raffle\Api\Data\NumberInterface::class
        );
        
        $numberModel = $this->numberFactory->create()->setData($numberData);
        
        try {
            $this->resource->save($numberModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the number: %1',
                $exception->getMessage()
            ));
        }
        return $numberModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($numberId)
    {
        $number = $this->numberFactory->create();
        $this->resource->load($number, $numberId);
        if (!$number->getId()) {
            throw new NoSuchEntityException(__('Number with id "%1" does not exist.', $numberId));
        }
        return $number->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->numberCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Angel\Raffle\Api\Data\NumberInterface::class
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
        \Angel\Raffle\Api\Data\NumberInterface $number
    ) {
        try {
            $numberModel = $this->numberFactory->create();
            $this->resource->load($numberModel, $number->getNumberId());
            $this->resource->delete($numberModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Number: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($numberId)
    {
        return $this->delete($this->getById($numberId));
    }
}
