<?php
include_once($relPath.'user_is.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath."faq.inc");

function abort_if_not_manager()
{
    global $site_manager_email_addr, $code_url;

    if (!user_is_PM()) {
        echo "<p>".sprintf(_("You are not listed as a project manager.
            Please contact a <a href='%s'>site manager</a>
            about resolving this problem."), "mailto:$site_manager_email_addr")."
              </p><p>
              ".sprintf(_("Back to <a href='%s'>home page</a>"), "$code_url/default.php")."
              </p>";
        exit();
    }
}

function echo_manager_links()
{
    global $theme, $code_url, $external_catalog_locator, $db_requests_email_addr;

    $create_project_url = (empty($external_catalog_locator)
        ? "$code_url/tools/project_manager/editproject.php?action=createnew"
        : "$code_url/tools/project_manager/external_catalog_search.php?action=show_query_form&hide_nontext=on");

    echo "<div id='linkbox'>\n";
    // TRANSLATORS: PM = project manager
    echo "<h2>", _("PM Links"), "</h2>\n";
    echo "<p>", _("Server Time"), ": <span id='server-time'></span></p>\n";
    echo "<ul>\n";
    if (user_is_PM()) {
        echo "<li><a href='$code_url/tools/project_manager/remote_file_manager.php'>"._("Manage uploads")."</a></li>\n";
        if (! user_has_project_loads_disabled()) {
            echo "<li><a href='" . attr_safe($create_project_url) . "'>" ._("Create Project") . "</a></li>\n";
        }
        echo "<li><a href='$code_url/tools/project_manager/project_quick_check.php'>"._("Project Quick Check")."</a></li>\n";
        echo "<li><a href='$code_url/tools/project_manager/clearance_check.php'>"._("Clearance Check")."</a></li>\n";
        echo "<li><a href='$code_url/tools/charsuites.php'>"._("Character Suites")."</a></li>\n";
        // SITE-SPECIFIC
        echo "<li>"._("Email").": ";
        echo "<a href='$code_url/../noncvs/send_email.php?to=db-req'>db-req</a> | ";
        echo "<a href='$code_url/../noncvs/send_email.php?to=dp-format'>dp-format</a>";
        echo "</li>\n";
        echo "<li><a href='$code_url/tools/project_manager/show_all_good_word_suggestions.php'>"._("Manage proofreaders' Suggestions")."</a></li>\n";
    }
    echo "<li>"._("Image Sources").": ";
    echo "<a href='$code_url/tools/project_manager/show_image_sources.php'>"._("Info")."</a>";
    if (user_is_image_sources_manager()) {
        echo " | <a href='$code_url/tools/project_manager/manage_image_sources.php?action=show_sources'>"._("Manage")."</a>\n";
    } elseif (user_is_PM()) {
        echo " | <a href='$code_url/tools/project_manager/manage_image_sources.php?action=add_source'>"._("Propose")."</a>";
    }
    echo "</li>\n";
    // We don't compact Special Days like we do Image Sources since only SAs
    // can manage Special Days (and it avoids explaining to PMs why this is
    // clearly set up for other things that they don't have access to).
    echo "<li><a href='$code_url/tools/project_manager/show_specials.php'>"._("Special Days Info")."</a></li>\n";
    if (user_is_a_sitemanager()) {
        echo "<li><a href='$code_url/tools/site_admin/manage_special_days.php'>"._("Manage Special Days")."</a></li>\n";
    }
    echo "<li><a href='$code_url/stats/release_queue.php'>"._("Show All Release Queues")."</a></li>\n";
    echo "<li><a href='https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM'>"._("PM Official Documentation")."</a></li>\n";
    $url = get_faq_url("proofreading_guidelines.php");
    echo "<li><a href='$url'>"._("Proofreading Guidelines")."</a></li>\n";
    $url = get_faq_url("formatting_guidelines.php");
    echo "<li><a href='$url'>"._("Formatting Guidelines")."</a></li>\n";

    echo "</ul>\n";
    echo "</div>\n";
}
