<div class="breadcrumbs">
    <a href="index.php">Εύδοξος</a>
    &gt;
    <span>Προφίλ Χρήστη</span>
</div>
<br />
<p><strong>Πληροφορίες Γραμματείας</strong></p>
<div class="userprofile">
    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    if (isset($_SESSION['group'])) {
        $session_group = $_SESSION['group'];
    }

    require_once('db_setup.php');

    if (is_null(GroupQuery::create()->findOneByAlias($session_group))) {
        exit();
    }

    if (isset($_SESSION['user_id'])) {
        $session_user_id = $_SESSION['user_id'];
    }

    $user = new User();
    $user = UserQuery::create()->findOneByUserId($session_user_id);

    if (is_null($user)) {
        exit();
    }

    $group = new Group();
    $group = $user->getGroups()[0];

    if ($group->getAlias() != $session_group)
        exit();

    $usersecretariat = UserSecretariatQuery::create()
            ->findOneByUser($user);

    if (is_null($usersecretariat))
        exit();
    ?>

    <?php
//    echo $group->getAlias();
//    echo '<br />';
    ?>
    
     <div>
        <table>
            <tbody>
                <tr><td>Επώνυμο:</td><td><?php echo $usersecretariat->getLastname(); ?></td></tr>
                <tr><td>Όνομα:</td><td><?php echo $usersecretariat->getFirstname(); ?></td></tr>
                <tr><td>Ηλεκτρονική διεύθυνση:</td><td><?php echo $usersecretariat->getUser()->getEmail(); ?></td></tr>
                <tr><td>Ίδρυμα:</td><td><?php echo $usersecretariat->getDepartment()->getInstitution()->getName(); ?></td></tr>
                <tr><td>Τμήμα:</td><td><?php echo $usersecretariat->getDepartment()->getName(); ?></td></tr>
            </tbody>
        </table>    
    </div>
    <br />

</div>
