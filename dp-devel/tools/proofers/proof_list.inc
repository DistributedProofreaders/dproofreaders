<?
include_once($relPath.'bookpages.inc');
include_once($relPath.'showavailablebooks.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'page_states.inc');

$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
$totalpages = mysql_result($result,0);

if ($userP['u_plist'] == 1 || $userP['u_plist'] == 3)
  {
    echo "\r\n<table border=1 width=630>";
    echo "\r\n<tr>";
    tde("<h3>Current First - Round Projects</h3>", bgcol(1,'first') . " colspan=2");
    tde("These files are output from the OCR software and have not been looked at.", bgcol(1,'first') . " colspan=5");
    echo "</tr>";

    //Select all projects in the list for round 1
    // now puts the BEGIN projects at the top
//    $result = mysql_query("(SELECT * FROM projects WHERE username = 'BEGIN' AND (state = '".PROJ_PROOF_FIRST_AVAILABLE."' or state = '".PROJ_PROOF_FIRST_VERIFY."') ORDER BY modifieddate desc, nameofwork asc) UNION (SELECT * FROM projects WHERE state = '".PROJ_PROOF_FIRST_AVAILABLE."' or state = '".PROJ_PROOF_FIRST_VERIFY."' ORDER BY modifieddate desc, nameofwork asc)");

$result = mysql_query("SELECT * FROM projects WHERE username = 'BEGIN' AND (state = '".PROJ_PROOF_FIRST_AVAILABLE."' or state = '".PROJ_PROOF_FIRST_VERIFY."')");

    showavailablebooks($result,AVAIL_FIRST,$userP,$tList,'first');


$result2 = mysql_query("SELECT * FROM projects WHERE username != 'BEGIN' AND (state = '".PROJ_PROOF_FIRST_AVAILABLE."' or state = '".PROJ_PROOF_FIRST_VERIFY."') ORDER BY modifieddate desc, nameofwork asc");

    showavailablebooks($result2,AVAIL_FIRST,$userP,$tList,'first');
    echo "</table>\n<br>";
  }

if ($userP['u_plist'] == 2 || $userP['u_plist'] == 3)
  {
    echo "\r\n<table border=1 width=630>";
    echo "\r\n<tr>";
    tde("<h3>Current Second - Round Projects </h3>", bgcol(1,'second') . " colspan=2");
    if ($totalpages < 50)
      {
      tde("Second round projects are unavailable until you have proofed more than 50 first round pages.  After 50 pages of first round proofing the second round projects will be unlocked for you.", bgcol(1,'second') . " colspan=5");
      echo "</tr>";
      }
    else
      {
      tde("These are files that have already been proofed once, but now need to be examined <B>closely</B> for small errors that may have been missed. See <A HREF='http://www.promo.net/pg/vol/proof.html#What_kinds' target='_new'>this page</A> for examples.", bgcol(1,'second') . " colspan=5");
      echo "</tr>";

      //Select all projects in the list for round 2 
        $result = mysql_query("SELECT * FROM projects WHERE state = '".PROJ_PROOF_SECOND_AVAILABLE."' or state = '".PROJ_PROOF_SECOND_VERIFY."' ORDER BY nameofwork ASC");
      showavailablebooks($result,AVAIL_SECOND,$userP,$tList,'second');
      }
  echo "</table>\n<br>";
} 
?>