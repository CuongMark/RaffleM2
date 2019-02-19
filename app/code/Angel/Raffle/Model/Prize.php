<?php


namespace Angel\Raffle\Model;

use Angel\Raffle\Api\Data\PrizeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Angel\Raffle\Api\Data\PrizeInterface;

class Prize extends \Magento\Framework\Model\AbstractModel
{

    protected $_eventPrefix = 'angel_raffle_prize';
    protected $dataObjectHelper;

    protected $prizeDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param PrizeInterfaceFactory $prizeDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Angel\Raffle\Model\ResourceModel\Prize $resource
     * @param \Angel\Raffle\Model\ResourceModel\Prize\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        PrizeInterfaceFactory $prizeDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Angel\Raffle\Model\ResourceModel\Prize $resource,
        \Angel\Raffle\Model\ResourceModel\Prize\Collection $resourceCollection,
        array $data = []
    ) {
        $this->prizeDataFactory = $prizeDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve prize model with prize data
     * @return PrizeInterface
     */
    public function getDataModel()
    {
        $prizeData = $this->getData();
        
        $prizeDataObject = $this->prizeDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $prizeDataObject,
            $prizeData,
            PrizeInterface::class
        );
        
        return $prizeDataObject;
    }
}
