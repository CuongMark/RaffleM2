<?php


namespace Angel\Raffle\Model\Data;

use Angel\Raffle\Api\Data\NumberInterface;

class Number extends \Magento\Framework\Api\AbstractExtensibleObject implements NumberInterface
{

    /**
     * Get number_id
     * @return string|null
     */
    public function getNumberId()
    {
        return $this->_get(self::NUMBER_ID);
    }

    /**
     * Set number_id
     * @param string $numberId
     * @return \Angel\Raffle\Api\Data\NumberInterface
     */
    public function setNumberId($numberId)
    {
        return $this->setData(self::NUMBER_ID, $numberId);
    }

    /**
     * Get prize_id
     * @return string|null
     */
    public function getPrizeId()
    {
        return $this->_get(self::PRIZE_ID);
    }

    /**
     * Set prize_id
     * @param string $prizeId
     * @return \Angel\Raffle\Api\Data\NumberInterface
     */
    public function setPrizeId($prizeId)
    {
        return $this->setData(self::PRIZE_ID, $prizeId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\Raffle\Api\Data\NumberExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Angel\Raffle\Api\Data\NumberExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\Raffle\Api\Data\NumberExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get number
     * @return int|null
     */
    public function getNumber()
    {
        return $this->_get(self::NUMBER);
    }

    /**
     * Set number
     * @param int $number
     * @return \Angel\Raffle\Api\Data\NumberInterface
     */
    public function setNumber($number)
    {
        return $this->setData(self::NUMBER, $number);
    }

    /**
     * Get count
     * @return int|null
     */
    public function getCount()
    {
        return $this->_get(self::COUNT);
    }

    /**
     * Set count
     * @param int $number
     * @return \Angel\Raffle\Api\Data\NumberInterface
     */
    public function setCount($count)
    {
        return $this->setData(self::COUNT, $count);
    }
}
