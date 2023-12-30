<?php

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
include_once($relPath.'privacy.inc');
include_once($relPath.'User.inc');

// -----------------------------------------------------------------------------

function get_page_tally_names()
{
    $page_tally_names = [];
    foreach (Rounds::get_all() as $round) {
        $page_tally_names[$round->id] =
            sprintf(_('Pages saved-as-done in round %s'), $round->id);
    }
    return $page_tally_names;
}

// -----------------------------------------------------------------------------

function get_ELR_tallyboards()
{
    global $ELR_round;

    // The following variables assume that the site has been configured with an
    // "Entry-Level Round" (ELR): a single round where new users start out and
    // become familiar with the site.  The page-tallies from this round are
    // distinguished from others in that they are (deemed to be) a fair indicator
    // of the user's experience.

    return [
        new TallyBoard($ELR_round->id, 'U'),  // users
        new TallyBoard($ELR_round->id, 'T'),  // teams
    ];
}

// -----------------------------------------------------------------------------

/**
 * Add $amount to the user's page tally,
 * and to the page tally of each team that the user currently belongs to.
 */
function page_tallies_add($tally_name, $username, $amount)
{
    // get the user's u_id, and the teams that he/she belongs to
    $user = new User($username);

    // update page tally for user
    $user_tallyboard = new TallyBoard($tally_name, 'U');
    $user_tallyboard->add_to_tally($user->u_id, $amount);

    // update page tally for site
    $site_tallyboard = new TallyBoard($tally_name, 'S');
    $site_tallyboard->add_to_tally(1, $amount);

    // update page tally for each team
    $team_tallyboard = new TallyBoard($tally_name, 'T');
    foreach ($user->load_teams() as $team_id) {
        $team_tallyboard->add_to_tally($team_id, $amount);
    }
}

// -----------------------------------------------------------------------------

// Not actually tally-specific, but that's all it's used for.

function get_daily_average($start_time, $total)
{
    $now = time();
    $seconds_since_start = $now - $start_time;
    $days_since_start = $seconds_since_start / 86400;
    return $total / $days_since_start;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Return the user's page tally for the Entry-Level Round.
 */
function user_get_ELR_page_tally($username)
{
    [$users_ELR_page_tallyboard, ] = get_ELR_tallyboards();

    [$joined_with_user_ELR_page_tallies, $user_ELR_page_tally_column] =
        $users_ELR_page_tallyboard->get_sql_joinery_for_current_tallies('u_id');

    $sql = sprintf(
        "
        SELECT $user_ELR_page_tally_column
        FROM users $joined_with_user_ELR_page_tallies
        WHERE username='%s'
        ",
        DPDatabase::escape($username)
    );
    $res = DPDatabase::query($sql);
    $row = mysqli_fetch_row($res);

    if (!$row) {
        // No row matched username='$username'.
        // This probably shouldn't happen.
        return 0;
    }

    return (int)$row[0];
}

// -----------------------------------------------------------------------------

/**
 * Get the user's page tally neighborhood
 *
 * @param int $radius
 *   (maximum) number of neighbors (on each side) to include in
 *   the neighborhood. (It will include fewer that the maximum iff the target
 *   user is within $radius of the corresponding end of the ranked list.)
 *
 * Return the page-tally neighborhood of $username.
 * This is an array where keys are integers from the range [-$radius, +$radius],
 * indicating a user's position relative to the target user (w.r.t. page tally).
 * (So key=0 refers to the target user.)
 * For a given key, the corresponding value is a PageTally_Neighbor object
 * supplying various information about the page-tally neighbor.
 */
function user_get_page_tally_neighborhood($tally_name, $username, $radius)
{
    $user = new User($username);

    $tallyboard = new TallyBoard($tally_name, 'U');
    $nb =
        $tallyboard->get_neighborhood($user->u_id, $radius);

    $neighbors = [];
    foreach ($nb as $rel_posn => $row) {
        if (should_anonymize($row['username'], $row['u_privacy'])) {
            $neighbor_is_anonymized = true;
            $neighbor_username = null;
            $neighbor_date_joined = null;
        } else {
            $neighbor_is_anonymized = false;
            $neighbor_username = $row['username'];
            $neighbor_date_joined = $row['date_created'];
        }

        $neighbors[$rel_posn] =
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
    public function __construct($tallyboard, $is_anonymized, $username, $date_joined, $u_id, $current_page_tally, $current_page_tally_rank)
    {
        $this->tallyboard = $tallyboard;
        $this->is_anonymized = $is_anonymized;
        $this->username = $username;
        $this->date_joined = $date_joined;
        $this->u_id = $u_id;
        $this->current_page_tally = $current_page_tally;
        $this->current_page_tally_rank = $current_page_tally_rank;
    }

    public function is_anonymized()
    {
        return $this->is_anonymized;
    }
    public function get_username()
    {
        return $this->username;
    }
    public function get_date_joined()
    {
        return $this->date_joined;
    }
    public function get_u_id()
    {
        return $this->u_id;
    }
    public function get_current_page_tally()
    {
        return $this->current_page_tally;
    }
    public function get_current_page_tally_rank()
    {
        return $this->current_page_tally_rank;
    }
}

// -----------------------------------------------------------------------------

/**
 * Should we anonymize information about the given user?
 */
function should_anonymize($username, $user_privacy_setting)
{
    return !can_reveal_details_about($username, $user_privacy_setting);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$SECONDS_TO_YESTERDAY = 1 + 3 * 60 * 60;
//
// Normally, a snapshot is taken shortly after local midnight, and ascribed
// (in the past_tallies table) to a timestamp equal to precisely local midnight.
// Thus, the tally_delta stored with a timestamp of 00:00 (local) today
// actually represents (pretty much) the net effect of (local) *yesterday*,
// and so we often want to associate that delta with yesterday's date.
// Simply converting the timestamp to a local date would result in today,
// so instead we subtract $SECONDS_TO_YESTERDAY from the timestamp,
// and convert *that* to a local date, resulting in yesterday.
//
// You'd think that a value of 1 would suffice, since
// 00:00:00 today minus 1 second is 23:59:59 yesterday.
// And indeed, we used 1 for many years.
// But in March 2014, pgdp.net changed its time zone
// from Pacific (UTC-8:00) to Eastern (UTC-5:00),
// so all the old snapshots ascribed to midnight (Pacific)
// now show up as ascribed to 03:00 (Eastern).
// Therefore, on pgdp.net, it's necessary (in general)
// to subtract 3 hours and 1 second from a snapshot timestamp
// to get a timestamp that will convert to the appropriate date.
//
// Of course, 1 second is all that's necesary for the *new* snapshots
// on pgdp.net, and all that's necessary on DP sites that don't change
// their time zone, but it's important to note that subtracting
// more than necessary isn't incorrect: yesterday is normally
// at least 23 hours long, so landing anywhere in that interval
// will deliver the desired date.

// -----------------------------------------------------------------------------

/**
 * Return an object whose attributes are various useful statistics about
 * the site page tally.
 *
 * Return object attributes:
 * - curr_day_{goal,actual}
 * - prev_day_{goal,actual}
 * - curr_month_{goal,actual}
 */
function get_site_page_tally_summary($tally_name)
{
    $site_stats = new StdClass();

    // Goals

    $site_stats->curr_day_goal =
        get_site_tally_goal_summed(
            $tally_name,
            "date = CURRENT_DATE"
        );

    $site_stats->prev_day_goal =
        get_site_tally_goal_summed(
            $tally_name,
            "date = (CURRENT_DATE - INTERVAL 1 DAY)"
        );

    $site_stats->curr_month_goal =
        get_site_tally_goal_summed(
            $tally_name,
            "YEAR(date) = YEAR(CURRENT_DATE) AND
            MONTH(date) = MONTH(CURRENT_DATE)"
        );

    // Actuals

    $tallyboard = new TallyBoard($tally_name, 'S');

    $holder_id = 1;

    $current_tally = $tallyboard->get_current_tally($holder_id);

    $snapshot_info = $tallyboard->get_info_from_latest_snapshot($holder_id);

    $site_stats->curr_day_actual =
        $current_tally - $snapshot_info['tally_value'];

    $site_stats->prev_day_actual = $snapshot_info['tally_delta'];

    $today = getdate();
    $site_stats->curr_month_actual =
        $tallyboard->get_delta_sum(
            $holder_id,
            mktime(0, 0, 0, $today['mon'], 1, $today['year']),
            mktime(0, 0, 0, $today['mon'] + 1, 1, $today['year'])
        )
        +
        $site_stats->curr_day_actual;

    return $site_stats;
}

function get_site_tally_goal_summed($tally_name, $date_condition)
{
    $sql = sprintf(
        "
        SELECT SUM(goal)
        FROM site_tally_goals
        WHERE tally_name = '%s' AND ($date_condition)
        ",
        DPDatabase::escape($tally_name)
    );
    $res = DPDatabase::query($sql);
    [$sum] = mysqli_fetch_row($res);
    return $sum;
}

// -----------------------------------------------------------------------------

/**
 * Return a string containing an SQL 'select' statement
 * dealing with site-specific rows from the past_tallies table,
 * and corresponding rows from the site_tally_goals table.
 */
function select_from_site_past_tallies_and_goals(
    $tally_name,
    $select,
    $where,
    $groupby,
    $orderby,
    $limit
) {
    global $SECONDS_TO_YESTERDAY;

    if (empty($where)) {
        $where_addition = '';
    } else {
        $where_addition =
            preg_replace('/^\s*WHERE\s+(.*)$/i', 'AND (\1)', $where);
    }

    $s = "
        $select
        FROM past_tallies
            LEFT OUTER JOIN site_tally_goals
            ON (past_tallies.tally_name = site_tally_goals.tally_name
                AND {date} = site_tally_goals.date
            )
        WHERE
            past_tallies.tally_name='$tally_name'
            AND holder_type='S'
            AND holder_id=1
            $where_addition
        $groupby
        $orderby
        $limit
    ";

    $date_expr = "FROM_UNIXTIME(past_tallies.timestamp - $SECONDS_TO_YESTERDAY, '%Y-%m-%d')";
    $ym_expr = "FROM_UNIXTIME(past_tallies.timestamp - $SECONDS_TO_YESTERDAY, '%Y-%m')";
    $y_expr = "FROM_UNIXTIME(past_tallies.timestamp - $SECONDS_TO_YESTERDAY, '%Y')";

    $s = preg_replace('/{date}/', $date_expr, $s);
    $s = preg_replace('/{year_month}/', $ym_expr, $s);
    $s = preg_replace('/{year}/', $y_expr, $s);

    return $s;
}

// -----------------------------------------------------------------------------

/**
 * Return an array of the daily deltas in the holder's page tally for the past $days_back days,
 * where $days_back is either the string "all" or a positive integer.
 *
 * In the returned array, each key is a date-string of the form "YYYY-MM-DD".
 */
function get_pages_per_day_for_past_n_days($tally_name, $holder_type, $holder_id, $days_back)
{
    global $SECONDS_TO_YESTERDAY;

    if ($days_back == "all") {
        $min_timestamp = 0;
    } else {
        $min_timestamp = time() - ($days_back * 86400);
    }

    $tallyboard = new TallyBoard($tally_name, $holder_type);
    $deltas = $tallyboard->get_deltas($holder_id, $min_timestamp);

    $pages_per_day = [];
    foreach ($deltas as $timestamp => $tally_delta) {
        $date = date("Y-m-d", $timestamp - $SECONDS_TO_YESTERDAY);
        $pages_per_day[$date] = $tally_delta;
    }

    return $pages_per_day;
}
