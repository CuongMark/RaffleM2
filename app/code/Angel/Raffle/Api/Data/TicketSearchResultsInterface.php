<?php


namespace Angel\Raffle\Api\Data;

interface TicketSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Ticket list.
     * @return \Angel\Raffle\Api\Data\TicketInterface[]
     */
    public function getItems();

    /**
     * Set start list.
     * @param \Angel\Raffle\Api\Data\TicketInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
