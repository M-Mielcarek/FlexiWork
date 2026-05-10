<?php

session_start();

require_once "../../config/database.php";
require_once "../../path.php";

$userId = $_SESSION['user_id'] ?? null;

$sql = "SELECT role FROM users WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();



if ($user['role'] !== 'manager') {

    die("Brak dostępu");
}