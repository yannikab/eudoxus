<?php

/*
 * json_institutions.php: Returns a list of all institutions in JSON format. Used for form autocomplete.
 */

use \Propel\Runtime\ActiveQuery\Criteria as Crit;
?>

<?php

if (!isset($_GET['term'])) {
    exit();
}

$term = $_GET['term'];

require_once('db_setup.php');

$institutions = InstitutionQuery::create()
        ->filterByName('%' . $term . '%', Crit::LIKE)
        ->find();

if (!($institutions->count() > 0)) {
    exit();
}

$i = new Institution();
foreach ($institutions as $i) {

    $data[] = array(
        "id" => $i->getInstId(),
        "label" => $i->getName(),
        "value" => $i->getName(),
    );
}

echo json_encode($data);
//
?>
