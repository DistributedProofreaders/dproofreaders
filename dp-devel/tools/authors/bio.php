<?php

  // Display the biography specified by bio_id-argument

  $relPath = '../../pinc/';
  include_once($relPath.'theme.inc');
  include_once('authors.inc');
  include_once('menu.inc');

  $bio_id  = get_integer_param($_GET, 'bio_id', null, 0, null, true);
  $message = @$_GET['message'];
  
  // argument provided?
  if (isset($bio_id)) {
    $id = $bio_id;
  }
  else {
    theme(_('No biography-id specified'), 'header');
    echo _('An error has occured:') . ' ' . _('No biography-id was specified.') . ' ';
    echo sprintf(_('You may return to the <a href="%1$s">authors-listing</a>.'), 'listing.php');
    theme('', 'footer');
    exit();
  }

  // try to get bio
  $result = mysql_query("SELECT author_id, bio FROM biographies WHERE bio_id=$id;");
  if (!$result || mysql_num_rows($result) == 0) {
    theme(_('Invalid biography-id specified'), 'header');
    echo _('An error has occured:') . ' ' . _('The specified biography-id was invalid.') . ' ';
    echo sprintf(_('You may return to the <a href="%1$s">authors-listing</a>.'), 'listing.php');
    theme('', 'footer');
    exit();
  }
  $author_id = mysql_result($result, 0, 'author_id');
  $bio = mysql_result($result, 0, 'bio');

  $bio = preg_replace("/å/", "&aring;", $bio);

  // the author
  $result = mysql_query("SELECT last_name, other_names FROM authors WHERE author_id=$author_id;");
  $last_name = mysql_result($result, 0, 'last_name');
  $other_names = mysql_result($result, 0, 'other_names');

  $name = $last_name . ($other_names!=''?", $other_names":'');

  // Start outputting
  theme(_('Biography:') . " $name", 'header');

  echo_menu();

  echo '<h1 align="center">' . _('Biography') . '</h1>';

  if (isset($message))
    echo '<center>' . htmlspecialchars($message) . '</center><br />';
?>
<h2 align="center"><?php echo htmlspecialchars($name); ?> 
<a href='<?php echo $code_url; ?>/tools/authors/bioxml.php?bio_id=<?php echo $id; ?>'><img src='<?php echo $code_url; ?>/graphics/xml.gif' border='0' width='36' height='14' style='vertical-align:middle'></a>
</h2>
<?php
  if (user_is_PM() || user_is_authors_db_manager()) {
    echo _('To include this biography into the project comments of a project, insert the following snippet into the project comments:');
    echo " <b>[biography=$id]</b>";
  }
?>
<br /><br />
<table align="center" border="1">
<tr><td>
<?php echo htmlspecialchars($bio); ?>
</td></tr>
</table>
<?php

  echo_menu();

  theme('', 'footer');
?>
