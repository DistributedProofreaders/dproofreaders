<?
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');

function abort_if_not_manager()
{
	global $userP, $site_manager_email_addr, $code_url;

	if ( $userP['manager'] != "yes") {
		echo "
			<p>
			You are not listed as a project manager.
			Please contact the
			<a href='mailto:$site_manager_email_addr'>site manager</a>
			about resolving this problem.
			<p>
			Back to <a href='$code_url/default.php'>home page</a>
		";
		exit();
	}
}

function echo_manager_header( $which )
{
	global $userP, $forums_url, $code_url;

	echo "<p>";
	echo "<center>";
	echo "<a href='projectmgr.php?show=user_active'>Show Your Active Projects</a>";
	echo " | ";
	echo "<a href='projectmgr.php?show=all'>Show All of Your Projects</a>";
	echo " | ";
	echo "<a href='editproject.php'>Create Project</a>";
	echo " | ";
	if ($userP['sitemanager'] == "yes")
	{ 
		$sitemgrCheck = mysql_query("SELECT sitemanager FROM users WHERE username = '".$GLOBALS['pguser']."'");
		if (mysql_result($sitemgrCheck,0,"sitemanager") == "yes") {
			echo "<a href='projectmgr.php?show=site'>Show All Projects</a>"; 
			echo " | ";
			echo "<b><a href='projectmgr.php?show=search_form'>Search for Projects</a></b>"; 
		} else {
			echo "<b><a href='projectmgr.php?show=search_form'>Search Your Projects</a></b>"; 
		}
	}
	else
	{
		echo "<b><a href='projectmgr.php?show=search_form'>Search Your Projects</a></b>"; 
	}
	echo "</center>";

	echo "
		<p>
		<font size=-1>
		Visit the 
		<a href='$code_url/faq/pm-faq.php#$which'>help section</a>
		or
		<a href='$forums_url/viewtopic.php?t=2793'>forum topic</a>
		for this page.
		</font>
		</p>
	";

}

?>
