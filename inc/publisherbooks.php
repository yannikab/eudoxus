<div class="publisherbooks">
    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    //
    require_once('db_setup.php');

    if (isset($_GET['publisher_id'])) {
        $bisplay_publisher = $_GET['publisher_id'];
    } else {
        $bisplay_publisher = -1;
    }

    $publisher = new Publisher();
    unset($publisher);
    $publisher = PublisherQuery::create()
            ->findOneByPublisherId($bisplay_publisher);

    if (is_null($publisher)) {
        exit();
    }
    ?>

    <div class="breadcrumbs">
        <a href="index.php">Εύδοξος</a>
        &gt;
        <a href="index.php?page=publishers">Εκδότες</a>
        &gt;
        <span><?php echo htmlentities($publisher->getName()); ?></span>
    </div>

    <?php
    $books = BookQuery::create()
            ->filterByPublisher($publisher)
            ->orderByTitle(Crit::ASC)
            ->find();

    if (!($books->count() > 0)) {
        exit();
    }

    $b = new Book();
    unset($b);
    //
    ?>

    <br />
    <p><strong>Συγγράμματα</strong></p>

    <!--<ul>
        <?php foreach ($books as $b) : ?>
            <li>
                <a href="index.php?page=bookinfo&amp;publisher_id=<?php echo $publisher->getPublisherId() ?>&amp;book_id=<?php echo $b->getBookId(); ?>">
                    <?php echo htmlentities($b->getTitle()) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>-->
    
    <table class="books">
        <tr><th>Τίτλος</th><th class="avail">Διαθέσιμο</th></tr>
            <?php foreach ($books as $b) : ?><?php // $b = new Book(); ?>
                <tr><td>
                        <a href="index.php?page=bookinfo&amp;publisher_id=<?php echo $publisher->getPublisherId() ?>&amp;book_id=<?php echo $b->getBookId(); ?>">
                            <?php echo htmlentities($b->getTitle()); ?>
                        </a>
                    </td>
                    <td class="avail"><?php echo $b->getAvailable() ? 'Ναι' : 'Όχι' ?></td>
                </tr>
            <?php endforeach; ?>
    </table>

</div>
