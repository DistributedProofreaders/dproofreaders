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

// -----------------------------------------------------------------------------

function user_page_tally_get_interval_title( $user_page_tally )
// The spectrum of user page tallies is partitioned into intervals,
// each with a different title. (The interface refers to it as
// "rank", but that just muddies the waters.)
// Return the title for the interval containing $user_page_tally.
{
    if ($user_page_tally >= 0) {
        $rankname = _("Newbie");
    }
    if ($user_page_tally >= 26) {
        $rankname = _("Proofreader");
    }
    if ($user_page_tally >= 151) {
        $rankname = _("Ace");
    }
    if ($user_page_tally >= 401) {
        $rankname = _("Proofreader Savant");
    }
    if ($user_page_tally >= 1001) {
        $rankname = _("Master Proofreader");
    }
    if ($user_page_tally >= 2001) {
        $rankname = _("Maestro");
    }
    if ($user_page_tally >= 5001) {
        $rankname = _("Connoisseur");
    }
    if ($user_page_tally >= 10001) {
        $rankname = _("Rare Talent");
    }
    if ($user_page_tally >= 20001) {
        $rankname = _("Super Proofreader");
    }
    if ($user_page_tally >= 30001) {
        $rankname = _("Profound Proofreader");
    }
    if ($user_page_tally >= 40001) {
        $rankname = _("Priceless Proofreader");
    }
    if ($user_page_tally >= 50001) {
        $rankname = _("Prishan Level Proofreader");
    }
    if ($user_page_tally >= 50101) {
        $rankname = _("Prime Proofreader");
    }
    if ($user_page_tally >= 60001) {
        $rankname = _("Peerless Proofer");
    }
    if ($user_page_tally >= 70001) {
        $rankname = _("Primordial Proofer");
    }
    if ($user_page_tally >= 75001) {
        $rankname = _("Proofer Magnifico");
    }
    if ($user_page_tally >= 80001) {
        $rankname = _("Proofer Supreme");
    }
    if ($user_page_tally >= 90001) {
        $rankname = _("Prooferissimo");
    }
    if ($user_page_tally >= 99001) {
        $rankname = _("Master of Proofreading");
    }
    if ($user_page_tally >= 99501) {
        $rankname = _("Doctor of Proofreading");
    }
    if ($user_page_tally >= 100001) {
        $rankname = _("Prishan of Proofers");
    }
    if ($user_page_tally >= 101001) {
        $rankname = _("Proofer of Proofers");
    }
    if ($user_page_tally >= 110001) {
        $rankname = _("Proofer Extraordinaire");
    }
    if ($user_page_tally >= 120001) {
        $rankname = _("Presidential Proofer");
    }
    if ($user_page_tally >= 125001) {
        $rankname = _("Professor of Proofreading");
    }
    return $rankname;
}

// vim: sw=4 ts=4 expandtab
?>
