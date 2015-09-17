<?php session_start() ?>

<div id="extra">
    <p id="name">
        <?php
        if (isset($_SESSION['username'])) {
            echo $_SESSION['fullname'] . ' (' . $_SESSION['groupname'] . ')';
        }
        ?>
    </p>

    <p id="profile">
        <?php
        if (isset($_SESSION['username'])) {
            echo '<a href="process_profile.php">Προφίλ</a> | <a href="index.php?page=logout">Αποσύνδεση</a>';
        } else {
            echo '<a href="index.php?page=login">Σύνδεση</a>';
        }
        ?>
    </p>
</div>
