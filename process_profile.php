<?php

/*
 * process_profile.php: Redirects user to correct type of profile page according to user group (student, publisher, secretariat)
 */

ob_start();

session_start();

if (isset($_SESSION['group'])) {
    $session_group = $_SESSION['group'];
}

require_once('db_setup.php');

if (is_null(GroupQuery::create()->findOneByAlias($session_group))) {
    header("Location: index.php");
    ob_end_flush();
    exit();
}

if (isset($_SESSION['user_id'])) {
    $session_user_id = $_SESSION['user_id'];
}

// $user = new User();
$user = UserQuery::create()->findOneByUserId($session_user_id);

if (is_null($user)) {
    header("Location: index.php");
    ob_end_flush();
    exit();
}

// $group = new Group();
$group = $user->getGroups()[0];

if ($group->getAlias() != $session_group) {
    header("Location: index.php");
    ob_end_flush();
    exit();
}

switch ($group->getAlias()) {
    case 'students':
        $userstudent = UserStudentQuery::create()
                ->findOneByUser($user);

        if (is_null($userstudent)) {
            header("Location: index.php");
        } else {
            header("Location: index.php?page=profilestudent");
        }

        break;
    case 'secretariats':
        $usersecretariat = UserSecretariatQuery::create()
                ->findOneByUser($user);

        if (is_null($usersecretariat)) {
            header("Location: index.php");
        } else {
            header("Location: index.php?page=profilesecretariat");
        }

        break;
    case 'publishers':
        $userpublisher = UserPublisherQuery::create()
                ->findOneByUser($user);

        if (is_null($userpublisher)) {
            header("Location: index.php");
        } else {
            header("Location: index.php?page=profilepublisher");
        }

        break;
    default:
        break;
}

ob_end_flush();
//
?>
