<?
include_once($relPath.'showavailablebooks.inc');

$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
$totalpages = mysql_result($result,0);


if ($totalpages <= 20) {

        echo "<font face=" . $theme['font_mainbody'] ."><b>";
        echo _("Click on the name of a book in the list below to start proofreading.");
        echo "</b></font><br><br>\n";
} else {

	include_once('filter_proof_list.inc');

}

// print warning if neither round is shown
$pref_url = "{$GLOBALS['code_url']}/userprefs.php";

if ($userP['u_plist'] == 0) {
    echo "<font size=+2><center>";
    printf(_("You have chosen not to see available texts from either of the proofreading rounds.<br> To see available texts, adjust the \"Show Projects From\" setting on <a href=%s>your preferences page</a>."), $pref_url);
    echo "</center></font><br><br>";
}

if ($userP['u_plist'] == 2 && $totalpages < 50) {
    echo "<font size=+2><center>";
    printf(_("You have chosen not to see first round projects.<br> To see first round projects, adjust the \"Show Projects From\" setting on <a href=%s>your preferences page</a>."), $pref_url);
    echo "</center></font><br><br>";
}

if ($userP['u_plist'] == 1 || $userP['u_plist'] == 3)
  {
    // read saved sort orders from user_settings table 
    $qry = "SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'R1order'";
    $result = mysql_query($qry);
    if (mysql_num_rows($result) >= 1) {
        $orderR1old = mysql_result($result, 0, "value");
    } else {
        $orderR1old = 'DaysD';
    }

    // read new sort order from url, if any
    $orderR1 = (isset($_GET['orderR1']) ? $_GET['orderR1'] : $orderR1old );

    // if orders have changed, save them to database
    if ($orderR1 != $orderR1old) {
        $qry = "DELETE FROM usersettings WHERE username = '$pguser' AND setting = 'R1order'";
        $result = mysql_query($qry);
        $qry = "INSERT INTO usersettings VALUES ('$pguser', 'R1order', '$orderR1') ";
        $result = mysql_query($qry);
    }

    echo "\n<a name='R1'></a>";
    echo "\r\n<table border=1>";
    echo "\r\n<tr>";
    tde("<h3>"._("Current First - Round Projects")."</h3>", bgcol(1,'first') . " colspan=2");
    tde(_("These pages are output from the OCR software and have not been looked at."), bgcol(1,'first') . " colspan=6");
    echo "</tr>";

    if (! user_can_see_BEGIN_in_round(1)) {
         $extra =  " AND difficulty != 'beginner' ";
    } else {
	$extra = " ";
    }

		if (empty($RFilter)) { $RFilter = ""; }
    show_books_available_for_proofing( 'first', $userP, $RFilter . $extra, $orderR1);

    echo "</table>\n<br>";
  }

if ($userP['u_plist'] == 2 || $userP['u_plist'] == 3)
  {
    // read saved sort orders from user_settings table 
    $qry = "SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'R2order'";
    $result = mysql_query($qry);
    if (mysql_num_rows($result) >= 1) {
        $orderR2old = mysql_result($result, 0, "value");
    } else {
        $orderR2old = 'DaysD';
    }

    // read new sort order from url, if any
    $orderR2 = (isset($_GET['orderR2']) ? $_GET['orderR2'] : $orderR2old );

    // if orders have changed, save them to database
    if ($orderR2 != $orderR2old) {
        $result = mysql_query("DELETE FROM usersettings WHERE username = '$pguser' AND setting = 'R2order'");
        $result = mysql_query("INSERT INTO usersettings VALUES ('$pguser', 'R2order', '$orderR2') ");
    }

    echo "\n<a name='R2'></a>";
    echo "\r\n<table border=1>";
    echo "\r\n<tr>";
    tde("<h3>"._("Current Second - Round Projects")."</h3>", bgcol(1,'second') . " colspan=2");
    if ($totalpages < 100)
      {
      tde(_("Second round projects are unavailable until you have proofread more than 100 first round pages.  After 100 pages of first round proofreading, the second round projects will be unlocked for you."), bgcol(1,'second') . " colspan=6");
      echo "</tr>";
      }
    else
      {
      tde(_("These are pages that have already been proofread once, but now need to be examined <B>closely</B> for small errors that may have been missed."), bgcol(1,'second') . " colspan=6");
      echo "</tr>";

      if (! user_can_see_BEGIN_in_round(2)) {
         $RFilter = $RFilter . " AND difficulty != 'beginner' ";
      }

      show_books_available_for_proofing( 'second', $userP, $RFilter, $orderR2 );
      }
  echo "</table>\n<br>";
}



?>

