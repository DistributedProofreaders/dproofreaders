<?PHP

// One-time script to create & populate 'past_tallies' table

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'f_dpsql.inc');
new dbConnect();

// -----------------------------------------------
// Create 'past_tallies' table.

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
// Move user page-tallies from 'member_stats' table.

dpsql_query("
    INSERT INTO past_tallies
    SELECT date_updated, 'U', u_id, 'P', daily_pagescompleted, total_pagescompleted, rank
    FROM member_stats
") or die("Aborting.");

// For ease of backing out during testing,
// merely rename 'member_stats' table, rather than dropping it.
dpsql_query("
    RENAME TABLE member_stats TO member_stats_obsolete
") or die("Aborting.");

// DROP TABLE member_stats

// -----------------------------------------------
// Move team page-tallies from 'user_teams_stats' table.

dpsql_query("
    INSERT INTO past_tallies
    SELECT date_updated, 'T', team_id, 'P', daily_page_count, total_page_count, rank
    FROM user_teams_stats
") or die("Aborting.");

// For ease of backing out during testing,
// merely rename 'user_teams_stats' table, rather than dropping it.
dpsql_query("
    RENAME TABLE user_teams_stats TO user_teams_stats_obsolete
") or die("Aborting.");

// DROP TABLE user_teams_stats

// -----------------------------------------------

echo "Done!";

// vim: sw=4 ts=4 expandtab
?>
