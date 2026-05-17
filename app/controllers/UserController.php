<?php

require_once ROOT_PATH . "app/config/database.php";

class UserController
{
    public static function store()
    {
        global $conn;

        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $password = $_POST['password'];


        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "
        INSERT INTO users (

            name,
            surname,
            email,
            phone,
            password,
            role

        )

        VALUES (

            ?, ?, ?, ?, ?, 'user'

        )
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssss",
            $name,
            $surname,
            $email,
            $phone,
            $hashedPassword
        );

        $stmt->execute();

        header(
            "Location: " .BASE_URL ."public/lista_uzytkownikow.php"
        );

        exit;
    }
}