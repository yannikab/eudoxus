<?php

/*
 * validate_institution.php: Returns true if parameter is a valid institution in db. Used for form validation.
 */

if (empty($_POST['institution'])) {
    echo "false";
    exit();
}

$institution = $_POST['institution'];

require_once('db_setup.php');

$inst = InstitutionQuery::create()
        ->findOneByName($institution);

if (is_null($inst)) {
    echo "false";
    exit();
}

echo "true";
//
?>
