<?
include_once($relPath.'showavailablebooks.inc');

$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
$totalpages = mysql_result($result,0);


if ($totalpages >= 10) {
    echo "<p><font face='{$theme['font_mainbody']}'>\n";
    include('special_legend.php');
    echo "</font></p><br>\n";
}


if ($totalpages <= 20) {

        echo "<font face=" . $theme['font_mainbody'] ."><b>";
        echo _("Click on the name of a book in the list below to start proofreading.");
        echo "</b></font><br><br>\n";
} else {

	include_once('filter_proof_list.inc');

}
if (!isset($RFilter)) { $RFilter = ""; }


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
    show_block_for_round(1, $RFilter);
  }

if ($userP['u_plist'] == 2 || $userP['u_plist'] == 3)
  {
    show_block_for_round(2, $RFilter);
}



?>

