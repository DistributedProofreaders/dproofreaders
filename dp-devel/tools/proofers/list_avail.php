<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include($relPath.'bookpages.inc');
include($relPath.'showavailablebooks.inc');
include($relPath.'project_states.inc');
include($relPath.'page_states.inc');

// $userP['prefschanged'] will be set ==1 if they have changed prefs while in proofing mode
// should offer link to save changes and|or restore defaults

    echo "\n<html><head>";
    echo "\n<title>Available Projects</title>";
    echo "</head><body>";

//get total pages completed
    $pagessql = "SELECT pagescompleted FROM users WHERE username = '$pguser' LIMIT 1";
    $pages = mysql_query($pagessql);
    $totalpages = mysql_result($pages, 0, "pagescompleted");

// store old win pref
    $winPref=$userP['i_newwin'];
    $userP['i_newwin']=0;

if ($userP['u_plist'] == 1 || $userP['u_plist'] == 3)
  {
    echo "<table border=1 width=630>";
    echo "<tr><td bgcolor='#CCCCCC' colspan=2><h3>Current First - Round Projects</h3></td>";
    echo "<td bgcolor='#CCCCCC' colspan=5> These files are output from the OCR software and have not been looked at.</td></tr>";
    //Select all projects in the list for round 1
    $result = mysql_query("SELECT * FROM projects WHERE state = '".AVAIL_PI_FIRST."' or state = '".VERIFY_PI_FIRST."' ORDER BY modifieddate asc, nameofwork asc");
    showavailablebooks($result,AVAIL_FIRST,$userP,1);
    echo "</table>";
  }

if ($userP['u_plist'] == 2 || $userP['u_plist'] == 3)
  {
    echo "\n<table border=1 width=630>";
    echo "<br><tr><td bgcolor='CCCCCC' colspan=2><h3>Current Second - Round Projects </h3></td>";
    if ($totalpages < 50)
      {
        echo "<td bgcolor='#cccccc' colspan=5>Second round projects are unavailable until you have proofed more than 50 first round pages.  After 50 pages of first round proofing the second round projects will be unlocked for you.";
        echo "</td></tr></table>\n<p>";
      }
    else
      {
        echo "<td bgcolor='CCCCCC' colspan=5>These are files that have already been proofed once, but now need to be examined <B>closely</B> for small errors that may have been missed.";
        echo "See <A HREF='http://www.promo.net/pg/vol/proof.html#What_kinds' target='_new'>this page</A> for examples.";
        echo "</td></tr>";
        //Select all projects in the list for round 2 
          $result = mysql_query("SELECT * FROM projects WHERE state = '".AVAIL_PI_SECOND."' or state = '".VERIFY_PI_SECOND."' ORDER BY nameofwork ASC");
          showavailablebooks($result,AVAIL_SECOND,$userP,1);
        echo "</table>";
      }
  } 

// restore old win pref
$userP['i_newwin']=$winPref;
?>
</body></html>
