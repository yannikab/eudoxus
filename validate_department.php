<?php

/*
 * validate_department.php: Returns true if parameter is a valid department in db. Used for form validation.
 */

if (empty($_POST['department'])) {
    echo "false";
    exit();
}

$department = $_POST['department'];

require_once('db_setup.php');

$dept = DepartmentQuery::create()
        ->findOneByName($department);

if (is_null($dept)) {
    echo "false";
    exit();
}

echo "true";
//
?>
