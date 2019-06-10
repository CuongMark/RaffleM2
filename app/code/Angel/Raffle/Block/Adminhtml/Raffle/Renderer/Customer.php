<?php
/**
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

namespace Angel\Raffle\Block\Adminhtml\Raffle\Renderer;

class Customer extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Customer\Model\CustomerFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        array $data = []
    )
    {
        $this->_customerFactory = $customerFactory;
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $emailAdrress = $row->getData('customer_email');
        $action_name = $this->getRequest()->getActionName();
        if($action_name == 'exportCSV' || $action_name == 'exportXml' || $action_name == 'ExportCSV'){
            return $emailAdrress;
        }
        $href = $this->getUrl('customer/index/edit', ['id' => $row->getCustomerId(), 'active_tab' => 'cart']);
        return '<a href="' . $href . '" target="_blank">' . $emailAdrress . '</a>';
    }

}
