<?php


namespace Angel\Raffle\Api;

interface PurchaseManagementInterface
{

    /**
     * POST for purchase api
     * @param int $product
     * @param int $qty
     * @param int $customerId
     * @return \Angel\Raffle\Api\Data\TicketInterface
     */
    public function postPurchase($product, $qty, $customerId);
}
