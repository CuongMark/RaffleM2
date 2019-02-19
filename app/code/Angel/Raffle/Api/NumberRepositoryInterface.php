<?php


namespace Angel\Raffle\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface NumberRepositoryInterface
{

    /**
     * Save Number
     * @param \Angel\Raffle\Api\Data\NumberInterface $number
     * @return \Angel\Raffle\Api\Data\NumberInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Angel\Raffle\Api\Data\NumberInterface $number
    );

    /**
     * Retrieve Number
     * @param string $numberId
     * @return \Angel\Raffle\Api\Data\NumberInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($numberId);

    /**
     * Retrieve Number matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Angel\Raffle\Api\Data\NumberSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Number
     * @param \Angel\Raffle\Api\Data\NumberInterface $number
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Angel\Raffle\Api\Data\NumberInterface $number
    );

    /**
     * Delete Number by ID
     * @param string $numberId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($numberId);
}
