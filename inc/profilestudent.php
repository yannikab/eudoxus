<div class="breadcrumbs">
    <a href="index.php">Εύδοξος</a>
    &gt;
    <span>Προφίλ Χρήστη</span>
</div>
<br />
<p><strong>Πληροφορίες Φοιτητή</strong></p>
<div class="userprofile">
    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    if (isset($_SESSION['group'])) {
        $session_group = $_SESSION['group'];
    }

    require_once('db_setup.php');

    if (is_null(GroupQuery::create()->findOneByAlias($session_group))) {
        exit();
    }

    if (isset($_SESSION['user_id'])) {
        $session_user_id = $_SESSION['user_id'];
    }

    // $user = new User();
    $user = UserQuery::create()->findOneByUserId($session_user_id);

    if (is_null($user)) {
        exit();
    }

    // $group = new Group();
    $group = $user->getGroups()[0];

    if ($group->getAlias() != $session_group) {
        exit();
    }

    // $userstudent = new UserStudent();
    // unset($userstudent);
    $userstudent = UserStudentQuery::create()
            ->findOneByUser($user);

    if (is_null($userstudent))
        exit();
    ?>

    <?php
//    echo $group->getAlias();
//    echo '<br />';
    ?>

    <div>
        <table>
            <tbody>
                <tr><td>Επώνυμο:</td><td><?php echo $userstudent->getLastname(); ?></td></tr>
                <tr><td>Όνομα:</td><td><?php echo $userstudent->getFirstname(); ?></td></tr>
                <tr><td>Ηλεκτρονική διεύθυνση:</td><td><?php echo $userstudent->getUser()->getEmail(); ?></td></tr>
                <tr><td>Ίδρυμα:</td><td><?php echo $userstudent->getDepartment()->getInstitution()->getName(); ?></td></tr>
                <tr><td>Τμήμα:</td><td><?php echo $userstudent->getDepartment()->getName(); ?></td></tr>
            </tbody>
        </table>    
    </div>
    <br />
    <p><strong>Συγγράμματα που έχουν δηλωθεί</strong></p>

    <div>
        <?php
//    $userstudent_query = UserStudentQuery::create()->use
//    echo $user_books->count();
//    echo '<br />';
        // $b = new Book();
//    foreach ($user_books as $b) {
//        echo $b->getTitle();
//        echo '<br />';
//    }

        $user_books = $userstudent->getBooks();

        if (!($user_books->count() > 0)) {
            echo 'Δεν έχετε δηλώσει συγγράμματα.';
        } else {
            if ($user_books->count() == 1) {
                echo 'Έχετε δηλώσει 1 σύγγραμμα.';
            } else {
                echo 'Έχετε δηλώσει ' . $user_books->count() . ' συγγράμματα.';
            }
        }
        ?>
    </div>

    <ol>
        <?php foreach ($user_books as $b) : ?><?php // $b = new Book();    ?>
            <li>
                <a href="index.php?page=bookinfo&amp;user_id=<?php echo $userstudent->getUserId(); ?>&amp;book_id=<?php echo $b->getBookId(); ?>">
                    <?php echo htmlentities($b->getTitle()); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ol>
</div>
