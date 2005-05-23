<?PHP

// One-time script to create 'best_tally_rank' & 'past_tallies' tables
// and populate them from 'member_stats' and 'user_teams_stats'.

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'f_dpsql.inc');
new dbConnect();

header( 'Content-type: text/plain');

// -----------------------------------------------
echo "Creating 'best_tally_rank' table...\n";

dpsql_query("
    CREATE TABLE best_tally_rank (
        tally_name   CHAR(2)          NOT NULL,
        holder_type  CHAR(1)          NOT NULL,
        holder_id    INT(6)  UNSIGNED NOT NULL,
        PRIMARY KEY (tally_name, holder_type, holder_id),

        best_rank           INT(6)           NOT NULL,
        best_rank_timestamp INT(10) UNSIGNED NOT NULL
    )
") or die("Aborting.");

// ---------------------------
echo "Extracting data from 'member_stats' to 'best_tally_rank'...\n";

$res = dpsql_query("
    SELECT DISTINCT u_id
    FROM member_stats
    WHERE rank > 0
") or die("Aborting.");

while ( list($u_id) = mysql_fetch_row($res) )
{
    dpsql_query("
        INSERT INTO best_tally_rank
        SELECT 'R*', 'U', u_id, rank, date_updated
        FROM member_stats
        WHERE u_id=$u_id AND rank>0
        ORDER BY rank ASC
        LIMIT 1
    ") or die("Aborting.");
}

// ---------------------------
echo "Extracting data from 'user_teams_stats' to 'best_tally_rank'...\n";

$res = dpsql_query("
    SELECT DISTINCT team_id
    FROM user_teams_stats
    WHERE rank > 0
") or die("Aborting.");

while ( list($team_id) = mysql_fetch_row($res) )
{
    dpsql_query("
        INSERT INTO best_tally_rank
        SELECT 'R*', 'T', team_id, rank, date_updated
        FROM user_teams_stats
        WHERE team_id=$team_id AND rank>0
        ORDER BY rank ASC
        LIMIT 1
    ") or die("Aborting.");
}

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
    SELECT date_updated, 'U', u_id, 'R*', daily_pagescompleted, total_pagescompleted, rank
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
    SELECT date_updated, 'T', team_id, 'R*', daily_page_count, total_page_count, rank
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
