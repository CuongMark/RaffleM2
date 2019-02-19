<?php


namespace Angel\Raffle\Block\Index;

use Angel\Raffle\Model\Product\Attribute\Source\RaffleStatus;

class Finished extends \Angel\Raffle\Block\Raffle
{
    protected function _getProductCollection(){
        $collection = parent::_getProductCollection();
        $collection->addAttributeToFilter('raffle_status', RaffleStatus::FINISHED);
        return $collection;
    }
}
