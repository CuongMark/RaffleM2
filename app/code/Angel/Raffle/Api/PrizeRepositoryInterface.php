<?php


namespace Angel\Raffle\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PrizeRepositoryInterface
{

    /**
     * Save Prize
     * @param \Angel\Raffle\Api\Data\PrizeInterface $prize
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Angel\Raffle\Api\Data\PrizeInterface $prize
    );

    /**
     * Retrieve Prize
     * @param string $prizeId
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($prizeId);

    /**
     * Retrieve Prize matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Angel\Raffle\Api\Data\PrizeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Prize
     * @param \Angel\Raffle\Api\Data\PrizeInterface $prize
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Angel\Raffle\Api\Data\PrizeInterface $prize
    );

    /**
     * Delete Prize by ID
     * @param string $prizeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($prizeId);
}
