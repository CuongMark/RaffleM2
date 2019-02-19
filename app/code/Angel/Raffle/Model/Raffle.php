<?php


namespace Angel\Raffle\Model;

use Angel\Raffle\Model\ResourceModel\Ticket\Collection;
use Angel\Raffle\Model\ResourceModel\Ticket\CollectionFactory;
use Magento\Catalog\Model\Product;

class Raffle
{
    /**
     * @var CollectionFactory
     */
    private $ticketCollectionFactory;

    public function __construct(
        CollectionFactory $ticketCollectionFactory
    ){
        $this->ticketCollectionFactory = $ticketCollectionFactory;
    }

    /**
     * @param Product $product
     * @return Collection
     */
    public function getTickets($product){
        /** @var Collection $collection */
        $collection = $this->ticketCollectionFactory->create();
        $collection->addFieldToFilter('product_id', $product->getId());
        return $collection;
    }

    /**
     * @param Product $product
     * @return int
     */
    public function getLastTicketNumber($product){
        $ticketNumber = $this->getTickets($product)->getLastItem()->getEnd();
        return $ticketNumber?$ticketNumber:0;
    }

    public function generateWinningNumber($product, $start, $end){

    }
}
