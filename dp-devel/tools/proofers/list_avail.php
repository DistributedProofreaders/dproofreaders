<?
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');

theme(_("Available Projects"), "header");
echo "<br>";

// $userP['prefschanged'] will be set ==1 if they have changed prefs while in proofing mode
// should offer link to save changes and|or restore defaults

// store old win pref
    $winPref=$userP['i_newwin'];
    $userP['i_newwin']=0;

include_once('proof_list.inc');

// restore old win pref
$userP['i_newwin']=$winPref;
theme("", "footer");
?>

