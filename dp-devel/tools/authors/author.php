<?PHP

  $relPath = '../../pinc/';
  include_once($relPath.'theme.inc');
  include_once('authors.inc');
  include_once('menu.inc');

  // argument provided?
  if (isset($_GET['author_id'])) {
    $id = $_GET['author_id'];
  } else {
    theme(_('No author-id specified'), 'header');
    echo _('There was an error: No author-id was specified.') . ' ';
    echo _('If you believe this to be the result of perfectly legitimate actions taken by you, please ');
    echo '<a href="whatever">' . _('report it to us') . "</a>.\n";
    echo _('You may return to <a href="listing.php">the authors-listing</a>.');
    theme('', 'footer');
    exit();
  }

  $result = mysql_query("SELECT * FROM authors WHERE author_id=$id;");
  $last_name = mysql_result($result, 0, "last_name");
  $other_names = mysql_result($result, 0, "other_names");
  $birth = format_date_from_sqlset($result, 0, 'b');
  $decease = format_date_from_sqlset($result, 0, 'd');

  // Start outputting
  theme(_('Author') . ': ' . $last_name . ($other_names!=''?", $other_names":''), 'header');

  echo_menu();

  echo '<h1 align="center">' . _('Author') . '</h1>';

  echo_author($last_name, $other_names, $birth, $decease, $id);

  if (user_is_authors_db_manager()) {
?>
<BR/>
<?php
  }

  echo_menu();

  theme('', 'footer');
?>
