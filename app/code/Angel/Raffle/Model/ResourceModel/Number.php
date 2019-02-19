<?php


namespace Angel\Raffle\Model\ResourceModel;

class Number extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('angel_raffle_number', 'number_id');
    }
}
