<?php

abstract class BaseController
{
    protected mixed $instance = null;

    public abstract static function getInstance();

    abstract function getAll();

    abstract function getByGuid(string $guid);

    abstract function getFiltered(mixed $filterItem);

    abstract function tryCreate(mixed $item);

    abstract function tryUpdate(?string $guid, mixed $newItem);

    abstract function tryDelete(string $guid);
}