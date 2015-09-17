<div class="breadcrumbs">
    <a href="index.php">Εύδοξος</a>
    &gt;
    <span>Προφίλ Χρήστη</span>
</div>
<br />
<p><strong>Πληροφορίες Εκδότη</strong></p>

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

    if ($group->getAlias() != $session_group)
        exit();

    $userpublisher = new UserPublisher();
    unset($userpublisher);
    $userpublisher = UserPublisherQuery::create()
            ->findOneByUser($user);

    if (is_null($userpublisher))
        exit();
    ?>
    
    
    <div>
        <table>
            <tbody>
                <tr><td>Επωνυμία:</td><td><?php echo $userpublisher->getPublisher()->getName(); ?></td></tr>
                <tr><td>Ηλεκτρονική διεύθυνση:</td><td><?php echo $userpublisher->getUser()->getEmail(); ?></td></tr>
            </tbody>
        </table>    
    </div>
    <br />

    <p><strong>Συγγράμματα Εκδότη</strong></p>

    <div>
        <?php
        $user_books = $userpublisher->getPublisher()->getBooks(BookQuery::create()->orderByTitle());

        if (!($user_books->count() > 0)) {
            echo 'Δεν έχετε συγγράμματα.';
            exit();
        } else {
            if ($user_books->count() == 1) {
                echo 'Έχετε 1 σύγγραμμα.';
            } else {
                echo 'Έχετε ' . $user_books->count() . ' συγγράμματα.';
            }
        }
        ?>
    </div>

    <table class="books">
        <tr><th>Τίτλος</th><th class="avail">Διαθέσιμο</th></tr>
            <?php foreach ($user_books as $b) : ?><?php // $b = new Book(); ?>
                <tr><td>
                        <a href="index.php?page=bookinfo&amp;user_id=<?php echo $userpublisher->getUserId(); ?>&amp;book_id=<?php echo $b->getBookId(); ?>">
                            <?php echo htmlentities($b->getTitle()); ?>
                        </a>
                    </td>
                    <td class="avail"><?php echo $b->getAvailable() ? 'Ναι' : 'Όχι' ?></td>
                </tr>
            <?php endforeach; ?>
    </table>
</div>
