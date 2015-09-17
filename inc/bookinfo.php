<script type = "text/javascript" src="js/bookinfo-initialize.js"></script>

<div class="bookinfo">
    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    //
    require_once('db_setup.php');

    if (isset($_GET['course_id'])) {
        $url_course_id = $_GET['course_id'];

        // $course = new Course();
        $course_query = CourseQuery::create()
                ->filterByCourseId($url_course_id);

        $course = $course_query->findOne();

        //    if (is_null($course)) {
        //        exit();
        //    }
    } else if (isset($_GET['publisher_id'])) {
        $url_publisher_id = $_GET['publisher_id'];

        $publisher_query = PublisherQuery::create()
                ->filterByPublisherId($url_publisher_id);

        $publisher = new Publisher();
        unset($publisher);
        $publisher = $publisher_query->findOne();
        //
    } else if (isset($_GET['user_id'])) {
        $url_user_id = $_GET['user_id'];

        // $userstudent = new UserStudent();
        $user_query = UserQuery::create()
                ->filterByUserId($url_user_id);

        $user = $user_query->findOne();
        //
    }

    $url_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : -1;
    
    $book = new Book();
    unset($book);
    
    $book = BookQuery::create()
            ->findOneByBookId($url_book_id);

    if (is_null($book)) {
        exit();
    }
    //
    ?>

    <?php if (!is_null($course)) : ?>
        <div class="breadcrumbs">
            <a href="index.php">Εύδοξος</a>
            &gt;
            <a href="index.php?page=institutions">Ιδρύματα</a>
            &gt;
            <a href="index.php?page=departments&amp;inst_id=<?php echo $course->getSemester()->getDepartment()->getInstitution()->getInstId() ?>"><?php echo htmlentities($course->getSemester()->getDepartment()->getInstitution()->getName()); ?></a>
            &gt;
            <a href="index.php?page=courses&amp;dept_id=<?php echo $course->getSemester()->getDepartment()->getDeptId() ?>"><?php echo htmlentities($course->getSemester()->getDepartment()->getName()); ?></a>
            &gt;
            <a href="index.php?page=courses&amp;dept_id=<?php echo $course->getSemester()->getDepartment()->getDeptId() ?>&amp;period=<?php echo $course->getSemester()->getPeriod(); ?>"><?php echo htmlentities($course->getSemester()->getName()); ?></a>
            &gt;
            <a href="index.php?page=courseinfo&amp;course_id=<?php echo $course->getCourseId(); ?>"><?php echo htmlentities($course->getName()); ?></a>
            &gt
            <span><?php echo htmlentities($book->getTitle()); ?></span>
        </div>
    <?php elseif (!is_null($publisher)) : ?>
        <div class="breadcrumbs">
            <a href="index.php">Εύδοξος</a>
            &gt;
            <a href="index.php?page=publishers">Εκδότες</a>
            &gt;
            <a href="index.php?page=publisherbooks&amp;publisher_id=<?php echo $publisher->getPublisherId(); ?>"><?php echo $publisher->getName(); ?></a>
            &gt;
            <span><?php echo htmlentities($book->getTitle()); ?></span>
        </div>
    <?php elseif (!is_null($user)) : ?>
        <div class="breadcrumbs">
            <a href="index.php">Εύδοξος</a>
            &gt;
            <a href="process_profile.php">Προφίλ Χρήστη</a>
            &gt
            <span><?php echo htmlentities($book->getTitle()); ?></span>
        </div>

    <?php else : ?>
        <div class="breadcrumbs">
            <a href="index.php">Εύδοξος</a>
            &gt;
            <a href="index.php?page=booksearch">Αναζήτηση Συγγραμμάτων</a>
            &gt
            <span><?php echo htmlentities($book->getTitle()); ?></span>
        </div>
    <?php endif; ?>

    <br />

    <p><img src="data:image/jpeg;base64,<?php echo $book->getCoverBase64(); ?>" /></p>
    <!--<p><img src="jpeg_cover.php?book_id=<?php echo $book->getBookId(); ?>" /></p>-->

    <p><strong>Πληροφορίες Συγγράμματος</strong></p>

    <table class="bookinfo">
        <tbody>
            <tr>
                <td><div class="label">Κωδικός:</div></td>
                <td><?php echo htmlentities($book->getCode()); ?></td>
            </tr>
            <tr>
                <td><div class="label">Τίτλος:</div></td>
                <td><?php echo htmlentities($book->getTitle()); ?></td>
            </tr>
            <tr>
                <td><div class="label">Συγγραφέας:</div></td>
                <td><?php echo htmlentities($book->getAuthor()); ?></td>
            </tr>
            <tr>
                <td><div class="label">Εκδότης:</div></td>
                <td><?php echo htmlentities($book->getPublisher()->getName()); ?></td>
            </tr>
            <tr>
                <td><div class="label">Σελίδες:</div></td>
                <td><?php echo $book->getPages(); ?></td>
            </tr>
            <tr>
                <td><div class="label">ISBN:</div></td>
                <td><?php echo htmlentities($book->getIsbn()); ?></td>
            </tr>
            <tr>
                <td><div class="label">Διαθέσιμο:</div></td>
                <td><label id="availability"><?php echo $book->getAvailable() ? 'Ναι' : 'Όχι'; ?></label></td>
            </tr>
        </tbody>
    </table>

    <?php
    if (isset($_SESSION['group'])) {
        $session_group = $_SESSION['group'];
    }

    require_once('db_setup.php');

    if (is_null(GroupQuery::create()->findOneByAlias($session_group))) {
        exit();
    }

    unset($user);

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

    // $userstudent = new UserStudent();
    $userstudent = UserStudentQuery::create()
            ->findOneByUser($user);

    // $userpublisher = new UserPublisher();
    $userpublisher = UserPublisherQuery::create()
            ->findOneByUser($user);

    if (!is_null($userstudent)) {

        // find all books from student's department
        $books = BookQuery::create()
                ->useCourseBookQuery()
                    ->useCourseQuery()
                        ->useSemesterQuery()
                        ->filterByDepartment($userstudent->getDepartment())
                        ->endUse()
                    ->enduse()
                ->enduse()
                ->distinct()
                ->find();

        if ($books->contains($book)) {
            $student_mode = true;
        }

        $userstudentbook = UserStudentBookQuery::create()
                ->filterByBook($book)
                ->filterByUserStudent($userstudent)
                ->findOne();

        if (!is_null($userstudentbook))
            $declared = true;
        //
    } else if (!is_null($userpublisher)) {

        if ($book->getPublisher()->equals($userpublisher->getPublisher())) {
            $publisher_mode = true;
        }
    }
    //
    ?>

    <?php if ($student_mode) : ?>
        <p>&nbsp;</p>
        <div class="actions">
            <p><strong>Λειτουργίες Φοιτητή</strong></p>
            <form class="actions">
                <label id="status"><?php echo 'Σύγγραμμα έχει δηλωθεί: '; echo $declared ? 'Ναι' : 'Όχι'; ?></label>
                <input id="student_id" type="text" value="<?php echo $userstudent->getUserId(); ?>" />
                <input id="book_id" type="text" value="<?php echo $book->getBookId(); ?>" />

                <input id="declare" type="submit" value="Δήλωση" <?php if($declared || !$book->getAvailable()) echo 'disabled="disabled" '; ?>/>

                <input id="remove" type="submit" value="Αφαίρεση" <?php if(!$declared) echo 'disabled="disabled" '; ?>/>
            </form>
            <div id="results">
            </div>
        </div>
    <?php elseif ($publisher_mode) : ?>
        <p>&nbsp;</p>
        <div class="actions">
            <p><strong>Λειτουργίες Εκδότη</strong></p>
            <form class="actions">
                <input id="publisher_id" type="text" value="<?php echo $userpublisher->getUserId(); ?>" />
                <input id="book_id" type="text" value="<?php echo $book->getBookId(); ?>" />

                <input id="available" type="submit" value="Διαθέσιμο" <?php if ($book->getAvailable()) echo 'disabled="disabled"'; ?>/>

                <input id="unavailable" type="submit" value="Μη διαθέσιμο" <?php if (!$book->getAvailable()) echo 'disabled="disabled"'; ?>/>
            </form>
            <div id="results">
            </div>
        </div>
    <?php endif; ?>
</div>
