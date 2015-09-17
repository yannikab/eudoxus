<script type = "text/javascript" src="js/booksearch-initialize.js"></script>
<script type = "text/javascript" src="js/booksearch-autocomplete.js"></script>

<div class="bookinfo">

    <?php

    use \Propel\Runtime\ActiveQuery\Criteria as Crit;
    ?>

    <?php
    //
    require_once('db_setup.php');
    //
    ?>
    <div class="breadcrumbs">
        <a href="index.php">Εύδοξος</a>
        &gt;
        <span>Αναζήτηση Συγγραμμάτων</span>
    </div>

    <div class="formcontainer">
        <form id="booksearch-form">
            <div>

                <label class="namedetails" for="code">Κωδικός:</label>
                <input class="namedetails" id="code" name="code" type="text" value="" />

                <label class="namedetails" for="title">Τίτλος:</label>
                <input class="namedetails" id="title" name="title" type="text" value="" />

                <label class="namedetails" for="author">Συγγραφέας:</label>
                <input class="namedetails" id="author" name="author" type="text" value="" />

                <label class="namedetails" for="isbn">ISBN:</label>
                <input class="namedetails" id="isbn" name="isbn" type="text" value="" />

                <label class="booksearch" for="publisher">Εκδότης:</label>
                <input class="booksearch" id="publisher" name="publisher" type="text" value="<?php
                if (isset($_SESSION['publisher_id'])) {
                    $pub = PublisherQuery::create()->findOneByPublisherId($_SESSION['publisher_id']);

                    if (!is_null($pub)) {
                        echo $pub->getName();
                    }
                }
                ?>" />

                <label class="booksearch" for="department">Τμήμα:</label>
                <input class="booksearch" id="department" name="department" type="text" value="<?php
                if (isset($_SESSION['dept_id'])) {
                    $dept = DepartmentQuery::create()->findOneByDeptId($_SESSION['dept_id']);

                    if (!is_null($dept)) {
                        echo $dept->getName();
                    }
                }
                ?>" />
                <label class="booksearch" for="sort">Ταξινόμηση:</label>

                <select id="sort" name="sort">
                    <option value="code">Κωδικός</option>
                    <option value="title" selected="selected">Τίτλος</option>
                    <option value="author">Συγγραφέας</option>
                    <option value="publisher">Εκδότης</option>
                    <option value="isbn">ISBN</option>
                </select>

                <select id="dir" name="dir">
                    <option value="asc" selected="selected">Αύξουσα</option>
                    <option value="desc">Φθίνουσα</option>
                </select>

                <input id="submit" type="submit" value="Αναζήτηση" />
            </div>
        </form>
    </div>

    <div id="results">
    </div>
</div>
