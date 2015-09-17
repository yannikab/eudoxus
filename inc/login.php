<div class="breadcrumbs">
    <a href="index.php">Εύδοξος</a>
    &gt;
    <span>Σύνδεση</span>
</div>
<!--<br />-->
<!--<p><strong>Σύνδεση</strong></p>-->

<div class="formcontainer">
    <form action="process_login.php" method="post">
        <label>Όνομα χρήστη:</label>
        <input class="logindetails" name="username" type="text" />
        <label>Συνθηματικό:</label>
        <input class="logindetails" name="password" type="password" >
        <input id="submit" type="submit" name="Submit" value="Σύνδεση">
    </form>
</div>

<?php if (isset($_GET['login_fail'])) : ?>
    <div>
        <p class="error"><br />Λάθος όνομα χρήστη ή συνθηματικό.</p>
    </div>
<?php endif; ?>
