<?php

class Exchange
{
    public static function getAll($conn)
    {
        $sql = "SELECT * FROM exchanges";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}