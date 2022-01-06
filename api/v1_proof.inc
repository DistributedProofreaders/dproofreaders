<?php
include_once($relPath.'Project.inc'); // can_be_proofed_by_current_user()
include_once($relPath.'stages.inc'); // can_user_get_pages_in_project()
include_once($relPath.'DPage.inc'); // _log_page_event(), _project_adjust_n_available_pages()
include_once($relPath.'RoundDescriptor.inc'); // get_Round_for_project_state(), is_a_mentor_round()
include_once("exceptions.inc");

// checkout a page to a user
// update the project table in one operation, then select the page we have
// checked out. (This could be modified to enable checking out several pages)
// Copying the text from previous round is different to previous action.
// POST because not idempotent
// POST v1/projects/:projectid/projectstates/:projectstateid/checkout
// (similar to LPage get_available_page())
function api_v1_project_state_checkout($method, $data, $query_params)
{
    global $pguser, $preceding_proofer_restriction;

    $project = $data[":projectid"];
    $project_state = $data[":projectstateid"];

    [$code, $msg] = $project->can_be_proofed_by_current_user();
    if ($code != $project->CBP_OKAY) {
        throw new BadRequest($msg);
    }

    $round = get_Round_for_project_state($project_state);
    $err = can_user_get_pages_in_project($pguser, $project, $round);
    if ($err) {
        throw new BadRequest($err);
    }

    if ($preceding_proofer_restriction == 'not_immediately_preceding') {
        $pages = check_out_pages($project, $project_state, $pguser, 'not_previously_proofed');
    }

    // If that failed, try again with the global setting. This is also the
    // common case if $preceding_proofer_restriction != not_immediately_preceding
    if (0 == $pages) {
        $pages = check_out_pages($project, $project_state, $pguser, $preceding_proofer_restriction);
    }
    if (0 == $pages) {
        throw new NotFoundError(_("No more files are available for proofreading for this round of the project."));
    }
    $projectid = $project->projectid;

    // update projects table
    _project_adjust_n_available_pages($projectid, -$pages);

    // now get the page(s) we have checked out, last first
    $page_state = $round->page_out_state;
    $dbQuery = "
        SELECT image
        FROM $projectid
        WHERE $round->user_column_name = '$pguser' AND state = '$page_state'
        ORDER BY image DESC
        LIMIT 1
    ";
    $result = DPDatabase::query($dbQuery);
    $row = mysqli_fetch_assoc($result);
    $image = $row['image'];
    _log_page_event($projectid, $image, 'checkout', $pguser, $round);

    return [
        "pagename" => $image,
        "pageState" => $page_state,
    ];
}

// get text and imageUrl for the given page which user has checked out, current or previous
// GET v1/projects/:projectid/projectstate/:projectstateid/pages/:pagename/pagestates/:pagestateid/versions/:versionname
function api_v1_project_state_page_state_version($method, $data, $query_params)
{
    $project_page = $data[":pagename"];
    $project_page->validate_user();

    $project = $data[":projectid"];
    $project_state = $data[":projectstateid"];
    $page_name = $project_page->name;
    $version = $data[":versionname"];
    $round = get_Round_for_project_state($project_state);

    if ($version === "previous") {
        $desired_column_name = $round->prevtext_column_name;
    } else {
        $desired_column_name = $round->text_column_name;
    }
    $sql = sprintf("
        SELECT $desired_column_name
        FROM $project->projectid
        WHERE image='%s'
    ",
        DPDatabase::escape($page_name),
    );
    $res = DPDatabase::query($sql);
    $page_text = mysqli_fetch_row($res)[0];
    return [
        "text" => $page_text,
        "imageUrl" => "{$project->url}/$page_name",
    ];
}

// return page to round
// PUT v1/projects/:projectid/projectstates/:projectstateid/pages/:pagename/pagestates/:pagestateid/return
function api_v1_project_state_page_state_return($method, $data, $query_params)
{
    global $pguser;
    $project_page = $data[":pagename"];
    $project_page->validate_user();
    $project_page->check_page_out_or_temp();
    $round = $project_page->round;
    $projectid = $data[":projectid"]->projectid;
    $image = $project_page->name;

    $sql = sprintf("
        UPDATE $projectid
        SET
            state = '$round->page_avail_state',
            $round->time_column_name = UNIX_TIMESTAMP(),
            $round->user_column_name = ''
        WHERE image = '%s'
    ",
        DPDatabase::escape($image),
    );
    DPDatabase::query($sql);

    _log_page_event($projectid, $image, 'returnToRound', $pguser, $round);

    _project_adjust_n_available_pages($projectid, +1);
    return [];
}

// save text for the given page which user has checked out as in progress
// POST v1/projects/:projectid/projectstates/:projectstateid/pages/:pagename/pagestates/:pagestateid/saveinprogress
function api_v1_project_state_page_state_saveinprogress($method, $data, $query_params)
{
    global $pguser;

    $project_page = $data[":pagename"];
    $project_page->validate_user();
    $project_page->check_page_out_or_temp();

    $projectid = $data[":projectid"]->projectid;
    $page_text = $query_params["text"];
    $page_name = $project_page->name;
    $round = $project_page->round;
    $page_state = $round->page_temp_state;
    $page_text = _normalize_page_text($page_text, $projectid);
    $timestamp = time();

    $sql = sprintf("
        UPDATE $projectid
        SET
            state = '$page_state',
            $round->time_column_name = UNIX_TIMESTAMP(),
            $round->text_column_name = '%s'
        WHERE image = '%s'
    ",
        DPDatabase::escape($page_text),
        DPDatabase::escape($page_name),
    );

    DPDatabase::query($sql);

    // insert into page_events table
    _log_page_event($projectid, $page_name, 'saveAsInProgress', $pguser, $round);
    // insert into user_project_info
    upi_set_t_latest_page_event($pguser, $projectid, $timestamp);

    return ["pageState" => $page_state];
}

// PUT v1/projects/:projectid/projectstates/:projectstateid/pages/:pagename/pagestates/:pagestateid/reopen
function api_v1_project_state_page_state_reopen($method, $data, $query_params)
{
    $projectid = $data[":projectid"]->projectid;
    $project_page = $data[":pagename"];
    $project_page->validate_user();
    $page_name = $project_page->name;
    $round = $project_page->round;
    $page_state = $project_page->state;

    if ($page_state == $round->page_save_state) {
        global $pguser;

        $page_state = $round->page_temp_state;

        $sql = sprintf("
            UPDATE $projectid
            SET
                state = '$page_state',
                $round->time_column_name = UNIX_TIMESTAMP()
            WHERE image = '%s'
        ",
            DPDatabase::escape($page_name),
        );
        DPDatabase::query($sql);

        page_tallies_add($round->id, $pguser, -1);
        _log_page_event($projectid, $page_name, 'reopen', $pguser, $round);
    }
    // else page must be 'out' or 'in-progress', no need to change database

    return ["pageState" => $page_state];
}

// save text for the given page which user has checked out as done
// POST v1/projects/:projectid/projectstates/:projectstateid/pages/:pagename/pagestates/:pagestateid/saveasdone
function api_v1_project_state_page_state_saveasdone($method, $data, $query_params)
{
    global $pguser;

    $project_page = $data[":pagename"];
    $project_page->validate_user();
    $project_page->check_page_out_or_temp();

    $projectid = $data[":projectid"]->projectid;
    $page_text = $query_params["text"];
    $page_name = $project_page->name;
    $round = $project_page->round;

    if ($round->has_a_daily_page_limit()) {
        $pre_save_dpl_count = get_dpl_count_for_user_in_round($pguser, $round);
        if ($pre_save_dpl_count >= $round->daily_page_limit) {
            // The user has already reached their limit of this kind of page.
            $limit_warning = sprintf(
                _("You have already reached the daily page limit for %s. You can save the page as 'in progress' and save more pages after server midnight."),
                $round->id,
            );
            throw new LimitExceeded($limit_warning);
        }
    }

    $page_text = _normalize_page_text($page_text, $projectid);
    $timestamp = time();

    $sql = sprintf("
        UPDATE $projectid
        SET
            state = '$round->page_save_state',
            $round->time_column_name = UNIX_TIMESTAMP(),
            $round->text_column_name = '%s'
        WHERE image = '%s'
    ",
        DPDatabase::escape($page_text),
        DPDatabase::escape($page_name),
    );
    DPDatabase::query($sql);

    // insert into page_events table
    _log_page_event($projectid, $page_name, 'saveAsDone', $pguser, $round);
    // insert into user_project_info
    upi_set_t_latest_page_event($pguser, $projectid, $timestamp);
    // update projects table
    _project_set_t_last_page_done($projectid, $timestamp);
    page_tallies_add($round->id, $pguser, +1);

    $limit_warning = "";
    if ($round->has_a_daily_page_limit()) {
        $dpl_count = get_dpl_count_for_user_in_round($pguser, $round);
        if (($dpl_count) >= $round->daily_page_limit) {
            $limit_warning = sprintf(
                _("Your page will be saved as 'Done'. However, you have now reached the daily page limit for %s. You will be able to save more pages after server midnight."),
                $round->id
            );
        }
    }
    return ["message" => $limit_warning];
}

// this is modified from LPage.inc get_available_page_array()
// Checks out pages for the user given the specified preceding
// project restriction. Return the number of pages checked out
function check_out_pages(
    $project,
    $proj_state,
    $pguser,
    $preceding_proofer_restriction
) {
    $round = get_Round_for_project_state($proj_state);

    // Normally, pages are served in order of "page number"
    // (i.e., by the 'image' field)
    $order = 'image ASC';
    // but this can be overridden below.

    if ($project->difficulty == 'beginner' and $round->is_a_mentor_round()) {
        // For beginner projects in a mentor round,
        // we serve up pages to the mentor in a different order,
        // to facilitate mentoring:
        // 1) Mentors like to give prompt feedback.
        // 2) Mentors like to deal with all of one proofreader's pages at a time.
        //
        // We look at the save-times recorded in the mentee round for pages
        // in this project, and for each mentee, ascertain their earliest
        // such save-time, and then order the mentees according to that time.
        // (So the mentee with the "longest-waiting" page comes first.
        // In the unlikely event that two mentees have exactly the same
        // earliest-save-time (down to the second), we break the tie by
        // taking the users in alphabetical order by username.)
        //
        // Taking the mentees in the above order, we serve each one's pages
        // in 'book order' (i.e., ordered by 'image' field).
        //
        // (See http://www.pgdp.net/phpBB3/viewtopic.php?p=1027908#p1027908 and following.)
        $order = "
            (
                SELECT MIN({$round->mentee_round->time_column_name})
                FROM $project->projectid
                WHERE {$round->mentee_round->user_column_name}
                    = p.{$round->mentee_round->user_column_name}
            ),
            {$round->mentee_round->user_column_name},
            image
        ";
    }

    // The page to be retrieved must be an available page, of course.
    $restrictions = "state='{$round->page_avail_state}'";

    // Are there any other restrictions that the page must satisfy?
    // (This should maybe be a property of $round.)
    if (empty($preceding_proofer_restriction)) {
        // Nope, no other restrictions.
    } elseif ($preceding_proofer_restriction == 'not_immediately_preceding') {
        // Don't give this user a page that they worked on in the preceding round.
        // (Where "preceding" takes into account skipped rounds.)
        if ($round->round_number > 1) {
            // We need an SQL expression for the preceding proofreader.
            $preceding_proofer = "CASE";
            for ($rn = $round->round_number - 1; $rn > 0; $rn--) {
                $earlier_round = get_Round_for_round_number($rn);
                $ucn = $earlier_round->user_column_name;
                $preceding_proofer .= " WHEN LENGTH($ucn) THEN $ucn";
            }
            // What if all of the earlier rounds were skipped?
            // (It's pretty unlikely, but we should allow for it.)
            // All of the WHEN LENGTH(...) clauses will fail.
            //
            // If the CASE expr doesn't have an ELSE clause, it will yield NULL,
            // so we'll end up requiring that
            //     NULL != '$pguser'
            // which is always NULL (effectively false), so no pages will
            // satisfy the restriction, which is not what we want.
            //
            // Instead, add an ELSE clause saying that the preceding proofreader's
            // name was the empty string. So we'll end up requiring that
            //     '' != '$pguser'
            // which is always true, so any (available) page will satisfy the
            // restriction, which is what we want.
            $preceding_proofer .= " ELSE ''";
            $preceding_proofer .= " END";

            $restrictions .= " AND $preceding_proofer != '$pguser'";
        }
    } elseif ($preceding_proofer_restriction == 'not_previously_proofed') {
        // Don't give this user a page that they have worked on before.
        for ($rn = 1; $rn < $round->round_number; $rn++) {
            $earlier_round = get_Round_for_round_number($rn);
            $restrictions .= " AND {$earlier_round->user_column_name} != '$pguser'";
        }
    } else {
        // The empty/null (i.e., no restriction) case has already been dealt with.
        throw new UnexpectedError("Bad value for 'preceding_proofer_restriction': '$preceding_proofer_restriction'.");
    }

    // Change pages to checked out
    $settings = sprintf("
        state = '$round->page_out_state',
        $round->time_column_name = UNIX_TIMESTAMP(),
        $round->user_column_name = '%s'
    ",
        DPDatabase::escape($pguser),
    );

    // 'p' is referred to in the constructor for $order above
    $sql = "
        UPDATE $project->projectid as p
        SET $settings
        WHERE $restrictions
        ORDER BY $order
        LIMIT 1
    ";
    DPDatabase::query($sql);

    // return the number of rows checked out
    return DPDatabase::affected_rows();
}
