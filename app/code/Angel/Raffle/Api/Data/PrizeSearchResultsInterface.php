<?php


namespace Angel\Raffle\Api\Data;

interface PrizeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Prize list.
     * @return \Angel\Raffle\Api\Data\PrizeInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Angel\Raffle\Api\Data\PrizeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
