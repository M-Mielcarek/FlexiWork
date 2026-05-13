<?php

class User
{
    public static function getById($conn, $id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public static function create($conn, $data)
        {
        $sql = "INSERT INTO users(name, surname, pesel, email, phone)
                VALUES(?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssss",
            $data['name'],
            $data['surname'],
            $data['pesel'],
            $data['email'],
            $data['phone']
        );

        return $stmt->execute();
    }
}