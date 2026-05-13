<?php

class Shift
{
    public static function getShift($conn, $id)
    {
        $sql = "SELECT * FROM shifts WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
}