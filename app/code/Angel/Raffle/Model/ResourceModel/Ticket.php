<?php


namespace Angel\Raffle\Model\ResourceModel;

class Ticket extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('angel_raffle_ticket', 'ticket_id');
    }
}
