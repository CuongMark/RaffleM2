<?php


namespace Angel\Raffle\Block\Adminhtml\Report;

use Magento\Catalog\Model\Locator\LocatorInterface;

class Index extends \Magento\Backend\Block\Template
{
    private $locator;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        LocatorInterface $locator,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->locator = $locator;
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProduct(){
        return $this->locator->getProduct();
    }
}
