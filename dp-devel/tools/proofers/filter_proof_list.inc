<?

function build_selectlist( $states, $field)
{

	$to_echo = "";
	$query = "
		SELECT distinct $field
		FROM projects
			WHERE $states
			ORDER BY $field
		";

	$result = mysql_query($query);

	while ($a_res = mysql_fetch_row($result))
	{
	    $val = $a_res[0];	
	    $to_echo .= "<option value='$val'>";
	    $to_echo .= $val;
	    $to_echo .= "</option>\n";
	}

return $to_echo;

}


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



    echo "<center>";
    echo _("Filter available ").$label._(" books by");
    echo ":<br>
	    <form method=get action='proof_per.php'>
	    <input type='hidden' name='filter' value='filter_on'>";

    echo "\r\n<table border=1 width=630>";
    echo "\r\n<tr>";
    echo   "<td>Language</td>";
    echo   "<td>Genre</td>";
    echo   "<td>Project Manager</td>";
    echo   "<td>Difficulty</td>";
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
		<option value=''>All languages</option>
	";

	echo build_selectlist( $state_sql, "language");

	echo "
	    </select>
	    </td>
	";

	// GENRE filter

 	echo "
	    <td>
	    <select name='genre1[]' size=5  multiple>
		<option value=''>All genres</option>
	";

	echo build_selectlist( $state_sql, "genre");
	echo "
	    </select>
	    </td>
	";


	// PM filter

 	echo "
	    <td>
	    <select name='PM1[]' size=5 multiple>
		<option value=''>All Project Managers</option>
	";

	echo build_selectlist( $state_sql, "username");
	echo "
	    </select>
	    </td>
	";


	// DIFFICULTY filter (hard coded so we can easily control the order properly)

 	echo "
	    <td>
	    <select name='difficulty1[]' size=5 multiple>
		<option value=''>All difficulties</option>
		<option value='beginner'>Beginners Only</option>
		<option value='easy'>Easy</option>
		<option value='average'>Average</option>
		<option value='hard'>Hard</option>
	    </select>
	    </td>
	";


    echo "</tr>";
    echo "</table>\n<br>";

    echo "<input type='submit' value='Apply Filter to Projects'>";
    echo "<input type='reset' value='Reset Filter to none'>";
    echo "</form><br>";
    echo _("If desired, you should be able to select multiple values for each (e.g., by holding down Ctrl).");
    echo "<br><br>";
    echo _("Restting the filter to null does not apply it. To see all projects again, Reset then Apply Filter.");
    echo "</center><br><br>\n";

?>

