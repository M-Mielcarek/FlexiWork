<?php

class ScheduleController
{
    public static function userSchedule($conn, $userId)
    {
        $sql = "
        SELECT

            schedules.*,
            users.hourly_rate

        FROM schedules

        JOIN users
        ON users.id = schedules.user_id

        WHERE schedules.user_id = ?

        ORDER BY schedules.work_date ASC
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $userId);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

public static function userScheduleByMonth(
    $conn,
    $userId,
    $month,
    $year
) {

    $sql = "
    SELECT
        schedules.*,
        users.day_rate,
        users.night_rate

    FROM schedules

    JOIN users
    ON users.id = schedules.user_id

    WHERE schedules.user_id = ?

    AND MONTH(work_date) = ?

    AND YEAR(work_date) = ?

    ORDER BY work_date ASC
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iii",
        $userId,
        $month,
        $year
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $schedules = [];

    while($row = $result->fetch_assoc()) {

        $schedules[] = $row;
    }

    return $schedules;
}
}