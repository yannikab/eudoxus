<?php

/*
 * json_publishers.php: Returns a list of all publishers in JSON format. Used for form autocomplete.
 */

use \Propel\Runtime\ActiveQuery\Criteria as Crit;
?>

<?php

if (!isset($_GET['term'])) {
    exit();
}

$term = $_GET['term'];

require_once('db_setup.php');

$publishers = PublisherQuery::create()
        ->filterByName('%' . $term . '%', Crit::LIKE)
        ->find();

if (!($publishers->count() > 0)) {
    exit();
}

// $p = new Publisher();
foreach ($publishers as $p) {

    $data[] = array(
        "id" => $p->getPublisherId(),
        "label" => $p->getName(),
        "value" => $p->getName(),
    );
}

echo json_encode($data);
//
?>
