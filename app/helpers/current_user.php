<?php

$userId = $_SESSION['user_id'];

$sql = "
SELECT *
FROM users
WHERE id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $userId);

$stmt->execute();

$result = $stmt->get_result();

$currentUser = $result->fetch_assoc();