<script type = "text/javascript">
    var nodeOpenClass = "liOpen";

    $(document).ready(function() {
        expandToItem('mktree', 'selected');
    });
</script>

<div>
    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    //
    require_once('db_setup.php');

    if (isset($_GET['dept_id'])) {
        $display_department = $_GET['dept_id'];
    } else {
        $display_department = -1;
    }

    if (isset($_GET['period'])) {
        $display_period = $_GET['period'];
    } else {
        $display_period = -1;
    }

    $department = DepartmentQuery::create()
            ->findOneByDeptId($display_department);

    if (is_null($department)) {
        exit();
    }
    //
    ?>

    <div class="breadcrumbs">
        <a href="index.php">Εύδοξος</a>
        &gt;
        <a href="index.php?page=institutions">Ιδρύματα</a>
        &gt;
        <a href="index.php?page=departments&amp;inst_id=<?php echo $department->getInstitution()->getInstId() ?>"><?php echo htmlentities($department->getInstitution()->getName()); ?></a>
        &gt;
        <span><?php echo htmlentities($department->getName()) ?></span>
    </div>

    <?php
    $semesters = SemesterQuery::create()
            ->filterByDepartment($department)
            ->orderByPeriod(Crit::ASC)
            ->find();

    if (!($semesters->count() > 0)) {
        exit();
    }
    //
    ?>

    <br />
    <p><strong>Μαθήματα</strong></p>

    <?php $selected = false; ?>
    <div class="coursecontainer">
        <ul class="mktree" id="mktree">
            <?php foreach ($semesters as $s) : ?><?php // $s = new Semester(); ?>
                <li>
                    <label><?php echo $s->getName(); ?></label>
                    <ul>
                        <?php foreach ($s->getCourses() as $c) : ?><?php // $c = new Course()      ?>
                            <li<?php
                            if ($s->getPeriod() == $display_period && !$selected) {
                                echo ' id="selected"';
                                $selected = true;
                            }
                            ?>>
                                <a href="index.php?page=courseinfo&amp;course_id=<?php echo $c->getCourseId(); ?>">
                                    <?php echo htmlentities($c->getName()); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
