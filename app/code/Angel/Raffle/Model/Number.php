<?php


namespace Angel\Raffle\Model;

use Angel\Raffle\Api\Data\NumberInterface;
use Magento\Framework\Api\DataObjectHelper;
use Angel\Raffle\Api\Data\NumberInterfaceFactory;

class Number extends \Magento\Framework\Model\AbstractModel
{

    protected $numberDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'angel_raffle_number';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param NumberInterfaceFactory $numberDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Angel\Raffle\Model\ResourceModel\Number $resource
     * @param \Angel\Raffle\Model\ResourceModel\Number\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        NumberInterfaceFactory $numberDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Angel\Raffle\Model\ResourceModel\Number $resource,
        \Angel\Raffle\Model\ResourceModel\Number\Collection $resourceCollection,
        array $data = []
    ) {
        $this->numberDataFactory = $numberDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve number model with number data
     * @return NumberInterface
     */
    public function getDataModel()
    {
        $numberData = $this->getData();
        
        $numberDataObject = $this->numberDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $numberDataObject,
            $numberData,
            NumberInterface::class
        );
        
        return $numberDataObject;
    }
}
