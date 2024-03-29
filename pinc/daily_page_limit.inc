<?php

// This include file is dedicated to the "Daily Page Limit" (DPL) feature:
// each round can optionally impose a daily limit on the
// number of pages that each user can save-as-done in a server day.
//
// (More precisely, the limit is on the *net* number of pages saved-as-done;
// reopens and clears decrement the count. This is the same quantity tracked
// by the page-tally system, so we just consult it for the user's "dpl count".)

// There's only one function here because the rest of the implementation
// of the feature fits fairly well elsewhere.
//
// Limits are set in invocations of the Round constructor in stages.inc.
//
// Limits are consulted and imposed at 2 points:
// - on the project page:
//   See decide_blurbs() in project.php.
//
// - in the proofing interface:
//   See LPage::attemptSaveAsDone() in LPage.inc
//   and PPage::attempt_to_save_as_done() in PPage.inc

include_once($relPath.'User.inc');
include_once($relPath.'TallyBoard.inc');

/**
 * Get the current daily page limit count for the user in a round
 *
 * The current "dpl count" for a user in a given round is the delta in their
 * page-tally for that round since the last snapshot.
 * (i.e., the net number of save-as-dones in that round today.)
 */
function get_dpl_count_for_user_in_round($username, $round)
{
    $user = new User($username);

    $round_tallyboard = new TallyBoard($round->id, 'U');

    return $round_tallyboard->get_delta_since_latest_snapshot($user->u_id);
}
