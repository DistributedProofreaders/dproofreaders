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

// vim: sw=4 ts=4 expandtab
?>
