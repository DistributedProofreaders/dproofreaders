<?PHP
  $relPath = '../../pinc/';
  include_once($relPath.'theme.inc');
  include_once("authors.inc");
  include_once("menu.inc");

  abort_if_not_authors_db_editor(true);

  // load posted values or defaults
  if (isset($_GET['author_id'])) {
    // init creation of new bio
    $author_id = $_GET['author_id'];
    $bio = '';
  }
  elseif (isset($_GET['bio_id'])) {
    // init edit of existing bio
    $bio_id = $_GET['bio_id'];
    $result = mysql_query("SELECT * FROM biographies WHERE bio_id = $bio_id;");
    $author_id = mysql_result($result, 0, 'author_id');
    $bio    = mysql_result($result, 0, 'bio');
  }
  elseif (isset($_POST['author_id'])) {
    // preview / save
    $author_id = $_POST['author_id'];
    $bio = $_POST['bio'];

    if (isset($_POST['SaveAndExit']) || isset($_POST['SaveAndView']) || isset($_POST['SaveAndNew'])) {

      // save

      if (isset($_POST['bio_id'])) {
        // edit existing bio
        $bio_id = $_POST['bio_id'];
        $result = mysql_query("UPDATE biographies ".
                              "SET bio = '$bio' " .
                              "WHERE bio_id = $bio_id;");
        $msg = _('The biography was successfully updated in the database!');
      }
      else {
        // add to database
        $result = mysql_query("INSERT INTO biographies ".
                              "(author_id, bio) " .
                              "VALUES($author_id, '$bio');");
        $bio_id = mysql_insert_id();
        $msg = _('The biography was successfully entered into the database!');
      }
      if ($result) {
        // success
        if (isset($_POST['SaveAndExit']))
          header('Location: listing.php?message=' . urlencode($msg));
        else if (isset($_POST['SaveAndView']))
          header("Location: bio.php?bio_id=$bio_id&message=" . urlencode($msg));
        else if (isset($_POST['SaveAndNew']))
          header('Location: add.php?message=' . urlencode($msg));
      }
      else {
        // failure!
        theme(_('An error has occured'), 'header');
        echo _('It was not possible to save the biography.') . _('The following error-message was recieved:') . ' ' .
               mysql_error($result);
        theme('', 'footer');
      }
      exit;
    }
    else {
      // Preview
      // One thinks it's only to display the values, but we actally need to *remove*
      // the slashes that php has inserted for us (to prepare strings for database-insertion)
      $bio = stripslashes($bio);
    }
  }
  else {
    // someone's trying to display this page outside of the workflow.
    theme(_('An error has occured'), 'header');
    echo _('Some information is missing and this page can not be displayed. This has most likely occured' .
           'because you have entered the URL manually. Please enter this page by following links from other pages.');
    theme('', 'footer');
    exit;
  }

  if (isset($_POST['bio_id']))
    $bio_id = $_POST['bio_id'];

  // from here on to end of file:
  // produce form (with blank values
  // or those to be edited)

  theme(_('Add biography'), 'header');

  echo_menu();
?>

<h1 align="center">Add Biography</h1>
<?php
  if (isset($msg))
    echo $msg;

  if (isset($_GET['message']))
    echo '<center>' . $_GET['message'] . '</center><br />';
  elseif (isset($_POST['Preview'])) {
    echo '<table align="center" border="1"><td>' . $bio . '</td></table>';
    echo '<br/>';
  }
?>
<form name="addform" action="addbio.php" method="POST">
<input type="hidden" name="author_id" value="<?=$author_id?>">
<?php
  if (isset($bio_id))
    echo '<input type="hidden" name="bio_id" value="' . $bio_id . '">';
?>
<table align="center" border="1">
<tr><th><?=_('Biography') . ' (' . _('HTML') . ')'?></th></tr>
<tr><td><textarea cols="70" rows="20" name="bio">
<?=htmlentities($bio)?></textarea></td></tr>
<tr><td>
<table><tr>
<td><input type="submit" name="Preview" value="<?=_('Preview')?>" /></td>
<td><input type="submit" name="SaveAndExit" value="<?=_('Save and Exit')?>" /></td>
<td><input type="submit" name="SaveAndView" value="<?=_('Save and View')?>" /></td>
<td><input type="submit" name="SaveAndNew" value="<?=_('Save and add Author')?>" /></td>
<td><input type="button" value="<?=_('Exit without saving')?>" onClick="location='listing.php';"/></td>
</tr></table>
</td></tr>
</table>
</form>

<?php
  echo_menu();

  theme('', 'footer');
?>