<?php


namespace Angel\Raffle\Model\ResourceModel\Ticket;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Angel\Raffle\Model\Ticket::class,
            \Angel\Raffle\Model\ResourceModel\Ticket::class
        );
    }
}
