<?php

/*
 * book_student.php: Handles student actions on specified book (declare/remove)
 */

use \Propel\Runtime\ActiveQuery\Criteria as Crit;
?>

<?php

require_once('db_setup.php');

$student_id = (isset($_POST['student_id']) && $_POST['student_id'] != '') ? $_POST['student_id'] : null;
$book_id = (isset($_POST['book_id']) && $_POST['book_id'] != '') ? $_POST['book_id'] : null;
$action = (isset($_POST['action']) && $_POST['action'] != '') ? $_POST['action'] : null;

// $userstudent = new UserStudent();
$userstudent = UserStudentQuery::create()->findOneByUserId($student_id);

// $book = new Book();
$book = BookQuery::create()->findOneByBookId($book_id);

if (is_null($userstudent) || is_null($book) || is_null($action)) {
    echo '<span class="error">Συνέβη κάποιο σφάλμα κατά την δήλωση/αφαίρεση του συγγράμματος.</span>';
    exit();
}

switch ($action) {
    case 'declare':
        $userstudentbook = UserStudentBookQuery::create()
                ->filterByBook($book)
                ->filterByUserStudent($userstudent)
                ->findOne();

        if (!is_null($userstudentbook)) {
            echo '<span class="error">Έχετε ήδη δηλώσει το σύγγραμμα αυτό.</span>';
            exit();
            //
        } else {
            if (!$book->getAvailable()) {
                echo '<span class="error">Το σύγγραμμα δεν είναι διαθέσιμο προς δήλωση.</span>';
                exit();
            }

            $userstudentbook = new UserStudentBook();
            $userstudentbook->setUserStudent($userstudent);
            $userstudentbook->setBook($book);

            try {
                $userstudentbook->save();

                echo '<span class="notify">Το σύγγραμμα δηλώθηκε επιτυχώς.</span>';
                //
            } catch (Exception $e) {

                do {
                    //$e = new Exception();
                    $logger->addError($e->getMessage());
                    $logger->addError($e->getTraceAsString());
                    $e = $e->getPrevious();
                } while (!is_null($e));

                echo '<span class="error">Συνέβη κάποιο σφάλμα κατά την δήλωση του συγγράμματος.</span>';
                exit();
            }
        }

        break;
    case 'remove':
        $userstudentbook = UserStudentBookQuery::create()
                ->filterByBook($book)
                ->filterByUserStudent($userstudent)
                ->findOne();

        if (is_null($userstudentbook)) {
            echo '<span class="error">Δεν έχετε δηλώσει το σύγγραμμα αυτό.</span>';
            exit();
            //
        } else {
            try {
                $userstudentbook->delete();

                echo '<span class="notify">Το σύγγραμμα αφαιρέθηκε επιτυχώς.</span>';
                //
            } catch (Exception $e) {

                do {
                    //$e = new Exception();
                    $logger->addError($e->getMessage());
                    $logger->addError($e->getTraceAsString());
                    $e = $e->getPrevious();
                } while (!is_null($e));

                echo '<span class="error">Συνέβη κάποιο σφάλμα κατά την αφαίρεση του συγγράμματος.</span>';
                exit();
            }
        }
        break;
    default:
        echo '<span class="error">Ζητήθηκε κάποια άγνωστη ενέργεια για το βιβλίο.</span>';
        exit();
        break;
}
?>

<?php
?>
