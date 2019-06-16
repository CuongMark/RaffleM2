<?php


namespace Angel\Raffle\Api\Data;

interface NumberInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRIZE_ID = 'prize_id';
    const NUMBER = 'number';
    const COUNT = 'count';
    const NUMBER_ID = 'number_id';

    /**
     * Get number_id
     * @return int|null
     */
    public function getNumberId();

    /**
     * Set number_id
     * @param int $numberId
     * @return \Angel\Raffle\Api\Data\NumberInterface
     */
    public function setNumberId($numberId);

    /**
     * Get prize_id
     * @return int|null
     */
    public function getPrizeId();

    /**
     * Set prize_id
     * @param int $prizeId
     * @return \Angel\Raffle\Api\Data\NumberInterface
     */
    public function setPrizeId($prizeId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\Raffle\Api\Data\NumberExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Angel\Raffle\Api\Data\NumberExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\Raffle\Api\Data\NumberExtensionInterface $extensionAttributes
    );

    /**
     * Get number
     * @return int|null
     */
    public function getNumber();

    /**
     * Set number
     * @param int $number
     * @return \Angel\Raffle\Api\Data\NumberInterface
     */
    public function setNumber($number);
    /**
     * Get count
     * @return int|null
     */
    public function getCount();

    /**
     * Set count
     * @param int $number
     * @return \Angel\Raffle\Api\Data\NumberInterface
     */
    public function setCount($number);
}
