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

function page_tallies_add( $username, $amount )
// Add $amount to the user's page tally,
// and to the page tally of each team that the user currently belongs to.
{
    // update page tally for user
    mysql_query("
        UPDATE users
        SET pagescompleted = pagescompleted + $amount
        WHERE username = '$username'
    ");

    // get teams that the user belongs to
    $result = mysql_query("
        SELECT team_1, team_2, team_3
        FROM users
        WHERE username = '$username'
    ");
    list($team_1, $team_2, $team_3) = mysql_fetch_row($result);

    // update page tally for each team
    mysql_query("
        UPDATE user_teams
        SET page_count = page_count + $amount
        WHERE id=1 OR id=$team_1 OR id=$team_2 OR id=$team_3
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

function user_get_page_tally( $username )
// Return the user's page tally.
{
    $res = mysql_query("
        SELECT pagescompleted
        FROM users
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
        SELECT u_id, pagescompleted
        FROM users
        ORDER BY pagescompleted DESC
    ");
    $i = 1;
    while ($row = mysql_fetch_assoc($result))
    {
        $user_id = $row['u_id'];
        if ($row['pagescompleted'] == 0) { $rankArray[$user_id] = 0; continue; }
        if ($row['pagescompleted'] == $lastcompleted)
        {
            $rankArray[$user_id] = $lastrank;
            $lastrank = $lastrank;
        }
        else
        {
            $rankArray[$user_id] = $i;
            $lastrank = $i;
        }
        $lastcompleted = $row['pagescompleted'];
        if ($i == 1) { $lastrank = 1; }
        $i++;
    }

    return $rankArray;
}

// -----------------------------------------------------------------------------

function user_get_page_tally_neighborhood( $username, $radius, $consider_privacy )
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
    assert( $radius >= 0 );

    $rankArray = array();

    $result = mysql_query("
        SELECT username, pagescompleted, u_privacy
        FROM users
        WHERE pagescompleted > 0
        ORDER BY pagescompleted DESC
    ");
    $i = 0;
    $lastcompleted = -1000; // A value that the first $row['pagescompleted'] will not be equal to.
    while ($row = mysql_fetch_assoc($result))
    {
        if ($row['username'] == $username)
        {
            $userindex = $i;
        }

        if ($row['pagescompleted'] == $lastcompleted)
        {
            $rankArray['rank'][$i] = $rankArray['rank'][$i - 1];
        }
        else
        {
            $rankArray['rank'][$i] = $i+1;
        }

        if ($consider_privacy && $row['u_privacy'] == PRIVACY_ANONYMOUS && $row['username'] != $username)
        {
            $rankArray['username'][$i] = _("Anonymous");
        }
        else
        {
            $rankArray['username'][$i] = $row['username'];
        }
        $rankArray['pages'][$i] = $row['pagescompleted'];
        $lastcompleted = $row['pagescompleted'];
        $i++;
    }

    if (!isset($userindex))
    {
        $userindex = $i;
        $rankArray['username'][$userindex] = $username;
        $rankArray['pages'][$userindex] = 0;
        $rankArray['rank'][$userindex] = $userindex+1;
        $max_current_rank = $userindex+1;
    }
    else
    {
        $max_current_rank = $rankArray['rank'][$i-1];
    }


    $neighbors = array();

    for ( $rel_posn = -$radius; $rel_posn <= $radius; $rel_posn++ )
    {
        $j = $userindex + $rel_posn;
        if (!isset($rankArray['username'][$j])) { continue; }
        $neighbors[$rel_posn] =&
            new PageTally_Neighbor(
                $rankArray['username'][$j],
                $rankArray['pages'][$j],
                $rankArray['rank'][$j]
            );
    }

    return array( $neighbors, $max_current_rank );
}

class PageTally_Neighbor
{
    function PageTally_Neighbor( $username, $current_page_tally, $current_page_tally_rank )
    {
        $this->username = $username;
        $this->current_page_tally = $current_page_tally;
        $this->current_page_tally_rank = $current_page_tally_rank;
    }

    function get_username()                { return $this->username; }
    function get_current_page_tally()      { return $this->current_page_tally; }
    function get_current_page_tally_rank() { return $this->current_page_tally_rank; }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function team_get_page_tally_rank( $team_id )
// Return the given team's rank (among all teams)
// as determined by page tallies.
{
    $result = mysql_query("SELECT id FROM user_teams WHERE id != 1 ORDER BY page_count DESC");
    $i = 1;
    while ($row = mysql_fetch_assoc($result))
    {
        if ($row['id'] == $team_id) { return $i; }
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
        SELECT id, page_count
        FROM user_teams
        WHERE id != 1
        ORDER BY page_count DESC
    ");
    $i = 1;
    while ($row = mysql_fetch_assoc($result))
    {
        $team_id = $row['id'];
        if ($row['page_count'] == $lastcompleted)
        {
            $rankArray[$team_id] = $lastrank;
            $lastrank = $lastrank;
        }
        else
        {
            $rankArray[$team_id] = $i;
            $lastrank = $i;
        }
        $lastcompleted = $row['page_count'];
        if ($i == 1) { $lastrank = 1; }
        $i++;
    }

    return $rankArray;
}

// vim: sw=4 ts=4 expandtab
?>
