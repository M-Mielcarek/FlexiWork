<?php

session_start();

require_once "../../app/config/database.php";
require_once "../../path.php";


$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM users
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if ($password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['surname'] = $user['surname'];
        $_SESSION['role'] = $user['role'];
        header("Location: " . BASE_URL . "index.php");
        exit;
    } else {
        echo "Nieprawidłowe hasło.";
    }

} else {
    echo "Nie znaleziono użytkownika.";
}