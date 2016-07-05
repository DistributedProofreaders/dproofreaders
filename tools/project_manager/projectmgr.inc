<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'user_is.inc');
include_once($relPath.'project_edit.inc');

function abort_if_not_manager()
{
    global $site_manager_email_addr, $code_url;

    if ( !user_is_PM()) 
    {
        echo "<p>".sprintf(_("You are not listed as a project manager.
            Please contact the <a href='%s'>site manager</a>
            about resolving this problem."),"mailto:$site_manager_email_addr")."
              </p><p>
              ".sprintf(_("Back to <a href='%s'>home page</a>"),"$code_url/default.php")."
              </p>";
        exit();
    }
}

function echo_manager_header()
{
    global $pguser, $code_url, $external_catalog_locator;

    $create_project_url = (empty($external_catalog_locator)
                           ? "$code_url/tools/project_manager/editproject.php?action=createnew"
                           : "$code_url/tools/project_manager/external_catalog_search.php?action=show_query_form"
                           );

    echo "<p>";
    echo "<center>\n";

    // ---------------------------------------
    // Links that aren't related to project searches.

    echo "<a href='$code_url/tools/project_manager/show_specials.php'>"._("Special Days Info")."</a>";
    echo "\n|\n";
    if ( user_is_a_sitemanager() )
    {
        echo "<a href='$code_url/tools/site_admin/manage_special_days.php'>"._("Manage Special Days")."</a>";
        echo "\n|\n";
    }
    echo "<a href='$code_url/tools/project_manager/show_image_sources.php'>"._("Image Sources Info")."</a>";
    if (user_is_image_sources_manager())
    {
        echo "\n|\n";
        echo "<a href='$code_url/tools/project_manager/manage_image_sources.php?action=show_sources'>"._("Manage Image Sources")."</a>";
    }
    else if ( user_is_PM() )
    {
        echo "\n|\n";
        echo "<a href='$code_url/tools/project_manager/manage_image_sources.php?action=add_source'>"._("Propose a new Image Source")."</a>";
    }
    echo "\n";

    echo "<br>\n";
    // ---------------------------------------
    // Links for everyone

    {
        echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=search_form'><b>"._("Search for Projects")."</b></a>";
        echo "\n|\n";
        echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=site_active'>"._("Show All Active Projects")."</a>"; 
        echo "\n|\n";
        echo "<a href='$code_url/stats/release_queue.php'>"._("Show All Release Queues")."</a>"; 
    }
    echo "\n";

    // ---------------------------------------
    // Links for PMs only

    if ( user_is_PM() )
    {
        echo "<br>\n";
        echo _("PM links"), ": ";
        $enc_pguser = urlencode($pguser);
        echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=search_form&project_manager=$enc_pguser'><b>"._("Search Your Projects")."</b></a>"; 
        echo "\n|\n";
        echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=user_active'>"._("Show Your Active Projects")."</a>";
        echo "\n|\n";
        echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=user_all'>"._("Show All of Your Projects"),"</a>";
        echo "\n|\n";
        // is this PM allowed to upload projects?
        if ( ! user_has_project_loads_disabled() )
        {
            echo "<a href='$create_project_url'>"._("Create Project")."</a>";
            echo "\n|\n";
        }
        echo "<a href='$code_url/tools/project_manager/show_all_good_word_suggestions.php'>"._("Manage All Proofreaders' Suggestions")."</a>";
    }
    echo "\n";

    // --------------------------

    echo "</center>";

    if ( user_is_PM() )
    {
        // possibly show message, but don't exit
        check_user_can_load_projects(false);
    }
}

// vim: sw=4 ts=4 expandtab
?>
