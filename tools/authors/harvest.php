<?php

// Tool for 'harvesting' existing bios from project comments.
// They should have been marked as such:
//   <!-- begin bio: Name (born-dead) -->
//   blah blah
//   <!-- end bio -->

// Three different ways of using this script.
// * Simple GET:         displays a small how-to
// * "Simulate harvest": simulates a harvest
// * "Harvest":          does the harvest
// The last two only differ in whether the sql to insert is run or displayed.

$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // html_safe()
include_once('authors.inc');
include_once('menu.inc');

require_login();

abort_if_not_authors_db_manager(true);

output_header(_("Harvest existing biographies from project comments"));

$sql = 'SELECT * FROM authors';
$result = DPDatabase::query($sql);
if (mysqli_num_rows($result) > 0) {
    echo _("The table 'authors' is not empty. Please empty it and try again.");
    exit;
}
$sql = 'SELECT * FROM biographies';
$result = DPDatabase::query($sql);
if (mysqli_num_rows($result) > 0) {
    echo _("The table 'biographies' is not empty. Please empty it and try again.");
    exit;
}

echo_menu();

if (!isset($_POST['actionBtn'])) {

    // print usage and the button to simulate harvesting

    echo '<p>' . sprintf( _("When you click the button below, the project comments of all projects will be searched for biographies. Records in the database will be created for both the author and the biography, but they will of course need to be examined. This will be done using the <a href='%s'>managing tool</a> and the process is explained further below."), 'manage.php');

    echo '<p>' . _("Please do note that this is a one-time event.");

?>

<form name="harvest" method="POST">
<input type="submit" name="actionBtn" value="<?php echo _("Simulate harvest"); ?>">
</form>

<?php

    echo '<h2>' . _("Following the harvest") . '</h2>';

    echo '<p>' . _("The authors and biographies need be examined for duplicates and obvious errors such as misplaced data.");

    echo " " . sprintf( _("This will be done using the <a href='%s'>managing tool</a>:"), 'manage.php');
    echo '<ul><li>';
    echo _("Select to only view non-enabled authors.");
    echo "<li>" . _("Authors and biographies are linked the way they were found in the database harvest. This means they are all in a one-to-one relationship. There may be duplicate entries for the same author. There should only be one author entry per author, and it should link to all biographies. This is done manually. Multiple occurrences of a biography should be reduced to one.");
    echo "<li>" . _("Make frequent and possibly creative use of the searching and listing possibilities to identify duplicate authors.");
    echo "<li>" . _("The authors and biographies are all marked as 'not enabled' in the database. Until you check the 'enabled' box next to it in this listing and submit, the author/bio will not appear to the average user but you will see it here. After that, it will be the other way round. This means you should only check that box when you are certain you won't need to edit that author again or move more biographies to it.");
    echo '</ul></p>';

} // end of usage and button
else {

    // Are we simulating? Determined by the label of the button clicked.
    $simulating = ($_POST['actionBtn'] == _("Simulate harvest"));

    if ($simulating) {
      echo _("<strong>Simulating!</strong> Queries are not run. Check that there are no peculiarities below that you believe should be addressed, and click the 'Harvest' button.");
      ?>
      <form name="harvest" method="POST">
      <input type="submit" name="actionBtn" value="<?php echo _("Simulate harvest"); ?>">
      <input type="submit" name="actionBtn" value="<?php echo _("Harvest"); ?>">
      </form>
      <?php
    }
    else {
      echo _("<strong>Harvesting!</strong> Below should be a log of what has been done.");
    }

    // Harvest

    echo '<pre>';

    // Get all the project comments that contain a biography

    $result = mysqli_query(DPDatabase::get_connection(), "SELECT projectID, comments FROM projects WHERE comments LIKE '%<!-- begin bio%'");
    echo sprintf('Found %d projects with biographies in them. Processing....', mysqli_num_rows($result)) . "\n";

    // Loop through, extracting all bios
    while ($row = mysqli_fetch_row($result)) {
        // get all the data with a regex, store matches in $matches
        $matches = array();
        $count = preg_match_all("/<!-- begin bio: (.*?) (\((.*?)\))? -->(.*?)<!-- end bio/is", $row[1], $matches, PREG_SET_ORDER);
        $project_id = $row[0];
        echo '  ' . sprintf( _("Project %1\$s contains %2\$d biographies."), $project_id, $count) . "\n";

        if ($count == 0) {
            echo _("It seems the biography in this project was not properly marked. Please check manually:");
            echo " $code_url/project.php?id=$project_id";
            continue;
        }

        // This is added to each bio, so that we'll know where it came from (yes, URL may become stale!).
        $project_reference = "<!-- biography harvested from project $project_id\n($code_url/project.php?id=$project_id). -->\n\n";

        foreach ($matches as $match) {
            // $match is an array matching the regexp above.
            // Array indices:
            //   1        name
            // [ 3        birth- and death-dates ]
            //   3 or 4   bio

            echo "\n    $match[1]" . (count($match)==5 ? " ($match[3])" : '(no dates found)') . "\n";

            // Split name (e.g. 'London, Jack') into $last_name, $other_names if possible
            $comma = strpos($match[1], ',');
            if ($comma) {
                $last_name = trim(substr($match[1], 0, $comma));
                $other_names = trim(substr($match[1], $comma+1));
                echo '    ' . sprintf( _("Name split into '%1\$s', '%2\$s'."), $last_name, $other_names) . "\n";
            }
            else {
                $last_name = trim($match[1]);
                $other_names = '';
                echo '    ' . _("Name not split") . "\n";
            }

            // The dates need some extra parsing.
            // Valid formats:
            // 2002-02-02-2004-04-04    2002-02-02-    20020202-    20020202-20040404    2002-2004 .
            // Spaces are OK. Question marks may be used to signify 'unknown'

            $fields = array('?', '?', '?', '?', '?', '?'); // extracted data: year, month, day, year, month, day

            // $match-array contains five elements if dates were found
            if (count($match) == 5) {
                // First, check if it's yyyy-yyyy
                $matches = array();
                if (preg_match('/^(\d{4})-(\d{4})$/', $match[3], $matches)) {
                    $fields[0] = $matches[1];
                    $fields[3] = $matches[2];
                    echo '    ' . 'Birth-year:' . ' ' . $fields[0] . "\n";
                    echo '    ' . 'Death-year:' . ' ' . $fields[3] . "\n";
                }
                // It's some other format. Parse as long as we get what we expect, leave others unknown.
                else {
                    $lengths = array(4, 2, 2, 4, 2, 2); // how many digits are expected for each field (y,m,d,y,m,d)
                    $pos = 0; // position in date-string
                    $string = $match[3]; // to parse
                    for ($i = 0; $i < 6; $i++) {
                        $string = ltrim($string);
                        // Find right number of digits or a question mark at start of string
                        $date_match = array();
                        if (!preg_match('/^(\d{'.$lengths[$i].'})|\?/', $string, $date_match))
                            continue;
                        $fields[$i] = $date_match[0];
                        echo '    ' . 'Part of date:' . ' ' . $date_match[0] . "\n";
                        // Remove the data from the date
                        $string = ltrim(substr($string, strlen($date_match[0])));
                        // Remove optional hyphen
                        if (substr($string, 0, 1) == '-')
                            $string = substr($string, 1);
                    }
                }
            }
            // build comma-separated list (ala join)
            // the difference is we turn '?' into 0 before joining
            $date_fields_str = '';
            foreach ($fields as $field)
                $date_fields_str .= ensure_digits($field) . ', ';

            // The bio doesn't need any special parsing,
            // but a comment is added about where it was found
            $bio = $project_reference . trim(array_pop($match));

            // Store found data
            $query = sprintf("
                INSERT INTO authors
                    (last_name, other_names,
                        byear, bmonth, bday,
                        dyear, dmonth, dday,
                        bcomments, dcomments, enabled)
                VALUES ('%s', '%s', $date_fields_str '', '', 'no')
            ", DPDatabase::escape($last_name),
                DPDatabase::escape($other_names));
            if ($simulating) {
                echo "<span class='error'>    " . _("The following query would have been run:") . "\n      " .
                     str_replace("\n", "\n      ", html_safe($query)) . "</span>\n";
                $author_id='#new author id#';
            }
            else {
                $store_result = DPDatabase::query($query);
                $author_id = mysqli_insert_id(DPDatabase::get_connection());
                echo '    ' . sprintf( _("The author was inserted into the database with the id %d."), $author_id) . "\n";
            }
            $query = sprintf("
                INSERT INTO biographies
                    (author_id, bio)
                VALUES(%d, '%s')
            ", $author_id, DPDatabase::escape($bio));
            if ($simulating)
                echo "<span class='warning'>    " . _("The following query would have been run:") . "\n      " .
                     str_replace("\n", "\n      ", html_safe($query)) . "</span>\n";
            else {
                $store_result = DPDatabase::query($query);
                echo '    ' . sprintf( _("The biography was inserted into the database with the id %d."), mysqli_insert_id(DPDatabase::get_connection())) . "\n\n";
            }
        }
    }
    echo "\n" . _("Done. All projects searched.");
}

function ensure_digits($digits_or_question_mark) {
    return $digits_or_question_mark == '?' ? 0 : $digits_or_question_mark;
}

echo_menu();

// vim: sw=4 ts=4 expandtab
