<?php

/*
 * process_login.php: Processes login requests
 * If successful, stores logged in user's data in PHP session for use by other pages
 * Otherwise, forces login page to display an error message
 */

ob_start();

session_start();

require_once('db_setup.php');

$username = $_POST['username'];
$password = $_POST['password'];

//$users = UserQuery::create()->filterByUsername($username)->filterByPassword($password);
//$usersfind = UserQuery::create()->filterByUsername($username)->filterByPassword($password)->find();
//    if ($users->count() == 1) { ...
//
//foreach ($users as $user) {
//    echo $user->getUserName() . "<br />";
//}
//    
//        $u = UserQuery::create()
//                ->filterByUsername($username)
//                ->findOneByPassword($password);
//        
// $u = new User();
$u = UserQuery::create()
        ->filterByUsername($username)
        ->filterByPassword(md5($password))
        ->findOne();

if ($u != NULL) {
    session_unset();

    //    session_register("username");
    //    session_register("password");

    $_SESSION['user_id'] = $u->getUserId();
    $_SESSION['username'] = $u->getUsername();
    $_SESSION['password'] = $u->getPassword();
    $_SESSION['fullname'] = $u->getName();
    $_SESSION['dept_id'] = $u->getDeptId();
    $_SESSION['publisher_id'] = $u->getPublisherId();
    
    // $g = new Group();
    $g = $u->getGroups()[0];
    if (!is_null($g)) {
        $_SESSION['group'] = $g->getAlias();
        $_SESSION['groupname'] = $g->getName();
    }
    header("Location: index.php");
//    header("Location: process_profile.php");
} else {
    header("Location: index.php?page=login&login_fail");
}

ob_end_flush();
//
?>
