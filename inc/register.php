<script type = "text/javascript" src="js/register-initialize.js"></script>
<script type = "text/javascript" src="js/register-validation.js"></script>
<script type = "text/javascript" src="js/register-autocomplete.js"></script>

<div class="breadcrumbs">
    <a href="index.php">Εύδοξος</a>
    &gt;
    <span>Εγγραφή</span>
</div>
<!--<br />-->
<!--<p><strong>Εγγραφή</strong></p>-->

<div class="formcontainer">

    <form id="register-form" action="process_register.php" method="post">
        <div>
            <label for="groupcombo">Όμάδα χρήστη:</label>
            <select id="groupcombo" class="groupcombo" name="group">
                <?php require_once('db_setup.php'); ?>

                <?php foreach (GroupQuery::create() as $g) : ?>
                    <option value="<?php echo $g->getAlias(); ?>">
                        <?php echo $g->getName() ?>
                    </option>
                <?php endforeach; ?>

                <?php unset($g); ?>
            </select>

            <label for="username">Όνομα χρήστη:</label>
            <input class="logindetails" id="username" name="username" type="text" />
            <label for="password">Συνθηματικό:</label>
            <input class="logindetails" id="password" name="password" type="password" />

            <label for="email">Διεύθυνση ηλ. ταχυδρομείου:</label>
            <input class="email" id="email" name="email" type="text" />
            
            <label class="namedetails" for="lastname">Επώνυμο:</label>
            <input class="namedetails" name="lastname" type="text" />
            <label class="namedetails" for="firstname">Όνομα:</label>
            <input class="namedetails" name="firstname" type="text" />
            
            <label class="pubdetails" for="pubname">Επωνυμία:</label>
            <input class="pubdetails" id="pubname" name="pubname" type="text" />

            <label class="instsearch" for="institution">Ίδρυμα:</label>
            <input class="instsearch" id="institution" name="institution" type="text" />

            <label class="deptsearch" for="department">Τμήμα:</label>
            <input class="deptsearch" id="department" name="department" type="text" />

            <input id="submit" type="submit" value="Υποβολή" />
        </div>
    </form>
</div>

<?php if (isset($_GET['register_success'])) : ?>
    <div>
        <p class="notify"><br />Η εγγραφή σας ήταν επιτυχής, μπορείτε να συνδεθείτε.</p>
    </div>
<?php elseif (isset($_GET['register_fail'])) : ?>
    <div>
        <p class="error"><br />Παρουσιάστηκε κάποιο σφάλμα κατά την εγγραφή σας.<br />Παρακαλώ επικοινωνήστε με τη διαχείριση.</p>
    </div>
<?php endif; ?>
