<?php
  $relPath = '../../pinc/';
  include_once($relPath.'theme.inc');
  include_once($relPath.'user_is.inc');
  include_once($relPath.'SortUtility.inc');
  include_once($relPath.'BrowseUtility.inc');
  include_once('authors.inc');
  include_once('search.inc');
  include_once('menu.inc');

  theme('Manage biographies', 'header');

  abort_if_not_authors_db_manager();

  if ($_SERVER['QUERY_STRING'] == '') {
    echo "<script language='JavaScript'><!--\nlocation.href='?reload';\n";
    echo "document.open();\ndocument.write('Please <a href=\"?reload\">continue</a>.');\ndocument.close();\n--></script>\n";
    echo '<noscript>Please note that you need to have JavaScript turned on in order to use this page.</noscript>';
    theme('', 'footer');
    exit();
  }

  echo_menu();

  echo '<h2 align="center">Manage biographies</h2>';

  if (isset($_GET['message']))
    echo '<center>' . $_GET['message'] . '</center><br />';

  if (isset($_POST) && count($_POST)>0) {

    // find out what to do -- store in different 'queues'

    $delete_bios = array();
    $move_bios = array();
    $delete_authors = array();
    $enable_author_values = array();
    $enable_author_ids = array();

    if (isset($_POST['move_to_author']))
      $moveTo = $_POST['move_to_author'];

    // loop through posted data, see what the field names start with, save ids in arrays
    reset($_POST);
    while (list($key, $val) = each($_POST)) {

      if (strpos($key, 'delete_bio_') !== false)
        array_push($delete_bios, substr($key, 11));

      if (strpos($key, 'move_bio_') !== false)
        array_push($move_bios, substr($key, 9));

      if (strpos($key, 'delete_author_') !== false)
        array_push($delete_authors, substr($key, 14));

      if (strpos($key, 'old_enabled_author_') !== false) {
        // find id and new status
        $id = substr($key, 19);
        $new = isset($_POST["new_enabled_author_$id"]) ? $_POST["new_enabled_author_$id"] : 'no';
        if ($new != $val) {
          // the value is changing
          // add the author id in the queue, unless it's been queued for removal
          // (I cannot get array_key_exists($id, $delete_authors) to work, thus the hack)
          if (!isset($_POST["delete_author_$id"])) {
            array_push($enable_author_values, $new);
            array_push($enable_author_ids, $id);
          }
        }
      }

    }

/*
    // Something to possibly do:
    // Go through authors and bios to be deleted, comparing 'lastmodified'
    // to the time the manage-interface loaded (specified by hidden field)
    // (don't allow removal of things that have changed)

    $timestamp = $_POST['timestamp'];

    if (count($delete_authors) != 0) {
      $clause = '(author_id=' . join(' OR author_id=', $delete_authors) . ')';
      $query = "SELECT author_id, last_modified FROM authors WHERE $clause";
      $result = mysql_query($query);
      $authors_lastmodified = array();
      while ($row = mysql_fetch_row($result))
        $authors_lastmodified[$row[0]] = $row[1];
    }

    if (count($delete_bios) != 0) {
      $clause = '(bio_id=' . join(' OR bio_id=', $delete_bios) . ')';
      $query = "SELECT bio_id, last_modified FROM biographies WHERE $clause";
      $result = mysql_query($query);
      $bios_lastmodified = array();
      while ($row = mysql_fetch_row($result))
        $bios_lastmodified[$row[0]] = $row[1];
    }

    // What should happen?
    // Failure at delete_bio:
    //   Delete those possible to. Halt.
    //   Display table. Also error message.
    //   Everything pre-selected?
    // Failure at delete_author:
    //   Delete those possible to. Continue to enable/disable.
    //   Display table. Also error message.
    //   Everything pre-selected?

    // $timestamp = mysql_result(mysql_query('SELECT CURRENT_TIMESTAMP AS time'), 0, 'time');
    // echo "<input type='hidden' name='timestamp' value='$timestamp' />";

*/

    // Those that do not pass the time-check are stored in arrays:
    $non_deleted_authors = array();
    $non_deleted_bios = array();

    // 1. delete bios
    foreach ($delete_bios as $bio)
      mysql_query("DELETE FROM biographies WHERE bio_id = $bio");

    // 2. move bios
    if (isset($moveTo)) {
      foreach ($move_bios as $bio)
        mysql_query("UPDATE biographies SET author_id = $moveTo WHERE bio_id = $bio");
    }

    // 3. delete authors
    foreach ($delete_authors as $author) {
        mysql_query("DELETE FROM authors WHERE author_id = $author");
    }

    // 4. enable/disable authors
    $count = count($enable_author_values);
    for ($i = 0; $i < $count; $i++)
      mysql_query("UPDATE authors SET enabled = '${enable_author_values[$i]}' WHERE author_id = ${enable_author_ids[$i]}");

  }

  echo '<p>' . _('This is the web interface for managing the authors. You should not be using this if you
  simply want to add or edit an author &mdash; this is for performing unusual tasks such as
  removing duplicates and enabling or disabling the view of a specific author.') . '</p>';

  echo '<p>' . _('The directives you give below are processed \'right to left\', i.e. first biographies are removed,
  then biographies are moved, then authors are deleted, and finally authors are (un-)checked as enabled.') . '</p>';

  echo '<p>' . _('You should not be able to mark a biography for removal <b>and</b> moval, nor should you be able to
  delete an author without first (or simultaneously) deleting or moving all biographies connected to it. The site is
  supposed to prevent you from doing so, but please don\'t try.') . '</p>';

  // order of operation with the checkboxes:
  // 1: Delete biographies marked for removal
  // 2: Move marked for moval to author marked with "move to here"
  // 3: Delete authors marked for removal
  // 4: Those marked as checked in the form should be marked in the database

?>

<SCRIPT LANGUAGE="JavaScript"><!--

  // Holds all the biography-ids in arrays connected to the authors.
  // Built below, after the table
  var bios = new Object();

  // methods that are called when a checkbox is clicked. Do a little validating and adjusting.

  function deleteAuthor(check, author) {
    var form = check.form;

    if (check.checked) {
      // make sure it's not marked for "move to"
      var sel = getSelectedRadioBox(form.move_to_author);
      if (sel != null && sel.value == author) {
        check.checked = false;
        return;
      }

      // make sure that all bios are marked "move" or "delete"
      if (!allAuthorsBiosAreMarkedForMovalOrRemoval(form, author)) {
        check.checked = false;
        return;
      }
      eval("form.new_enabled_author_"+author+".disabled=true;");
      setMoveToAuthorDisabled(form, author, true);
      setAuthorsBiosChecksDisabled(form, author, true);
    }
    else {
      eval("form.new_enabled_author_"+author+".disabled=false;");
      setMoveToAuthorDisabled(form, author, false);
      setAuthorsBiosChecksDisabled(form, author, false);
    }
  }

  // An author has zero or more biographies. Loop through and return false
  // if one is found which is not marked for moval or removal.
  function allAuthorsBiosAreMarkedForMovalOrRemoval(form, author) {
    var authorsBios = bios[author];
    for (var i=0;i<authorsBios.length;i++) {
      var bio = authorsBios[i];
      if (!(  eval('form.delete_bio_'+bio+'.checked')
            ||eval('form.move_bio_'+bio+'.checked'))) {
        return false;
      }
    }
    return true;
  }

  function setAuthorsBiosChecksDisabled(form, author, disabled) {
    var authorsBios = bios[author];
    if (disabled) {
      // simply disable all
      for (var i=0;i<authorsBios.length;i++) {
        var bio = authorsBios[i];
        eval('form.delete_bio_'+bio+'.disabled=true');
        eval('form.move_bio_'+bio+'.disabled=true');
      }
    }
    else {
      // only disable the one that's checked
      for (var i=0;i<authorsBios.length;i++) {
        var bio = authorsBios[i];
        if (eval('form.delete_bio_'+bio+'.checked'))
          eval('form.delete_bio_'+bio+'.disabled=false');
        else
          eval('form.move_bio_'+bio+'.disabled=false');
      }
    }
  }

  function setMoveToAuthorDisabled(form, author, disabled) {
    var boxes = form.move_to_author;
    for (var i=0;i<boxes.length;i++)
      if (boxes[i].value == author) {
        boxes[i].disabled = disabled;
        return;
      }
  }

  function getSelectedRadioBox(boxes) {
    for (var i=0;i<boxes.length;i++)
      if (boxes[i].checked)
        return boxes[i];
    return null;
  }
  function setSelectedRadioBox(boxes, value) {
    for (var i=0;i<boxes.length;i++)
      if (boxes[i].value == value) {
        boxes[i].checked = true;
        return;
      }
  }

  // the one to reset to if the user makes an invalid choice -- updated whenever the user makes a selection
  var selectedMoveToHere = 0;

  function moveToHere(check, author) {
    var form = check.form;

    if (author == 0) {
      check.checked = false;
      if (selectedMoveToHere != 0)
        eval('form.delete_author_'+selectedMoveToHere+'.disabled=!allAuthorsBiosAreMarkedForMovalOrRemoval(form, selectedMoveToHere)')
      selectedMoveToHere = 0;
      return;
    }

    if (eval('form.delete_author_'+author+'.checked')) {
      // undo, reset
      check.checked = false;
      if (selectedMoveToHere != 0)
        setSelectedRadioBox(form.move_to_author, selectedMoveToHere);
    }
    else {
      if (selectedMoveToHere != 0)
        eval('form.delete_author_'+selectedMoveToHere+'.disabled=!allAuthorsBiosAreMarkedForMovalOrRemoval(form, selectedMoveToHere)')
      selectedMoveToHere = getSelectedRadioBox(form.move_to_author).value;
      eval('form.delete_author_'+author+'.disabled=true')
    }
  }

  // user /un/checks to move bio
  function moveBio(check, author, bio) {
    var form = check.form;

    var deleteChk = eval("form.delete_bio_"+bio);
    var authorChk = eval("form.delete_author_"+author);

    // the two values currently selected for the "delete" and "move"-checkboxes for this bio
    var move = check.checked;
    var del = deleteChk.checked;

    if (move) {
      if (del)
        // undo
        check.checked = false;
      else {
        deleteChk.disabled = true;
        if (allAuthorsBiosAreMarkedForMovalOrRemoval(form, author))
          authorChk.disabled = false;
      }
    }
    else if (!move) {
      if (!del && eval("form.delete_author_"+author+".checked"))
        // author is marked for removal
        // undo -- keep the bio selected for removal
        check.checked = true;
      else {
        deleteChk.disabled = false;
        authorChk.disabled = true;
      }
    }
  }

  // user decides/regrets to delete bio
  function deleteBio(check, author, bio) {
    var form = check.form;

    var moveChk = eval("form.move_bio_"+bio);
    var authorChk = eval("form.delete_author_"+author);

    // the two values currently selected for the "delete" and "move"-checkboxes for this bio
    var del = check.checked;
    var move = moveChk.checked;

    if (del) {
      if (move)
        // undo
        check.checked = false;
      else {
        moveChk.disabled = true;
        if (allAuthorsBiosAreMarkedForMovalOrRemoval(form, author))
          authorChk.disabled = false;
      }
    }
    else if (!del) {
      if (!move && eval("form.delete_author_"+author+".checked"))
        // author is marked for removal
        // undo -- keep the bio selected for moval
        check.checked = true;
      else {
        moveChk.disabled = false;
        authorChk.disabled = true;
      }
    }
  }

  // evaluate the form:
  // * make sure that if something's to be moved, there's a destination
  // * vice versa

  function evaluateForm(form) {
    // find something to move and a destination
    var toMove = false;
    var el;
    for (var i=0;i<form.elements.length;i++) {
      el = form.elements[i];
      if (el.name.indexOf('move_bio_')==0 && el.checked) {
        toMove = true;
        break;
      }
    }
    // check whether or not a destination is selected
    var moveTo = false;
    for (var i=0;i<form.move_to_author.length;i++) {
      if (form.move_to_author[i].checked) {
        moveTo = true;
        break;
      }
    }

    if (toMove && !moveTo) {
      alert("You have selected one or more biographies for moving, but no author to move them to.");
      return false;
    }
    else if (!toMove && moveTo) {
      alert("You have selected an author to move biographies to, but no biographies to move.");
      return false;
    }
    return true;
  }
// --></SCRIPT>

<?

  $sortUtility = new SortUtility('authors_manage');

  prepare_search();

  echo_search_form();

  $sortby = $sortUtility->getQueryStringForCurrentView();

?>

<a name="results"></a><h2 align="center"><?=get_search_title()?></h2>

<form name="adminform" action="?<?=$query.$sortby?>" method="POST" onSubmit="return evaluateForm(this);">

<?php

// argument 'view': 'enabled'(defualt), 'disabled', 'all'
// provide links for those and also buttons for submitting/resetting form.
$view = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'enabled';

$links_and_buttons = _('View:') . ' ';
if ($view != 'disabled' && $view != 'all')
  $links_and_buttons .= _('enabled');
else
  $links_and_buttons .= "<a href='?$query_without_view$sortby&view=enabled#results'>" . _('enabled') . '</a>';
$links_and_buttons .= " | ";
if ($view == 'disabled')
  $links_and_buttons .= _('disabled');
else
  $links_and_buttons .= "<a href='?$query_without_view$sortby&view=disabled#results'>" . _('disabled') . '</a>';
$links_and_buttons .= " | ";
if ($view == 'all')
  $links_and_buttons .= _('all');
else
  $links_and_buttons .= "<a href='?$query_without_view$sortby&view=all#results'>" . _('all') . '</a>';

$links_and_buttons .= ' &nbsp; &nbsp; &nbsp; &nbsp; <input type="submit" value="Process">';

echo $links_and_buttons;

?>

<br /><br />

<table align="center" border="1">
<tr>
<?
  // print headers
  // links to allow sorting (asc/desc)

  echo "<th rowspan='2'>Enabled</th><th rowspan='2'>Delete</th><th rowspan='2'>"._('Move to here');
  echo "<br />[<input type='radio' name='move_to_author' value='0' onClick='moveToHere(this, 0);'>"._('reset')."]</th>\n";
  echo "<th rowspan='2'><a href='manage.php?$query".$sortUtility->getQueryStringForSortableValue($sort_author_id).
       "'>Id</a></th>\n";
  echo "<th rowspan='2'><a href='manage.php?$query".$sortUtility->getQueryStringForSortableValue($sort_last_name).
       "'>Last name</a></th>\n";
  echo "<th rowspan='2'><a href='manage.php?$query".$sortUtility->getQueryStringForSortableValue($sort_other_names).
       "'>Other name(s)</a></th>\n";
  echo "<th rowspan='2'><a href='manage.php?$query".$sortUtility->getQueryStringForSortableValue($sort_born).
       "'>Born</a></th>\n";
  echo "<th rowspan='2'><a href='manage.php?$query".$sortUtility->getQueryStringForSortableValue($sort_dead).
       "'>Deceased</a></th>\n";
  echo "<th rowspan='2'>"._('Edit')."</th>\n<th rowspan='2'</th>\n";
  echo "<th colspan='3'>"._('Biographies')."</th>\n</tr>\n";
  echo "<tr><th>"._('View')."</th><th>"._('Move')."</th><th>"._('Delete')."</th></tr>\n";

  $result = search();

  $browseUtility = new BrowseUtility($result);

  // "Previous" and/or "Next" links?
  $prev_next_links = '';
  if ($browseUtility->isPreviousBrowseAvailable()) {
    $prev_next_links = "<a href='manage.php?$query".$browseUtility->getPreviousBrowseQueryString()."'>&lt;- "
                       . _('Previous') . '</a> &nbsp; &nbsp; &nbsp; ';
  }
  if ($browseUtility->isNextBrowseAvailable()) {
    $prev_next_links .= "<a href='manage.php?$query".$browseUtility->getNextBrowseQueryString()."'>"
                           . _('Next') . ' -&gt;</a>';
  }
  if ($prev_next_links != '')
    echo "<p align='center'>$prev_next_links</p>";

  // "Displaying entries x-y of z"
  echo '<p align="center">' . $browseUtility->getDisplayingString() . '</p>';

  // Added to for each bio. Looks like: array[author_id] = array of bio_ids
  $javascript_to_build_bios_array = '';
  // Added to for each author that has a bio. Disables "delete author"-check
  // when the page is loaded
  $javascript_to_disable_delete_authors = '';

  $count = $browseUtility->getRowCountToList();
  $i = 0;

  while ($i++ < $count && $author = @mysql_fetch_array($result)) {
    echo "<tr><td colspan='13'></td></tr>\n";
    $id = $author['author_id'];
    $bioresult = mysql_query("SELECT bio_id FROM biographies WHERE author_id = $id ORDER BY bio_id;");
    $bio_count = mysql_numrows($bioresult);

    if ($bio_count > 0) {
      $javascript_to_disable_delete_authors .= "document.adminform.delete_author_$id.disabled = true;\n";
    }

    $enabled = $author['enabled'];

    // csl with the bio-ids
    $bios_for_this_author = '';

    echo "<input type='hidden' name='old_enabled_author_$id' value='$enabled' />\n";
    echo "<tr><td rowspan='$bio_count'><input type='checkbox' name='new_enabled_author_$id' value='yes'" .
         ($enabled=='yes'?' checked':'') . " /></td>\n    " .
         "<td rowspan='$bio_count'><input type='checkbox' name='delete_author_$id' value='yes' onClick='deleteAuthor(this, $id);' /></td>\n";
    echo "<td rowspan='$bio_count'><input type='radio' name='move_to_author' value='$id' onClick='moveToHere(this, $id);' /></td>\n    " .
         "<td rowspan='$bio_count'>$id</td>\n    <td rowspan='$bio_count'><a href=\"author.php?author_id=$id\">" .
         $author['last_name'] . "</a></td>\n    <td rowspan='$bio_count'>" .
         $author['other_names'] . "</td>\n    <td rowspan='$bio_count'>" .
         format_date_from_array($author, 'b') . "</td>\n    <td rowspan='$bio_count'>" .
         format_date_from_array($author, 'd') . "</td>\n    " .
         "<td rowspan='$bio_count'><a href=\"add.php?author_id=$id&mode=manage\">" . _('Edit') . "</a></td>\n    " .
         "<td rowspan='$bio_count'></td>\n    ";
    for ($j = 0; $j < $bio_count; $j++) {
      $bio_id = mysql_result($bioresult, $j);
      if ($j != 0)
        echo "<tr>";
      write_bio_links($id, $bio_id);
    }
    $javascript_to_build_bios_array .= "bios[$id] = new Array($bios_for_this_author);\n";
  }

  echo "<SCRIPT LANGUAGE='JavaScript'><!--\n$javascript_to_build_bios_array\n";
  echo "$javascript_to_disable_delete_authors\n--></SCRIPT>";

  function write_bio_links($author_id, $bio_id) {
    global $bios_for_this_author;
    echo "<td><a href=\"bio.php?bio_id=$bio_id\">" . _('Biography') . " $bio_id</a></td>\n    " .
         "<td><input type='checkbox' name='move_bio_$bio_id' value='yes' onClick='moveBio(this, $author_id, $bio_id);' /></td>\n    " .
         "<td><input type='checkbox' name='delete_bio_$bio_id' value='yes' onClick='deleteBio(this, $author_id, $bio_id);' /></td>\n</tr>\n";
    if ($bios_for_this_author == '')
      // Javascript: if only one argument is passed to the Array()-constructor and it's numeric,
      // it's considered the length. Thus pass the first/only argument as a string.
      $bios_for_this_author = "'$bio_id'";
    else
      $bios_for_this_author .= ", $bio_id";
  }

?>
</form>
</table>
<br/>
<?php

  echo $links_and_buttons;

  if ($prev_next_links != '')
    echo "<p align='center'>$prev_next_links</p>";

  echo '<br />';

  $browseUtility->echoCountSelectionList();

  echo_menu();

  theme('', 'footer');

?>
