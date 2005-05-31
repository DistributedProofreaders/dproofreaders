<?php
$relPath='../../../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');

// THIS IS A ONE-TIME SCRIPT!
//
// In the 'projects' table, the values of 'postcomments' are currently html-encoded.
// This makes it easy to display them, but it is less good design and it makes
// appending to the comments difficult since a string must be html-encoded once and only once.

// The solution is to reverse the changes made to the data and rather use
// htmlspecialchars(...) when *displaying* the text (in a particular context).

// The following translations are performed by htmlspecialchars():
/*
   '&' (ampersand) becomes '&amp;'
   '"' (double quote) becomes '&quot;'
   ''' (single quote) becomes '&#039;'
   '<' (less than) becomes '&lt;'
   '>' (greater than) becomes '&gt;'
*/
// This script goes through the values in 'postcomments' and reverses this operation.

// By default, this script does nothing but reporting old and new value as well
// as the SQL code that should be executed.
// If the parameter 'act' is passed to the script (e.g. .../update_postcomments.php?act),
// the SQL-code will be executed.

// It is probably a good idea to remove this script once it has been run..

$result = mysql_query("SELECT projectid, postcomments FROM projects WHERE postcomments != ''");

header('Content-Type: text/plain');

$act = isset($_REQUEST['act']);

while ($row = mysql_fetch_array($result)) {
  $new_value = reversehtmlspecialchars($row['postcomments']);

  if (!$act)
    echo "OLD VALUE: {$row['postcomments']}\nNEW VALUE: $new_value\n";

  if ($new_value != $row['postcomments']) {
    $new_value = addslashes($new_value);
    $query = "UPDATE projects SET postcomments='$new_value' WHERE projectid='{$row['projectid']}'";

    if ($act)
      mysql_query($query);
    else
      echo "SQL QUERY: $query\n\n";
  }
  else {
    if ($act)
      ; // Do nothing
    else
      echo "No need to issue an SQL query.\n\n";
  }
}

echo mysql_error();

echo "Done.";

function reversehtmlspecialchars($s) {
  $s = str_replace('&#039;', '\'', $s);
  $s = str_replace('&quot;', '"', $s);
  $s = str_replace('&lt;', '<', $s);
  $s = str_replace('&gt;', '>', $s);
  $s = str_replace('&amp;', '&', $s);
  return $s;
}

?>
