<div class="courseinfo">
    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    //
    require_once('db_setup.php');

    if (isset($_GET['course_id'])) {
        $display_course = $_GET['course_id'];
    } else {
        $display_course = -1;
    }

    $course = new Course();
    unset($course);

    $course = CourseQuery::create()
            ->findOneByCourseId($display_course);

    if (is_null($course)) {
        exit();
    }
    //
    ?>

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
        <span><?php echo htmlentities($course->getName()); ?></span>
    </div>

    <br />
    <p><strong>Συγγράμματα</strong></p>

    <?php
    $b = new Book();
    unset($b);
    // 
    ?>
    <table>
        <tr><th>Τίτλος</th><th class="avail">Διαθέσιμο</th></tr>
        <?php foreach ($course->getBooks() as $b) : ?>
            <tr><td>
                    <a href="index.php?page=bookinfo&amp;course_id=<?php echo $course->getCourseId(); ?>&amp;book_id=<?php echo $b->getBookId(); ?>">
                        <?php echo htmlentities($b->getTitle()); ?>
                    </a>
                </td>
                <td class="avail"><?php echo $b->getAvailable() ? 'Ναι' : 'Όχι' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>
