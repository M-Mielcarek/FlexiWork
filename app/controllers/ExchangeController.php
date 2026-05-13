<?php

require_once ROOT_PATH . "app/models/Exchange.php";

class ExchangeController
{
    public static function index()
    {
        return Exchange::getAll($GLOBALS['conn']);
    }
}