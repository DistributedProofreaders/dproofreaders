<?
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_project_info( $projectid, $proofstate, $sub )
{
	global $code_url, $pguser, $userP;

	echo "<table border=1 width=630>";

	$project = mysql_fetch_assoc(mysql_query("
	    SELECT * FROM projects WHERE projectid = '$projectid'
	"));

	$phpuser = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$pguser'");
	$user_id = mysql_result($phpuser, 0, "user_id");

	if ($proofstate != 'proj_post')
	{
		if ($sub)
		{
			if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) {
				$left  = _("First Round Project");
				$right = _("This is a First-Round project, these files are output from the OCR software and have not been looked at.");
			} else {
				$left  = _("Second Round Project");
				$right = _("These are files that have already been proofed once, but now need to be examined <B>closely</B> for small errors that may have been missed.")." "._("See <A HREF=\"http://www.promo.net/pg/vol/proof.html#What_kinds\" target=\" \">this page</A> for examples.");
			}
			echo_row_a( $left, "<b>$right</b>", 1 );
		}
	}

	echo_row_a( _("Title"),           $project['nameofwork'] );
	echo_row_a( _("Author"),          $project['authorsname'] );
	echo_row_a( _("Project Manager"), $project['username'] );

	if ($proofstate == 'proj_post')
	{
		echo_row_a( _("Post Proofer"), $project['postproofer'] );
	}

	if ($proofstate != 'proj_post')
	{
		if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) {
			$wTime="round1_time"; $wState=SAVE_FIRST;
		} else {
			$wTime="round2_time"; $wState=SAVE_SECOND;
		}
		$proofdate=mysql_query("SELECT $wTime FROM $projectid WHERE state='$wState' ORDER BY $wTime DESC LIMIT 1");
		if (mysql_num_rows($proofdate)!=0) {
			$lastproofed=date("l, F jS, Y \a\\t g:i:sA",mysql_result($proofdate,0,$wTime))."&nbsp;&nbsp;&nbsp; ("._("Current Time:")." ".date("g:i:sA",time()).")";
		} else {
			$lastproofed=_("Project has not been proofread in this round.");
		}
		echo_row_a( _("Last Proofread"), $lastproofed );
	}

	$topic_id = $project['topic_id'];
	if (!empty($topic_id)) {
		$last_post = mysql_query("SELECT post_time FROM phpbb_posts WHERE topic_id = $topic_id ORDER BY post_time DESC LIMIT 1");
		$last_post_date = mysql_result($last_post,0,"post_time");
		$last_post_date = date("l, F jS, Y \a\\t g:i:sA", $last_post_date);
		echo_row_a( "Last Forum Post", $last_post_date );
	}

	if ($topic_id == "") {
		$blurb = _("Start a discussion about this project");
	} else {
		$blurb = _("Discuss this project");
	}
	echo_row_a( _("Forum"), "<a href=\"$code_url/tools/proofers/project_topic.php?project=$projectid\">$blurb</a>" );

	if ($proofstate == 'proj_post')
	{
		//Look up realnames for project manager, scanner and post processor for credit line

		$managercredit   = get_real_name( $project['username'] );
		$scannercredit   = get_real_name( $project['scannercredit'] );
		$postproofername = get_real_name( $project['postproofer'] );

		$creditline = $scannercredit.', '.$managercredit.', '.$postproofername.' and the Online Distributed Proofreading Team.';

		echo_row_a( "Credits line so far", $creditline );
		echo_row_a( "Clearance Line:", $project['clearance'] );

		echo_row_b( "Post Processor Comments", '' );
		echo_row_c( $project['postcomments'] );
	}
	else
	{
		$temp = mysql_query("SELECT * FROM usersettings WHERE username = '".$pguser."' AND setting = 'posted_notice' AND value = '".$projectid."'");
		if (mysql_num_rows($temp) == 0) {
			$blurb = _("Yes, I would like to be notified when this has been posted to Project Gutenberg.");
		} else {
			$blurb = _("No, I would like to not be notified when this has been posted to Project Gutenberg.");
		}
		echo_row_a( _("Book Completed:"), "<a href=\"posted_notice.php?project=$projectid&proofstate=$proofstate\">$blurb</a>" );

		if ($sub)
		{
			recentlyproofed($projectid, $proofstate, $pguser,$userP,0);
			recentlyproofed($projectid, $proofstate, $pguser,$userP,1);
		}
	}

	$comments = $project['comments'];
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

	echo_row_b( _("Project Comments"), "("._("Please check below for Guideline Modifications").")" );

	$a = _("Follow the current <a href='$code_url/faq/document.php'>Proofing Guidelines</a> for detailed project formatting directions.");
	$b = _("Instructions below take precedence over the guidelines");
	echo_row_c( "$a<b>$b:</b><P>$comments" );

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
	echo "</tr>";
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
	echo "</tr>";
}

function echo_row_c( $content )
{
	echo "<tr>";
	echo "<td colspan=5>";
	echo $content;
	echo "</td>";
	echo "</tr>";
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
	if ($wlist==0) {
		$top = _("DONE");
		$bottom = "(<b>"._("My Recently Completed")."</b> - "._("pages I've finished proofing, that are still available for correction)");
	} else {
		$top = _("IN PROGRESS");
		$bottom = "(<b>"._("My Recently Proofread")."</b> - "._("pages I haven't yet completed)");
	}
	echo_row_b( $top, $bottom );
	$recentNum=5;

	$sql = "SELECT image, fileid, state, ";
	$whichTime = $proofstate==PROJ_PROOF_FIRST_AVAILABLE ? "round1_time" : "round2_time";
	$sql.=$whichTime." FROM $projectid WHERE ";
	if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) {$sql.="round1_user";} else {$sql.="round2_user";}
	$sql.="='$pguser' AND ";
	if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE)
	{
		if ($wlist==0)
		{$sql.="state ='".SAVE_FIRST."'";}
		else
		{$sql.="(state ='".TEMP_FIRST."' OR state ='".OUT_FIRST."')";}
	}
	else
	{
		if ($wlist==0)
		{$sql.="state ='".SAVE_SECOND."'";}
		else
		{$sql.="(state ='".TEMP_SECOND."' OR state ='".OUT_SECOND."')";}
	}
	$sql.=" ORDER BY ".$whichTime." DESC LIMIT $recentNum";
	$result = mysql_query($sql);
	$rownum = 0;
	$numrows = mysql_num_rows($result);

	while (($rownum < $recentNum) && ($rownum < $numrows)) {
		$imagefile = mysql_result($result, $rownum, "image");
		$fileid = mysql_result($result, $rownum, "fileid");
		$timestamp = mysql_result($result, $rownum, $whichTime);
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
		echo date("M d", $timestamp).": ".$imagefile."</a></td>\r\n";
		$rownum++;
	}

	while (($rownum % 5) !=0) {echo "<td>&nbsp;</td>"; $rownum++;}
	echo "</tr>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

?>
