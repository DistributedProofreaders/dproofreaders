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
    $page_tally_names['R*'] = _('Pages saved-as-done in old rounds R1+R2');
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
 * @param string $tally_name
 * @param string $username
 * @param int $radius
 *   (maximum) number of neighbors (on each side) to include in
 *   the neighborhood. (It will include fewer than the maximum iff the target
 *   user is within $radius of the corresponding end of the ranked list.)
 *
 * Return the page-tally neighborhood of $username.
 * This is an array where keys are integers from the range [-$radius, +$radius],
 * indicating a user's position relative to the target user (w.r.t. page tally).
 * (So key=0 refers to the target user.)
 * For a given key, the corresponding value is a PageTally_Neighbor object
 * supplying various information about the page-tally neighbor.
 *
 * @return PageTally_Neighbor[]
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
    private bool $is_anonymized;
    private ?string $username;
    private ?int $date_joined;
    private int $u_id;
    private int $current_page_tally;
    private int $current_page_tally_rank;

    public function __construct($is_anonymized, $username, $date_joined, $u_id, $current_page_tally, $current_page_tally_rank)
    {
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
// Of course, 1 second is all that's necessary for the *new* snapshots
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
            "date >= DATE_FORMAT(CURRENT_DATE, '%%Y-%%m-01') AND
            date <= LAST_DAY(CURRENT_DATE)"
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
 * Return tally_delta sums over a timeframe grouped into predefined date units.
 *
 * The multi-dimensional array returned contains a row for every time unit:
 * ```
 * [
 *     [dateunit, tally_delta, goal],
 *     [dateunit, tally_delta, goal],
 *     ...
 * ]
 * ```
 *
 * @param string $tally_name
 *   Tally name
 * @param string $grouped_into
 *   Which date format to group into, one of: date, year_month, year
 * @param ?int $min_timestamp
 *   Earliest timestamp to evaluate
 * @param ?int $max_timestamp
 *   Latest timestamp to evaluate
 * @param ?int $limit
 *   Limit the number of rows to return
 * @param ?string $sort
 *   Field to sort on, one of: dateunit, tally_delta, goal
 *
 * @return array
 */
function get_site_tally_grouped(
    string $tally_name,
    string $grouped_into,
    ?int $min_timestamp = null,
    ?int $max_timestamp = null,
    ?int $limit = null,
    ?string $sort = null
): array {
    global $SECONDS_TO_YESTERDAY;

    if ($grouped_into == "date") {
        $date_format = "%Y-%m-%d";
    } elseif ($grouped_into == "year_month") {
        $date_format = "%Y-%m";
    } elseif ($grouped_into == "year") {
        $date_format = "%Y";
    } else {
        throw new InvalidArgumentException("\$grouped_into is an invalid value");
    }

    $goal_where_time = "1 ";
    $tally_where_time = "1 ";
    if (isset($min_timestamp)) {
        $goal_where_time .= sprintf("AND UNIX_TIMESTAMP(date) >= %d ", $min_timestamp + $SECONDS_TO_YESTERDAY);
        $tally_where_time .= sprintf("AND timestamp >= %d ", $min_timestamp + $SECONDS_TO_YESTERDAY);
    }
    if (isset($max_timestamp)) {
        $goal_where_time .= sprintf("AND UNIX_TIMESTAMP(date) < %d ", $max_timestamp + $SECONDS_TO_YESTERDAY);
        $tally_where_time .= sprintf("AND timestamp < %d ", $max_timestamp + $SECONDS_TO_YESTERDAY);
    }

    if (!$sort) {
        $sort = "dateunit asc";
    }
    if (stripos($sort, " ") !== false) {
        [$sort_column, $sort_direction] = explode(" ", $sort);
    } else {
        $sort_column = $sort;
        $sort_direction = "asc";
    }
    if (!in_array($sort_column, ["dateunit", "tally_delta", "goal"])) {
        throw new InvalidArgumentException("Invalid \$sort column");
    }
    if (!in_array(strtolower($sort_direction), ["asc", "desc"])) {
        throw new InvalidArgumentException("Invalid \$sort direction`");
    }
    $sort_direction = $sort_direction == strtolower("asc") ? SORT_ASC : SORT_DESC;

    // get all the goals
    $sql = sprintf(
        "
        SELECT DATE_FORMAT(date, '%s') as dateunit, sum(goal) as goal
        FROM site_tally_goals
        WHERE
            $goal_where_time
            AND tally_name = '%s'
        GROUP BY dateunit
        ",
        $date_format,
        $tally_name
    );
    $result = DPDatabase::query($sql);
    $dataset = [];
    while ([$dateunit, $goal] = mysqli_fetch_row($result)) {
        $dataset[$dateunit] = [
            "dateunit" => $dateunit,
            "tally_delta" => 0,
            "goal" => $goal,
        ];
    }

    // get the tally deltas
    $sql = sprintf(
        "
        SELECT FROM_UNIXTIME(timestamp - %d, '%s') as dateunit, sum(tally_delta) as tally_delta
        FROM past_tallies
        WHERE
            $tally_where_time
            AND past_tallies.tally_name = '%s'
            AND holder_type = 'S'
            AND holder_id = 1
        GROUP BY dateunit
        ",
        $SECONDS_TO_YESTERDAY,
        $date_format,
        $tally_name
    );
    $result = DPDatabase::query($sql);
    while ([$dateunit, $tally_delta] = mysqli_fetch_row($result)) {
        // this shouldn't happen, but check for it anyway
        if (!isset($dataset[$dateunit])) {
            $dataset[$dateunit] = [
                "dateunit" => $dateunit,
                "goal" => 0,
            ];
        }
        $dataset[$dateunit]["tally_delta"] = $tally_delta;
    }

    // now we need to sort and enforce our limit
    array_multisort(array_column($dataset, $sort_column), $sort_direction, $dataset);

    $rows = [];
    foreach ($dataset as $data) {
        $rows[] = [$data["dateunit"], $data["tally_delta"], $data["goal"] ?? 0];
    }

    if ($limit) {
        return array_slice($rows, 0, $limit);
    }

    return $rows;
}

/**
 * Return either the cumulative or the incremental daily page stats for
 * a site tally with optional simple moving average for the incremental.
 *
 * @param string $tally_name
 *   Tally name
 * @param string $c_or_i
 *   One of: cumulative, increment
 * @param int $start_timestamp
 *   Earliest timestamp to evaluate
 * @param int $end_timestamp
 *   Latest timestamp to evaluate
 * @param int $days_to_average
 *   If using 'increment', number of days to calculate simple moving average
 *
 * @return array
 */
function get_site_tally_cumulative_or_incremental(
    string $tally_name,
    string $c_or_i,
    int $start_timestamp,
    int $end_timestamp,
    int $days_to_average = 21
): array {
    global $SECONDS_TO_YESTERDAY;

    // get_site_tally_grouped() handles the ascribed-vs-actual time difference
    $data = get_site_tally_grouped($tally_name, 'date', $start_timestamp, $end_timestamp);
    $datax = array_column($data, 0);
    $datay1 = array_column($data, 1);
    $datay2 = array_column($data, 2);

    if ($c_or_i == 'cumulative') {
        $datay1 = array_accumulate($datay1);
        $datay2 = array_accumulate($datay2);

        // The accumulated 'actual' for today and subsequent days is bogus,
        // so delete it.
        $date_today = date('Y-m-d');
        for ($i = 0; $i < count($datax); $i++) {
            if ($datax[$i] >= $date_today) {
                unset($datay1[$i]);
            }
        }
    }

    if (empty($datay1)) {
        $datay1[0] = 0;
    }


    // Calculate a simple moving average for 'increments' graphs
    if ($c_or_i == 'increments') {
        // To calculate a simple moving average over $days_to_average we need
        // to have at least that many days worth of data before $start_timestamp.
        $sma_start_timestamp = max($start_timestamp - ($days_to_average * 60 * 60 * 24), 0);
        $sma_end_timestamp = $end_timestamp + 60 * 60 * 24;

        $site_tallyboard = new TallyBoard($tally_name, 'S');
        $deltas = $site_tallyboard->get_deltas(1, $sma_start_timestamp, $sma_end_timestamp);

        $smas = calculate_tally_sma($deltas, $days_to_average);

        // store the results in a date-based array we can use to populate the
        // graph's data array; we need to account for ascribed-vs-actual diff
        foreach ($smas as $timestamp => $average) {
            $average_lookup[date("Y-m-d", $timestamp - $SECONDS_TO_YESTERDAY)] = $average;
        }

        // pull out the smas just for the dates in the data we're going to return
        $moving_average = [];
        foreach ($datax as $date) {
            $moving_average[] = $average_lookup[$date] ?? 0;
        }
    } else {
        $moving_average = null;
    }

    // if no data was returned from the SELECT, create an empty dataset
    if (empty($datax)) {
        // set arrays to empty before populating them
        $datax = $datay1 = [];
        $datay2 = null;

        // don't go past the current date
        $end_timestamp = min($end_timestamp, time());

        // iterate through the days of the specified month
        for ($dateTimestamp = $start_timestamp; $dateTimestamp <= $end_timestamp; $dateTimestamp += (60 * 60 * 24)) {
            $datax[] = date('Y-m-d', $dateTimestamp);
            $datay1[] = 0;
        }
    }

    return [$datax, $datay1, $datay2, $moving_average];
}

/**
 * Given an array of numbers and a window, calculate the moving average over
 * the window.
 */
function calculate_tally_sma(array $array, int $window): array
{
    // We calculate the simple moving average from the $window of days leading
    // up to, and including, a given date. If there are fewer than $window days
    // before the date, we average the available data up to $window.
    $timestamps = array_keys($array);
    $values = array_values($array);
    $results = [];
    for ($i = 0; $i < count($values); $i++) {
        $start_range = max($i - ($window - 1), 0);
        $length = min($window, $i + 1);
        $results[$timestamps[$i]] = array_sum(array_slice($values, $start_range, $length)) / $length;
    }
    return $results;
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

    // skip initial zeros on "all" days back
    if ($min_timestamp == 0) {
        foreach (array_keys($pages_per_day) as $key) {
            if ($pages_per_day[$key] == 0) {
                unset($pages_per_day[$key]);
            } else {
                break;
            }
        }
    }

    return $pages_per_day;
}
