<?php


namespace Angel\Raffle\Api;

interface TrashManagementInterface
{

    /**
     * POST for trash api
     * @param string $param
     * @return string
     */
    public function postTrash($param);
}
