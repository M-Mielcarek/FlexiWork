<?php

require_once ROOT_PATH . "app/models/Schedule.php";

class ScheduleController
{
    public static function userSchedule()
    {
        return Schedule::getUserSchedule(
            $GLOBALS['conn'],
            $_SESSION['user_id']
        );
    }
}