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
	global $pguser, $forums_url, $code_url, $external_catalog_locator;

	$create_project_url = (
		empty($external_catalog_locator)
		? "$code_url/tools/project_manager/editproject.php?action=createnew"
		: "$code_url/tools/project_manager/external_catalog_search.php?action=show_query_form"
	);

	echo "<p>";
	echo "<center>\n";

	// ---------------------------------------
	// Links that aren't related to project searches.

	echo "<a href='$code_url/tools/project_manager/show_specials.php'>"._("Special Days Info")."</a>";
	echo "\n|\n";

	echo "<a href='$code_url/tools/project_manager/show_image_sources.php'>"._("Image Sources Info")."</a>";
	if (user_is_image_sources_manager())
	{
		echo "\n|\n";
		echo "<a href='$code_url/tools/project_manager/manage_image_sources.php?action=show_sources'>"._("Manage Image Sources")."</a>";
	}
	else
	{
		echo "\n|\n";
		echo "<a href='$code_url/tools/project_manager/manage_image_sources.php?action=add_source'>"._("Propose a new Image Source")."</a>";
	}
	echo "\n";

	echo "<br>\n";
	// ---------------------------------------

	if (user_is_a_sitemanager() or user_is_proj_facilitator())
	{
		echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=search_form'>"._("<b>Search for Projects</b>")."</a>";
		echo "\n|\n";
		echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=site_active'>"._("Show All Active Projects")."</a>"; 
	}
	echo "\n";

	// ---------------------------------------

	{
		echo "<br>\n";
		$enc_pguser = urlencode($pguser);
		echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=search_form&project_manager=$enc_pguser'>"._("<b>Search Your Projects</b>")."</a>"; 
		echo "\n|\n";
		echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=user_active'>"._("Show Your Active Projects")."</a>";
		echo "\n|\n";
		echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=user_all'>"._("Show All of Your Projects"),"</a>";
		echo "\n|\n";
		echo "<a href='$create_project_url'>"._("Create Project")."</a>";
	}
	echo "\n";

	// --------------------------

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
