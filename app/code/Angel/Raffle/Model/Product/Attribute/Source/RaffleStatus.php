<?php


namespace Angel\Raffle\Model\Product\Attribute\Source;

class RaffleStatus extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const PENDING = 0;
    const PROCESSING = 1;
    const FINISHED = 2;
    const CANCELED = 3;
    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => self::PENDING, 'label' => __('Pending')],
            ['value' => self::PROCESSING, 'label' => __('Processing')],
            ['value' => self::FINISHED, 'label' => __('Finished')],
            ['value' => self::CANCELED, 'label' => __('Canceled')]
        ];
        return $this->_options;
    }

    public static function OptionsArray(){
        return [
            ['value' => self::PENDING, 'label' => __('Pending')],
            ['value' => self::PROCESSING, 'label' => __('Processing')],
            ['value' => self::FINISHED, 'label' => __('Finished')],
            ['value' => self::CANCELED, 'label' => __('Canceled')]
        ];
    }

    public static function Options(){
        return [
            [self::PENDING => __('Pending')],
            [self::PROCESSING => __('Processing')],
            [self::FINISHED => __('Finished')],
            [self::CANCELED => __('Canceled')]
        ];
    }

    /**
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
        $attributeCode => [
        'unsigned' => false,
        'default' => null,
        'extra' => null,
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        'length' => 255,
        'nullable' => true,
        'comment' => $attributeCode . ' column',
        ],
        ];
    }

    /**
     * @return array
     */
    public function getFlatIndexes()
    {
        $indexes = [];
        
        $index = 'IDX_' . strtoupper($this->getAttribute()->getAttributeCode());
        $indexes[$index] = ['type' => 'index', 'fields' => [$this->getAttribute()->getAttributeCode()]];
        
        return $indexes;
    }

    /**
     * @param int $store
     * @return \Magento\Framework\DB\Select|null
     */
    public function getFlatUpdateSelect($store)
    {
        return $this->eavAttrEntity->create()->getFlatUpdateSelect($this->getAttribute(), $store);
    }
}
