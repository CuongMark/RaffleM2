<?php


namespace Angel\Raffle\Api\Data;

interface NumberSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Number list.
     * @return \Angel\Raffle\Api\Data\NumberInterface[]
     */
    public function getItems();

    /**
     * Set prize_id list.
     * @param \Angel\Raffle\Api\Data\NumberInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
