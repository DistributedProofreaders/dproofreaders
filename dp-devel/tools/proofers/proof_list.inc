<?
include_once($relPath.'showavailablebooks.inc');



$array_of_langs = array();
$array_of_genres = array();
$array_of_PMs = array();
$array_of_diffs = array();


if ($userP['u_plist'] == 1) 
{
	$state_sql = " (state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR
		 state = '".PROJ_PROOF_FIRST_VERIFY."') ";
	$label = _("First Round");
}
if ($userP['u_plist'] == 2)
{
	$state_sql = " (state = '".PROJ_PROOF_SECOND_AVAILABLE."' OR
		 state = '".PROJ_PROOF_SECOND_VERIFY."') ";
	$label = _("Second Round");
}
if ($userP['u_plist'] == 3)
{
	$state_sql = " (state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR
		 state = '".PROJ_PROOF_FIRST_VERIFY."' OR
		 state = '".PROJ_PROOF_SECOND_AVAILABLE."' OR
		 state = '".PROJ_PROOF_SECOND_VERIFY."') ";
	$label = "";
}


$query = "
	SELECT distinct language FROM projects	WHERE $state_sql ORDER BY language
	";
$result = mysql_query($query);
$i = 0;
while ($a_res = mysql_fetch_row($result))
{
    $array_of_langs[$i] = $val;
    $i++;
}

$query = "
	SELECT distinct genre FROM projects	WHERE $state_sql ORDER BY genre
	";
$result = mysql_query($query);
$i = 0;
while ($a_res = mysql_fetch_row($result))
{
    $array_of_genres[$i] = $val;
    $i++;
}


$query = "
	SELECT distinct username FROM projects	WHERE $state_sql ORDER BY username
	";
$result = mysql_query($query);
$i = 0;
while ($a_res = mysql_fetch_row($result))
{
    $array_of_PMs[$i] = $val;
    $i++;
}

$array_of_diffs = array('beginner','easy','average','hard');


include_once('filter_proof_list.inc');


// determine filter for projects

$RFilter = " ";



if ( isset($_GET['filter']) and ($_GET['filter'] == 'filter_on')) {
	$condition1 = '1';
	if ( isset($_GET['language1']) && count($_GET['language1']) > 0 )
	{
	    $condition1 .= " AND (0";
	    foreach( $_GET['language1'] as $lang )
	    {



		if (!in_array( $lang, $array_of_langs )) { echo "UHOH" & exit(); }

		if ( $lang == '' )
		{
		    $condition1 .= " OR 1";
		}
		else
		{
		    $condition1 .= " OR language='$lang'";
		}
	    }
	    $condition1 .= ")";
	}

	if ( isset($_GET['genre1']) && count($_GET['genre1']) > 0 )
	{
	    $condition1 .= " AND (0";
	    foreach( $_GET['genre1'] as $genre )
	    {
		if (!in_array($genre, $array_of_genres )) { echo "UHOH" &  exit(); }

		if ( $genre == '' )
		{
		    $condition1 .= " OR 1";
		}
		else
		{
		    $condition1 .= " OR genre='$genre'";
		}
	    }
	    $condition1 .= ")";
	}

	if ( isset($_GET['PM1']) && count($_GET['PM1']) > 0 )
	{
	    $condition1 .= " AND (0";
	    foreach( $_GET['PM1'] as $PM )
	    {

		if (!in_array($PM, $array_of_PMs )) { echo "UHOH" & exit(); }

		if ( $PM == '' )
		{
		    $condition1 .= " OR 1";
		}
		else
		{
		    $condition1 .= " OR username='$PM'";
		}
	    }
	    $condition1 .= ")";
	}


	if ( isset($_GET['difficulty1']) && count($_GET['difficulty1']) > 0 )
	{
	    $condition1 .= " AND (0";
	    foreach( $_GET['difficulty1'] as $diff )
	    {
		if (!in_array($diff, $array_of_diffs )) { echo "UHOH" & exit(); }

		if ( $diff == '' )
		{
		    $condition1 .= " OR 1";
		}
		else
		{
		    $condition1 .= " OR difficulty='$diff'";
		}
	    }
	    $condition1 .= ")";
	}

	$RFilter = " AND ".$condition1;

}

$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
$totalpages = mysql_result($result,0);






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

      show_books_available_for_proofing( 'second', $userP, $tList, $RFilter );
      }
  echo "</table>\n<br>";
}



?>

