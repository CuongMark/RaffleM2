<?php


namespace Angel\Raffle\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        //Your install script

        $table_angel_raffle_prize = $setup->getConnection()->newTable($setup->getTable('angel_raffle_prize'));

        $table_angel_raffle_prize->addColumn(
            'prize_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_angel_raffle_prize->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product Id'
        );

        $table_angel_raffle_prize->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            256,
            ['nullable' => False],
            'Prize Name'
        );

        $table_angel_raffle_prize->addColumn(
            'prize',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => False,'precision' => 12,'scale' => 4],
            'Prize'
        );

        $table_angel_raffle_prize->addColumn(
            'total',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'Total Prize'
        );

        $table_angel_raffle_prize->addForeignKey(
            $setup->getFkName('angel_raffle_prize', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $table_angel_raffle_number = $setup->getConnection()->newTable($setup->getTable('angel_raffle_number'));

        $table_angel_raffle_number->addColumn(
            'number_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_angel_raffle_number->addColumn(
            'prize_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False,'unsigned' => true],
            'Prize Id'
        );

        $table_angel_raffle_number->addColumn(
            'number',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'Winning Number'
        );

        $table_angel_raffle_number->addForeignKey(
            $setup->getFkName('angel_raffle_number', 'prize_id', 'angel_raffle_prize', 'prize_id'),
            'prize_id',
            $setup->getTable('angel_raffle_prize'),
            'prize_id',
            Table::ACTION_CASCADE
        );

        $table_angel_raffle_ticket = $setup->getConnection()->newTable($setup->getTable('angel_raffle_ticket'));

        $table_angel_raffle_ticket->addColumn(
            'ticket_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_angel_raffle_ticket->addColumn(
            'start',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'Start Number'
        );

        $table_angel_raffle_ticket->addColumn(
            'end',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'End Number'
        );

        $table_angel_raffle_ticket->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['default' => '0','nullable' => False,'precision' => 12,'scale' => 4],
            'Ticket Price'
        );

        $table_angel_raffle_ticket->addColumn(
            'prize',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['default' => '0','precision' => 12,'scale' => 4],
            'Winning Prize'
        );

        $table_angel_raffle_ticket->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product Id'
        );


        $table_angel_raffle_ticket->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Customer Id'
        );

        $table_angel_raffle_ticket->addColumn(
            'transaction_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'Credit Transaction Id'
        );

        $table_angel_raffle_ticket->addColumn(
            'payout_transaction_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Payout for winning credit transaction Id'
        );

        $table_angel_raffle_ticket->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['default' => new \Zend_Db_Expr('CURRENT_TIMESTAMP'),'nullable' => False],
            'Created At'
        );

        $table_angel_raffle_ticket->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            [],
            'Raffle Status'
        );

        $table_angel_raffle_ticket->addIndex(
            $setup->getIdxName(
                'angel_raffle_ticket',
                ['product_id', 'start'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['product_id', 'start'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        );

        $table_angel_raffle_ticket->addForeignKey(
            $setup->getFkName('angel_raffle_ticket', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $table_angel_raffle_ticket->addForeignKey(
            $setup->getFkName('angel_raffle_ticket', 'customer_id', 'customer_entity', 'entity_id'),
            'customer_id',
            $setup->getTable('customer_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $setup->getConnection()->createTable($table_angel_raffle_prize);
        $setup->getConnection()->createTable($table_angel_raffle_number);
        $setup->getConnection()->createTable($table_angel_raffle_ticket);
    }
}
