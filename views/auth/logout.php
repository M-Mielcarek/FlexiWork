<?php

session_start();
session_destroy();

require_once "../../path.php";

header("Location: " . BASE_URL . "views/auth/login.php");
exit;