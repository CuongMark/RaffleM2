<?php
/**
 * Angel Raffle Raffles
 * Copyright (C) 2018 Mark Wolf
 *
 * This file included in Angel/Raffle is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Angel\Raffle\Observer\Frontend\Page;

use Magento\Framework\Data\Tree\Node;
use Magento\Framework\UrlInterface;

class BlockHtmlTopmenuGethtmlBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        UrlInterface $urlBuilder
    ){
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $data = [
            'name'      => __('Raffle'),
            'id'        => 'raffle_menu_item',
            'url'       => $this->urlBuilder->getUrl('raffle'),
            'is_active' => false
        ];
        $node = new Node($data, 'id', $tree, $menu);
        $menu->addChild($node);

        $data = [
            'name'      => __('Finished'),
            'id'        => 'raffle_finished_menu_item',
            'url'       => $this->urlBuilder->getUrl('raffle/index/finished'),
            'is_active' => false
        ];
        $processing = new Node($data, 'id', $tree, $node);
        $node->addChild($processing);

    }
}