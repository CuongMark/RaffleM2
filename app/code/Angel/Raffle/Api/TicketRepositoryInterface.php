<?php


namespace Angel\Raffle\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface TicketRepositoryInterface
{

    /**
     * Save Ticket
     * @param \Angel\Raffle\Api\Data\TicketInterface $ticket
     * @return \Angel\Raffle\Api\Data\TicketInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Angel\Raffle\Api\Data\TicketInterface $ticket
    );

    /**
     * Retrieve Ticket
     * @param string $ticketId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($ticketId);

    /**
     * Retrieve Ticket matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Angel\Raffle\Api\Data\TicketSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Ticket
     * @param \Angel\Raffle\Api\Data\TicketInterface $ticket
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Angel\Raffle\Api\Data\TicketInterface $ticket
    );

    /**
     * Delete Ticket by ID
     * @param string $ticketId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($ticketId);
}
