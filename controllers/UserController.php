<?php

require_once "database/db.php";
require_once "models/Item.php";
require_once "utils/helpers.php";

class UserController extends BaseController
{
    public static function getInstance()
    {
        if (empty($instance)) {
            $instance = new UserController();
        }

        return $instance;
    }

    function getByGuid(string $guid): ?Item
    {
    }

    function tryCreate(mixed $item): bool
    {
    }

    function getAll()
    {
        throw new Exception("Method is obsolete for this class");
    }

    function getFiltered(mixed $filterItem)
    {
        throw new Exception("Method is obsolete for this class");
    }

    function tryUpdate(?string $guid, mixed $newItem): bool
    {
        throw new Exception("Method is obsolete for this class");
    }

    function tryDelete(string $guid): bool
    {
        throw new Exception("Method is obsolete for this class");
    }
}