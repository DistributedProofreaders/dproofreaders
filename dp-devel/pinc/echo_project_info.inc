<?
include_once($relPath.'v_site.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'authors.inc');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_project_info( $projectid, $proofstate, $sub )
{
	global $code_url, $pguser, $userP;

	$prd = get_PRD_for_project_state($proofstate);
	// Note that $prd may be NULL;

	echo "<table border=1 width=630>";

	$project = mysql_fetch_assoc(mysql_query("
	    SELECT * FROM projects WHERE projectid = '$projectid'
	"));

	// These don't appear to be used.
	// $phpuser = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$pguser'");
	// $user_id = mysql_result($phpuser, 0, "user_id");

	// -------------------------------------------------------------------------
	// The state of the project

	$right = project_states_text($project['state']);
	if ($prd)
	{
		$extra = $prd->description;
		$right = "$right ($extra)";
	}
	echo_row_a( _("Project State"), $right );

	// -------------------------------------------------------------------------
	// Information about the work itself (independent of DP)

	$word =  _("Title");
	echo_row_a($word,           $project['nameofwork'] );
	$word = _("Author");
	echo_row_a($word,          $project['authorsname'] );
	$word = _("Language");
	echo_row_a($word,        $project['language'] );
	$word = _("Genre");
	echo_row_a($word,           $project['genre'] );
	$word =  _("Difficulty");
	echo_row_a($word,      $project['difficulty'] );

	// -------------------------------------------------------------------------
	// Basic DP info

	if ($proofstate == 'proj_post')
	{
		$word = _("Project ID");
		echo_row_a($word,     $project['projectid'] );

		// The clearance line normally contains the email address of the
		// person who submitted the clearance request. Since this is
		// private information, we restrict who can see it.
		if ( user_is_PPV_of($project['projectid']) )
		{
			$word = _("Clearance Line");
			echo_row_a($word, htmlspecialchars($project['clearance'],ENT_NOQUOTES) );
		}
	}

	// -------------------------------------------------------------------------
	// People who have certain roles with respect to the project

	$word = _("Project Manager");
	echo_row_a($word, $project['username'] );

	if ($proofstate == 'proj_post')
	{
		$word = _("Post Processor");
		echo_row_a($word, $project['postproofer'] );

		$managercredit   = get_real_name( $project['username'] );
		$scannercredit   = get_real_name( $project['scannercredit'] );
		$postproofername = get_real_name( $project['postproofer'] );

		$creditline = $scannercredit.', '.$managercredit.', '.$postproofername.' and the Online Distributed Proofreading Team.';

		$word = _("Credits line so far");
		echo_row_a($word, $creditline );
	}

	// -------------------------------------------------------------------------
	// Current activity

	if ($prd)
	{
		$proofdate = mysql_query("
			SELECT {$prd->time_column_name}
		       	FROM $projectid
		       	WHERE state='{$prd->page_save_state}'
		       	ORDER BY {$prd->time_column_name} DESC
		       	LIMIT 1
		");
		if (mysql_num_rows($proofdate)!=0) {
			$lastproofed=strftime(_("%A, %B %e, %Y at %X"),mysql_result($proofdate,0,$prd->time_column_name))."&nbsp;&nbsp;&nbsp; ("._("Current Time:")." ".strftime(_("%X"),time()).")";
		} else {
			$lastproofed=_("Project has not been proofread in this round.");
		}
		$word = _("Last Proofread");
		echo_row_a($word, $lastproofed );
	}

	// -------------------------------------------------------------------------

	$state = $project['state'];
	if ( $state == PROJ_SUBMIT_PG_POSTED
	  || $state == PROJ_CORRECT_AVAILABLE
	  || $state == PROJ_CORRECT_CHECKED_OUT )
	{
		$postednum = $project['postednum'];
		echo_row_a( _("PG etext number"),
			get_pg_catalog_link_for_etext($postednum, $postednum) );
	}

	// -------------------------------------------------------------------------
	// Forum topic

	$topic_id = $project['topic_id'];
	if (!empty($topic_id)) {
		$last_post = mysql_query("SELECT post_time FROM phpbb_posts WHERE topic_id = $topic_id ORDER BY post_time DESC LIMIT 1");
		$last_post_date = mysql_result($last_post,0,"post_time");
		$last_post_date = strftime(_("%A, %B %e, %Y at %X"), $last_post_date);
		$word = _("Last Forum Post");
		echo_row_a($word, $last_post_date );
	}

	if ($topic_id == "") {
		$blurb = _("Start a discussion about this project");
	} else {
		$blurb = _("Discuss this project");
	}
	$word = _("Forum");
	echo_row_a($word, "<a href=\"$code_url/tools/proofers/project_topic.php?project=$projectid\">$blurb</a>" );

	// -------------------------------------------------------------------------

	$word = _("Page Detail");
	$blurb = _("Images, Pages Proofread, & Differences");

	echo_row_a($word, "<a href=\"$code_url/tools/project_manager/page_detail.php?project=$projectid&show_image_size=0\">$blurb</a>");

	// -------------------------------------------------------------------------
	// Personal data with respect to this project
	// (This is the only section that uses $pguser and $userP.)

	if (TRUE)
	{
		$temp = mysql_query("SELECT * FROM usersettings WHERE username = '".$pguser."' AND setting = 'posted_notice' AND value = '".$projectid."'");
		if (mysql_num_rows($temp) == 0) {
			$blurb = _("Click here to register for automatic email notification of when this has been posted to Project Gutenberg.");
		} else {
			$blurb = _("Click here to <b>undo</b> registration for automatic email notification of when this has been posted to Project Gutenberg.");
		}
		$word = _("Book Completed:");
		echo_row_a($word, "<a href=\"posted_notice.php?project=$projectid&proofstate=$proofstate\">$blurb</a>" );

		if ($sub && $prd)
		{
			recentlyproofed($projectid, $proofstate, $pguser,$userP,0);
			recentlyproofed($projectid, $proofstate, $pguser,$userP,1);
		}
	}

	// -------------------------------------------------------------------------
	// Comments

	if ($proofstate == 'proj_post')
	{
		$word = _("Post Processor Comments");
		echo_row_b($word, '' );
		echo_row_c( $project['postcomments'] );
	}

	$comments = $project['comments'];


        // automatically prepend R2 intro for Beginners Only
        if  ($project['difficulty'] == "beginner")
	{
		if ($proofstate==PROJ_PROOF_SECOND_AVAILABLE) {
	             $comments = "[template=BGr2.txt]".$comments;
		}
	}


	// insert e.g. templates and biographies
	$comments = parse_project_comments($comments);

	$word = _("Project Comments");
	$details = _("(Please check below for guideline modifications.)");
	echo_row_b($word, $details);

	$a = _("Follow the current")." <a href='$code_url/faq/document.php'>"._("Proofreading Guidelines")."</a> "._("for detailed project formatting directions.");
	$b = _("Instructions below take precedence over the guidelines");
	echo_row_c( "$a<b>$b:</b><P>$comments" );

	// -------------------------------------------------------------------------

	echo "</table>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_row_a( $left, $right, $darken_right_background=0 )
{
	if ($darken_right_background)
	{
		$right_bg = "bgcolor='CCCCCC'";
	}
	else
	{
		$right_bg = "";
	}
	echo "<tr>";
	echo "<td bgcolor='CCCCCC' align='center'><b>$left</b></td>";
	echo "<td colspan='4' $right_bg>$right</td>";
	echo "</tr>\n";
}

function echo_row_b( $top, $bottom )
{
	echo "<tr>";
	echo "<td colspan='5' bgcolor='CCCCCC' align='center'>";
	echo "<font size='+1'><b>$top</b></font>";
	if ($bottom)
	{
		echo "<br>$bottom";
	}
	echo "</td>";
	echo "</tr>\n";
}

function echo_row_c( $content )
{
	echo "<tr>";
	echo "<td colspan=5>";
	echo $content;
	echo "</td>";
	echo "</tr>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_real_name( $login_name )
{
	if ($login_name == '')
	{
		return '(no name)';
	}

	$res = mysql_query("
		SELECT real_name FROM users WHERE username='$login_name'
	");
	if (mysql_num_rows($res) > 0)
	{
		$name = mysql_result($res, 0);
	}
	else
	{
		$name = $login_name;
	}
	return $name;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function recentlyproofed($projectid, $proofstate, $pguser,$userP,$wlist)
{
	$prd = get_PRD_for_project_state($proofstate);
	assert($prd);

	if ($wlist==0) {
		$top = _("DONE");
		$bottom = "(<b>"._("My Recently Completed")."</b> - "._("pages I've finished proofreading, that are still available for correction)");
		$state_condition = "state='{$prd->page_save_state}'";
	} else {
		$top = _("IN PROGRESS");
		$bottom = "(<b>"._("My Recently Proofread")."</b> - "._("pages I haven't yet completed)");
		$state_condition = "(state='{$prd->page_temp_state}' OR state='{$prd->page_out_state}')";
	}

	echo_row_b( $top, $bottom );

	$recentNum=5;

	$sql = "
		SELECT image, fileid, state, {$prd->time_column_name}
		FROM $projectid
		WHERE {$prd->user_column_name}='$pguser' AND $state_condition
		ORDER BY {$prd->time_column_name} DESC
		LIMIT $recentNum
	";
	$result = mysql_query($sql);
	$rownum = 0;
	$numrows = mysql_num_rows($result);

	while (($rownum < $recentNum) && ($rownum < $numrows)) {
		$imagefile = mysql_result($result, $rownum, "image");
		$fileid = mysql_result($result, $rownum, "fileid");
		$timestamp = mysql_result($result, $rownum, $prd->time_column_name);
		$pagestate = mysql_result($result, $rownum, "state");
		$newproject = "project=$projectid";
		$newfileid="&amp;fileid=$fileid";
		$newimagefile = '&amp;imagefile='.$imagefile;
		$newproofstate = '&amp;proofstate='.$proofstate;
		$newpagestate = '&amp;pagestate='.$pagestate;
		$saved="&amp;saved=1";
		$editone="&amp;editone=1";
		if (($rownum % 5) ==0) {echo "</tr><tr>";}
		$eURL="proof_frame.php?".$newproject.$newfileid.$newimagefile.$newproofstate.$newpagestate.$saved.$editone;
		echo "<TD ALIGN=\"center\">";
		echo "<A HREF=\"$eURL\" target=\"proofframe\">";
		echo strftime(_("%b %d"), $timestamp).": ".$imagefile."</a></td>\r\n";
		$rownum++;
	}

	while (($rownum % 5) !=0) {echo "<td>&nbsp;</td>"; $rownum++;}
	echo "</tr>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Project comments can contain two kinds of special markup:
//   [template=........]
// and
//   [biography=....]
// This markup should always be replaced with the
// proper template/biography before the project
// comments are displayed, zipped up for the
// post-processor, etc.
//
function parse_project_comments($comments) {
	global $relPath;

	// insert templates instead of [template=abcd.txt]
	$template_count = substr_count($comments, "[template=");
	if (!empty($template_count)) {
		$i = 1;
		while ($i <= $template_count) {
			$comments_backup = $comments;
			$comments = substr($comments_backup, 0, strpos($comments_backup, "[template="))."<br>";
			$comments .= file_get_contents($relPath."templates/comment_files/".substr($comments_backup, (strpos($comments_backup, "[template=")+10), 8));
			$comments .= "<br>".substr($comments_backup, (strpos($comments_backup, ".txt]")+5));
			$i++;
		}
	}

	// insert biographies instead of [biography=123]-markup
	$biography_count = substr_count($comments, '[biography=');
	if (!empty($biography_count)) {
		$i = 1;
		while ($i <= $biography_count) {
			$comments_backup = $comments;
			// where the [biography=123] starts and ends
			$pos = strpos($comments_backup, '[biography=');
			$pos2 = strpos($comments_backup, ']', $pos);
			$comments = substr($comments_backup, 0, $pos).'<br>';
			$comments .= get_biography(substr($comments_backup, $pos+11, $pos2-$pos-11));
			$comments .= '<br>'.substr($comments_backup, $pos2+1);
			$i++;
		}
	}
	return $comments;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

?>
