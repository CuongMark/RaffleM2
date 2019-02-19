<?php


namespace Angel\Raffle\Model\Data;

use Angel\Raffle\Api\Data\PrizeInterface;

class Prize extends \Magento\Framework\Api\AbstractExtensibleObject implements PrizeInterface
{

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
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     */
    public function setPrizeId($prizeId)
    {
        return $this->setData(self::PRIZE_ID, $prizeId);
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
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\Raffle\Api\Data\PrizeExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Angel\Raffle\Api\Data\PrizeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\Raffle\Api\Data\PrizeExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get name
     * @return string|null
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get prize
     * @return string|null
     */
    public function getPrize()
    {
        return $this->_get(self::PRIZE);
    }

    /**
     * Set prize
     * @param string $prize
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     */
    public function setPrize($prize)
    {
        return $this->setData(self::PRIZE, $prize);
    }

    /**
     * Get total
     * @return string|null
     */
    public function getTotal()
    {
        return $this->_get(self::TOTAL);
    }

    /**
     * Set total
     * @param string $total
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     */
    public function setTotal($total)
    {
        return $this->setData(self::TOTAL, $total);
    }
}
