<?
include_once($relPath.'showavailablebooks.inc');

$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
$totalpages = mysql_result($result,0);

if ($userP['u_plist'] == 1 || $userP['u_plist'] == 3)
  {

    echo "<center>";
    echo _("Filter available Round One books by");
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

	$query = "
		SELECT distinct language
		FROM projects
			WHERE
				(state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR
				state = '".PROJ_PROOF_FIRST_VERIFY."') 
			ORDER BY language
		";

	$result = mysql_query($query);

	while ($a_res = mysql_fetch_row($result))
	{
	    $val = $a_res[0];	
	    echo "<option value='$val'>";
	    echo $val;
	    echo "</option>\n";
	}
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

	$query = "
		SELECT distinct genre
		FROM projects
			WHERE
				(state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR
				state = '".PROJ_PROOF_FIRST_VERIFY."') 
			ORDER BY genre
		";

	$result = mysql_query($query);

	while ($a_res = mysql_fetch_row($result))
	{
	    $val = $a_res[0];	
	    echo "<option value='$val'>";
	    echo $val;
	    echo "</option>\n";
	}
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

	$query = "
		SELECT distinct username
		FROM projects
			WHERE
				(state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR
				state = '".PROJ_PROOF_FIRST_VERIFY."') 
			ORDER BY username
		";

	$result = mysql_query($query);

	while ($a_res = mysql_fetch_row($result))
	{
	    $val = $a_res[0];	
	    echo "<option value='$val'>";
	    echo $val;
	    echo "</option>\n";
	}
	echo "
	    </select>
	    </td>
	";


	// DIFFICULTY filter

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

    echo "<input type='submit' value='Filter Projects'>";
    echo "<input type='reset' value='Clear Filter'>";
    echo "</form><br>";
    echo _("If desired, you should be able to select multiple values for each (e.g., by holding down Ctrl).");
    echo "</center><br>\n";


    echo "\r\n<table border=1 width=630>";
    echo "\r\n<tr>";
    tde("<h3>Current First - Round Projects</h3>", bgcol(1,'first') . " colspan=2");
    tde("These files are output from the OCR software and have not been looked at.", bgcol(1,'first') . " colspan=5");
    echo "</tr>";

    show_books_available_for_proofing( 'first', $userP, $tList, $R1Filter );

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

      show_books_available_for_proofing( 'second', $userP, $tList, $R2Filter );
      }
  echo "</table>\n<br>";
}

?>

