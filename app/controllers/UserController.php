<?php

require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/models/User.php";

class UserController
{
    public static function profile()
    {
        $userId = $_SESSION['user_id'];

        return User::getById($GLOBALS['conn'], $userId);
    }

    public static function store()
    {
        $data = [
            'name' => $_POST['name'],
            'surname' => $_POST['surname'],
            'pesel' => $_POST['pesel'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone']
        ];

        User::create($GLOBALS['conn'], $data);

        header("Location: " . BASE_URL . "public/lista_uzytkownikow.php");
        exit;
    }
}