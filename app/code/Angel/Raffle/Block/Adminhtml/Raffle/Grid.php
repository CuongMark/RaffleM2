<?php
/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Customercredit
 * @copyright   Copyright (c) 2017 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 *
 */

namespace Angel\Raffle\Block\Adminhtml\Raffle;

use Angel\Fifty\Model\PrizeManagement;
use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Angel\Raffle\Model\Product\Attribute\Source\RaffleStatus;
use Angel\Raffle\Model\Raffle;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Angel\Fifty\Model\TicketManagement;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    private $raffle;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        CollectionFactory $collectionFactory,
        Raffle $raffle,
        array $data = []
    ){
        parent::__construct($context, $backendHelper, $data);
        $this->collectionFactory = $collectionFactory;
        $this->raffle = $raffle;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('raffleGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToFilter('type_id', ['in' => [\Angel\Raffle\Model\Product\Type\Raffle::TYPE_ID]]);
        $collection->addAttributeToSelect('*');
        $this->raffle->joinTotalPrizeToProductCollection($collection);
        $this->raffle->joinTotalPrizeWonToProductCollection($collection);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        try {
            $this->addColumn('entity_id', array(
                'header' => __('ID'),
                'width' => '50px',
                'index' => 'entity_id',
                'type' => 'number',
            ));
            $this->addColumn('name', array(
                'header' => __('Product'),
                'width' => '50px',
                'index' => 'name',
                'type' => 'text',
            ));
            $this->addColumn('numbers_generated', array(
                'header' => __('Winning Numbers Generated'),
                'width' => '50px',
                'index' => 'numbers_generated',
                'type' => 'number',
            ));
            $this->addColumn('total_prizes', array(
                'header' => __('Total Prizes'),
                'width' => '50px',
                'index' => 'total_prizes',
                'type' => 'number',
            ));

//            $this->addColumn('fifty_finish_at', array(
//                'header' => __('Start At'),
//                'align' => 'left',
//                'index' => 'fifty_finish_at',
//                'type' => 'datetime',
//            ));
            $currency = $this->_storeManager->getStore()->getCurrentCurrencyCode();
            $this->addColumn('price', array(
                'header' => __('Price'),
                'width' => '100',
                'align' => 'right',
                'currency_code' => $currency,
                'index' => 'price',
                'type' => 'total_prizes_price'
            ));
            $this->addColumn('total_prizes_price', array(
                'header' => __('Total Prizes Price'),
                'width' => '100',
                'align' => 'right',
                'currency_code' => $currency,
                'index' => 'total_prizes_price',
                'type' => 'price'
            ));
            $this->addColumn('total_prize_won', array(
                'header' => __('Total Prize Won'),
                'width' => '100',
                'align' => 'right',
                'currency_code' => $currency,
                'index' => 'total_prize_won',
                'type' => 'price'
            ));
            $this->addColumn('total_prize_won', array(
                'header' => __('Total Prize Won'),
                'width' => '100',
                'align' => 'right',
                'currency_code' => $currency,
                'index' => 'total_prize_won',
                'type' => 'price'
            ));
            $this->addColumn('total_price', array(
                'header' => __('Total Sales'),
                'width' => '100',
                'align' => 'right',
                'currency_code' => $currency,
                'index' => 'total_price',
                'type' => 'price'
            ));
//            $this->addColumn('total_price', array(
//                'header' => __('Total Price'),
//                'width' => '100',
//                'align' => 'right',
//                'currency_code' => $currency,
//                'index' => 'total_price',
//                'type' => 'price'
//            ));

            $this->addColumn(
                'raffle_status',
                [
                    'header' => __('Status'),
                    'width' => '70px',
                    'index' => 'raffle_status',
                    'type' => 'number',
                ]
            );
        } catch (\Exception $e){

        }

        $this->addExportType('*/*/exportCSV', __('CSV'));
        return parent::_prepareColumns(); // TODO: Change the autogenerated stub
    }


    public function getGridUrl()
    {
        return '';
    }

    public function getRowUrl($row)
    {
        return '';
    }
}