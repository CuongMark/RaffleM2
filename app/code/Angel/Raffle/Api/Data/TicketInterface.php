<?php


namespace Angel\Raffle\Api\Data;

interface TicketInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const START = 'start';
    const CREATED_AT = 'created_at';
    const PRODUCT_ID = 'product_id';
    const PRIZE = 'Prize';
    const TRANSACTION_ID = 'transaction_id';
    const PRICE = 'Price';
    const STATUS = 'status';
    const CUSTOMER_ID = 'customer_id';
    const TICKET_ID = 'ticket_id';
    const END = 'end';
    const WINNING_NUMBERS = 'winning_numbers';
    const WINNING_PRIZE = 'winning_prize';
    const PAYOUT_TRANSACTION_ID = 'payout_transaction_id';

    /**
     * Get ticket_id
     * @return int|null
     */
    public function getTicketId();

    /**
     * Set ticket_id
     * @param int $ticketId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setTicketId($ticketId);

    /**
     * Get int
     * @return string|null
     */
    public function getStart();

    /**
     * Set start
     * @param int $start
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setStart($start);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\Raffle\Api\Data\TicketExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Angel\Raffle\Api\Data\TicketExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\Raffle\Api\Data\TicketExtensionInterface $extensionAttributes
    );

    /**
     * Get end
     * @return int|null
     */
    public function getEnd();

    /**
     * Set end
     * @param int $end
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setEnd($end);

    /**
     * Get Price
     * @return float|null
     */
    public function getPrice();

    /**
     * Set Price
     * @param float $price
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setPrice($price);

    /**
     * Get Prize
     * @return float|null
     */
    public function getPrize();

    /**
     * Set Prize
     * @param float $prize
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setPrize($prize);

    /**
     * Get product_id
     * @return int|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param int $productId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setProductId($productId);

    /**
     * Get transaction_id
     * @return int|null
     */
    public function getTransactionId();

    /**
     * Set transaction_id
     * @param int $transactionId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setTransactionId($transactionId);

    /**
     * Get payout_transaction_id
     * @return int|null
     */
    public function getPayoutTransactionId();

    /**
     * Set payout_transaction_id
     * @param int $payoutTransactionId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setPayoutTransactionId($payoutTransactionId);

    /**
     * Get customer_id
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param int $customerId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setStatus($status);

    /**
     * Get total
     * @return string|null
     */
    public function getWinningNumbers();

    /**
     * Set total
     * @param string $winning_numbers
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setWinningNumbers($winning_numbers);
}
