

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
	
	$i = 0;
	while ($a_res = mysql_fetch_row($result))
	{
	    $val = $a_res[0];	
	    $to_echo .= "<option value='$val'>";
	    $to_echo .= $val;
	    $to_echo .= "</option>\n";
	}
		return $to_echo;
}
