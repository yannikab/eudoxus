<div id="navigation">
    <!--<p><strong>Εύδοξος</strong></p>-->
    <ul>
        <li><a href="index.php">Αρχική</a></li>
        <li><a href="index.php?page=register">Εγγραφή</a></li>
        <li><a href="index.php?page=login">Σύνδεση</a></li>
        <li><a href="process_browse.php">Συγγράμματα ανά Τμήμα</a></li>
        <li><a href="process_publisher.php">Συγγράμματα ανά Εκδότη</a></li>
        <li><a href="index.php?page=booksearch">Αναζήτηση Συγγραμμάτων</a></li>
        <li><a href="index.php?page=faq">Συχνές Ερωτήσεις</a></li>
        <?php if (isset($_SESSION['username'])) : ?>
            <li><a href="process_profile.php">Προφίλ Χρήστη</a></li>
            <li><a href="index.php?page=logout">Αποσύνδεση</a></li>
        <?php endif; ?>
    </ul>
</div>
