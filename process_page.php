<?php

/*
 * process_page.php: Determine page title
 */

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = "";
}
?>

<?php

switch ($page) {
    case "login":
        $page_title = "Σύνδεση";
        break;
    case "register":
        $page_title = "Εγγραφή";
        break;
    case "logout":
        $page_title = "Αποσύνδεση";
        session_destroy();
        // header("Location: index.php?page=logout");
        break;
    case "faq":
        $page_title = "Συχνές Ερωτήσεις";
        break;
    case "institutions":
        $page_title = "Ιδρύματα";
        break;
    case "departments":
        $page_title = "Τμήματα";
        break;
    case "courses":
        $page_title = "Μαθήματα";
        break;
    case "courseinfo":
        $page_title = "Πληροφορίες Μαθήματος";
        break;
    case "bookinfo":
        $page_title = "Πληροφορίες Συγγράμματος";
        break;
    case "booksearch":
        $page_title = "Αναζήτηση Συγγραμμάτων";
        break;
    case "publishers":
        $page_title = "Εκδότες";
        break;
    case "publisherbooks":
        $page_title = "Συγγράμματα";
        break;
    case "home":
    default:
        $page_title = "Εύδοξος";
        break;
}
?>
