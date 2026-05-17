<?php

if (!isset($_SESSION['user_id'])) {

    header(
        "Location: " .
        BASE_URL .
        "views/auth/login_form.php"
    );

    exit;
}