<?php
include_once($relPath.'User.inc');
include_once($relPath.'TallyBoard.inc');

function get_dpl_count_for_user_in_round($username, $round)
// The current "dpl count" for a user in a given round
// is the delta in their page-tally for that round
// since the last snapshot.
// (i.e., the net number of save-as-dones in that round today.)
{
    $user = new User($username);

    $round_tallyboard = new TallyBoard($round->id, 'U');

    return $round_tallyboard->get_delta_since_latest_snapshot($user->u_id);
}

// vim: sw=4 ts=4 expandtab
