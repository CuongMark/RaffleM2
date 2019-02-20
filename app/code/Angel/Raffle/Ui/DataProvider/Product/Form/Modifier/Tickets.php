<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\Raffle\Ui\DataProvider\Product\Form\Modifier;

/**
 * Class Review
 */

use Angel\Raffle\Model\Product\Attribute\Source\RaffleStatus;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form;
use Magento\Framework\UrlInterface;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Framework\App\ObjectManager;

/**
 * Review modifier for catalog product form
 *
 * @api
 * @since 100.1.0
 */
class Tickets extends AbstractModifier
{
    const GROUP_TICKETS = 'tickets';
    const GROUP_CONTENT = 'content';
    const DATA_SCOPE_REVIEW = 'grouped';
    const SORT_ORDER = 20;
    const LINK_TYPE = 'associated';

    /**
     * @var LocatorInterface
     * @since 100.1.0
     */
    protected $locator;

    /**
     * @var UrlInterface
     * @since 100.1.0
     */
    protected $urlBuilder;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @param LocatorInterface $locator
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder
    ) {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritdoc}
     * @since 100.1.0
     */
    public function modifyMeta(array $meta)
    {
        if (!$this->locator->getProduct()->getId()
            || $this->locator->getProduct()->getTypeId() != \Angel\Raffle\Model\Product\Type\Raffle::TYPE_ID
            || $this->locator->getProduct()->getRaffleStatus() == RaffleStatus::PENDING
            || !$this->getModuleManager()->isOutputEnabled('Angel_Raffle')) {
            return $meta;
        }

        $meta[static::GROUP_TICKETS] = [
            'children' => [
                'ticket_listing' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => true,
                                'componentType' => 'insertListing',
                                'dataScope' => 'ticket_listing',
                                'externalProvider' => 'ticket_listing.ticket_listing_data_source',
                                'selectionsProvider' => 'ticket_listing.ticket_listing.product_columns.ids',
                                'ns' => 'ticket_listing',
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => false,
                                'behaviourType' => 'simple',
                                'externalFilterMode' => true,
                                'imports' => [
                                    'productId' => '${ $.provider }:data.product.current_product_id'
                                ],
                                'exports' => [
                                    'productId' => '${ $.externalProvider }:params.current_product_id'
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Tickets'),
                        'collapsible' => true,
                        'opened' => false,
                        'componentType' => Form\Fieldset::NAME,
                        'sortOrder' =>
                            $this->getNextGroupSortOrder(
                                $meta,
                                static::GROUP_CONTENT,
                                static::SORT_ORDER
                            ),
                    ],
                ],
            ],
        ];

        return $meta;
    }

    /**
     * {@inheritdoc}
     * @since 100.1.0
     */
    public function modifyData(array $data)
    {
        $productId = $this->locator->getProduct()->getId();

        $data[$productId][self::DATA_SOURCE_DEFAULT]['current_product_id'] = $productId;

        return $data;
    }

    /**
     * Retrieve module manager instance using dependency lookup to keep this class backward compatible.
     *
     * @return ModuleManager
     *
     * @deprecated 100.2.0
     */
    private function getModuleManager()
    {
        if ($this->moduleManager === null) {
            $this->moduleManager = ObjectManager::getInstance()->get(ModuleManager::class);
        }
        return $this->moduleManager;
    }
}
