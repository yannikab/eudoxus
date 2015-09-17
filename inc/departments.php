<div>
    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    //
    require_once('db_setup.php');

    if (isset($_GET['inst_id'])) {
        $display_institution = $_GET['inst_id'];
    } else {
        $display_institution = -1;
    }

    $institution = InstitutionQuery::create()->findOneByInstId($display_institution);

    if (is_null($institution)) {
        exit();
    }
    ?>

    <div class="breadcrumbs">
        <a href="index.php">Εύδοξος</a>
        &gt;
        <a href="index.php?page=institutions">Ιδρύματα</a>
        &gt;
        <span><?php echo htmlentities($institution->getName()); ?></span>
    </div>

    <?php
    $departments = DepartmentQuery::create()
            ->filterByInstitution($institution)
            ->orderByName(Crit::ASC)
            ->find();

    if (!($departments->count() > 0)) {
        exit();
    }
    //
    ?>

    <br />
    <p><strong>Τμήματα</strong></p>

    <ul>
        <?php foreach ($departments as $d) : ?><?php // $d = new Department(); ?>
            <li>
                <a href="index.php?page=courses&amp;dept_id=<?php echo $d->getDeptId() ?>">
                    <?php echo htmlentities($d->getName()) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</div>
