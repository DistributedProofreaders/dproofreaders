<?PHP

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
  include_once($relPath.'theme.inc');
  include_once($relPath.'dp_main.inc');
  include_once('authors.inc');
  include_once('menu.inc');

  abort_if_not_authors_db_manager(true);

  theme('Harvest existing biographies from project comments', 'header');

  // Tables must exist (because this script shouldn't need to care about creating them)
  // and be empty (because if they're not, the harvest is likely done in error)

  if (!table_exists('authors') || !table_exists('biographies')) {
    echo 'The tables have not been created! Please create them by having a Site Admin run create_authors_bios_tables.php.';
    theme('', 'footer');
    exit;
  }

  $result = mysql_query('SELECT * FROM authors');
  if (mysql_num_rows($result) > 0) {
    echo 'The table `authors` is not empty. Please empty it and try again.';
    theme('', 'footer');
    exit;
  }
  $result = mysql_query('SELECT * FROM biographies');
  if (mysql_num_rows($result) > 0) {
    echo 'The table `biographies` is not empty. Please empty it and try again.';
    theme('', 'footer');
    exit;
  }

  echo_menu();

  if (!isset($_POST['actionBtn'])) {

    // print usage and the button to simulate harvesting

    echo '<p>' . sprintf(
                   'When you click the button below, the project comments of all projects will be searched for biographies.
                      Records in the database will be created for both the author and the biography, but they will of course
                      need to be examined. This will be done using the <a href="%1$s">managing tool</a> and the process is
                      explained further below.',
                   'manage.php');

    echo '<p>' . 'Please do note that this is a one-time event.';

?>

<form name="harvest" method="POST">
<input type="submit" name="actionBtn" value="<?='Simulate harvest'?>">
</form>
  
<?php

    echo '<h2>' . 'Following the harvest' . '</h2>';

    echo '<p>' . 'The authors and biographies need be examined
    for duplicates and obvious errors such as misplaced data.';

    echo ' ' . sprintf('This will be done using the <a href="%1$s">managing tool</a>:', 'manage.php');
    echo '<ul><li>';
    echo 'Select to only view non-enabled authors.';
    echo '<li>' . 'Authors and biographies are linked the way they were found in the database harvest.
    This means they are all in a one-to-one relationship. There may be duplicate entries
    for the same author. There should only be one author entry per author,
    and it should link to all biographies. This is done manually. Multiple occurances of a
    biography should be reduced to one.';
    echo '<li>' . 'Make frequent and possibly creative use of the searching
    and listing possibilities to identify duplicate authors.';
    echo '<li>' . 'The authors and biographies are all marked as "not enabled" in the database.
    Until you check the "enabled" box next to it in this listing and submit, the author/bio
    will not appear to the average user but you will see it here. After that, it will be the
    other way round. This means you should only check that box when you are certain you won\'t
    need to edit that author again or move more biographies to it.';
    echo '</ul></p>';

  } // end of usage and button
  else {

    // Are we simulating? Determined by the label of the button clicked.
    $simulating = ($_POST['actionBtn'] == 'Simulate harvest');

    if ($simulating) {
      echo "<strong>Simulating!</strong> Queries are not run. Check that there are no peculiarities
            below that you believe should be addressed, and click the 'Harvest'-button.";
      ?>
      <form name="harvest" method="POST">
      <input type="submit" name="actionBtn" value="<?='Simulate harvest'?>">
      <input type="submit" name="actionBtn" value="<?='Harvest'?>">
      </form>
      <?php
    }
    else {
      echo "<strong>Harvesting!</strong> Below should be a log of what has been done.";
    }

    // Harvest

    echo '<pre>';

    // Get all the project comments that contain a biography

    $result = mysql_query("SELECT projectID, comments FROM projects WHERE comments LIKE '%<!-- begin bio%'");
    echo sprintf('Found %1$d projects with biographies in them. Processing....', mysql_num_rows($result)) . "\n";

    // Loop through, extracting all bios
    while ($row = mysql_fetch_row($result)) {
      // get all the data with a regex, store matches in $matches
      $matches = array();
      $count = preg_match_all("/<!-- begin bio: (.*?) (\((.*?)\))? -->(.*?)<!-- end bio/is", $row[1], $matches, PREG_SET_ORDER);
      $project_id = $row[0];
      echo '  ' . sprintf('Project %1$s contains %2$d biographies.', $project_id, $count) . "\n";

      if ($count == 0) {
        echo 'It seems the biography in this project was not properly marked. Please check manually:';
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
          $last_name = addslashes(trim(substr($match[1], 0, $comma)));
          $other_names = addslashes(trim(substr($match[1], $comma+1)));
          echo '    ' . sprintf("Name split into '%1\$s', '%2\$s'.", $last_name, $other_names) . "\n";
        }
        else {
          $last_name = addslashes(trim($match[1]));
          $other_names = '';
          echo '    ' . 'Name not split.' . "\n";
        }

        // The dates need some extra parsing.
        // Valid formats:
        // 2002-02-02-2004-04-04    2002-02-02-    20020202-    20020202-20040404    2002-2004 .
        // Spaces are ok. Question marks may be used to signify 'unknown'

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
        $bio = addslashes($project_reference . trim(array_pop($match)));

        // Store found data
        $query = "INSERT INTO authors ".
                 "(last_name, other_names, byear, bmonth, bday, dyear, dmonth, dday, bcomments, dcomments, enabled)\n" .
                 "VALUES('$last_name', '$other_names', $date_fields_str '', '', 'no')";
        if ($simulating) {
          echo "<font color='red'>    The following query would have been run:\n      " .
               str_replace("\n", "\n      ", htmlspecialchars($query)) . "</font>\n";
          $author_id='#new author id#';
        }
        else {
          $store_result = mysql_query($query);
          if (!$store_result) {
            echo '    ' . 'An error occured while saving the author:' . ' ' . mysql_error() . "\n";
            exit;
          }
          $author_id = mysql_insert_id();
          echo '    ' . sprintf('The author was inserted into the database with the id %1$d.', $author_id) . "\n";
        }
        $query = "INSERT INTO biographies ".
                 "(author_id, bio) " .
                 "VALUES($author_id, '$bio');";
        if ($simulating)
          echo "<font color='blue'>    The following query would have been run:\n      " .
               str_replace("\n", "\n      ", htmlspecialchars($query)) . "</font>\n";
        else {
          $store_result = mysql_query($query);
          if (!$store_result) {
            echo '    ' . 'An error occured while saving the biography:' . ' ' . mysql_error() . "\n";
            exit;
          }
          echo '    ' . sprintf('The biography was inserted into the database with the id %1$d.', mysql_insert_id()) . "\n\n";
        }
      }
    }
    echo "\nDone. All projects searched.";
  }

  function ensure_digits($digits_or_question_mark) {
    return $digits_or_question_mark == '?' ? 0 : $digits_or_question_mark;
  }

  echo_menu();

  theme('', 'footer');

  // Returns true if the table exists in the current database, false otherwise.
  function table_exists($tableName) {
    return ( mysql_query("DESCRIBE $tableName") != FALSE );
  }
?>
