<?php


namespace Angel\Raffle\Api\Data;

interface NumberInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRIZE_ID = 'prize_id';
    const NUMBER = 'number';
    const NUMBER_ID = 'number_id';

    /**
     * Get number_id
     * @return string|null
     */
    public function getNumberId();

    /**
     * Set number_id
     * @param string $numberId
     * @return \Angel\Raffle\Api\Data\NumberInterface
     */
    public function setNumberId($numberId);

    /**
     * Get prize_id
     * @return string|null
     */
    public function getPrizeId();

    /**
     * Set prize_id
     * @param string $prizeId
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
     * @return string|null
     */
    public function getNumber();

    /**
     * Set number
     * @param string $number
     * @return \Angel\Raffle\Api\Data\NumberInterface
     */
    public function setNumber($number);
}
