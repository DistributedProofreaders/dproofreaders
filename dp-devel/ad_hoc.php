<?

// This is an ad hoc file for testing things on the server,
// for developers who don't have shell accounts on it.

error_reporting(E_ALL);

$relPath='./pinc/';
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'connect.inc');
new dbConnect();

echo "<pre>\n";

echo date("r");
echo "<BR>\n";

system("date");
echo "<BR>\n";

echo "<hr>\n";

if (0)
{
	// Test factor_strings()

	include_once($relPath.'misc.inc');
	function test( $strings )
	{
		echo "----------------------------\n";
		echo "\$strings:\n";
		var_dump( $strings );
		echo "\n";
		echo "factor_strings( \$strings ):\n";
		var_dump( factor_strings( $strings ) );
	}

	test( array( 'aaaa', 'aaaaaa', 'aaaaa' ) );
	test( array( 'desert', 'dessert' ) );
	test( array( 'boot', 'boo' ) );
	test( array( 'stan', 'tan' ) );
	test( array( 'one' ) );
	test( array( 'two', 'two' ) );
	test( array( '' ) );
	test( array( '', '' ) );
}

if (0)
{
	// Get Settings twice for the same user.
       	// Change one, see if the other changes.
	include_once($relPath.'SettingsClass.inc');
	$user_name = 'a';
	$setting_name = uniqid('s_');
	$s1 =& Settings::get_settings($user_name);
	$s2 =& Settings::get_settings($user_name);
	echo "before:\n";
	echo "  s1.foo: "; var_dump( $s1->get_boolean($setting_name) ); echo "\n";
	echo "  s2.foo: "; var_dump( $s2->get_boolean($setting_name) ); echo "\n";
	$s1->set_boolean($setting_name,TRUE);
	echo "after:\n";
	echo "  s1.foo: "; var_dump( $s1->get_boolean($setting_name) ); echo "\n";
	echo "  s2.foo: "; var_dump( $s2->get_boolean($setting_name) ); echo "\n";
}

if (0)
{
	include_once($relPath.'project_continuity.inc');
	$projectid = 'projectID40ee33c5e1436';
	$orig_state = 'P1.proj_avail';
	$no_more_pages = true;
	project_continuity_check( $projectid, $orig_state, $no_more_pages );
}

if (0)
{
	include_once($relPath.'project_states.inc');
	var_dump( $PROJECT_STATES_IN_ORDER );
	var_dump( $project_state_label_ );
	var_dump( $project_state_forum_ );
	var_dump( $project_state_phase_ );
	var_dump( $project_states_for_star_metal_ );
}

if (0)
{
	// Find projectID* tables that aren't referenced by the projects table.
	$referenced = array();
	$res1 = mysql_query("SELECT projectid FROM projects");
	while (list($projectid) = mysql_fetch_row($res1) )
	{
		$referenced[] = $projectid;
	}

	$res2 = mysql_query("SHOW TABLES LIKE 'projectID%'");
	while (list($projectid) = mysql_fetch_row($res2) )
	{
		if (!in_array($projectid, $referenced))
		{
			echo "$projectid\n";
		}
	}
}

if (0)
{
	$project_res = dpsql_query("
		SELECT projectid
		FROM projects
	");

	while ( list($projectid) = mysql_fetch_row($project_res) )
	{
		// First check whether the page-table exists.
		if (!mysql_query("DESCRIBE $projectid"))
		{
			// page-table doesn't exist (has been archived).
			// echo "$projectid doesn't exist\n";
			continue;
		}

		echo "$projectid:";

		$res = dpsql_query("
			SELECT fileid, state
			FROM project_pages
			WHERE projectid='$projectid'
		");
		echo " transferring states for ". mysql_num_rows($res) ." rows\n";
		while ( list($fileid,$state) = mysql_fetch_row($res) )
		{
			dpsql_query("
				UPDATE $projectid
				SET state='$state'
				WHERE fileid='$fileid'
			");
		}
	}
}

if (0)
{
	include_once($relPath.'../locale/translators/parse_po.inc');
	$lang = 'de';
	$m = file("$dyn_locales_dir/$lang/LC_MESSAGES/messages.po");
	var_dump(parse_po($m));
}

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

	echo "\nget_PRD_for_round_id:\n";
	foreach (array('P1','P2','F1') as $round_id)
	{
		$prd = get_PRD_for_round_id($round_id);
		echo "$round_id\t$prd->round_id\n";
	}

	echo "\nget_PRD_for_round:\n";
	foreach (array(1,2,3) as $round_number)
	{
		$prd = get_PRD_for_round($round_number);
		echo "$round_number\t$prd->round_id\n";
	}

	echo "\nget_PRD_for_project_state:\n";
	foreach ($PROJECT_STATES_IN_ORDER as $project_state)
	{
		$prd = get_PRD_for_project_state($project_state);
		echo "$project_state\t$prd->round_id\n";
	}

	echo "\nget_PRD_for_page_state:\n";
	foreach ($PAGE_STATES_IN_ORDER as $page_state)
	{
		$prd = get_PRD_for_page_state($page_state);
		echo "$page_state\t$prd->round_id\n";
	}

	var_dump($P1);
	var_dump($P2);
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
    dpsql_query("
	DELETE FROM projects
	WHERE nameofwork LIKE 'ADHOC: %'
    ");

    $i = 0;
    foreach ( $project_state_label_ as $project_state => $label )
    {
	$i += 1;
	dpsql_query("
	    INSERT INTO projects
	    SET
		projectid='jmd_$i',
		nameofwork='ADHOC: $label',
		authorsname='anon',
		username='jmdyck',
		state='$project_state'
	");
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
