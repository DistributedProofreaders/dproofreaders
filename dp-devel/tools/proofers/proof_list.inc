<?
include_once($relPath.'showavailablebooks.inc');

$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
$totalpages = mysql_result($result,0);


if ($totalpages <= 20) {

        echo "<font face=" . $theme['font_mainbody'] ."><b>";
        echo _("Click on the name of a book in the list below to start proofing.");
        echo "</b></font><br><br>\n";
} else {

	include_once('filter_proof_list.inc');

}


if ($userP['u_plist'] == 1 || $userP['u_plist'] == 3)
  {

    echo "\r\n<table border=1 width=630>";
    echo "\r\n<tr>";
    tde("<h3>Current First - Round Projects</h3>", bgcol(1,'first') . " colspan=2");
    tde("These files are output from the OCR software and have not been looked at.", bgcol(1,'first') . " colspan=5");
    echo "</tr>";

    show_books_available_for_proofing( 'first', $userP, $tList, $RFilter );

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

      if (! user_can_see_BEGIN_R2()) {
         $RFilter = $RFilter . " AND difficulty != 'beginner' ";
      }

      show_books_available_for_proofing( 'second', $userP, $tList, $RFilter );
      }
  echo "</table>\n<br>";
}



?>

