<?php

/*
 * jpeg_cover.php: Gets book cover image data for specified book id. Instructs browser to cache the results.
 */

session_start();

header('Content-Type: image/jpeg');
header("Cache-Control: private, max-age=10800, pre-check=10800");
header("Pragma: private");
header("Expires: " . date(DATE_RFC822, strtotime(" 2 day")));

if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
    header('Last-Modified: ' . $_SERVER['HTTP_IF_MODIFIED_SINCE'], true, 304);
    exit;
}

require_once('db_setup.php');

//$b = new Book();
//unset($b);

$id = (isset($_GET['book_id']) && is_numeric($_GET['book_id'])) ? intval($_GET['book_id']) : 0;

$b = BookQuery::create()
        ->findOneByBookId($id);

if (is_null($b))
    exit();

echo stream_get_contents($b->getCover());
//
?>
