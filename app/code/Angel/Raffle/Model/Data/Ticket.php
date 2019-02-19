<?php


namespace Angel\Raffle\Model\Data;

use Angel\Raffle\Api\Data\TicketInterface;

class Ticket extends \Magento\Framework\Api\AbstractExtensibleObject implements TicketInterface
{

    /**
     * Get ticket_id
     * @return string|null
     */
    public function getTicketId()
    {
        return $this->_get(self::TICKET_ID);
    }

    /**
     * Set ticket_id
     * @param string $ticketId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setTicketId($ticketId)
    {
        return $this->setData(self::TICKET_ID, $ticketId);
    }

    /**
     * Get start
     * @return string|null
     */
    public function getStart()
    {
        return $this->_get(self::START);
    }

    /**
     * Set start
     * @param string $start
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setStart($start)
    {
        return $this->setData(self::START, $start);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\Raffle\Api\Data\TicketExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Angel\Raffle\Api\Data\TicketExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\Raffle\Api\Data\TicketExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get end
     * @return string|null
     */
    public function getEnd()
    {
        return $this->_get(self::END);
    }

    /**
     * Set end
     * @param string $end
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setEnd($end)
    {
        return $this->setData(self::END, $end);
    }

    /**
     * Get Price
     * @return string|null
     */
    public function getPrice()
    {
        return $this->_get(self::PRICE);
    }

    /**
     * Set Price
     * @param string $price
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * Get Prize
     * @return float|null
     */
    public function getPrize()
    {
        return $this->_get(self::PRIZE);
    }

    /**
     * Set Prize
     * @param float $prize
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setPrize($prize)
    {
        return $this->setData(self::PRIZE, $prize);
    }

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId()
    {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Get transaction_id
     * @return string|null
     */
    public function getTransactionId()
    {
        return $this->_get(self::TRANSACTION_ID);
    }

    /**
     * Set transaction_id
     * @param string $transactionId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setTransactionId($transactionId)
    {
        return $this->setData(self::TRANSACTION_ID, $transactionId);
    }

    /**
     * Get payout_transaction_id
     * @return string|null
     */
    public function getPayoutTransactionId()
    {
        return $this->_get(self::PAYOUT_TRANSACTION_ID);
    }

    /**
     * Set payout_transaction_id
     * @param string $payoutTransactionId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setPayoutTransactionId($payoutTransactionId)
    {
        return $this->setData(self::PAYOUT_TRANSACTION_ID, $payoutTransactionId);
    }

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_get(self::CUSTOMER_ID);
    }

    /**
     * Set customer_id
     * @param string $customerId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get status
     * @return string|null
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get total
     * @return string|null
     */
    public function getWinningNumbers()
    {
        return $this->_get(self::WINNING_NUMBERS);
    }

    /**
     * Set total
     * @param string $winning_number
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function setWinningNumbers($winning_number)
    {
        return $this->setData(self::WINNING_NUMBERS, $winning_number);
    }
}
