<?
// set $label, $action_page, $filtertype_stem  and $state_sql before inclusion

include_once('select_list.inc');


// arrays to hold legal filter values (determined from database)
// as some protection against "injection of SQL in URL" attacks

$array_of_langs = array();
$array_of_genres = array();
$array_of_PMs = array();
$array_of_diffs = array();


// get legal languages

$query = "
	SELECT distinct language FROM projects	WHERE $state_sql ORDER BY language
	";
$result = mysql_query($query);
$i = 0;
while ($a_res = @mysql_fetch_row($result))
{
    $val = $a_res[0];	
    $array_of_langs[$i] = $val;
    $i++;
}


// get legal genres

$query = "
	SELECT distinct genre FROM projects	WHERE $state_sql ORDER BY genre
	";
$result = mysql_query($query);
$i = 0;
while ($a_res = @mysql_fetch_row($result))
{
    $val = $a_res[0];	
    $array_of_genres[$i] = $val;
    $i++;
}


// get legal PMs

$query = "
	SELECT distinct username FROM projects	WHERE $state_sql ORDER BY username
	";
$result = mysql_query($query);
$i = 0;
while ($a_res = @mysql_fetch_row($result))
{
    $val = $a_res[0];	
    $array_of_PMs[$i] = $val;
    $i++;
}

// legal difficulties 

// TO DO: take into account visibility of beginner?

$array_of_diffs = array('beginner','easy','average','hard');



// build and show the filter selection section

echo "<center>";
echo _("Filter available ").$label._(" books by").":";
echo "</center>\n";    
echo "<br>
    <form method=get action='$action_page'>
    <input type='hidden' name='filter_".$filtertype_stem."' value='filter_on'>";


// checkboxes for selection of difficulty

echo "\r\n<table border=1 width=630>";
echo "\r\n<tr>";

echo "<td>"._("Difficulty")."</td>";
echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"._("All")." <input type='checkbox' name='difficulty1[]' value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo _("Beginners Only").' <input type="checkbox" name="difficulty1[]" value="beginner">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
echo _("Easy").' <input type="checkbox" name="difficulty1[]" value="easy">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
echo _("Average").' <input type="checkbox" name="difficulty1[]" value="average">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
echo _("Hard").' <input type="checkbox" name="difficulty1[]" value="hard"></td>';

echo "\r\n</tr>";
echo "</table>\n";


// drop down multiselect listboxes for Language, Genre and PM

echo "\r\n<table border=1 width=630>";
echo "\r\n<tr>";

echo   "<td>"._("Language")."</td>";
echo   "<td>"._("Genre")."</td>";
echo   "<td>"._("Project Manager")."</td>";

echo "\r\n</tr>";
echo "\r\n<tr>";

// In the <select> tags, we set the name attribute to 'whatever[]'.
// I'm pretty sure this doesn't mean anything to HTML/HTTP,
// but PHP takes it as a cue to make the multiple values of
// the select control available as an array.
// That is, $_GET['whatever'] will be an array containing
// all selected values.


// LANGUAGE filter

echo "
    <td>
    <select name='language1[]' size=5 multiple>
	<option value=''>"._("All languages")."</option>
";

echo build_selectlist( $state_sql, "language", $array_of_langs);

echo "
    </select>
    </td>
";



// GENRE filter

echo "
    <td>
    <select name='genre1[]' size=5  multiple>
	<option value=''>"._("All genres")."</option>
";

echo build_selectlist( $state_sql, "genre", $array_of_genres);
echo "
    </select>
    </td>
";
       
// PM filter

echo "
    <td>
    <select name='PM1[]' size=5 multiple>
	<option value=''>"._("All PMs")."</option>
";

echo build_selectlist( $state_sql, "username", $array_of_PMs);
echo "
    </select>
    </td>
";


echo "</tr>";
echo "</table>\n<br>";


// buttons

echo "<center>";
echo "<input type='reset' value='".
	_("Reset New Filter")
     ."'>";
echo "<input type='submit' value='".
	_("Apply New Filter to Projects")
     ."'>";
echo "</center>\n";    
echo "</form><br>";


// usage notes

echo "<center>";
echo _("If desired, you should be able to select multiple values for each (e.g., by holding down Ctrl).");
echo "<br><br>";
echo _("Resetting the filter does not apply it. To see all projects again, Reset then Apply Filter.");
echo "</center><br><br>\n";    

// $filtertype_stem (which must be initialised in enclosing file) 
// allows different filters to be set for different list/pages/purposes
// (e.g. "proof" and "PP")

$FT_int = $filtertype_stem . "_internal";
$FT_dis = $filtertype_stem . "_display";


// read saved filters (actual and display) from user_filters table 

$result = mysql_query("SELECT value FROM user_filters WHERE username = '$pguser' AND filtertype = '$FT_int'");
if (mysql_num_rows($result) >= 1) {
	$RFilter = mysql_result($result, 0, "value");
}
$result = mysql_query("SELECT value FROM user_filters WHERE username = '$pguser' AND filtertype = '$FT_dis'");
if (mysql_num_rows($result) >= 1) {
	$display_filter = mysql_result($result, 0, "value");
}



// get new filters from passed arguments (if any) in URL

// validate each set value against the arrays of legal values
// as some protection against the "injection of SQL into URL" attack

if ( isset($_GET["filter_$filtertype_stem"]) and ($_GET["filter_$filtertype_stem"] == 'filter_on')) {

	$RFilter = " ";
	$display_filter = "";
	$show_df = FALSE;


	// construct language clause of new filter

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

	// construct genre clause of new filter

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

	// construct PM clause of new filter

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


	// construct difficulty clause of new filter

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

	// save or update filter to usersettings table (since they've potentially changed)

	//Note:  When using UPDATE, MySQL will not update columns where the new value is the same as the old value. 
	//This creates the possiblity that mysql_affected_rows() may not actually equal the number of rows matched, 
	//only the number of rows that were literally affected by the query.
	//To avoid the risk of creatign multiple rows per user for each of these two settings,
	//we delete and insert rather than UPDATE and "if no rows affected", INSERT, since the
	//no rows affected isn't reliable



	// _internal filter is database-readable SQL for WHERE clause in queries
	// _display filter is human readable summary for reporting current filter to user

	

	// remove old filter values (if any) from table and add news ones

	$query = "
		DELETE FROM user_filters WHERE username = '$pguser' AND filtertype = '$FT_int'
		";
	$result = mysql_query($query);
	$query = "
		DELETE FROM user_filters WHERE username = '$pguser' AND filtertype = '$FT_dis'
		";
	$result = mysql_query($query);

	$query = "INSERT INTO user_filters (username, filtertype, value) VALUES ('$pguser', '$FT_int', '".addslashes($RFilter)."')";
	$result = mysql_query($query);

	$query = "
		INSERT INTO user_filters (username, filtertype, value) VALUES ('$pguser', '$FT_dis', '".addslashes($display_filter)."')
		";
	$result = mysql_query($query);

}


// report filter in use to user

if ($show_df || $display_filter) {
	echo "<center>"._("Currently filtered by:")."<b> $display_filter </b></center><br><br>\n";
} else {
	echo "<center><b>"._("No filter, all books shown")."</b></center><br><br>\n";
}


// $RFilter is now set to new filter value 
// for passing to a project listing function in enclosing file


?>
