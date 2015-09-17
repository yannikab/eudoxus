<?php

/*
 * process_browse.php: Used while browsing books by department.
 * If the user belongs to a department, redirect to the specific department, otherwise browse from root (all institutions)
 */

ob_start();

session_start();

if (isset($_SESSION['dept_id'])) { // prefer user selection
    $display_dept = $_SESSION['dept_id'];
}

require_once('db_setup.php');

// $dept = new Department();
$dept = DepartmentQuery::create()
        ->findOneByDeptId($display_dept);

if (is_null($dept)) {
    header("Location: index.php?page=institutions");
} else {
    header("Location: index.php?page=courses&dept_id=" . $dept->getDeptId());
}

ob_end_flush();
//
?>
