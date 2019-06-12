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

namespace Angel\Raffle\Block\Adminhtml\Raffle\Details\Prize;

use Angel\Raffle\Model\Raffle;
use Angel\Raffle\Model\ResourceModel\Prize\Collection;
use Angel\Raffle\Model\ResourceModel\Prize\CollectionFactory;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var TicketManagement
     */
    protected $ticketManagement;
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
        $this->setId('PrizeGrid');
        $this->setDefaultSort('prize_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('*');
        $productId = $this->getRequest()->getParam('id');
        if ($productId) {
            $collection->addFieldToFilter('product_id', $productId);
        }
        $this->raffle->joinTotalWinningNumbersToPrizeCollection($collection);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        try {
            $this->addColumn('prize_id', array(
                'header' => __('ID'),
                'width' => '50px',
                'index' => 'prize_id',
                'type' => 'number',
            ));
            $this->addColumn('name', array(
                'header' => __('Prize Label'),
                'width' => '150',
                'index' => 'name'
            ));
            $currency = $this->_storeManager->getStore()->getCurrentCurrencyCode();
            $this->addColumn('prize', array(
                'header' => __('Winngin Prize'),
                'width' => '100',
                'align' => 'right',
                'currency_code' => $currency,
                'index' => 'prize',
                'type' => 'prize'
            ));
            $this->addColumn('total', array(
                'header' => __('Total Prize'),
                'index' => 'total'
            ));
            $this->addColumn('winning_numbers', array(
                'header' => __('Winning Numbers'),
                'index' => 'winning_numbers'
            ));
            $this->addColumn('total_winning_numbers', array(
                'header' => __('Total Winning Numbers'),
                'index' => 'total_winning_numbers'
            ));
            $this->addColumn('total_prize_left', array(
                'header' => __('Total Prize Left'),
                'index' => 'total_prize_left'
            ));

            $this->addColumn('total_winning_price', array(
                'header' => __('Total Winning Prize'),
                'width' => '100',
                'align' => 'right',
                'currency_code' => $currency,
                'index' => 'total_winning_price',
                'type' => 'total_winning_price'
            ));
        } catch (\Exception $e){

        }

        $this->addExportType('*/prize/exportCSV', __('CSV'));
        return parent::_prepareColumns(); // TODO: Change the autogenerated stub
    }


    public function getGridUrl()
    {
        return $this->getUrl('*/*/details', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return '';
    }

    public function getCsv()
    {
        $csv = '';
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();
        $data = array();
        $data[] = '"' . __('ID') . '"';
        $data[] = '"' . __('Prize Label') . '"';
        $data[] = '"' . __('Total Prize') . '"';
        $data[] = '"' . __('Total Won Prize') . '"';
        $data[] = '"' . __('Total Prize left') . '"';
        $data[] = '"' . __('Prize') . '"';
        $data[] = '"' . __('Total Prize left') . '"';
        $csv .= implode(',', $data) . "\n";

        foreach ($this->getCollection() as $item) {
            $data = [
                $item->getId(),
                $item->getData('name'),
                $item->getData('prize'),
                $item->getData('total'),
                $item->getData('total_winning_numbers'),
                $item->getData('total_prize_left'),
                $item->getData('winning_numbers'),
                $item->getData('prize'),
                $item->getData('total_winning_price')
            ];
            $str = implode(',',$data);
            $csv .= $str . "\n";
        }
        return $csv;
    }
}