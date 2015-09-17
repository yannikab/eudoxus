<?php

/*
 * update_covers.php: Helper script that updates BLOB image data (cover) for books. Not used in front end.
 */

require_once('db_setup.php');

$books = BookQuery::create()
        ->find();

foreach ($books as $b) {
    // $b = new Book();
    $b->setCover(file_get_contents("db/covers/cover-" . $b->getCode() . ".jpg"));
    $b->save();
}
?>
