<?php

class Schedule
{
    public static function getUserSchedule($conn, $userId)
    {
        $sql = "SELECT * FROM schedules WHERE user_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}