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


$result = mysql_query("SELECT );

// read saved sort orders from user_settings table 

$result = mysql_query("SELECT value FROM user_settings WHERE username = '$pguser' AND setting = 'R1order'");
if (mysql_num_rows($result) >= 1) {
	$orderR1old = mysql_result($result, 0, "value");
} else {
	$orderR1old = 'daysD';
}


$orderR1 = (isset($_GET['orderR1']) ? $_GET['orderR1'] : $orderR1old );
if ( $orderR1 == 'TitleA' )
{
	$orderR1clause = 'nameofwork ASC';
}
elseif ( $orderR1 == 'TitleD' )
{
	$orderR1clause = 'nameofwork DESC';
}
elseif ( $orderR1 == 'AuthorA' )
{
	$orderR1clause = 'authorsname ASC, nameofwork ASC';
}
elseif ( $orderR1 == 'AuthorD' )
{
	$orderR1clause = 'authorsname DESC, nameofwork ASC';
}
elseif ( $orderR1 == 'LangA' )
{
	$orderR1clause = 'language ASC, nameofwork ASC';
}
elseif ( $orderR1 == 'LangD' )
{
	$orderR1clause = 'language DESC, nameofwork ASC';
}
elseif ( $orderR1 == 'GenreA' )
{
	$orderR1clause = 'genre ASC, nameofwork ASC';
}
elseif ( $orderR1 == 'GenreD' )
{
	$orderR1clause = 'genre DESC, nameofwork ASC';
}
elseif ( $orderR1 == 'PMA' )
{
	$orderR1clause = 'username ASC, nameofwork ASC';
}
elseif ( $orderR1 == 'PMD' )
{
	$orderR1clause = 'username DESC, nameofwork ASC';
}
elseif ( $orderR1 == 'DaysA' )
{
	$orderR1clause = 'days ASC, nameofwork ASC';
}
elseif ( $orderR1 == 'DaysD' )
{
	$orderR1clause = 'days DESC, nameofwork ASC';
}
else
{
	echo "proof_list.inc: bad round 1 order value: '$orderR1'";
	exit;
}







if ($userP['u_plist'] == 1 || $userP['u_plist'] == 3)
  {

    echo "\r\n<table border=1 width=630>";
    echo "\r\n<tr>";
    tde("<h3>Current First - Round Projects</h3>", bgcol(1,'first') . " colspan=2");
    tde("These pages are output from the OCR software and have not been looked at.", bgcol(1,'first') . " colspan=6");
    echo "</tr>";

    if (! user_can_see_BEGIN_R1()) {
         $extra =  " AND difficulty != 'beginner' ";
    } else {
	$extra = " ";
    }


    show_books_available_for_proofing( 'first', $userP, $tList, $RFilter . $extra, $orderR1clause );

    echo "</table>\n<br>";
  }

if ($userP['u_plist'] == 2 || $userP['u_plist'] == 3)
  {
    echo "\r\n<table border=1 width=630>";
    echo "\r\n<tr>";
    tde("<h3>Current Second - Round Projects </h3>", bgcol(1,'second') . " colspan=2");
    if ($totalpages < 50)
      {
      tde("Second round projects are unavailable until you have proofread more than 50 first round pages.  After 50 pages of first round proofreading, the second round projects will be unlocked for you.", bgcol(1,'second') . " colspan=6");
      echo "</tr>";
      }
    else
      {
      tde("These are pages that have already been proofread once, but now need to be examined <B>closely</B> for small errors that may have been missed.", bgcol(1,'second') . " colspan=6");
      echo "</tr>";

      if (! user_can_see_BEGIN_R2()) {
         $RFilter = $RFilter . " AND difficulty != 'beginner' ";
      }

      show_books_available_for_proofing( 'second', $userP, $tList, $RFilter );
      }
  echo "</table>\n<br>";
}



?>

