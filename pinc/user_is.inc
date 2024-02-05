<?php
// $Id$

// This file provides settings (mostly permissions) for the logged-on user.

include_once($relPath.'page_tally.inc');
include_once($relPath.'Settings.inc');

// This function returns the Settings object for $pguser.
// It should work properly (by returning appropriate null-type values) if
// the user is not logged on.

function & get_pguser_settings()
{
    global $pguser;
    return Settings::get_settings($pguser);
}

// -----------------------------------------------------------------------------

function that_user_is_a_sitemanager($username)
{
    $userSettings = & Settings::get_settings($username);
    return $userSettings->get_boolean("sitemanager");
}

function user_is_a_sitemanager()
{
    global $pguser;
    return that_user_is_a_sitemanager($pguser);
}

// ---------

function that_user_is_proj_facilitator($username)
{
    $userSettings = & Settings::get_settings($username);
    return
        $userSettings->get_boolean("proj_facilitator")
        ||
        $userSettings->get_boolean("sitemanager");
}

function user_is_proj_facilitator()
{
    global $pguser;
    return that_user_is_proj_facilitator($pguser);
}

// ---------

function user_is_an_access_request_reviewer()
{
    $userSettings = & get_pguser_settings();
    return $userSettings->get_boolean("access_request_reviewer");
}

function user_is_taskcenter_mgr()
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("task_center_mgr");
}

function user_can_see_user_access_chart_of($subject_username)
{
    [$can_grant, $can_revoke] = user_can_modify_access_of($subject_username);
    return ($can_grant || $can_revoke || user_is_proj_facilitator());
    // If X can modify the access of Y, then X needs to be able to see Y's access chart.
    // We also allow PFs to see access charts.
}

function user_can_modify_access_of($subject_username)
{
    // Currently, $subject_username is ignored unless $testing is true.
    if (user_is_a_sitemanager() || user_is_an_access_request_reviewer()) {
        $can_grant = true;
        $can_revoke = true;
    } else {
        $can_grant = false;
        $can_revoke = false;
    }

    global $testing, $pguser;
    if ($testing && (!$can_grant || !$can_revoke) && $subject_username == $pguser) {
        static $shown = false;
        if (!$shown) {
            echo "<p class='test_warning'>";
            echo _("You would not normally be able to grant+revoke access, but because this is a test site, you are permitted to do so <i>for yourself</i>.");
            echo "</p>";
            $shown = true;
        }
        $can_grant = true;
        $can_revoke = true;
    }

    return [$can_grant, $can_revoke];
}

// ---------

function that_user_is_PM($username)
{
    $userSettings = & Settings::get_settings($username);

    return that_user_is_a_sitemanager($username)
            || that_user_is_proj_facilitator($username)
            || $userSettings->get_boolean("manager");
}
function user_is_PM()
{
    global $pguser;
    return that_user_is_PM($pguser);
}
function that_user_has_project_loads_disabled($username)
{
    $userSettings = & Settings::get_settings($username);
    return $userSettings->get_boolean("disable_project_loads");
}
function user_has_project_loads_disabled()
{
    global $pguser;
    return that_user_has_project_loads_disabled($pguser);
}
// ---------

function that_user_is_over_PP_checked_out_limit($username)
{
    $userSettings = & Settings::get_settings($username);
    $limit = $userSettings->get_value("pp_limit_value", 'none');
    if (! is_numeric($limit)) {
        return false;
    }
    // the user does have a limit
    $actual_limit = intval($limit);

    // now see how many they have checked out
    $query = sprintf(
        "
        SELECT COUNT(*)
        FROM projects
        WHERE checkedoutby LIKE '%s'
            AND state = 'proj_post_first_checked_out'
        ",
        DPDatabase::escape($username)
    );
    $result = DPDatabase::query($query);
    $row = mysqli_fetch_row($result);
    $number_out = $row[0];

    // a limit of zero counts as no limit
    return $actual_limit > 0 && $number_out >= $actual_limit;
}

function user_is_over_PP_checked_out_limit()
{
    global $pguser;
    return that_user_is_over_PP_checked_out_limit($pguser);
}

// ---------

function user_is_site_news_editor()
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("site_news_editor");
}

function user_is_site_translator()
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("site_translator");
}

function user_can_mentor_in_any_round()
{
    if (user_is_a_sitemanager() || user_is_proj_facilitator()) {
        return true;
    }

    $userSettings = & get_pguser_settings();
    foreach (Rounds::get_all() as $round) {
        if ($userSettings->get_boolean("{$round->id}_mentor.access")) {
            return true;
        }
    }
    return false;
}

function user_can_mentor_in_round($round)
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager()
            || user_is_proj_facilitator()
            || $userSettings->get_boolean("{$round->id}_mentor.access");
}

function that_user_can_work_on_beginner_pages_in_round($username, $round)
{
    $userSettings = & Settings::get_settings($username);
    $round_number = $round->round_number;
    if (that_user_is_a_sitemanager($username)
            || that_user_is_proj_facilitator($username)
            || $userSettings->get_boolean("see_BEGIN_R".$round_number)
            || $userSettings->get_boolean("{$round->id}_mentor.access")
    ) {
        return true;
    }

    $n_pages = user_get_ELR_page_tally($username);
    if ($round_number == 1) {
        return $n_pages <= 40;
    } elseif ($round_number == 2) {
        // Can't qualify by page-count. Must qualify by mentor.access above.
        return false;
    } else {
        return true;
    }
    // The round-restriction is sufficient.
}

function user_can_work_on_beginner_pages_in_round($round)
{
    global $pguser;
    return that_user_can_work_on_beginner_pages_in_round($pguser, $round);
}

function user_is_authors_db_manager()
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean('authors_db_manager');
}

function user_is_authors_db_editor()
{
    return user_is_PM();
}

function user_is_image_sources_manager()
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("image_sources_manager");
}

function user_has_DU_access()
{
    $userSettings = & get_pguser_settings();
    return $userSettings->get_boolean("DU.access") || $userSettings->get_boolean("PPV.access");
}

function user_can_see_queue_settings()
{
    global $ordinary_users_can_see_queue_settings;
    return $ordinary_users_can_see_queue_settings ||
        user_is_a_sitemanager() || user_is_proj_facilitator();
}
