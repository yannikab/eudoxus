<?php

/*
 * json_departments.php: Returns a list of all departments in JSON format. Used for form autocomplete.
 */

use \Propel\Runtime\ActiveQuery\Criteria as Crit;
?>

<?php

if (!isset($_GET['term'])) {
    exit();
}

$term = $_GET['term'];

if (isset($_GET['inst_id'])) {
    $inst_id = $_GET['inst_id'];
} else {
    $inst_id = -1;
}

require_once('db_setup.php');

$institution = InstitutionQuery::create()->findOneByInstId($inst_id);

//if (is_null($institution)) {
//    exit();
//}

$departments = DepartmentQuery::create()
        ->_if(!is_null($institution))
            ->filterByInstitution($institution)
        ->_endif()
        ->filterByName('%' . $term . '%', Crit::LIKE)
        ->find();

if (!($departments->count() > 0)) {
    exit();
}

$d = new Department();
foreach ($departments as $d) {

    $data[] = array(
        "id" => $d->getDeptId(),
        "label" => $d->getName(),
        "value" => $d->getName(),
    );
}

echo json_encode($data);
//
?>
