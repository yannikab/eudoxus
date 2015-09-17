<div>
    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    //
    require_once('db_setup.php');

    $institutions = InstitutionQuery::create()
            ->find();

    if (!($institutions->count() > 0)) {
        exit();
    }
    //
    ?>

    <div class="breadcrumbs">
        <a href="index.php">Εύδοξος</a>
        &gt;
        <span>Ιδρύματα</span>
    </div>

    <br />
    <p><strong>Ιδρύματα</strong></p>

    <ul>
        <?php foreach ($institutions as $i) : ?><?php // $i = new Institution();  ?>
            <li>
                <a href="index.php?page=departments&amp;inst_id=<?php echo $i->getInstId() ?>">
                    <?php echo htmlentities($i->getName()); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</div>
