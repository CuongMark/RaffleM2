<?php


namespace Angel\Raffle\Setup;

use Angel\Raffle\Model\Product\Type\Raffle;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'total_tickets',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Total Tickets',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => Raffle::TYPE_ID,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'prefix',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Prefix',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => Raffle::TYPE_ID,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'raffle_status',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Raffle Status',
                'input' => 'select',
                'class' => '',
                'source' => \Angel\Raffle\Model\Product\Attribute\Source\RaffleStatus::class,
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => Raffle::TYPE_ID,
                'system' => 1,
                'group' => 'General',
                'option' => []
            ]
        );

        // associate these attributes with new product type
        $fieldList = [
            'price'
        ];
        
        // make these attributes applicable to new product type
        foreach ($fieldList as $field) {
            $applyTo = explode(
                ',',
                $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field, 'apply_to')
            );
            if (!in_array(\Angel\Raffle\Model\Product\Type\Raffle::TYPE_ID, $applyTo)) {
                $applyTo[] = \Angel\Raffle\Model\Product\Type\Raffle::TYPE_ID;
                $eavSetup->updateAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $field,
                    'apply_to',
                    implode(',', $applyTo)
                );
            }
        }

        //Your install script
    }
}
