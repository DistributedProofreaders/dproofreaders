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
			echo "<tr><td bgcolor='CCCCCC' align=center><h3><b>";
			if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) {
				echo _("First Round Project")."</b></h3></td><td bgcolor = \"CCCCCC\" colspan=4>";
				echo "<b>"._("This is a First-Round project, these files are output from the OCR software and have not been looked at.")."</b></td></tr>";
			} else {
				echo _("Second Round Project")."</b></h3></td><td bgcolor = \"CCCCCC\" colspan=4>";
				echo "<b>"._("These are files that have already been proofed once, but now need to be examined <B>closely</B> for small errors that may have been missed.")." "._("See <A HREF=\"http://www.promo.net/pg/vol/proof.html#What_kinds\" target=\" \">this page</A> for examples.")."</b></td>";
			}
		}
	}

	echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>"._("Title")."</b></td>";
	echo "<td colspan=4>{$project['nameofwork']}</td></tr>";
	echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>"._("Author")."</b></td>";
	echo "<td colspan=4>{$project['authorsname']}</td></tr>";
	echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>"._("Project Manager")."</b></td>";
	echo "<td colspan=4>{$project['username']}</td></tr>";

	if ($proofstate == 'proj_post')
	{
		echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>"._("Post Proofer")."</b></td>";
		echo "<td colspan=4>{$project['postproofer']}</td></tr>";
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
		echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>"._("Last Proofread")."</b></td>";
		echo "<td colspan=4>$lastproofed</td></tr>";
	}

	$topic_id = $project['topic_id'];
	if (!empty($topic_id)) {
		$last_post = mysql_query("SELECT post_time FROM phpbb_posts WHERE topic_id = $topic_id ORDER BY post_time DESC LIMIT 1");
		$last_post_date = mysql_result($last_post,0,"post_time");
		$last_post_date = date("l, F jS, Y \a\\t g:i:sA", $last_post_date);
		echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Last Forum Post</b></td>";
		echo "<td colspan=4>$last_post_date</td></tr>";
	}

	echo "<tr>";
	echo "<td bgcolor=\"CCCCCC\" align=center><b>"._("Forum")."</b></td>";
	echo "<td colspan=4><a href=\"$code_url/tools/proofers/project_topic.php?project=$projectid\">";
	if ($topic_id == "") {
		echo _("Start a discussion about this project");
	} else {
		echo _("Discuss this project");
	}
	echo "</a></td></tr>";

	if ($proofstate == 'proj_post')
	{
		//Look up realnames for project manager, scanner and post processor for credit line

		$result = mysql_query("SELECT real_name FROM users WHERE username = '{$project['username']}'");
		$managercredit = mysql_result($result, 0, "real_name");

		$result = mysql_query("SELECT real_name FROM users WHERE username = '{$project['scannercredit']}'");
		$scannercredit = mysql_result($result, 0, "real_name");

		$result = mysql_query("SELECT real_name FROM users WHERE username = '{$project['postproofer']}'");                                       
		$postproofername = mysql_result($result, 0, "real_name");     

		$creditline = $scannercredit.', '.$managercredit.', '.$postproofername.' and the Online Distributed Proofreading Team.';

		echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Credits line so far</b></td>";
		echo "<td colspan=4>$creditline</td></tr>";
		echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Clearance Line:</b></td>";
		echo "<td colspan=4>{$project['clearance']}</td></tr>";

		echo "<tr><td bgcolor=\"CCCCCC\" colspan=5 align=center><h3>Post Processor Comments</h3></td></tr><tr><td colspan=5>";
		echo "{$project['postcomments']}</td></tr>";
	}
	else
	{
		notify($projectid, $proofstate, $pguser);

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

	echo "<tr><td bgcolor=\"CCCCCC\" colspan=5 align=center><font size='+1'><b>"._("Project Comments")."</b></font><br>("._("Please check below for Guideline Modifications").")</td></tr><tr><td colspan=5>";
	echo _("Follow the current <a href=\"$code_url/faq/document.php\">Proofing Guidelines</a> for detailed project formatting directions. ");
	echo "<b>"._("Instructions below take precedence over the guidelines").":</b><P>";
	echo "$comments</td></tr>";

	echo "</table>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function notify($projectid, $proofstate, $pguser)
{
	echo "<tr><td bgcolor=\"CCCCCC\" align=center><b>"._("Book Completed:")."</b></td><td colspan=4><a href=\"posted_notice.php?project=$projectid&proofstate=$proofstate\">";
	$temp = mysql_query("SELECT * FROM usersettings WHERE username = '".$pguser."' AND setting = 'posted_notice' AND value = '".$projectid."'");
	if (mysql_num_rows($temp) == 0) {
		echo _("Yes, I would like to be notified when this has been posted to Project Gutenberg.");
	} else {
		echo _("No, I would like to not be notified when this has been posted to Project Gutenberg.");
	}
	echo "</a></td></tr>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function recentlyproofed($projectid, $proofstate, $pguser,$userP,$wlist)
{
	echo "<tr><td colspan=5 bgcolor=CCCCCC align=center><font size='+1'><b>";
	if ($wlist==0) {
		echo _("DONE");
	} else {
		echo _("IN PROGRESS");
	}
	echo "</b></font><br>";
	if ($wlist==0)
	{
		echo "(<b>"._("My Recently Completed")."</b> - "._("pages I've finished proofing, that are still available for correction)");
	}
	else
	{
		echo "(<b>"._("My Recently Proofread")."</b> - "._("pages I haven't yet completed)");
	}
	echo "</td>";
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
