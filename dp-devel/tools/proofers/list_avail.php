<?
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');

theme("Available Projects", "header");
echo "<br>";

// $userP['prefschanged'] will be set ==1 if they have changed prefs while in proofing mode
// should offer link to save changes and|or restore defaults

//get total pages completed
    $pagessql = "SELECT pagescompleted FROM users WHERE username = '$pguser' LIMIT 1";
    $pages = mysql_query($pagessql);
    $totalpages = mysql_result($pages, 0, "pagescompleted");

// store old win pref
    $winPref=$userP['i_newwin'];
    $userP['i_newwin']=0;

$tList=0;
include_once('proof_list.inc');

// restore old win pref
$userP['i_newwin']=$winPref;
theme("", "footer");
?>

