<?PHP

// There are two different ways of counting saved-as-done pages in the DP code:
//
// 1) As each page is saved, increment a counter associated with the user who
//    saved the page (and decrement it if the page is un-saved).
// 2) Look in each projectID* table for pages saved in a given interval.
//
// Lacking concise terminology for this distinction, I propose the term
// "page tally" for (1), and leave "page count" for (2).
//
// This file is for code dealing with page tallies.

include_once($relPath.'TallyBoard.inc');
include_once($relPath.'RoundDescriptor.inc');

// -----------------------------------------------------------------------------

$page_tally_names = array();
foreach ( $PRD_for_round_id_ as $prd )
{
    $page_tally_names[$prd->round_id] =
        sprintf( _('Pages saved-as-done in round %s'), $prd->round_id );
}

// -----------------------------------------------------------------------------

// The following variables assume that the site has been configured with an
// "Entry-Level Round" (ELR): a single round where new users start out and
// become familiar with the site.  The page-tallies from this round are
// distinguished from others in that they are (deemed to be) a fair indicator
// of the user's experience.

// This assignment particularly assumes that the ELR is the first round
// defined in RoundDescriptor.inc.
$ELR_prd = get_PRD_for_round(1);

$users_P_page_tallyboard = new TallyBoard( $ELR_prd->round_id, 'U' );
$teams_P_page_tallyboard = new TallyBoard( $ELR_prd->round_id, 'T' );

// See TallyBoard.inc for explanation of these two strings:
//
list($joined_with_user_P_page_tallies,$user_P_page_tally_column) =
    $users_P_page_tallyboard->get_sql_joinery_for_current_tallies( 'u_id' );

// -----------------------------------------------------------------------------

function page_tallies_add( $tally_name, $username, $amount )
// Add $amount to the user's page tally,
// and to the page tally of each team that the user currently belongs to.
{
    // get the user's u_id, and the teams that he/she belongs to
    $result = mysql_query("
        SELECT u_id, team_1, team_2, team_3
        FROM users
        WHERE username = '$username'
    ");
    list($u_id, $team_1, $team_2, $team_3) = mysql_fetch_row($result);

    // update page tally for user
    $user_tallyboard = new TallyBoard( $tally_name, 'U' );
    $user_tallyboard->add_to_tally( $u_id, $amount );

    // update page tally for each team
    $team_tallyboard = new TallyBoard( $tally_name, 'T' );
    // (The 'array_unique' shouldn't be necessary, but just in case.)
    foreach (array_unique(array(1, $team_1, $team_2, $team_3)) as $team_id)
    {
        if ( $team_id != 0 )
        {
            $team_tallyboard->add_to_tally( $team_id, $amount );
        }
    }
}

// -----------------------------------------------------------------------------

function get_daily_average( $start_time, $total )
// Not actually tally-specific, but that's all it's used for.
{
    $now = time();
    $seconds_since_start = $now - $start_time;
    $days_since_start = $seconds_since_start / 86400;
    return $total / $days_since_start;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function user_get_page_tally( $username )
// Return the user's page tally.
{
    global $joined_with_user_P_page_tallies, $user_P_page_tally_column;

    $res = mysql_query("
        SELECT $user_P_page_tally_column
        FROM users $joined_with_user_P_page_tallies
        WHERE username='$username'
    ") or die(mysql_error());

    if ( mysql_num_rows($res) == 0 )
    {
        // No row matched username='$username'.
        // This probably shouldn't happen.
        return 0;
    }

    return mysql_result($res,0);
}

// -----------------------------------------------------------------------------

function user_page_tally_get_interval_title( $user_page_tally )
// The spectrum of user page tallies is partitioned into intervals,
// each with a different title. (The interface refers to it as
// "rank", but that just muddies the waters.)
// Return the title for the interval containing $user_page_tally.
{
    $thresholds = array(
        125001 => _("Professor of Proofreading"),
        120001 => _("Presidential Proofer"),
        110001 => _("Proofer Extraordinaire"),
        101001 => _("Proofer of Proofers"),
        100001 => _("Prishan of Proofers"),
         99501 => _("Doctor of Proofreading"),
         99001 => _("Master of Proofreading"),
         90001 => _("Prooferissimo"),
         80001 => _("Proofer Supreme"),
         75001 => _("Proofer Magnifico"),
         70001 => _("Primordial Proofer"),
         60001 => _("Peerless Proofer"),
         50101 => _("Prime Proofreader"),
         50001 => _("Prishan Level Proofreader"),
         40001 => _("Priceless Proofreader"),
         30001 => _("Profound Proofreader"),
         20001 => _("Super Proofreader"),
         10001 => _("Rare Talent"),
          5001 => _("Connoisseur"),
          2001 => _("Maestro"),
          1001 => _("Master Proofreader"),
           401 => _("Proofreader Savant"),
           151 => _("Ace"),
            26 => _("Proofreader"),
             0 => _("Newbie"),
    );

    foreach ( $thresholds as $threshold => $title )
    {
        if ($user_page_tally >= $threshold)
        {
            return $title;
        }
    }
}

// -----------------------------------------------------------------------------

function user_get_page_tally_neighborhood( $tally_name, $username, $radius )
//
// $radius is the (maximum) number of neighbors (on each side) to include in
// the neighborhood. (It will include fewer that the maximum iff the target
// user is within $radius of the corresponding end of the ranked list.)
//
// Return the page-tally neighborhood of $username.
//    This is an array:
//    The keys are integers from the range [-$radius, +$radius],
//    indicating a user's position relative to the target user (w.r.t. page tally).
//    (So key=0 refers to the target user.)
//    For a given key, the corresponding value is a PageTally_Neighbor object
//    supplying various information about the page-tally neighbor.
{
    $result = mysql_query("
        SELECT u_id
        FROM users
        WHERE username='$username'
    ");
    if ( mysql_num_rows($result) == 0 )
    {
        // The target user does not appear in the 'users' table.
        // This shouldn't ever happen.
        die( "User '$username' does not appear in the 'users' table." );
    }
    else if ( mysql_num_rows($result) > 1 )
    {
        // This shouldn't ever happen either.
        die( "username='$username' matched more than one row in 'users' table." );
    }
    list($u_id) = mysql_fetch_row($result);

    $tallyboard = new TallyBoard( $tally_name, 'U' );
    $nb =
        $tallyboard->get_neighborhood(
            $u_id, $radius,
            'users',
            'u_id',
            'username, u_privacy, date_created, u_id',
            'current_tally',
            'current_rank'
        );

    $neighbors = array();
    foreach ( $nb as $rel_posn => $row )
    {
        if ( should_anonymize( $row['username'], $row['u_privacy'] ) )
        {
            $neighbor_is_anonymized = TRUE;
            $neighbor_username = NULL;
            $neighbor_date_joined = NULL;
        }
        else
        {
            $neighbor_is_anonymized = FALSE;
            $neighbor_username = $row['username'];
            $neighbor_date_joined = $row['date_created'];
        }

        $neighbors[$rel_posn] =&
            new PageTally_Neighbor(
                $tallyboard,
                $neighbor_is_anonymized,
                $neighbor_username,
                $neighbor_date_joined,
                $row['u_id'],
                $row['current_tally'],
                $row['current_rank']
            );
    }

    return $neighbors;
}

class PageTally_Neighbor
{
    function PageTally_Neighbor( $tallyboard, $is_anonymized, $username, $date_joined, $u_id, $current_page_tally, $current_page_tally_rank )
    {
        $this->tallyboard = $tallyboard;
        $this->is_anonymized = $is_anonymized;
        $this->username = $username;
        $this->date_joined = $date_joined;
        $this->u_id = $u_id;
        $this->current_page_tally = $current_page_tally;
        $this->current_page_tally_rank = $current_page_tally_rank;
    }

    function is_anonymized()               { return $this->is_anonymized; }
    function get_username()                { return $this->username; }
    function get_date_joined()             { return $this->date_joined; }
    function get_u_id()                    { return $this->u_id; }
    function get_current_page_tally()      { return $this->current_page_tally; }
    function get_current_page_tally_rank() { return $this->current_page_tally_rank; }

    function get_latest_saved_page_tally_rank()
    {
        $info = $this->tallyboard->get_info_from_latest_snapshot($this->u_id);

        return $info['tally_rank'];
    }
}

// -----------------------------------------------------------------------------

function should_anonymize( $username, $user_privacy_setting )
// Should we anonymize information about the given user?
{
    global $pguser; // the "requestor"

    // The given user's details should be visible to whom?
    switch ($user_privacy_setting)
    {
        case PRIVACY_PUBLIC:
            // Visible to all.
            // Never anonymize.
            return FALSE;

        case PRIVACY_PRIVATE:
            // Visible to anyone logged in.
            // Anonymize if the requestor is not logged in.
            return is_null($pguser);

        case PRIVACY_ANONYMOUS:
            // Visible to the user him/herself only.
            // Anonymize if the requestor is not logged in,
            // or is someone other than the given user.
            return is_null($pguser) || $pguser != $username;

        default:
            // Shouldn't happen.
            die( "should_anonymize(): bad privacy setting: '$user_privacy_setting'" );
    }
}

// vim: sw=4 ts=4 expandtab
?>
