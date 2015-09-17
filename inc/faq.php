<div class="breadcrumbs">
    <a href="index.php">Εύδοξος</a>
    &gt;
    <span>Συχνές ερωτήσεις</span>
</div>
<br />
<!--<p><strong>Συχνές ερωτήσεις</strong></p>-->

<div class="faqcontainer">
    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    // figure out group to display
    if (isset($_GET['group'])) { // prefer user selection
        $display_group = $_GET['group'];
    } else { // no user preference
        if (isset($_SESSION['group'])) { // user logged in, use relevant group
            $display_group = $_SESSION['group'];
        } else { // user not logged in, show all groups
            $display_group = 'all';
        }
    }

    // setup db
    require_once('db_setup.php');

    // ignore invalid group names
    if (is_null(GroupQuery::create()->findOneByAlias($display_group))) {
        $display_group = 'all';
    }
    ?>

    <div>
        <select class="groupcombo" onchange="insert_param('group', this.value)">

            <option value="all"<?php if ($display_group == 'all') : ?> selected="selected"<?php endif ?>>
                Όλες
            </option>

            <?php foreach (GroupQuery::create() as $g) : ?>
                <option value="<?php echo $g->getAlias(); ?>"<?php if ($display_group == $g->getAlias()) : ?> selected="selected"<?php endif ?>><?php echo $g->getName() ?></option>
            <?php endforeach; ?>

            <?php unset($g); ?>
        </select>
    </div>

    <?php
    //    if ($display_group == 'all') {
    //        $groups = GroupQuery::create();
    //    } else {
    //        $groups = GroupQuery::create()->filterByAlias($display_group);
    //    }

    $groups = GroupQuery::create()
            ->_if($display_group != 'all')
                ->filterByAlias($display_group)
            ->_endif()
            ->find();
    ?>

    <?php foreach ($groups as $g) : ?>

        <?php
        $faqs = FaqQuery::create()
                ->filterByGroup($g)
                ->orderByIndex(Crit::ASC)
                ->find();
        ?>

        <?php if ($groups->count() > 1 && $faqs->count() > 0) : ?>
            <h4><?php echo htmlentities($g->getName()); ?></h4>
        <?php endif; ?>

        <?php if ($faqs->count() > 0) : ?>
            <ul class="mktree">
                <?php foreach ($faqs as $faq): ?>
                    <li>
                        <label><?php echo $faq->getIndex() . '. ' . htmlentities($faq->getQuestion()); ?></label>
                        <ul><div><?php echo htmlentities($faq->getAnswer()); ?></div></ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    <?php endforeach; ?>

    <?php unset($g); ?>
</div>
