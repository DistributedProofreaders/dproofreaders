<?PHP

// One-time script to create 'past_tallies' table
// and populate it from 'member_stats' and 'user_teams_stats'.

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'f_dpsql.inc');
new dbConnect();

header( 'Content-type: text/plain');

// -----------------------------------------------
echo "Creating 'past_tallies' table...\n";

dpsql_query("
    CREATE TABLE past_tallies (
        timestamp    int(10) UNSIGNED NOT NULL,
        holder_type  CHAR(1)          NOT NULL,
        holder_id    INT(6)  UNSIGNED NOT NULL,
        tally_name   CHAR(2)          NOT NULL,
        tally_delta  INT(8)           NOT NULL,
        tally_value  INT(8)           NOT NULL,
        tally_rank   INT(6)           NOT NULL,

        PRIMARY KEY (timestamp,holder_type, holder_id, tally_name)
    )
") or die("Aborting.");

// -----------------------------------------------
echo "Copying user page-tallies from 'member_stats' table to 'past_tallies'...\n";

dpsql_query("
    INSERT INTO past_tallies
    SELECT date_updated, 'U', u_id, 'P', daily_pagescompleted, total_pagescompleted, rank
    FROM member_stats
") or die("Aborting.");

echo "Renaming 'member_stats' table...\n";
// For ease of backing out during testing,
// merely rename 'member_stats' table, rather than dropping it.
dpsql_query("
    RENAME TABLE member_stats TO member_stats_obsolete
") or die("Aborting.");

// DROP TABLE member_stats

// -----------------------------------------------
echo "Copying team page-tallies from 'user_teams_stats' table to 'past_tallies'...\n";

dpsql_query("
    INSERT INTO past_tallies
    SELECT date_updated, 'T', team_id, 'P', daily_page_count, total_page_count, rank
    FROM user_teams_stats
") or die("Aborting.");

echo "Renaming 'user_teams_stats' table...\n";
// For ease of backing out during testing,
// merely rename 'user_teams_stats' table, rather than dropping it.
dpsql_query("
    RENAME TABLE user_teams_stats TO user_teams_stats_obsolete
") or die("Aborting.");

// DROP TABLE user_teams_stats

// -----------------------------------------------

echo "Done!\n";

// vim: sw=4 ts=4 expandtab
?>
