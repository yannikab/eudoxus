<?php

/*
 * process_publisher.php: Used while browsing books by publisher.
 * If the user corresponds to a publisher, redirect to the specific publisher page, otherwise browse from root (all publishers)
 */

ob_start();

session_start();

if (isset($_SESSION['publisher_id'])) {
    $display_publisher = $_SESSION['publisher_id'];
}

require_once('db_setup.php');

$pub = new Publisher();
unset($pub);
$pub = PublisherQuery::create()
        ->findOneByPublisherId($display_publisher);

if (is_null($pub)) {
    header("Location: index.php?page=publishers");
} else {
    header("Location: index.php?page=publisherbooks&publisher_id=" . $pub->getPublisherId());
}

ob_end_flush();
//
?>
