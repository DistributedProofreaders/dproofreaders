<?PHP
  $relPath = '../../pinc/';
  include_once($relPath.'theme.inc');
  include_once("authors.inc");
  include_once("menu.inc");

  abort_if_not_authors_db_editor(true);

  // two default values (B. C.)
  $bbc = $dbc = FALSE;

  // load posted values or defaults
  if (isset($_POST['last_name'])) {
    // get the values from the form
    $vars = array( 'last_name', 'other_names',
                   'byear', 'bmonth', 'bday', 'bcomments', 'byearRadio',
                   'dyear', 'dmonth', 'dday', 'dcomments', 'dyearRadio'  );

    foreach ($vars as $var) {
      $$var = $_POST[$var];
    }

    // years are specified using radio-buttons and text-fields.
    // a little logic to get the right data
    // also, years might be negated by checking 'B. C.'
    if ($byearRadio == '0')
      $byear = 0;
    elseif (isset($_POST['bbc'])) {
      $bbc = TRUE;
      $byear = -$byear;
    }
    if ($dyearRadio == '0')
      $dyear = 0;
    elseif (isset($_POST['dbc'])) {
      $dbc = TRUE;
      $dyear = -$dyear;
    }

    if (isset($_POST['SaveAndExit']) || isset($_POST['SaveAndBio']) || isset($_POST['SaveAndNew'])) {
      // validate

      // insert into the database
      if (isset($_POST['author_id'])) {
        // edit existing author
        $author_id = $_POST['author_id'];
        $result = mysql_query("UPDATE authors " .
                              "SET last_name='$last_name', other_names='$other_names', " .
                              "byear=$byear, bmonth=$bmonth, bday=$bday, bcomments='$bcomments', " .
                              "dyear=$dyear, dmonth=$dmonth, dday=$dday, dcomments='$dcomments' " .
                              "WHERE author_id = $author_id;");
        $msg = _('The author was successfully updated in the database!');
      }
      else {
        // add new author to database
        $result = mysql_query("INSERT INTO authors ".
                              "(last_name, other_names, byear, bmonth, bday, bcomments, dyear, dmonth, dday, dcomments, enabled) " .
                              "VALUES('$last_name', '$other_names', $byear, $bmonth, $bday, '$bcomments', $dyear, $dmonth, $dday, '$dcomments', 'yes');");
        $msg = _('The author was successfully entered into the database!');
        $author_id = mysql_insert_id();
      }
      if ($result) {
        // success
        $msg = _('The author was successfully entered into the database!');
        if (isset($_POST['SaveAndExit'])) {
          // exit to where? mode=manage: manage.php, all else: listing.php
          if (isset($_POST['mode']) && $_POST['mode'] == 'manage')
            $exit_to = 'manage.php';
          else
            $exit_to = 'listing.php';
          header("Location: $exit_to?message=" . urlencode($msg));
        }
        else if (isset($_POST['SaveAndBio']))
          header('Location: addbio.php?message=' . urlencode($msg) . '&author_id=' . $author_id);
        else if (isset($_POST['SaveAndNew']))
          header('Location: add.php?message=' . urlencode($msg));
      }
      else {
        // failure!
        echo _('The author could not be added into the database.');
        echo ' ' . _('Please try again.');
        echo ' ' . _('If the problem persists, please contact a system administrator.');
      }
      exit();
    }
    else {
      // Preview
      // This means we have to strip the slashes that php added for us
      foreach (array('last_name', 'other_names', 'bcomments', 'dcomments') as $var)
        $$var = stripslashes($$var);
    }
  }
  else {
    // GET => output form

    if (isset($_GET['author_id'])) {
      // edit specified author
      $author_id = $_GET['author_id'];
      // get the values from the database
      $result = mysql_query("SELECT * FROM authors WHERE author_id = $author_id;");
      if (!$result) {
        echo "That author doesn't exist!";
        exit();
      }
      $vars = array( 'last_name', 'other_names',
                     'byear', 'bmonth', 'bday', 'bcomments',
                     'dyear', 'dmonth', 'dday', 'dcomments'  );
      foreach ($vars as $var)
        $$var = mysql_result($result, 0, $var);
      // select the correct year-radio-button
      if ($byear == 0) {
        $byearRadio = '0';
        $byear = '';
      }
      else {
        $byearRadio = 'entered';
        $bbc = ($byear < 0);
        $byear = abs($byear);
      }
      if ($dyear == 0) {
        $dyearRadio = '0';
        $dyear = '';
      }
      else {
        $dyearRadio = 'entered';
        $dbc = ($dyear < 0);
        $dyear = abs($dyear);
      }
    }
    else {
      // default values
      $last_name = $other_names = $byear = $dyear = $bcomments = $dcomments = '';
      $bmonth = $bday = $dmonth = $dday = $dyearRadio = $byearRadio = '0';
    }
  }

  // from here on to end of file:
  // produce form (with blank values
  // or those to be edited)

  theme(_('Add author'), 'header');


  echo_menu();

  function write_months_list($bd, $selected) {
    global $months;
    echo "<select name=\"$bd" . "month\" size=\"1\">\n";
    for ($i = 0; $i < $selected; $i++)
      echo "<option value=\"$i\"/>$months[$i]\n";
    echo "<option value=\"$selected\" SELECTED />$months[$i]\n";
    for ($i = $selected + 1; $i < 13; $i++)
      echo "<option value=\"$i\"/>$months[$i]\n";
    echo "</select>\n";
  }
  function write_days_list($bd, $selected) {
    echo "<select name=\"$bd" . "day\" size=\"1\">\n";
    echo "<option value=\"0\"" . ($selected=='0' ? ' SELECTED' : '') . " />" . _('Unknown');
    for ($i = 1; $i <= 31; $i++)
      echo "<option value=\"$i\"". ($i==$selected?' SELECTED':'') .">$i\n";
    echo "</select>\n";
  }

?>

<script language="JavaScript">
<!--
/*
  Not validated: That days exists within that month and year.
  For example, it is possible to select Feb. 31 without any complaints
*/
function validate() {
  var form = document.addform;
  if (form.last_name.value == "") {
    alert("<?=_('Invalid last name.')?>");
    form.last_name.focus();
    return false;
  }

  var born = form.byear.value;
  if (born != "") {
    if (!isInteger(born) || born == "0") {
      alert("<?=_('Invalid birth year.')?>");
      form.byear.focus();
      return false;
    }
  }
  else if (form.bbc.checked) {
    alert("<?=_('No birth year entered, but B. C. checked. Inconsecvent.')?>");
    return false;
  }
  if (form.bbc.checked)
    born *= -1;
  var dead = form.dyear.value;
  if (dead != "") {
    if (!isInteger(dead) || dead == "0") {
      alert("<?=_('Invalid death year.')?>");
      form.dyear.focus();
      return false;
    }
  }
  else if (form.dbc.checked) {
    alert("<?=_('No death year entered, but B. C. checked. Inconsecvent.')?>");
    return false;
  }
  if (form.dbc.checked)
    dead *= -1;

  var bmonth = form.bmonth.value,
      bday   = form.bday.value,
      dmonth = form.dmonth.value,
      dday   = form.dday.value;

  // birth-date: unknown year implies totally unknown
  //             unknown month implies unknown day
  // date of death: same check....
  if (!isValidDate(born, bmonth, bday)) {
    alert("<?=_('Invalid birth-date: Bad mix of unknown/known constraints.')?>");
    return false;
  }
  if (!isValidDate(dead, dmonth, dday)) {
    alert("<?=_('Invalid date of death: Bad mix of unknown/known constraints.')?>");
    return false;
  }

  // check for a reasonable life-span (1-150 years)
  if (born != "" && dead != "") {
    var lifeSpan = dead - born;
    if (lifeSpan <= 0) {
      // length of life zero or less
      // Yes, I assume no one dies within one
      // year of birth AND writes a book.
      // A better solution includes validating the
      // days specified within that year
      alert("<?=_('Invalid combination of year of birth and year of death.')?>");
      return false;
    }
    if (150 < lifeSpan) {
      alert("<?=_('Invalid length of life.')?>");
      return false;
    }
  }

  // everything seems ok. start data transfer
  return true;
}
function isInteger(value) {
  if (value.length == 0)
    return false;
  for (i=0;i<value.length;i++)
    if ("0123456789".indexOf(value.substr(i, 1)) == -1)
      return false;
  return true;
}
function isValidDate(year, month, day) {
  // Flag: specified.
  // True as soon as something is defined.
  // If something is undefined, but this is true, then
  // there is an error
  // (e.g. I cannot have year unknown, but be certain
  // it was March, or if I don't even know it's March,
  // then where come "the 3rd" from?).
  var specified = (0 < day); // day known
  if (0 < month) {
    // month known
    specified = true;
  }
  else {
    // if day was specified - but month not so - something's wrong
    if (specified)
      return false;
  }
  if (year == "" || year == 0) {
    // year not specified. if month was, something's wrong
    if (specified)
      return false;
  }
  return true;
}

function setComments(bd, comments) {
  eval("document.addform."+bd+"comments.value=comments;");
}
// -->
</script>
<?php
  if (isset($_GET['message']))
    echo '<center>' . $_GET['message'] . '</center><br />';
  elseif (isset($_POST['Preview'])) {
    echo_author($last_name, $other_names,
                format_date($byear, $bmonth, $bday, $bcomments),
                format_date($dyear, $dmonth, $dday, $dcomments),
                 (isset($_POST['author_id']) ? $_POST['author_id'] : FALSE)     );
    echo '<br/>';
    if (isset($_POST['author_id']))
      $author_id = $_POST['author_id'];
  }
?>
<form name="addform" action="add.php" method="POST" onSubmit="return validate();">
<?
  if (isset($author_id))
    echo "<input type='hidden' name='author_id' value='$author_id'>\n";

  function _var($bd, $name) {
    $var = $bd . $name;
    global $$var;
    return $$var;
  }
  function echo_date_fields($bd) {
?>
<table>
<tr><td><?=_('Month')?>:</td><td>
<?=write_months_list($bd, _var($bd, 'month'))?></td></tr>
<tr><td><?=_('Day')?>:</td><td>
<?=write_days_list($bd, _var($bd, 'day'))?></td></tr>
<tr><td><?=_('Year')?>:</td><td>
<input type="radio" name="<?=$bd?>yearRadio" value="0"<?=(_var($bd, 'yearRadio')=='0'?' CHECKED':'')?> /><?=_('Unknown')?>
<br/><input type="radio" name="<?=$bd?>yearRadio" value="entered"<?=(_var($bd, 'yearRadio')=='entered'?' CHECKED':'')?> onClick="this.form.<?=$bd?>year.focus();" /><?=_('As entered')?>:
<input type="text" name="<?=$bd?>year" size="4" maxlength="4"<?=(_var($bd, 'yearRadio')=='entered'?' VALUE="'.abs(_var($bd, 'year')).'"':'')?> onFocus="this.form.<?=$bd?>yearRadio[1].checked=true;" />
<input type="checkbox" name="<?=$bd?>bc" value="yes"<?=(_var($bd, 'bc')?' CHECKED':'')?> /><?=_('B. C.')?>
</td></tr><tr><td><?=_('Comments (in<br />English, please)')?>:</td><td><input type="text" size="20" maxlength="20" name="<?=$bd?>comments" value="<?=htmlspecialchars(_var($bd, 'comments'),ENT_QUOTES)?>" /> 
<?=_('Handy links:').' ' ?>
<a href="javascript:setComments('<?=$bd?>', '');" onClick="false">Empty (Unknown)</A> | 
<a href="javascript:setComments('<?=$bd?>', '(circa)');" onClick="false">(circa)</A>
<?php
    // 'Still alive' only if death-field.
    if ($bd == 'd')
      echo " | <a href=\"javascript:setComments('$bd', 'Still alive');\" onClick=\"false\">Still alive</A>";
    echo '</td></tr></table>';
  }

// mode=manage means we exit to manage.php
// everything else defaults to listing.php
if (isset($_GET['mode']) && $_GET['mode'] == 'manage') {
  echo '<input type="hidden" name="mode" value="manage" />';
  $exit_to = 'manage.php';
}
else
  $exit_to = 'listing.php';

echo '<br>';

echo '<p>' .
     _('When entering birth- and death-dates, please make sure you get the data from a trustable source. This is unfortunately a hard term to define, but at the bottom of the page, you will find a list of suggested sites that provide a lot of data like this.')
     . '</p><p>' .
     _('You can provide a comment with each date, but it should be short and concise. It will be displayed with the date as e.g. "200 B. C. (circa)", "(circa)" being the comment. If you wish to imply that the date is unknown, simply leave the field empty (do not type "Unknown" since that string will not be displayed in the other users\' preferred languages).')
     . '</p>';

?>
<center><table cellspacing='0' cellpadding='5' border='1' width='90%' bordercolor='#000000' style='border-collapse:collapse'>
<tr><td bgcolor='<?=$theme['color_headerbar_bg']?>' colspan='2'><center><b><font color='<?=$theme['color_headerbar_font']?>'>
<?=(isset($_GET['author_id']))
     ?
       _('Edit Author')
     :
       _('Add Author')?></font></b></center></td></tr>
<tr><th bgcolor='#CCCCCC'><?=_('Last name')?></th><td><input type="text" size="40" name="last_name" VALUE="<?=htmlspecialchars($last_name,ENT_QUOTES)?>"/></td></tr>
<tr><th bgcolor='#CCCCCC'><?=_('Other name(s)')?></th><td><input type="text" size="40" name="other_names" VALUE="<?=htmlspecialchars($other_names,ENT_QUOTES)?>"/></td></tr>
<tr><th bgcolor='#CCCCCC'><?=_('Born')?></th><td>
<?
  echo_date_fields('b');
?>
</td></tr>
<tr><th bgcolor='#CCCCCC'><?=_('Deceased')?></th><td>
<?
  echo_date_fields('d');
?>
</td></tr>
<tr><td colspan="2" bgcolor='#CCCCCC' align="center">
<input type="submit" name="Preview" value="<?=_('Preview')?>" />
<input type="submit" name="SaveAndExit" value="<?=_('Save and Exit')?>" />
<input type="submit" name="SaveAndBio" value="<?=_('Save and add Biography')?>" />
<input type="submit" name="SaveAndNew" value="<?=_('Save and add Another')?>" />
<input type="button" value="<?=_('Exit without saving')?>" onClick="location='<?=$exit_to?>';"/></td>
</td></tr>
</table>
</center>
</form>

<?php

  echo '<p>' . _('Are you looking for birth- and death-data for an author? These links are good and trustable sources of information in our experience:');
  echo '<ul>';

  echo '<li><a href="http://www.newadvent.org/cathen/" target="_blank">' . _('Catholic Encyclopedia') . '</a></li>';
  echo '<li><a href="http://www.bartleby.com/65/" target="_blank">' . _('Columbia Encyclopedia') . '</a></li>';
  echo '<li><a href="http://www.copac.ac.uk/copac/" target="_blank">COPAC</a> (' .
       _('24 major university research libraries in the UK and Ireland searchable at one place') . ')</li>';
  echo '<li><a href="http://www.britannica.com/" target="_blank">' . _('Encyclop&aelig;dia Britannica') . '</a> (' .
       _('birth- and death-dates can be accessed without registering') . ')</li>';
  echo '<li><a href="http://authorities.loc.gov/" target="_blank">' . _('The Library of Congress') . '</a></li>';

  echo '</ul>';

  echo_menu();

  theme('', 'footer');
?>
