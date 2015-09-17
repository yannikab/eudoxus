<div class="publishers">
    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    //
    require_once('db_setup.php');

    $publishers = PublisherQuery::create()
            ->orderByName()
            ->find();

    if (!($publishers->count() > 0)) {
        exit();
    }

    $p = new Publisher();
    unset($p);
    ?>

    <div class="breadcrumbs">
        <a href="index.php">Εύδοξος</a>
        &gt;
        <span>Εκδότες</span>
    </div>

    <!--<br />-->
    <!--<p><strong>Εκδότες</strong></p>-->

    <table>
        <tr><th>Επωνυμία</th><th class="books">Συγγράμματα</th></tr>
            <?php foreach ($publishers as $p) : ?>
                <?php if ($p->getBooks()->count() > 0) : ?>
                    <tr><td>
                            <a href="index.php?page=publisherbooks&amp;publisher_id=<?php echo $p->getPublisherId(); ?>">
                                <?php echo htmlentities($p->getName()); ?>
                            </a>
                        </td>
                        <td class="books"><?php echo $p->getBooks()->count(); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
    </table>

</div>
