<?php
// $Id$

// This file provides settings (mostly permissions) for the logged-on user.

include_once($relPath.'page_tally.inc');
include_once($relPath.'Settings.inc');

// This function returns the Settings object for $pguser.
// It should work properly (by returning appropriate null-type values) if
// the user is not logged on.

function & get_pguser_settings(): Settings
{
    global $pguser;
    return Settings::get_settings($pguser);
}

// -----------------------------------------------------------------------------

function that_user_is_a_sitemanager(?string $username): bool
{
    $userSettings = & Settings::get_settings($username);
    return $userSettings->get_boolean("sitemanager");
}

function user_is_a_sitemanager(): bool
{
    global $pguser;
    return that_user_is_a_sitemanager($pguser);
}

// ---------

function that_user_is_proj_facilitator(?string $username): bool
{
    $userSettings = & Settings::get_settings($username);
    return
        $userSettings->get_boolean("proj_facilitator")
        ||
        $userSettings->get_boolean("sitemanager");
}

function user_is_proj_facilitator(): bool
{
    global $pguser;
    return that_user_is_proj_facilitator($pguser);
}

// ---------

function user_is_an_access_request_reviewer(): bool
{
    $userSettings = & get_pguser_settings();
    return $userSettings->get_boolean("access_request_reviewer");
}

function user_is_taskcenter_mgr(): bool
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("task_center_mgr");
}

function user_can_see_user_access_chart_of(?string $subject_username): bool
{
    [$can_grant, $can_revoke] = user_can_modify_access_of($subject_username);
    return ($can_grant || $can_revoke || user_is_proj_facilitator());
    // If X can modify the access of Y, then X needs to be able to see Y's access chart.
    // We also allow PFs to see access charts.
}

/** @return array{0:bool, 1:bool} */
function user_can_modify_access_of(?string $subject_username): array
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
    if ($testing && (!$can_grant || !$can_revoke) && $subject_username == $pguser) { /** @phpstan-ignore-line */
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

function that_user_is_PM(?string $username): bool
{
    $userSettings = & Settings::get_settings($username);

    return that_user_is_a_sitemanager($username)
            || that_user_is_proj_facilitator($username)
            || $userSettings->get_boolean("manager");
}
function user_is_PM(): bool
{
    global $pguser;
    return that_user_is_PM($pguser);
}
function that_user_has_project_loads_disabled(?string $username): bool
{
    $userSettings = & Settings::get_settings($username);
    return $userSettings->get_boolean("disable_project_loads");
}
function user_has_project_loads_disabled(): bool
{
    global $pguser;
    return that_user_has_project_loads_disabled($pguser);
}
// ---------

function that_user_is_over_PP_checked_out_limit(?string $username): bool
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
            AND state = '%s'
        ",
        DPDatabase::escape($username),
        PROJ_POST_FIRST_CHECKED_OUT,
    );
    $result = DPDatabase::query($query);
    $row = mysqli_fetch_row($result);
    $number_out = $row[0];

    // a limit of zero counts as no limit
    return $actual_limit > 0 && $number_out >= $actual_limit;
}

function user_is_over_PP_checked_out_limit(): bool
{
    global $pguser;
    return that_user_is_over_PP_checked_out_limit($pguser);
}

// ---------

function user_is_site_news_editor(): bool
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("site_news_editor");
}

function user_is_site_translator(): bool
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("site_translator");
}

function user_can_mentor_in_any_round(): bool
{
    if (user_is_a_sitemanager() || user_is_proj_facilitator()) {
        return true;
    }

    $userSettings = & get_pguser_settings();
    return any(Rounds::get_all(), fn ($r) => $userSettings->get_boolean("{$r->id}_mentor.access"));
}

function user_can_mentor_in_round(Round $round): bool
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager()
            || user_is_proj_facilitator()
            || $userSettings->get_boolean("{$round->id}_mentor.access");
}

function that_user_can_work_on_beginner_pages_in_round(?string $username, Round $round): bool
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

function user_can_work_on_beginner_pages_in_round(Round $round): bool
{
    global $pguser;
    return that_user_can_work_on_beginner_pages_in_round($pguser, $round);
}

function user_is_authors_db_manager(): bool
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean('authors_db_manager');
}

function user_is_authors_db_editor(): bool
{
    return user_is_PM();
}

function user_is_image_sources_manager(): bool
{
    $userSettings = & get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("image_sources_manager");
}

function user_has_DU_access(): bool
{
    $userSettings = & get_pguser_settings();
    return $userSettings->get_boolean("DU.access") || $userSettings->get_boolean("PPV.access");
}

function user_can_see_queue_settings(): bool
{
    global $ordinary_users_can_see_queue_settings;
    return $ordinary_users_can_see_queue_settings ||
        user_is_a_sitemanager() || user_is_proj_facilitator();
}
