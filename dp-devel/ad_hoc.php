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

if (1)
{
	// Regenerate page counts for projects in Post.
	include_once($relPath.'bookpages.inc');
	$res = mysql_query("
		SELECT projectid,nameofwork
		FROM projects
		WHERE 0
		OR state='".PROJ_POST_FIRST_UNAVAILABLE."'
		OR state='".PROJ_POST_FIRST_AVAILABLE."'
		OR state='".PROJ_POST_FIRST_CHECKED_OUT."'
		OR state='".PROJ_POST_SECOND_AVAILABLE."'
		OR state='".PROJ_POST_SECOND_CHECKED_OUT."'
		OR state='".PROJ_POST_COMPLETE."'
	");
	while ( list($projectid,$nameofwork) = mysql_fetch_row($res) )
	{
		echo "$nameofwork<br>";
		project_update_page_counts($projectid);
	}
}

if (0)
{
	// Regenerate joined text files
	include('sendtopost.php');
	$res = dpsql_query( "
		SELECT projectid
		FROM projects 
		WHERE state='".POST_AVAILABLE."' or state='".POST_CHECKED_OUT
	" );
	while ( list($projectid) = mysql_fetch_something($res) )
	{
		// backup existing
		// set $fp
		join_proofed_text( $projectid, $fp );
		join_proofed_text_tei( $projectid, $fp );
	}
}

if (0)
{
	include_once($relPath.'email_address.inc');

	$f_in = fopen( '../email_addrs', 'r' );
	$f_out = fopen( '../email_addrs.new', 'w' );
	while ( $a = fgetcsv( $f_in, 1024, "\t", '' ) )
	{
		list($date_created,$email_addr) = $a;
		$err = check_email_address($email_addr);
		fwrite($f_out, "$date_created\t$email_addr\t$err\n" );
	}
	fclose($f_in);
	fclose($f_out);
}

if (0)
{
    // For each possible project state, create a project in that state.
    include($relPath.'project_states.inc');
    $state_constant_names = array(
	'PROJ_NEW',
	'PROJ_NEW_WAITING_APPROVAL',
	'PROJ_NEW_UNAPPROVED',
	'PROJ_NEW_APPROVED',
	'PROJ_NEW_FILE_UPLOADED',
	'PROJ_NEW_METADATA_FIRST',
	'PROJ_NEW_METADATA_BAD',
	'PROJ_NEW_METADATA_SECOND',
	'PROJ_NEW_PREPROCESSING',
	'PROJ_NEW_PENDING_PM',

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
