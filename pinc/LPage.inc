<?php

include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'send_mail.inc');
include_once($relPath.'daily_page_limit.inc'); // get_dpl_count_for_user_in_round

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Returns the best page entry for the user given the specified preceding
 * project restriction.
 *
 * If successful, it returns [ $imagename, $state ],
 * on failure it returns NULL;
 */
function get_available_page_array($project, $round, $pguser, $preceding_proofer_restriction)
{
    // Normally, pages are served in order of "page number"
    // (i.e., by the 'image' field)
    $order = 'image ASC';
    // but this can be overridden below.

    if ($project->difficulty == 'beginner' and $round->is_a_mentor_round()) {
        // For beginner projects in a mentor round,
        // we serve up pages in a different order,
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
        throw new ValueError("Bad value for 'preceding_proofer_restriction': '$preceding_proofer_restriction'.");
    }

    // Find page to be proofread.
    // (It appears that a simultaneous query from someone else would return the same page.)
    $dbQuery = "
        SELECT image, state
        FROM $project->projectid AS p
        WHERE $restrictions
        ORDER BY $order
        LIMIT 1
    ";
    $result = DPDatabase::query($dbQuery);
    $npage = mysqli_fetch_assoc($result);
    if (!$npage) {
        return null;
    }

    return [$npage['image'], $npage['state']];
}

/**
 * Returns an LPage, unless no page is available,
 * in which case returns NULL and sets $err.
 */
function get_available_page($projectid, $proj_state, $pguser, &$err)
{
    // can throw NonexistentProjectException
    $project = new Project($projectid);

    // Make sure project is still in same state.
    $err = project_continuity_test($project, $proj_state, true);
    if ($err) {
        return null;
    }

    $round = get_Round_for_project_state($proj_state);

    try {
        [$imagefile, $state] = get_available_proof_page_array($project, $round, $pguser);
    } catch (ProjectException | UserAccessException $exception) {
        $err = $exception->getMessage();
        return null;
    }
    $lpage = new LPage($project->projectid, $imagefile, $state, 0);

    $lpage->checkout($pguser);

    $err = null;
    return $lpage;
}

/**
 * Returns an array [image, state] unless no page is available,
 * in which case throw an exception.
 */
function get_available_proof_page_array($project, $round, $pguser)
{
    global $preceding_proofer_restriction;

    validate_user_can_get_pages_in_project($pguser, $project, $round);

    // If the global setting is to not serve the immediately preceding page,
    // first see if we can serve them a page they haven't at all seen before,
    // then fall back to not the immediately preceding page.
    if ($preceding_proofer_restriction == 'not_immediately_preceding') {
        $page_array = get_available_page_array($project, $round, $pguser, 'not_previously_proofed');
    }

    // If that failed, try again with the global setting. This is also the
    // common case if $preceding_proofer_restriction != not_immediately_preceding
    if (!$page_array) {
        $page_array = get_available_page_array($project, $round, $pguser, $preceding_proofer_restriction);
    }
    if (!$page_array) {
        throw new ProjectNoMorePagesException(_("The project has no more pages available for proofreading in this round."));
    }

    return $page_array;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Returns an LPage, unless the parameters do not correctly identify a page,
 * in which case it throws an exception.
 */
function get_indicated_LPage(
    $projectid,
    $proj_state,
    $imagefile,
    $page_state,
    $reverting_to_orig
) {
    // Make sure project is still in same state.
    // can throw NonexistentProjectException
    $project = new Project($projectid);

    $err = project_continuity_test($project, $proj_state, false);
    if ($err) {
        throw new ProjectException($err);
    }

    $sql = "
        SELECT state
        FROM $projectid
        WHERE image='$imagefile'
    ";
    $res = DPDatabase::query($sql);

    $row = mysqli_fetch_row($res);
    if (!$row) {
        // This should only happen if they've been tweaking URLs.
        // Or if the page has actually been deleted.
        $err = _('No such page.');
        throw new ProjectPageException($err);
    }

    [$current_page_state] = $row;

    if ($page_state != $current_page_state) {
        $err = sprintf(
            _('Page %1$s has changed state from %2$s to %3$s, so your request is invalid.'),
            $imagefile,
            $page_state,
            $current_page_state
        );
        throw new ProjectPageException($err);
    }

    return new LPage($projectid, $imagefile, $page_state, $reverting_to_orig);
}

function project_continuity_test($project, $orig_state, $no_more_pages)
{
    $curr_state = $project->state;
    if ($curr_state != $orig_state) {
        $orig_state_text = project_states_text($orig_state);
        $curr_state_text = project_states_text($curr_state);
        $err = sprintf(
            _("This project is no longer '%1\$s', it is now '%2\$s', so your request is no longer valid."),
            $orig_state_text,
            $curr_state_text
        );

        if ($no_more_pages) {
            $err .= "\n" . _("The project has no more pages available for proofreading in that round.");
        }
        return $err;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Logical page
 *
 * The "L" stands for "Logic", as in the 3 tiers of Data, Logic, and Presentation.
 * For pages, the Data layer is `pinc/DPage.inc`,
 * and the Presentation layer is most of `tools/proofers/*`.
 * However, we're not very strict about the divisions yet.
 */
class LPage
{
    public function __construct($projectid, $imagefile, $page_state, $reverting_to_orig)
    {
        $this->projectid = $projectid;
        $this->imagefile = $imagefile;
        $this->page_state = $page_state;
        $this->reverting_to_orig = $reverting_to_orig;

        // It's debatable whether LPage should have a 'reverting_to_orig' property.
        // You could argue that it's an interface-level artifact,
        // and thus belongs in PPage.

        $this->round = get_Round_for_page_state($page_state);
    }

    // -------------------------------------------------------------------------

    public function can_be_reverted_to_last_save()
    {
        return ($this->reverting_to_orig == 1
            || $this->page_state == $this->round->page_save_state
            || $this->page_state == $this->round->page_temp_state
        );
    }

    public function get_text()
    {
        if ($this->reverting_to_orig == 1
            || $this->page_state == $this->round->page_avail_state
            || $this->page_state == $this->round->page_out_state) {
            $desired_column_name = $this->round->prevtext_column_name;
        } else {
            $desired_column_name = $this->round->text_column_name;
        }

        $sql = "
            SELECT $desired_column_name
            FROM {$this->projectid}
            WHERE image='{$this->imagefile}'
        ";
        $res = DPDatabase::query($sql);
        [$page_text] = mysqli_fetch_row($res);

        return $page_text;
    }

    public function get_language()
    {
        $project = new Project($this->projectid);
        return langcode2_for_langname($project->languages[0]);
    }

    public function get_username_for_round($round)
    {
        $res = DPDatabase::query("
            SELECT {$round->user_column_name}
            FROM {$this->projectid}
            WHERE image = '{$this->imagefile}'
        ");
        $row = mysqli_fetch_assoc($res);
        return $row[$round->user_column_name];
    }

    // -------------------------------------------------------------------------

    public function checkout($user)
    {
        $this->page_state =
            Page_checkout($this->projectid, $this->imagefile, $this->round, $user);
    }

    public function saveAsInProgress($page_text, $user)
    {
        $this->page_state = Page_saveAsInProgress($this->projectid, $this->imagefile, $this->round, $user, $page_text);
        $this->reverting_to_orig = 0;
    }

    /**
     * Attempt to save a page as done
     *
     * Return an array containing two values:
     * - a boolean indicating whether the page was saved,
     * - a boolean indicating whether user has reached the round's daily page limit.
     *
     * Note that `[FALSE, FALSE]` is currently impossible:
     * - if this function says that the page was not saved,
     * - it can only be because the user already reached the daily page limit.
     */
    public function attemptSaveAsDone($text_data, $pguser)
    {
        if ($this->round->has_a_daily_page_limit()) {
            $pre_save_dpl_count = get_dpl_count_for_user_in_round($pguser, $this->round);
            if ($pre_save_dpl_count >= $this->round->daily_page_limit) {
                // The user has already reached their limit of this kind of page.
                return [false, true];
            }
        }

        $this->page_state =
            Page_saveAsDone($this->projectid, $this->imagefile, $this->round, $pguser, $text_data);

        // add to user page count
        page_tallies_add($this->round->id, $pguser, +1);

        $dpl_has_now_been_reached = (
            $this->round->has_a_daily_page_limit()
            &&
            ($pre_save_dpl_count + 1 >= $this->round->daily_page_limit)
        );
        return [true, $dpl_has_now_been_reached];
    }

    public function returnToRound($pguser)
    {
        $this->page_state =
            Page_returnToRound($this->projectid, $this->imagefile, $this->round, $pguser);
    }

    public function revertToOriginal()
    {
        $this->reverting_to_orig = 1;
    }

    public function revertToSaved()
    {
        $this->page_state = $this->round->page_temp_state;
        $this->reverting_to_orig = 0;
    }

    /**
     * Return TRUE if this report causes project to be marked bad.
     */
    public function markAsBad($user, $reason)
    {
        global $code_url, $PAGE_BADNESS_REASONS;

        Page_markAsBad($this->projectid, $this->imagefile, $this->round, $user, $reason);

        // Advise PM that the page has been marked bad.
        $project = new Project($this->projectid);
        configure_gettext_for_user($project->username);
        $body_blurb_messages[] = sprintf(_('Page %1$s of this project has been marked bad due to %2$s.'), $this->imagefile, $PAGE_BADNESS_REASONS[$reason]);
        // TRANSLATORS: %s is a url
        $body_blurb_messages[] = sprintf(
            _("Please visit %s to make any needed changes and make the page available for proofreading again."),
            "$code_url/tools/project_manager/handle_bad_page.php?projectid={$this->projectid}&image={$this->imagefile}"
        );
        $body_blurb_messages[] = _("Until this report has been resolved, the project will not be able to leave the current round.");
        $body_blurb_messages[] = _("If 10 pages are marked bad by at least 3 different users, the project will automatically be made unavailable.");
        $body_blurb = implode("\n\n", $body_blurb_messages);
        send_mail_project_manager($project, $body_blurb, _("Bad Page Report"));
        configure_gettext_for_user();

        // Now determine whether the project as a whole should be marked bad.
        $project = new Project($this->projectid);
        if (!$project->is_bad_from_pages($this->round)) {
            return false;
        }

        $error_msg = project_transition($this->projectid, $this->round->project_bad_state, PT_AUTO);
        if ($error_msg) {
            echo "$error_msg<br>\n";
        }
        return true;
    }

    // -----------------------------------------------------------------------------

    public function resume_saved_page($pguser)
    {
        if ($this->page_state == $this->round->page_save_state) {
            // Page comes from DONE.

            // no need to update text, just record state change
            $this->page_state = Page_reopen($this->projectid, $this->imagefile, $this->round, $pguser);

            // When it was saved, the user's page-count was incremented.
            // Now they are 'unsaving' it, so decrement their page-count.
            // They'll get it back if/when they save-as-done again.
            // (Plugs a former page-count cheat.)

            page_tallies_add($this->round->id, $pguser, -1);
        } else {
            // Page comes from IN PROGRESS.

            // Resuming such a page has no actual effect on the page,
            // though it might in the future.

            // For now, just confirm that the user is entitled to resume
            // this page. (In the DONE case, Page_reopen performs this check.)

            $res = DPDatabase::query("
                SELECT {$this->round->user_column_name}
                FROM $this->projectid
                WHERE image='$this->imagefile'
            ");
            [$current_round_user] = mysqli_fetch_row($res);

            if ($pguser != $current_round_user) {
                $err = sprintf(
                    _('You (%1$s) do not have the necessary access to page %2$s'),
                    $pguser,
                    $this->imagefile
                );
                throw new ProjectPageException($err);
            }
        }
    }
} // end class
