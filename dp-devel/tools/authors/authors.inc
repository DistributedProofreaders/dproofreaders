<?PHP

  $months = array( _('Unknown'),
                   _('January'), _('February'), _('March'),
                   _('April'), _('May'), _('June'),
                   _('July'), _('August'), _('September'),
                   _('October'), _('November'), _('December') );

  // Arguments: the data to be displayed. If $author_id is FALSE, no biographies will be displayed.
  // If $author_id is not FALSE, all biographies that apply to that author id will be listed
  // and a link to the xml data will be provided.
  function echo_author($last_name, $other_names, $born, $dead, $author_id) {
    $name = $last_name . ($other_names!='' ? ", $other_names" : '');
?>

<h2 align="center"><?=htmlspecialchars($name)?>
<?
if ($author_id) {
  echo " <a href='{$GLOBALS['code_url']}/tools/authors/authorxml.php?author_id=$author_id'><img src='{$GLOBALS['code_url']}/graphics/xml.gif' border='0' width='36' height='14' style='vertical-align:middle'></a>\n";
}
?>
</h2>
<table align="center" border="1">
<tr><th>Born</th><td><?=$born?></td></tr>
<tr><th>Deceased</th><td><?=$dead?></td></tr>
<?php
    if ($author_id) {
      // this author actually exists - not only Preview before adding
      // list biographies (if any)
      $result = mysql_query("SELECT bio_id FROM biographies WHERE author_id = $author_id;");
      $numrow = mysql_numrows($result);
      if ($numrow == 1) {
        $bio_id = mysql_result($result, 0);
        echo "<tr><th>" . _('Biography') . "</th><td><a href=\"bio.php?bio_id=$bio_id\">" . _('Biography') . " $bio_id</a></td></tr>\n";
      }
      elseif ($numrow > 1) {
        $bio_id = mysql_result($result, 0);
        echo "<tr><th rowspan=\"$numrow\" valign=\"top\">" . _('Biographies') . "</th><td><a href=\"bio.php?bio_id=$bio_id\">" . _('Biography') . " $bio_id</a></td></tr>\n";
        for ($rownum = 1; $rownum < $numrow; $rownum++) {
          $bio_id = mysql_result($result, $rownum);
          echo "<tr><td><a href=\"bio.php?bio_id=$bio_id\">Biography $bio_id</a></td></tr>\n";
        }
      }
    }
    echo "</table>\n";
  }
  // end echo_author

  // $bd is 'b' or 'd'
  function format_date_from_sqlset($sql_result, $row, $bd) {
    $date = format_ymd(mysql_result($sql_result, $row, $bd.'year'),
                       mysql_result($sql_result, $row, $bd.'month'),
                       mysql_result($sql_result, $row, $bd.'day'));
    $comments = mysql_result($sql_result, $row, $bd.'comments');

    if ($date == null) {
      if ($comments == '')
        return _('Unknown');
      else
        return $comments;
    }
    else {
      if ($comments == '')
        return $date;
      else
        return "$date $comments";
    }
  }

  // $bd is 'b' or 'd'
  function format_date_from_array($row, $bd) {
    return format_date($row[$bd.'year'],
                       $row[$bd.'month'],
                       $row[$bd.'day'],
                       $row[$bd.'comments']);
  }

  function format_date($year, $month, $day, $comments) {
    $date = format_ymd($year, $month, $day);

    if ($date == null) {
      if ($comments == '')
        return _('Unknown');
      else
        return $comments;
    }
    else {
      if ($comments == '')
        return $date;
      else
        return "$date $comments";
    }
  }

  // Possible output:
  //   January 28, 1930
  //   April, 1954
  //   1917
  //   123 A. D.
  //   17 B. C.
  //   null
  // Years < 0 : Add B. C.
  // Years > 0 : Add A. D. if it's year 150 or earlier (this limit is very subjective).
  function format_ymd($y, $m, $d) {
    global $months;
    // one-letter variables contain database data
    // multi-letters variables contain formatted data
    if ($y > 150)
      $year = $y;
    else if ($y > 0)
      $year = $y . ' ' . _('A. D.');
    else if ($y < 0)
      $year = -$y . ' ' . _('B. C.');
    else
      // Year unknown = Everything unknown
      return null;

    if ($m > 0)
      $month = $months[$m];
    else
      // Month unknown = Only year known
      return $year;

    if ($d > 0)
      $day = $d;
    else
      // Day unknown = Year & Month known
      return $month . ', ' . $year;

    // Everything known
    return $month . ' ' . $day . ', ' . $year;
  }

  // convenience methods to politely throw out a user if s/he's not a manager/editor

  // $echo_header-argument should be true if the calling script has not yet output a header.

  function abort_if_not_authors_db_manager($echo_header = false) {
    if (!user_is_authors_db_manager()) {
      if ($echo_header)
        theme(_('Sorry'), 'header');
      echo "<h2 align='center'>" . _('Sorry!') . "</h2>";
      echo _('You do not have permission to access this page.
              If you believe you should, please contact a system administrator.');
      theme('', 'footer');
      exit();
    }
  }

  function abort_if_not_authors_db_editor($echo_header = false) {
    if (!user_is_authors_db_editor()) {
      if ($echo_header)
        theme(_('Sorry'), 'header');
      echo "<h2 align='center'>" . _('Sorry!') . "</h2>";
      echo _('You do not have permission to access this page.
              If you believe you should, please contact a system administrator.');
      theme('', 'footer');
      exit();
    }
  }
?>