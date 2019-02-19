<?php


namespace Angel\Raffle\Model;

class TrashManagement implements \Angel\Raffle\Api\TrashManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function postTrash($param)
    {
        return 'hello api POST return the $param ' . $param;
    }
}
