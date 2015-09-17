<?php

/*
 * validate_username.php: Returns true if parameter is an existing user in db. Used for form validation (user registration page).
 */

if (empty($_POST['username'])) {
    echo "false";
    exit();
}

$username = $_POST['username'];

require_once('db_setup.php');

$user = UserQuery::create()
        ->findOneByUsername($username);

if (!is_null($user)) {
    echo "false";
    exit();
}

echo "true";
//
?>
