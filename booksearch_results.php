<?php

/*
 * booksearch_results.php: Searches for books in db using specified criteria and generates HTML for the results
 */

use \Propel\Runtime\ActiveQuery\Criteria as Crit;
?>

<?php
//
require_once('db_setup.php');

$code = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code'] : null;

$title = isset($_POST['title']) && $_POST['title'] != '' ? $_POST['title'] : null;
$author = isset($_POST['author']) && $_POST['author'] != '' ? $_POST['author'] : null;
$isbn = isset($_POST['isbn']) && $_POST['isbn'] != '' ? $_POST['isbn'] : null;

$publisher = isset($_POST['publisher']) && $_POST['publisher'] != '' ? $_POST['publisher'] : null;

$department = isset($_POST['department']) && $_POST['department'] != '' ? $_POST['department'] : null;

$sort = isset($_POST['sort']) && $_POST['sort'] != '' ? $_POST['sort'] : null;
$dir = isset($_POST['dir']) && $_POST['dir'] != '' ? $_POST['dir'] : null;

//echo 'isset code: ';
//echo isset($code) ? 'yes' : 'no';
//echo '<br />';
//echo 'isset title: ';
//echo isset($title) ? 'yes' : 'no';
//echo '<br />';
//echo 'isset author: ';
//echo isset($author) ? 'yes' : 'no';
//echo '<br />';
//echo 'isset isbn: ';
//echo isset($isbn) ? 'yes' : 'no';
//echo '<br />';
//echo '<br />';
//    echo 'code: ';
//    echo isset($code) ? $code : 'null';
//    echo '<br />';
//    echo 'title: ';
//    echo isset($title) ? $title : 'null';
//    echo '<br />';
//    echo 'author: ';
//    echo isset($author) ? $author : 'null';
//    echo '<br />';
//    echo 'isbn: ';
//    echo isset($isbn) ? $isbn : 'null';
//    echo '<br />';
//    echo '<br />';
//
//    echo 'publisher: ';
//    echo isset($publisher) ? $publisher : 'null';
//    echo '<br />';
//    echo 'department: ';
//    echo isset($department) ? $department : 'null';
//    echo '<br />';
//    echo '<br />';
// // $book = new Book();
// $book = BookQuery::create()
//            ->findOneByBookId($display_book);
?>

<?php
$book_query = BookQuery::create()
        ->_if(isset($code))
            ->filterByCode('%' . $code . '%', Crit::LIKE)
        ->_endif()
        ->_if(isset($title))
            ->filterByTitle('%' . $title . '%', Crit::LIKE)
        ->_endif()
        ->_if(isset($author))
            ->filterByAuthor('%' . $author . '%', Crit::LIKE)
        ->_endif()
        ->_if(isset($isbn))
            ->filterByIsbn('%' . $isbn . '%', Crit::LIKE)
        ->_endif()
        ->_if(isset($publisher))
            ->usePublisherQuery()
            ->filterByName($publisher, Crit::EQUAL)
            ->enduse()
        ->_endif()
        ->_if(isset($department))
            ->useCourseBookQuery()
                ->useCourseQuery()
                    ->useSemesterQuery()
                        ->useDepartmentQuery()
                        ->filterByName($department, Crit::EQUAL)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
        ->_endif()
        ->distinct()
        ->_if(isset($sort) && $sort == "code")
            ->_if(isset($dir) && $dir == "asc")
                ->orderByCode(Crit::ASC)
            ->_elseif(isset($dir) && $dir == "desc")
                ->orderByCode(Crit::DESC)
            ->_endif()
        ->_elseif(isset($sort) && $sort == "title")
            ->_if(isset($dir) && $dir == "asc")
                ->orderByTitle(Crit::ASC)
            ->_elseif(isset($dir) && $dir == "desc")
                ->orderByTitle(Crit::DESC)
            ->_endif()
        ->_elseif(isset($sort) && $sort == "author")
            ->_if(isset($dir) && $dir == "asc")
                ->orderByAuthor(Crit::ASC)
            ->_elseif(isset($dir) && $dir == "desc")
                ->orderByAuthor(Crit::DESC)
            ->_endif()
        ->_elseif(isset($sort) && $sort == "isbn")
            ->_if(isset($dir) && $dir == "asc")
                ->orderByIsbn(Crit::ASC)
            ->_elseif(isset($dir) && $dir == "desc")
                ->orderByIsbn(Crit::DESC)
            ->_endif()
        ->_elseif(isset($sort) && $sort == "publisher")
            ->usePublisherQuery()
                ->_if(isset($dir) && $dir == "asc")
                    ->orderByName(Crit::ASC)
                ->_elseif(isset($dir) && $dir == "desc")
                    ->orderByName(Crit::DESC)
                ->_endif()
            ->endUse()
        ->_endif();

$books = $book_query->find();
//
?>
<div class="resultscount">

    <?php
    if (!($books->count() > 0)) {
        echo '<p class="notify">Δεν βρέθηκαν συγγράμματα.</p>';
    } else {
        if ($books->count() == 1) {
            echo '<p class="notify">Βρέθηκε 1 σύγγραμμα.</p>';
        } else {
            echo '<p class="notify">Βρέθηκαν ' . $books->count() . ' συγγράμματα.</p>';
        }
    }
    ?>
</div>

<table class="searchresults">
    <?php foreach ($books as $b) : ?>
        <tr>
            <!--<td><a href="index.php?page=bookinfo&amp;book_id=<?php echo $b->getBookId(); ?>"><img src="data:image/jpeg;base64,<?php echo $b->getCoverBase64(); ?>" /></a></td>-->
            <td><a href="index.php?page=bookinfo&amp;book_id=<?php echo $b->getBookId(); ?>"><img src="jpeg_cover.php?book_id=<?php echo $b->getBookId(); ?>" /></a></td>
            <td>
                <table>
                    <tr><td><div class="label">Κωδ.:</div></td><td><?php echo htmlentities($b->getCode()); ?></td></tr>
                    <tr><td><div class="label">Τίτλ.:</div></td><td><a href="index.php?page=bookinfo&amp;book_id=<?php echo $b->getBookId(); ?>"><?php echo htmlentities($b->getTitle()); ?></a></td></tr>
                    <!--<tr><td><div class="label">Τίτλ.:</div></td><td><?php echo htmlentities($b->getTitle()); ?></td></tr>-->
                    <tr><td><div class="label">Συγγ.:</div></td><td><?php echo htmlentities($b->getAuthor()); ?></td></tr>
                    <tr><td><div class="label">ISBN.:</div></td><td><?php echo htmlentities($b->getIsbn()); ?></td></tr>
                    <tr><td><div class="label">Εκδ.:</div></td><td><?php echo htmlentities($b->getPublisher()->getName()); ?></td></tr>
                    <tr><td><div class="label">Διαθ.:</div></td><td><?php echo $b->getAvailable() ? 'Διαθέσιμο' : 'Μη διαθέσιμο'; ?></td></tr>
                </table>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
//    //    $b = new Book();
//    foreach ($books as $b) {
//        // $booksarray[$b->getBookId()] = array('code' => $b->getCode(), 'isbn' => $b->getIsbn());
//        echo $b->getCode();
//        echo "<br />";
//        echo $b->getTitle();
//        echo "<br />";
//        echo $b->getAuthor();
//        echo "<br />";
//        echo $b->getIsbn();
//        echo "<br />";
//        echo $b->getPublisher()->getName();
//        echo "<br />";
//        echo "<br />";
//    }
// $json = json_encode($booksarray);
// var_dump($json);
//
?>
