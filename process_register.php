<?php

/*
 * process_register.php: Handles user registration requests. 
 * If successful, creates correct type of new user, sets related data, saves user in db.
 * Otherwise, forces register page to display an error message.
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
?>

<?php

ob_start();

require_once('db_setup.php');

$u = new User();
$u->setUsername($_POST['username']);
$u->setPassword(md5($_POST['password']));
$u->setEmail($_POST['email']);

switch ($_POST['group']) {
    case 'students':
        $d = DepartmentQuery::create()
                ->findOneByName($_POST['department']);

        if (!is_null($d)) {
            $us = new UserStudent();
            $us->setDepartment($d);
            $us->setFirstName($_POST['firstname']);
            $us->setLastname($_POST['lastname']);
            $u->setUserStudent($us);
        }

        break;
    case 'publishers':
        $up = new UserPublisher();

        $p = new Publisher();
        $p->setName($_POST['pubname']);
        $up->setPublisher($p);

        $u->setUserPublisher($up);
        break;
    case 'secretariats':
        $d = DepartmentQuery::create()
                ->findOneByName($_POST['department']);

        if (!is_null($d)) {
            $us = new UserSecretariat();
            $us->setDepartment($d);
            $us->setFirstName($_POST['firstname']);
            $us->setLastname($_POST['lastname']);
            $u->setUserSecretariat($us);
        }

        break;
    default:
        break;
}

try {
    $u->save();

    // throw new Exception("registration error");

    header("Location: index.php?page=register&register_success");
    //
} catch (Exception $e) {
    // echo 'Caught exception: ', $e->getMessage(), "\n";    
    // $logger = new Logger('defaultLogger');
    do {
        //$e = new Exception();
        $logger->addError($e->getMessage());
        $logger->addError($e->getTraceAsString());
        $e = $e->getPrevious();
    } while (!is_null($e));

    header("Location: index.php?page=register&register_fail");
}

ob_end_flush();
//
?>
