<?
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');

function abort_if_not_manager()
{
	global $site_manager_email_addr, $code_url;

	if ( !user_is_PM()) {
		echo "
			<p>".sprintf(_("
			You are not listed as a project manager.
			Please contact the
			%ssite manager%s
			about resolving this problem."),"<a href='mailto:$site_manager_email_addr'>","</a>")."
			</p><p>
			".sprintf(_("Back to %shome page%s"),"<a href='$code_url/default.php'>","</a>")."
		</p>";
		theme("","footer");
		exit();
	}
}

function echo_manager_header( $which )
{
	global $userP, $forums_url, $code_url;

	echo "<p>";
	echo "<center>";
	echo "<a href='projectmgr.php?show=user_active'>"._("Show Your Active Projects")."</a>";
	echo " | ";
	echo "<a href='projectmgr.php?show=all'>"._("Show All of Your Projects"),"</a>";
	echo " | ";
	echo "<a href='editproject.php'>"._("Create Project")."</a>";
	echo " | ";
	if (user_is_a_sitemanager() or user_is_proj_facilitator())
	{ 
		echo "<a href='projectmgr.php?show=site'>"._("Show All Projects")."</a>"; 
		echo " | ";
		echo "<a href='projectmgr.php?show=search_form'>"._("<b>Search for Projects</b>")."</a>"; 
	}
	else
	{
		echo "<a href='projectmgr.php?show=search_form'>"._("<b>Search Your Projects</b>")."</a>"; 
	}
	echo "</center>";

	echo "
		<p><center>
		".sprintf("<font size=-1>
		Visit the 
		%shelp section%s
		or
		%sforum topic%s
		for this page.
		</font>","<a href='$code_url/faq/pm-faq.php#$which'>","</a>","<a href='$forums_url/viewtopic.php?t=2793'>","</a>")."
		</center></p>
	";

}

?>
