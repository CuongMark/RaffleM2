<?php


namespace Angel\Raffle\Model\ResourceModel\Prize;

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
            \Angel\Raffle\Model\Prize::class,
            \Angel\Raffle\Model\ResourceModel\Prize::class
        );
    }
}
