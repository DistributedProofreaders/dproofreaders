<?

// This is an ad hoc file for testing things on the server,
// for developers who don't have shell accounts on it.

error_reporting(E_ALL);

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
	include_once($relPath.'../stats/pages_proofed.inc');
	$sql = sql_n_proofings_for_page( 'START', 'END', 'proj_post_first_available' );
	echo "$sql\n";
}

if (0)
{
	include_once($relPath.'../tools/project_manager/post_files.inc');
	$projectid = 'projectID40e7bd24f37af';
	generate_post_files( $projectid, FALSE );
}

if (0)
{
	// include_once($relPath.'project_trans.inc');
	// project_transition( 'projectID40fae3d3dd5ef', 'foo', 'bar' );

	// include_once($relPath.'../tools/project_manager/projectmgr_select.inc');
	// ksort($transition_options_); var_dump($transition_options_);

	// include_once($relPath.'bookpages.inc');
	// project_update_page_counts('FOO');

	include_once($relPath.'RoundDescriptor.inc');
	foreach ($PAGE_STATES_IN_ORDER as $page_state)
	{
		$prd = get_PRD_for_page_state($page_state);
		echo "$page_state\t$prd->round_name\n";
	}
}

if (0)
{
	include_once($relPath.'project_states.inc');
	foreach ( array('BRONZE','SILVER','GOLD') as $metal )
	{
		$constant_name = "SQL_CONDITION_$metal";
		$val = constant($constant_name);
		// $val = str_replace( ' ', "\n", $val );
		echo "\n$constant_name:\n$val\n";
	}
}

if (0)
{
	$res = mysql_query("SHOW TABLES LIKE 'projectID%'");
	while (list($projectid) = mysql_fetch_row($res) )
	{
		// Look for old-style tables:
		$q = "
			SHOW COLUMNS FROM $projectid LIKE 'Image_Filename'
		";
		// Look for cases where 'fileid' and 'image' don't match:
		$q = "
			SELECT fileid,image
			FROM $projectid
			WHERE CONCAT(fileid,'.png') != image
		";
		$res2 = mysql_query($q) or die( "$projectid: " . mysql_error() );
		if ( mysql_num_rows($res2) > 0 ) echo "$projectid\n";
	}
}

if (0)
{
	include_once($relPath.'../stats/pages_proofed.inc');
	$start_ts = mktime(0,0,0,10,16,2004);
	$end_ts   = mktime(0,0,0,10,17,2004);
	$before_t = time();
	$n_pages = get_n_pages_proofed( $start_ts, $end_ts, $n_projects );
	$after_t = time();
	$elapsed_t = $after_t - $before_t;

	echo "n_pages = $n_pages, n_projects = $n_projects, elapsed_t = $elapsed_t seconds\n";
}

if (0)
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
		WHERE state='".POST_AVAILABLE."' or state='".POST_CHECKED_OUT."'
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
    $i = 0;
    foreach ( $project_state_label_ as $project_state => $label )
    {
	$i += 1;
	mysql_query("
	    INSERT INTO projects
	    SET
		projectid='jmd_$i',
		nameofwork='ADHOC: $label',
		authorsname='anon',
		username='jmdyck',
		state='$project_state'
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

if (0)
{
    include_once($relPath.'misc.inc');

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
// 
?>
