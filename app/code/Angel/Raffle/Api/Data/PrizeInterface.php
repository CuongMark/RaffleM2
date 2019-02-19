<?php


namespace Angel\Raffle\Api\Data;

interface PrizeInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRIZE_ID = 'prize_id';
    const TOTAL = 'total';
    const PRODUCT_ID = 'product_id';
    const NAME = 'name';
    const PRIZE = 'prize';

    /**
     * Get prize_id
     * @return string|null
     */
    public function getPrizeId();

    /**
     * Set prize_id
     * @param string $prizeId
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     */
    public function setPrizeId($prizeId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     */
    public function setProductId($productId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\Raffle\Api\Data\PrizeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Angel\Raffle\Api\Data\PrizeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\Raffle\Api\Data\PrizeExtensionInterface $extensionAttributes
    );

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     */
    public function setName($name);

    /**
     * Get prize
     * @return float|null
     */
    public function getPrize();

    /**
     * Set prize
     * @param float $prize
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     */
    public function setPrize($prize);

    /**
     * Get total
     * @return int|null
     */
    public function getTotal();

    /**
     * Set total
     * @param int $total
     * @return \Angel\Raffle\Api\Data\PrizeInterface
     */
    public function setTotal($total);
}
