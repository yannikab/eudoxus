<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php session_start() ?>

<?php include('process_page.php') ?>

<html xmlns="http://www.w3.org/1999/xhtml">

    <?php include('inc/head.php') ?>

    <body>

        <div id="container">

            <?php include('inc/header.php') ?>

            <?php include('inc/logged_in_user.php') ?>

            <?php include('inc/navigation.php') ?>

            <div id="wrapper">
                <div id="content">
                    <?php
                    switch ($page) {
                        case "login":
                            include('inc/login.php');
                            break;
                        case "register":
                            include('inc/register.php');
                            break;
                        case "logout":
                            include('inc/logout.php');
                            break;
                        case "faq":
                            include('inc/faq.php');
                            break;
                        case "institutions":
                            include('inc/institutions.php');
                            break;
                        case "departments":
                            include('inc/departments.php');
                            break;
                        case "courses":
                            include('inc/courses.php');
                            break;
                        case "courseinfo":
                            include('inc/courseinfo.php');
                            break;
                        case "bookinfo":
                            include('inc/bookinfo.php');
                            break;
                        case "publishers":
                            include('inc/publishers.php');
                            break;
                        case "publisherbooks":
                            include('inc/publisherbooks.php');
                            break;
                        case "booksearch":
                            include('inc/booksearch.php');
                            break;
                        case "profilestudent":
                            include('inc/profilestudent.php');
                            break;
                        case "profilesecretariat":
                            include('inc/profilesecretariat.php');
                            break;
                        case "profilepublisher":
                            include('inc/profilepublisher.php');
                            break;
                        case "home":
                        default:
                            include('inc/home.php');
                            break;
                    }
                    ?>
                </div>
            </div>

            <?php include('inc/footer.php') ?>

        </div>

    </body>

</html>
