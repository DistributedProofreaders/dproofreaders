<?
// $Id$

// This file provides settings (mostly permissions) for the logged-on user.

include_once($relPath.'page_tally.php');
include_once($relPath.'SettingsClass.inc');

// This function reuturns the Settings object for $pguser.
// It should work properly (by returning appropriate null-type values) if
// the user is not logged on.

function & get_pguser_settings()
{
    global $pguser;
    return Settings::get_settings($pguser);
}

// -----------------------------------------------------------------------------

function user_is_a_sitemanager()
{
    $userSettings =& get_pguser_settings();
    return $userSettings->get_boolean("sitemanager");
}

function user_is_taskcenter_mgr()
{
    $userSettings =& get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("task_center_mgr");
}

function user_is_proj_facilitator()
{
    $userSettings =& get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("proj_facilitator");
}

function user_is_PM()
{
    $userSettings =& get_pguser_settings();

    return user_is_a_sitemanager()
            || user_is_proj_facilitator()
            || $userSettings->get_boolean("manager");
}

function user_is_site_news_editor()
{
    $userSettings =& get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("site_news_editor");
}

function user_is_site_translator()
{
    $userSettings =& get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("site_translator");
}

function user_can_see_BEGIN_in_round($round_number)
{
    $userSettings =& get_pguser_settings();
    if (user_is_a_sitemanager()
            || user_is_proj_facilitator()
            || $userSettings->get_boolean("see_BEGIN_R".$round_number))
        return TRUE;

    global $pguser;
    $n_pages = user_get_ELR_page_tally( $pguser );
    if ($round_number == 1)
        return $n_pages <= 40;
    else if ($round_number == 2)
        return $n_pages >= 300;
    else
        return TRUE;
        // The round-restriction is sufficient.
}
    
function user_is_authors_db_manager()
{
    $userSettings =& get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean('authors_db_manager');
}

function user_is_authors_db_editor() {
  return user_is_PM();
}

// vim: sw=4 ts=4 expandtab
?>
