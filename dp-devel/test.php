<?

// This is an ad hoc file for testing things on the server,
// for developers who don't have shell accounts on it.

$relPath='./pinc/';
include($relPath.'f_dpsql.inc');
include($relPath.'connect.inc');
new dbConnect();

echo "<pre>\n";

echo date("r");
echo "<BR>\n";
system("date");
echo "<BR>\n";
echo "<hr>\n";

if (0)
{
    // For each possible project state, create a project in that state.
    include($relPath.'project_states.inc');
    $state_constant_names = array(
	'PROJ_NEW',

	'PROJ_PROOF_FIRST_BAD_PROJECT',
	'PROJ_PROOF_FIRST_UNAVAILABLE',
	'PROJ_PROOF_FIRST_WAITING_FOR_RELEASE',
	'PROJ_PROOF_FIRST_AVAILABLE',
	'PROJ_PROOF_FIRST_VERIFY',
	'PROJ_PROOF_FIRST_COMPLETE',

	'PROJ_PROOF_SECOND_BAD_PROJECT',
	'PROJ_PROOF_SECOND_UNAVAILABLE',
	'PROJ_PROOF_SECOND_WAITING_FOR_RELEASE',
	'PROJ_PROOF_SECOND_AVAILABLE',
	'PROJ_PROOF_SECOND_VERIFY',
	'PROJ_PROOF_SECOND_COMPLETE',

	'PROJ_POST_FIRST_UNAVAILABLE',
	'PROJ_POST_FIRST_AVAILABLE',
	'PROJ_POST_FIRST_CHECKED_OUT',
	'PROJ_POST_SECOND_AVAILABLE',
	'PROJ_POST_SECOND_CHECKED_OUT',
	'PROJ_POST_COMPLETE',

	'PROJ_SUBMIT_PG_UNAVAILABLE',
	'PROJ_SUBMIT_PG_AVAILABLE',
	'PROJ_SUBMIT_PG_POSTING',
	'PROJ_SUBMIT_PG_POSTED',

	'PROJ_CORRECT_AVAILABLE',
	'PROJ_CORRECT_CHECKED_OUT',

	'PROJ_COMPLETE',
	'PROJ_DELETE',
    );
    $i = 0;
    foreach( $state_constant_names as $state_constant_name )
    {
	$i += 1;
	$state_value = constant($state_constant_name);
	mysql_query("
	    INSERT INTO projects
	    SET
		projectid='jmd_$i',
		nameofwork='$state_constant_name',
		authorsname='anon',
		username='jmdyck',
		state='$state_value'
	") or die(mysql_error());
    }
}

if (0)
{
    system("pwd");
    echo "\n";
    system("ls -l .");
    echo "<hr>\n";

    system("ls -l /0/htdocs");
    echo "\n";
    echo "<hr>\n";
}

function startswith( $str, $pre )
{
    return ( substr( $str, 0, strlen($pre) ) == $pre );
}

if (0)
{
    if (0)
    {
	$project_cutoff_ts = gmmktime(0,0,0,1,2,2003);
	$criterion = "modifieddate >= $project_cutoff_ts";
	$criterion = "archived='0'";
	$criterion = "1";
	$res = mysql_query("SELECT projectid FROM projects WHERE $criterion")
		or die(mysql_error());
    }
    else
    {
	$res = mysql_query("SHOW TABLES");
    }

    while( $project_row = mysql_fetch_array($res) )
    {
	list($projectid) = $project_row;
	if ( ! startswith( $projectid, 'projectID' ) )
	{
	    continue;
	}

	echo $projectid;
	echo " ";
	# $res2 = mysql_query("SELECT COUNT(*) FROM $projectid");
	$res2 = mysql_query("SELECT COUNT(*), COUNT(DISTINCT(fileid)), COUNT(DISTINCT(image)) FROM $projectid");
	if (!$res2)
	{
	    echo mysql_error();
	}
	else
	{
	    list($n_pages,$n_distinct_fileid,$n_distinct_image) = mysql_fetch_array($res2);
	    echo "$n_pages $n_distinct_fileid $n_distinct_image";
	}
	echo "\n";
    }
    echo "<hr>\n";
}

echo "</pre>\n";

if (0)
{
    dpsql_dump_query("SELECT username FROM users");
    echo "<hr>\n";
}


if (0)
{
    dpsql_dump_query("DESCRIBE projects");
    echo "<HR>\n";
}

if (0)
{
    dpsql_dump_query("
	SELECT projectID, modifieddate
	FROM projects
	WHERE archived='1'
	ORDER BY modifieddate
	");

    echo "<br>";

    dpsql_dump_query("
	SELECT projectID, modifieddate
	FROM projects
	WHERE archived='0'
	ORDER BY modifieddate
    ");
}
?>
