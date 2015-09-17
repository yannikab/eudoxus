<?php

/*
 * book_publisher.php: Handles publisher actions on specified book (available/unavailable)
 */

use \Propel\Runtime\ActiveQuery\Criteria as Crit;
?>

<?php

require_once('db_setup.php');

$publisher_id = (isset($_POST['publisher_id']) && $_POST['publisher_id'] != '') ? $_POST['publisher_id'] : null;
$book_id = (isset($_POST['book_id']) && $_POST['book_id'] != '') ? $_POST['book_id'] : null;
$action = (isset($_POST['action']) && $_POST['action'] != '') ? $_POST['action'] : null;

$userpublisher = new UserPublisher();
$userpublisher = UserPublisherQuery::create()->findOneByUserId($publisher_id);

// $book = new Book();
$book = BookQuery::create()->findOneByBookId($book_id);

if (is_null($userpublisher) || is_null($book) || is_null($action)) {
    echo '<span class="error">Συνέβη κάποιο σφάλμα κατά την δήλωση/αφαίρεση διαθεσιμότητας του συγγράμματος.</span>';
    exit();
}

if (!$book->getPublisher()->equals($userpublisher->getPublisher())) {
    echo '<span class="error">Δεν αντιστοιχείτε στον εκδότη αυτού του βιβλίου, δεν επιτρέπεται να το διαχειριστείτε.</span>';
    exit();
}

switch ($action) {
    case 'available':
        if ($book->getAvailable()) {
            echo '<span class="error">Το σύγγραμμα είναι ήδη διαθέσιμο.</span>';
            exit();
        } else {
            try {
                $book->setAvailable(true);
                $book->save();

                echo '<span class="notify">Το σύγγραμμα δηλώθηκε ως διαθέσιμο.</span>';
                //
            } catch (Exception $e) {

                do {
                    //$e = new Exception();
                    $logger->addError($e->getMessage());
                    $logger->addError($e->getTraceAsString());
                    $e = $e->getPrevious();
                } while (!is_null($e));

                echo '<span class="error">Συνέβη κάποιο σφάλμα κατά την δήλωση διαθεσιμότητας του συγγράμματος.</span>';
                exit();
            }
        }
        break;
    case 'unavailable':
        if (!$book->getAvailable()) {
            echo '<span class="error">Το σύγγραμμα είναι ήδη μη διαθέσιμο.</span>';
            exit();
        } else {
            try {
                $book->setAvailable(false);
                $book->save();

                echo '<span class="notify">Το σύγγραμμα δηλώθηκε ως μη διαθέσιμο.</span>';
                //
            } catch (Exception $e) {

                do {
                    //$e = new Exception();
                    $logger->addError($e->getMessage());
                    $logger->addError($e->getTraceAsString());
                    $e = $e->getPrevious();
                } while (!is_null($e));

                echo '<span class="error">Συνέβη κάποιο σφάλμα κατά την δήλωση μη διαθεσιμότητας του συγγράμματος.</span>';
                exit();
            }
        }
        break;
    default:
        echo '<span class="error">Ζητήθηκε κάποια άγνωστη ενέργεια για το σύγγραμμα.</span>';
        exit();
        break;
}
?>

<?php ?>
