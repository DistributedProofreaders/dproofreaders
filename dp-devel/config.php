<?
$relPath="./pinc/";
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();
include($relPath.'theme.inc');

$auth = false;

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
	$result = mysql_query("SELECT * FROM phpbb_users WHERE username='".$_SERVER['PHP_AUTH_USER']."' AND user_password='".md5($_SERVER['PHP_AUTH_PW'])."'");
	if (mysql_numrows($result) != 0) {
		$result = mysql_query("SELECT sitemanager FROM users WHERE username='".$_SERVER['PHP_AUTH_USER']."'");
		if (mysql_result($result, 0, "sitemanager") == "yes") { $auth = true; }
	}
}

if (!$auth) {
	header( 'WWW-Authenticate: Basic realm="Private"' );
	header( 'HTTP/1.0 401 Unauthorized' );
	theme("Authorization Failed", "header");
	echo "<br><center><h2><b>Authorization Failed!</b></h2></center>";
	theme("", "footer");
	exit;
} else {
	$no_stats=1;
	if (!empty($_POST['posted'])) { theme("Update Completed!", "header"); } else { theme("DP Configuration", "header"); }
	if (!empty($_POST['posted'])) {
		//Update udb_user.php
		$lines = file($relPath.'udb_user.php');
		$udb_user_file = fopen($relPath.'udb_user.php', "w");
		$i=0;
		while ($i < count($lines)) {
			if (substr($lines[$i], 5, 6) == "server") { fputs($udb_user_file, "var \$server   = '".$_POST['server']."';\n"); }
			elseif (substr($lines[$i], 5, 8) == "username") { fputs($udb_user_file, "var \$username = '".$_POST['username']."';\n"); }
			elseif (substr($lines[$i], 5, 8) == "password") { fputs($udb_user_file, "var \$password = '".$_POST['password']."';\n"); }
			elseif (substr($lines[$i], 5, 6) == "dbname") { fputs($udb_user_file, "var \$dbname   = '".$_POST['dbname']."';\n"); }
			else { fputs($udb_user_file, trim($lines[$i])."\n"); }
		$i++;
		}
		fclose($udb_user_file);

		//Update v_site.inc
		$lines = file($relPath.'v_site.inc');
		$v_site_file = fopen($relPath.'v_site.inc', "w");
		$i=0;
		while ($i < count($lines)) {
			if (substr($lines[$i], 1, 8) == "code_dir") { fputs($v_site_file, "\$code_dir = '".$_POST['code_dir']."';\n"); }
			elseif (substr($lines[$i], 1, 8) == "code_url") { fputs($v_site_file, "\$code_url = '".$_POST['code_url']."';\n"); }
			elseif (substr($lines[$i], 1, 12) == "projects_dir") { fputs($v_site_file, "\$projects_dir = '".$_POST['projects_dir']."';\n"); }
			elseif (substr($lines[$i], 1, 12) == "projects_url") { fputs($v_site_file, "\$projects_url = '".$_POST['projects_url']."';\n"); }
			elseif (substr($lines[$i], 1, 12) == "dynstats_dir") { fputs($v_site_file, "\$dynstats_dir = '".$_POST['dynstats_dir']."';\n"); }
			elseif (substr($lines[$i], 1, 12) == "dynstats_url") { fputs($v_site_file, "\$dynstats_url = '".$_POST['dynstats_url']."';\n"); }
			elseif (substr($lines[$i], 1, 12) == "xmlfeeds_dir") { fputs($v_site_file, "\$xmlfeeds_dir = '".$_POST['xmlfeeds_dir']."';\n"); }
			elseif (substr($lines[$i], 1, 12) == "xmlfeeds_url") { fputs($v_site_file, "\$xmlfeeds_url = '".$_POST['xmlfeeds_url']."';\n"); }
			elseif (substr($lines[$i], 1, 10) == "forums_url") { fputs($v_site_file, "\$forums_url = '".$_POST['forums_url']."';\n"); }
			elseif (substr($lines[$i], 1, 18) == "reset_password_url") { fputs($v_site_file, "\$reset_password_url = \"".$_POST['reset_password_url']."\";\n"); }
			elseif (substr($lines[$i], 1, 17) == "general_forum_url") { fputs($v_site_file, "\$general_forum_url = \"".$_POST['general_forum_url']."\";\n"); }
			elseif (substr($lines[$i], 1, 18) == "projects_forum_url") { fputs($v_site_file, "\$projects_forum_url = \"".$_POST['projects_forum_url']."\";\n"); }
			elseif (substr($lines[$i], 1, 25) == "post_processing_forum_url") { fputs($v_site_file, "\$post_processing_forum_url = \"".$_POST['post_processing_forum_url']."\";\n"); }
			elseif (substr($lines[$i], 1, 11) == "uploads_dir") { fputs($v_site_file, "\$uploads_dir = '".$_POST['uploads_dir']."';\n"); }
			elseif (substr($lines[$i], 1, 12) == "uploads_host") { fputs($v_site_file, "\$uploads_host = '".$_POST['uploads_host']."';\n"); }
			elseif (substr($lines[$i], 1, 15) == "uploads_account") { fputs($v_site_file, "\$uploads_account = '".$_POST['uploads_account']."';\n"); }
			elseif (substr($lines[$i], 1, 16) == "uploads_password") { fputs($v_site_file, "\$uploads_password = '".$_POST['uploads_password']."';\n"); }
			elseif (substr($lines[$i], 1, 17) == "aspell_executable") { fputs($v_site_file, "\$aspell_executable = '".$_POST['aspell_executable']."';\n"); }
			elseif (substr($lines[$i], 1, 13) == "aspell_prefix") { fputs($v_site_file, "\$aspell_prefix = \"".$_POST['aspell_prefix']."\";\n"); }
			elseif (substr($lines[$i], 1, 15) == "aspell_temp_dir") { fputs($v_site_file, "\$aspell_temp_dir = '".$_POST['aspell_temp_dir']."';\n"); }
			elseif (substr($lines[$i], 1, 19) == "no_reply_email_addr") { fputs($v_site_file, "\$no_reply_email_addr = '".$_POST['no_reply_email_addr']."';\n"); }
			elseif (substr($lines[$i], 1, 23) == "general_help_email_addr") { fputs($v_site_file, "\$general_help_email_addr = '".$_POST['general_help_email_addr']."';\n"); }
			elseif (substr($lines[$i], 1, 23) == "site_manager_email_addr") { fputs($v_site_file, "\$site_manager_email_addr = '".$_POST['site_manager_email_addr']."';\n"); }
			elseif (substr($lines[$i], 1, 15) == "auto_email_addr") { fputs($v_site_file, "\$auto_email_addr = '".$_POST['auto_email_addr']."';\n"); }
			elseif (substr($lines[$i], 1, 11) == "pagesneeded") { fputs($v_site_file, "\$pagesneeded = ".$_POST['pagesneeded'].";\n"); }
			elseif (substr($lines[$i], 1, 18) == "noneng_pagesneeded") { fputs($v_site_file, "\$noneng_pagesneeded = ".$_POST['noneng_pagesneeded'].";\n"); }
			elseif (substr($lines[$i], 1, 15) == "eng_pagesneeded") { fputs($v_site_file, "\$eng_pagesneeded = ".$_POST['eng_pagesneeded'].";\n"); }
			elseif (substr($lines[$i], 1, 18) == "beginners_projects") { fputs($v_site_file, "\$beginners_projects = ".$_POST['beginners_projects'].";\n"); }
			elseif (substr($lines[$i], 1, 13) == "easy_projects") { fputs($v_site_file, "\$easy_projects = ".$_POST['easy_projects'].";\n"); }
			elseif (substr($lines[$i], 1, 7) == "testing") { fputs($v_site_file, "\$testing = ".$_POST['testing'].";\n"); }
			else { fputs($v_site_file, trim($lines[$i])."\n"); }
		$i++;
		}
		fclose($udb_user_file);
		echo "<br><center><b>Update Completed!</b></center>";
	}
	include($relPath.'v_site.inc');
	$db_info=new db_udb_user();

	echo "<br><form method='post' action='".$_SERVER['PHP_SELF']."'>";

	echo "<center><table border='1' style='border-collapse: collapse' width='90%' cellpadding='2' cellspacing='0'>";
	echo "<tr><td bgcolor='#006699' colspan='2' align='center' valign='top'><center><b><font color='#ff9966' face='Verdana' size='2'>Basic Configuration</font></b></center></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Testing Mode:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'>";
	if ($testing) { echo "<input type='radio' name='testing' value='TRUE' checked>On&nbsp;<input type='radio' name='testing' value='FALSE'>Off"; } else { echo "<input type='radio' name='testing' value='TRUE'>On&nbsp;<input type='radio' name='testing' value='FALSE' checked>Off"; }
	echo "</td></tr></center></table>";

	echo "<center><table border='1' style='border-collapse: collapse' width='90%' cellpadding='2' cellspacing='0'>";
	echo "<tr><td bgcolor='#006699' colspan='2' align='center' valign='top'><center><b><font color='#ff9966' face='Verdana' size='2'>Directory Configuration</font></b></center></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Code Directory:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='code_dir' value='$code_dir'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Code URL:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='code_url' value='$code_url'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Projects Directory:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='projects_dir' value='$projects_dir'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Projects URL:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='projects_url' value='$projects_url'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Statistics Directory:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='dynstats_dir' value='$dynstats_dir'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Statistics URL:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='dynstats_url' value='$dynstats_url'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>XML Feeds Directory:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='xmlfeeds_dir' value='$xmlfeeds_dir'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>XML Feeds URL:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='xmlfeeds_url' value='$xmlfeeds_url'></td></tr>";
	echo "</center></table>";

	echo "<center><table border='1' style='border-collapse: collapse' width='90%' cellpadding='2' cellspacing='0'>";
	echo "<tr><td bgcolor='#006699' colspan='2' align='center' valign='top'><center><b><font color='#ff9966' face='Verdana' size='2'>Forums Configuration</font></b></center></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Forums URL:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='forums_url' value='$forums_url'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Reset Password URL:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='reset_password_url' value='$reset_password_url'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>General Forum URL:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='general_forum_url' value='$general_forum_url'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Projects Forum URL:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='projects_forum_url' value='$projects_forum_url'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Post Processing Forum URL:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='80' name='post_processing_forum_url' value='$post_processing_forum_url'></td></tr>";
	echo "</center></table>";

	echo "<center><table border='1' style='border-collapse: collapse' width='90%' cellpadding='2' cellspacing='0'>";
	echo "<tr><td bgcolor='#006699' colspan='2' align='center' valign='top'><center><b><font color='#ff9966' face='Verdana' size='2'>Uploads Configuration</font></b></center></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Uploads Directory:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='uploads_dir' value='$uploads_dir'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Uploads Hostname:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='uploads_host' value='$uploads_host'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Uploads Username:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='uploads_account' value='$uploads_account'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Uploads Password:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='uploads_password' value='$uploads_password'></td></tr>";
	echo "</center></table>";

	echo "<center><table border='1' style='border-collapse: collapse' width='90%' cellpadding='2' cellspacing='0'>";
	echo "<tr><td bgcolor='#006699' colspan='2' align='center' valign='top'><center><b><font color='#ff9966' face='Verdana' size='2'>aspell Configuration</font></b></center></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>aspell Executable:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='aspell_executable' value='$aspell_executable'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>aspell Prefix:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='aspell_prefix' value='$aspell_prefix'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>aspell Temporary Directory:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='aspell_temp_dir' value='$aspell_temp_dir'></td></tr>";
	echo "</center></table>";

	echo "<center><table border='1' style='border-collapse: collapse' width='90%' cellpadding='2' cellspacing='0'>";
	echo "<tr><td bgcolor='#006699' colspan='2' align='center' valign='top'><center><b><font color='#ff9966' face='Verdana' size='2'>e-Mail Configuration</font></b></center></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>No Reply e-Mail Address:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='no_reply_email_addr' value='$no_reply_email_addr'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>General Help e-Mail Address:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='general_help_email_addr' value='$general_help_email_addr'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Site Manager e-Mail Address:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='site_manager_email_addr' value='$site_manager_email_addr'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Auto e-Mail Address:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='auto_email_addr' value='$auto_email_addr'></td></tr>";
	echo "</center></table>";

	echo "<center><table border='1' style='border-collapse: collapse' width='90%' cellpadding='2' cellspacing='0'>";
	echo "<tr><td bgcolor='#006699' colspan='2' align='center' valign='top'><center><b><font color='#ff9966' face='Verdana' size='2'>Project Allocation Configuration</font></b></center></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Total Pages Needed:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='pagesneeded' value='$pagesneeded'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Non-English Pages Needed:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='noneng_pagesneeded' value='$noneng_pagesneeded'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>English Pages Needed:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='eng_pagesneeded' value='$eng_pagesneeded'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Beginners Projects Needed:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='beginners_projects' value='$beginners_projects'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Easy Projects Needed:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='easy_projects' value='$easy_projects'></td></tr>";
	echo "</center></table>";

	echo "<center><table border='1' style='border-collapse: collapse' width='90%' cellpadding='2' cellspacing='0'>";
	echo "<tr><td bgcolor='#006699' colspan='2' align='center' valign='top'><center><b><font color='#ff9966' face='Verdana' size='2'>Database Configuration</font></b></center></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Database Server Hostname:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='server' value='$db_info->server'></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Database Username:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='username' value='$db_info->username'>&nbsp;<font size='2'>Does not actually change the database username.</font></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Database Password:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='password' value='$db_info->password'>&nbsp;<font size='2'>Does not actually change the database password.</font></td></tr>";
	echo "<tr><td align='right' width='30%' valign='top'>Database Name:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'><input type='text' size='30' name='dbname' value='$db_info->dbname'></td></tr>";
	echo "</center></table>";

	echo "<input type='hidden' name='posted' value='1'>";
	echo "<p><center><input type='submit' name='submit' value='Update'>&nbsp;<input type='button' name='quit' value='Quit' onclick='javascript:location.href(\"default.php\")'></center>";
	echo "<br><br>";
	theme("", "footer");
}
?>