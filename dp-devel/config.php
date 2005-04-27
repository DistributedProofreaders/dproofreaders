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
			elseif (substr($lines[$i], 1, 12) == "dyn_dir") { fputs($v_site_file, "\$dyn_dir = '".$_POST['dyn_dir']."';\n"); }
			elseif (substr($lines[$i], 1, 12) == "dyn_url") { fputs($v_site_file, "\$dyn_url = '".$_POST['dyn_url']."';\n"); }
			elseif (substr($lines[$i], 1, 10) == "forums_dir") { fputs($v_site_file, "\$forums_dir = '".$_POST['forums_dir']."';\n"); }
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

	start_section( 'Basic Configuration' );
	echo "<tr><td align='right' width='30%' valign='top'>Testing Mode:</td><td bgcolor='#dee3e7' align='left' width='70%' valign='top'>";
	if ($testing) {
		$true_checked = 'checked'; $false_checked = '';
	} else {
		$true_checked = '';        $false_checked = 'checked';
	}
	echo "<input type='radio' name='testing' value='TRUE' $true_checked>On";
	echo "&nbsp;";
	echo "<input type='radio' name='testing' value='FALSE' $false_checked>Off";
	echo "</td></tr>";
	end_section();

	start_section( 'Directory Configuration' );
	tr_text_1( 'Code Directory',     'code_dir',     $code_dir );
	tr_text_1( 'Code URL',           'code_url',     $code_url );
	tr_text_1( 'Projects Directory', 'projects_dir', $projects_dir );
	tr_text_1( 'Projects URL',       'projects_url', $projects_url );
	tr_text_1( 'Dynamics Directory', 'dyn_dir',      $dyn_dir );
	tr_text_1( 'Dynamics URL',       'dyn_url',      $dyn_url );
	end_section();

	start_section( 'Forums Configuration' );
	tr_text_1( 'Forums Directory',          'forums_dir',                $forums_dir );
	tr_text_1( 'Forums URL',                'forums_url',                $forums_url );
	tr_text_1( 'Reset Password URL',        'reset_password_url',        $reset_password_url );
	tr_text_1( 'General Forum URL',         'general_forum_url',         $general_forum_url );
	tr_text_1( 'Projects Forum URL',        'projects_forum_url',        $projects_forum_url );
	tr_text_1( 'Post Processing Forum URL', 'post_processing_forum_url', $post_processing_forum_url );
	end_section();

	start_section( 'Uploads Configuration' );
	tr_text_2( 'Uploads Directory', 'uploads_dir',      $uploads_dir );
	tr_text_2( 'Uploads Hostname',  'uploads_host',     $uploads_host );
	tr_text_2( 'Uploads Username',  'uploads_account',  $uploads_account );
	tr_text_2( 'Uploads Password',  'uploads_password', $uploads_password );
	end_section();

	start_section( 'aspell Configuration' );
	tr_text_2( 'aspell Executable',          'aspell_executable', $aspell_executable );
	tr_text_2( 'aspell Prefix',              'aspell_prefix',     $aspell_prefix );
	tr_text_2( 'aspell Temporary Directory', 'aspell_temp_dir',   $aspell_temp_dir );
	end_section();

	start_section( 'e-Mail Configuration' );
	tr_text_2( 'No Reply e-Mail Address',     'no_reply_email_addr',     $no_reply_email_addr );
	tr_text_2( 'General Help e-Mail Address', 'general_help_email_addr', $general_help_email_addr );
	tr_text_2( 'Site Manager e-Mail Address', 'site_manager_email_addr', $site_manager_email_addr );
	tr_text_2( 'Auto e-Mail Address',         'auto_email_addr',         $auto_email_addr );
	end_section();

	start_section( 'Database Configuration' );
	tr_text_2( 'Database Server Hostname', 'server',   $db_info->server );
	tr_text_2( 'Database Username',        'username', $db_info->username, "Does not actually change the database username." );
	tr_text_2( 'Database Password',        'password', $db_info->password, "Does not actually change the database password." );
	tr_text_2( 'Database Name',            'dbname',   $db_info->dbname );
	end_section();

	echo "<input type='hidden' name='posted' value='1'>";
	echo "<p><center><input type='submit' name='submit' value='Update'>&nbsp;<input type='button' name='quit' value='Quit' onclick='javascript:location.href(\"default.php\")'></center>";
	echo "<br><br>";
	theme("", "footer");
}

function start_section( $label )
{
	echo "<center>";
	echo "<table border='1' style='border-collapse: collapse' width='90%' cellpadding='2' cellspacing='0'>";
	echo "\n";

	echo "<tr>";
	echo   "<td bgcolor='#006699' colspan='2' align='center' valign='top'>";
	echo     "<center>";
	echo       "<b>";
	echo         "<font color='#ff9966' face='Verdana' size='2'>";
	echo           $label;
	echo         "</font>";
	echo       "</b>";
	echo     "</center>";
	echo   "</td>";
	echo "</tr>";
	echo "\n";
}

function end_section()
{
	echo "</table>";
	echo "</center>";
	echo "\n";
}

function tr_text_1( $label, $name, $value )
{
	tr_text( $label, 80, $name, $value, NULL );
}

function tr_text_2( $label, $name, $value, $note=NULL )
{
	tr_text( $label, 30, $name, $value, $note );
}

function tr_text( $label, $size, $name, $value, $note=NULL )
{
	echo "<tr>";
	echo   "<td align='right' width='30%' valign='top'>$label:</td>";
	echo   "<td bgcolor='#dee3e7' align='left' width='70%' valign='top'>";
	echo     "<input type='text' size='$size' name='$name' value='$value'>";
	if (!is_null($note))
	{
		echo "&nbsp;<font size='2'>$note</font>";
	}
	echo   "</td>";
	echo "</tr>";
	echo "\n";
}

?>
