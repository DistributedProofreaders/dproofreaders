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

// -----------------------------------------------------------------------------

// $users_table_with_tallies = "users";
$users_table_with_tallies = "users LEFT OUTER JOIN current_tallies ON (holder_type='U' AND holder_id=u_id AND tally_name='P')";
// A string containing an SQL table-expression that yields a table that
// has all of the data of the 'users' table, plus a page-tally column.

// $user_P_page_tally_column = "pagescompleted";
$user_P_page_tally_column = "tally_value";
// A string containing the name of the column in the above table
// that gives the user's tally of pages saved-as-done in proofing rounds.

// -----------------------------------------------------------------------------

function page_tallies_add( $username, $amount )
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
    mysql_query("
        UPDATE current_tallies
        SET tally_value = tally_value + $amount
        WHERE holder_type='U' AND holder_id='$u_id' AND tally_name='P'
    ");

    // update page tally for each team
    mysql_query("
        UPDATE current_tallies
        SET tally_value = tally_value + $amount
        WHERE holder_type='T' AND holder_id IN (1, $team_1, $team_2, $team_3) AND tally_name='P'
    ");
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

function user_initialize_current_page_tally( $u_id )
// Ensure that subsequent queries about the given (new) user's
// current page-tally will find something.
{
    mysql_query("
        INSERT INTO current_tallies
        SET
            holder_type = 'U',
            holder_id   = $u_id,
            tally_name  = 'P',
            tally_value = 0
    ") or die(mysql_error());
}

// -----------------------------------------------------------------------------

function user_get_page_tally( $username )
// Return the user's page tally.
{
    global $users_table_with_tallies, $user_P_page_tally_column;

    $res = mysql_query("
        SELECT $user_P_page_tally_column
        FROM $users_table_with_tallies
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

function users_get_page_tally_ranks()
// Returns an associative array that maps each user's id
// to his/her rank (as determined by page tallies).
{
    $rankArray = "";

    $result = mysql_query("
        SELECT holder_id, tally_value
        FROM current_tallies
        WHERE holder_type='U' AND tally_name='P'
        ORDER BY tally_value DESC
    ");
    $i = 1;
    while (list($u_id, $P_tally) = mysql_fetch_row($result))
    {
        if ($P_tally == 0) { $rankArray[$u_id] = 0; continue; }
        if ($P_tally == $latest_page_tally)
        {
            $rankArray[$u_id] = $latest_rank;
            $latest_rank = $latest_rank;
        }
        else
        {
            $rankArray[$u_id] = $i;
            $latest_rank = $i;
        }
        $latest_page_tally = $P_tally;
        if ($i == 1) { $latest_rank = 1; }
        $i++;
    }

    return $rankArray;
}

// -----------------------------------------------------------------------------

function user_get_page_tally_neighborhood( $username, $radius )
//
// $radius is the (maximum) number of neighbors (on each side) to include in
// the neighborhood. (It will include fewer that the maximum iff the target
// user is within $radius of the corresponding end of the ranked list.)
//
// Return an array consisting of:
//
// -- The page-tally neighborhood of $username.
//    This is an array:
//    The keys are integers from the range [-$radius, +$radius],
//    indicating a user's position relative to the target user (w.r.t. page tally).
//    (So key=0 refers to the target user.)
//    For a given key, the corresponding value is a PageTally_Neighbor object
//    supplying various information about the page-tally neighbor.
//    
// -- The maximum current page tally rank of any user.
{
    global $users_table_with_tallies, $user_P_page_tally_column;

    assert( $radius >= 0 );

    $rankArray = array();

    // Note that for the purposes of this function,
    // we pretend that no users with user_P_page_tally_column <= 0 exist,
    // with the possible exception of the target user.

    $result = mysql_query("
        SELECT username, date_created, u_id, $user_P_page_tally_column, u_privacy
        FROM $users_table_with_tallies
        WHERE $user_P_page_tally_column > 0 OR username='$username'
        ORDER BY $user_P_page_tally_column DESC
    ");

    $num_rows = mysql_num_rows($result);
    
    // First, run through the results,
    // looking for the row that contains the target user.
    $target_user_i = NULL;
    for ($i = 0; $i < $num_rows; $i++)
    {
        $row = mysql_fetch_row($result);
        if ( $row[0] == $username )
        {
            $target_user_i = $i;
            break;
        }
    }

    if ( is_null($target_user_i) )
    {
        // The target user does not appear in the 'users' table.
        // This shouldn't ever happen.
        die( "User '$username' does not appear in the 'users' table." );
    }

    // Now, go through the results again
    // (calculating page-tally rank as you go),
    // and when you get to the neighborhood of the target user,
    // save the results in the $neighbors array.

    mysql_data_seek( $result, 0 );

    $neighbors = array();
    $i = 0;
    $latest_page_tally = -1000; // A value that the first $row[$user_P_page_tally_column] will not be equal to.
    $latest_rank = NULL;
    while ($row = mysql_fetch_assoc($result))
    {
        if ($row[$user_P_page_tally_column] != $latest_page_tally)
        {
            $latest_page_tally = $row[$user_P_page_tally_column];
            $latest_rank = $i+1;
        }

        if ( abs( $i - $target_user_i ) <= $radius )
        {
            // We're in the neighborhood of the target user.

            $rel_posn = $i - $target_user_i;

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
                    $neighbor_is_anonymized,
                    $neighbor_username,
                    $neighbor_date_joined,
                    $row['u_id'],
                    $row[$user_P_page_tally_column],
                    $latest_rank
                );
        }

        $i++;
    }

    $max_current_rank = $latest_rank;

    return array( $neighbors, $max_current_rank );
}

class PageTally_Neighbor
{
    function PageTally_Neighbor( $is_anonymized, $username, $date_joined, $u_id, $current_page_tally, $current_page_tally_rank )
    {
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
        $result = mysql_query("
            SELECT rank
            FROM member_stats
            WHERE u_id = {$this->u_id}
            ORDER BY date_updated DESC
            LIMIT 1
        ");
        return mysql_result($result, 0, "rank");
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

// -----------------------------------------------------------------------------

function user_initialize_page_tally_history( $u_id )
// Ensure that subsequent queries about the given (new) user's page-tally history
// will find something.
{
    // We'll be inserting a record into member_stats.
    // The timestamp we use in that record should be the same as the most recent
    // timestamp used by the periodic snapshot script (mbrstats.php). (If we
    // use a more recent timestamp, we'll mislead the next run of that script.)

    $result = mysql_query("SELECT MAX(date_updated) FROM member_stats");
    $latest_time_in_table = mysql_result($result,0,0);

    if (!is_null($latest_time_in_table))
    {
        $time_for_record = $latest_time_in_table;
    }
    else
    {
        // member_stats is empty.
        // (This is presumably the very first user.)
        // The choice of timestamp is arbitrary?
        // Pick the most recent midnight.
        $now = getdate();
        $time_for_record = mktime(0,0,0,$now['mon'],$now['mday'],$now['year']);
    }

    $result = mysql_query("
        INSERT INTO member_stats
        SET
            u_id                 = $u_id,
            date_updated         = $time_for_record,
            daily_pagescompleted = 0,
            total_pagescompleted = 0,
            rank                 = 0
    ");
}

// -----------------------------------------------------------------------------

function user_get_largest_page_tally_increment_info( $u_id )
// Return an array consisting of:
// -- the given user's largest recorded page tally increment, and
// -- a timestamp for which that increment was recorded.
{
    $result = mysql_query("
        SELECT daily_pagescompleted, date_updated
        FROM member_stats
        WHERE u_id=$u_id
        ORDER BY daily_pagescompleted DESC
        LIMIT 1
    ");
    return mysql_fetch_row($result);
}

// -----------------------------------------------------------------------------

function user_get_best_page_tally_rank_info( $u_id )
{
    $result = mysql_query("
        SELECT rank, date_updated
        FROM member_stats
        WHERE u_id=$u_id
        ORDER BY rank ASC
        LIMIT 1
    ");
    return mysql_fetch_row($result);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function team_initialize_current_page_tally( $team_id )
// Ensure that subsequent queries about the given (new) team's
// current page-tally will find something.
{
    mysql_query("
        INSERT INTO current_tallies
        SET
            holder_type = 'T',
            holder_id   = $team_id,
            tally_name  = 'P',
            tally_value = 0
    ") or die(mysql_error());
}

// -----------------------------------------------------------------------------

function team_get_page_tally_rank( $team_id )
// Return the given team's rank (among all teams)
// as determined by page tallies.
{
    $result = mysql_query("
        SELECT holder_id
        FROM current_tallies
        WHERE holder_type='T' AND holder_id != 1 AND tally_name='P'
        ORDER BY tally_value DESC
    ");
    $i = 1;
    while ($row = mysql_fetch_assoc($result))
    {
        if ($row['holder_id'] == $team_id) { return $i; }
        $i++;
    }
    // assert(FALSE); ?
    return NULL;
}

// -----------------------------------------------------------------------------

function teams_get_page_tally_ranks()
// Return an associative array that maps each team's id
// to its rank (as determined by page tallies).
{
    $rankArray = "";

    $result = mysql_query("
        SELECT holder_id, tally_value
        FROM current_tallies
        WHERE holder_type='T' AND holder_id != 1 AND tally_name='P'
        ORDER BY tally_value DESC
    ");
    $i = 1;
    while (list($team_id, $P_tally) = mysql_fetch_row($result))
    {
        if ($P_tally == $latest_page_tally)
        {
            $rankArray[$team_id] = $latest_rank;
            $latest_rank = $latest_rank;
        }
        else
        {
            $rankArray[$team_id] = $i;
            $latest_rank = $i;
        }
        $latest_page_tally = $P_tally;
        if ($i == 1) { $latest_rank = 1; }
        $i++;
    }

    return $rankArray;
}

// -----------------------------------------------------------------------------

function team_get_largest_page_tally_increment_info( $team_id )
// Return an array consisting of:
// -- the given team's largest recorded page tally increment, and
// -- a timestamp for which that increment was recorded.
{
    $result = mysql_query("
        SELECT daily_page_count, date_updated
        FROM user_teams_stats
        WHERE team_id=$team_id
        ORDER BY daily_page_count DESC
        LIMIT 1
    ");
    return mysql_fetch_row($result);
}

// vim: sw=4 ts=4 expandtab
?>
