<?php

require_once ROOT_PATH . "app/models/Shift.php";

class ShiftController
{
    public static function show($id)
    {
        return Shift::getShift($GLOBALS['conn'], $id);
    }
}