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
    $val = $a_res[0];	
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
    $val = $a_res[0];	
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
    $val = $a_res[0];	
    $array_of_PMs[$i] = $val;
    $i++;
}

$array_of_diffs = array('beginner','easy','average','hard');


include_once('filter_proof_list.inc');


// determine filter for projects

// read filters (actual and display) from usersettings here

$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'round_filter'");
if (mysql_num_rows($result) >= 1) {
	$RFilter = mysql_result($result, 0, "value");
echo "rfilter read from DB is ".$Rfilter;
}
$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'display_filter'");
if (mysql_num_rows($result) >= 1) {
	$display_filter = mysql_result($result, 0, "value");
echo "dfilter read from DB is ".$display_filter;
}



if ( isset($_GET['filter']) and ($_GET['filter'] == 'filter_on')) {

	$RFilter = " ";
	$display_filter = "";
	$show_df = FALSE;


	$condition1 = '1';
	if ( isset($_GET['language1']) && count($_GET['language1']) > 0 )
	{
	    $condition1 .= " AND (0";
	    $display_filter .= "<br>Languages=";
	    foreach( $_GET['language1'] as $lang )
	    {

		if ( $lang == '' )
		{
		    $condition1 .= " OR 1";
		}
		else
		{
		    if (!in_array( $lang, $array_of_langs )) { echo "UHOH" & exit(); }
		    $condition1 .= " OR language='$lang'";
                    $display_filter .= $lang." ";
		    $show_df = TRUE;
		}
	    }
	    $condition1 .= ")";
	}

	if ( isset($_GET['genre1']) && count($_GET['genre1']) > 0 )
	{
	    $condition1 .= " AND (0";
	    $display_filter .= "<br>Genres=";
	    foreach( $_GET['genre1'] as $genre )
	    {
		if ( $genre == '' )
		{
		    $condition1 .= " OR 1";
		}
		else
		{
		    if (!in_array($genre, $array_of_genres )) { echo "UHOH" &  exit(); }
		    $condition1 .= " OR genre='$genre'";
                    $display_filter .= $genre." ";
		    $show_df = TRUE;
		}
	    }
	    $condition1 .= ")";
	}

	if ( isset($_GET['PM1']) && count($_GET['PM1']) > 0 )
	{
	    $condition1 .= " AND (0";
	    $display_filter .= "<br>PMs=";
	    foreach( $_GET['PM1'] as $PM )
	    {
		if ( $PM == '' )
		{
		    $condition1 .= " OR 1";
		}
		else
		{
		    if (!in_array($PM, $array_of_PMs )) { echo "UHOH" & exit(); }
		    $condition1 .= " OR username='$PM'";
                    $display_filter .= $PM." ";
		    $show_df = TRUE;
		}
	    }
	    $condition1 .= ")";
	}


	if ( isset($_GET['difficulty1']) && count($_GET['difficulty1']) > 0 )
	{
	    $condition1 .= " AND (0";
	    $display_filter .= "<br>Difficulty=";
	    foreach( $_GET['difficulty1'] as $diff )
	    {
		if ( $diff == '' )
		{
		    $condition1 .= " OR 1";
		}
		else
		{
		    if (!in_array($diff, $array_of_diffs )) { echo "UHOH" & exit(); }
		    $condition1 .= " OR difficulty='$diff'";
                    $display_filter .= $diff." ";
		    $show_df = TRUE;
		}
	    }
	    $condition1 .= ")";
	}

	$RFilter = " AND ".$condition1;





	// save or update filter to usersettings here (since they've potentially changed)

	//Note:  When using UPDATE, MySQL will not update columns where the new value is the same as the old value. 
	//This creates the possiblity that mysql_affected_rows() may not actually equal the number of rows matched, 
	//only the number of rows that were literally affected by the query.
	//To avoid the risk of creatign multiple rows per user for each of these two settings,
	//we delete and insert rather than UPDATE and "if no rows affected", INSERT, since the
	//no rows affected isn't reliable

	$query = "
		DELETE FROM usersettings WHERE username = '$pguser' AND setting = 'round_filter'
		";
	$result = mysql_query($query);
	$query = "
		DELETE FROM usersettings WHERE username = '$pguser' AND setting = 'display_filter'
		";
	$result = mysql_query($query);


	$query = "
		INSERT INTO usersettings (username, setting, value) VALUES ('$pguser', 'round_filter', '$RFilter')
		";
	$result = mysql_query($query);
echo $query;
	$query = "
		INSERT INTO usersettings (username, setting, value) VALUES ('$pguser', 'display_filter', '$display_filter')
		";
	$result = mysql_query($query);
echo $query;


}

if ($show_df || $display_filter) {
	echo "<center>"._("Currently filtered by:")."<b> $display_filter </b></center><br><br>\n";
} else {
	echo "<center><b>"._("No filter, all books shown")."</b></center><br><br>\n";
}




$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
$totalpages = mysql_result($result,0);


if ($pagesproofed <= 20) {

                echo "<font face=" . $theme['font_mainbody'] ."><b>";
                echo _("Click on the name of a book in the list below to start proofing.");
                echo "</b></font><br><br>\n";
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

      show_books_available_for_proofing( 'second', $userP, $tList, $RFilter );
      }
  echo "</table>\n<br>";
}



?>

