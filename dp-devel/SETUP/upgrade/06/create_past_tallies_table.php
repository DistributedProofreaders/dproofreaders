<?PHP

// One-time script to create 'best_tally_rank' & 'past_tallies' tables
// and populate them from 'member_stats' and 'user_teams_stats'.

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'dpsql.inc');
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
        SELECT 'R*', IF(team_id=1,'S','T'), team_id, rank, date_updated
        FROM user_teams_stats
        WHERE team_id=$team_id AND rank>0
        ORDER BY rank ASC
        LIMIT 1
    ") or die("Aborting.");
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo "Creating 'past_tallies' table...\n";

dpsql_query("
    CREATE TABLE past_tallies (
        timestamp    int(10) UNSIGNED NOT NULL,
        holder_type  CHAR(1)          NOT NULL,
        holder_id    INT(6)  UNSIGNED NOT NULL,
        tally_name   CHAR(2)          NOT NULL,
        tally_delta  INT(8)           NOT NULL,
        tally_value  INT(8)           NOT NULL,

        PRIMARY KEY (tally_name, holder_type, holder_id, timestamp)
    )
") or die("Aborting.");

// -----------------------------------------------

if ( check_uniqueness( 'member_stats', 'u_id', 'date_updated' ) )
{
    echo "\n";
    echo "Copying user page-tallies from 'member_stats' table to 'past_tallies'...\n";

    dpsql_query("
        INSERT INTO past_tallies
        SELECT date_updated, 'U', u_id, 'R*', daily_pagescompleted, total_pagescompleted
        FROM member_stats
    ") or die("Aborting.");

    echo "Renaming 'member_stats' table...\n";
    // For ease of backing out during testing,
    // merely rename 'member_stats' table, rather than dropping it.
    dpsql_query("
        RENAME TABLE member_stats TO member_stats_obsolete
    ") or die("Aborting.");

    // DROP TABLE member_stats
}

// -----------------------------------------------

if ( check_uniqueness( 'user_teams_stats', 'team_id', 'date_updated' ) )
{
    echo "\n";
    echo "Copying team page-tallies from 'user_teams_stats' table to 'past_tallies'...\n";

    dpsql_query("
        INSERT INTO past_tallies
        SELECT date_updated, IF(team_id=1,'S','T'), team_id, 'R*', daily_page_count, total_page_count
        FROM user_teams_stats
    ") or die("Aborting.");

    echo "Renaming 'user_teams_stats' table...\n";
    // For ease of backing out during testing,
    // merely rename 'user_teams_stats' table, rather than dropping it.
    dpsql_query("
        RENAME TABLE user_teams_stats TO user_teams_stats_obsolete
    ") or die("Aborting.");

    // DROP TABLE user_teams_stats
}

// -----------------------------------------------

echo "\nDone!\n";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function check_uniqueness(
    $table_name,
    $holder_id_column_name,
    $timestamp_column_name )
// In the given table, there should be at most one row for each combination of
// holder_id and timestamp values. (Otherwise it won't transfer easily into
// the past_tallies table.)
// Return TRUE iff the table satisfies this uniqueness constraint,
// or can be made to satisfy it by deleting redundant rows.
// (Really, we should have put the uniqueness constraint on *this* table
// in the first place.)
{
    echo "Adding index to $table_name. This could take a while...\n";
    $before = gettimeofday();
    dpsql_query("
        ALTER TABLE $table_name
        ADD INDEX ($holder_id_column_name,$timestamp_column_name)
    ");
    $after = gettimeofday();
    $diff = ( $after['sec']-$before['sec'] ) + 1e-6 * ( $after['usec'] - $before['usec'] );
    echo "$diff seconds\n";
    //   4.2 sec for user_teams_stats on .net
    // 414.0 sec for member_stats on .net

    echo "Looking for rows that violate uniqueness...\n";
    $res = dpsql_query("
        SELECT $holder_id_column_name, $timestamp_column_name, COUNT(*) AS c
        FROM $table_name
        GROUP BY $holder_id_column_name, $timestamp_column_name
        HAVING c > 1
        ORDER BY $timestamp_column_name, $holder_id_column_name
    ") or die("Aborting");

    if ( mysql_num_rows($res) == 0 )
    {
        $satisfies_uniqueness = TRUE;
    }
    else
    {
        echo "Some data in $table_name table does not satisfy uniqueness.\n";
        echo "Probably they are just duplicate rows.\n";
        echo "Attempting to clean them up...\n";
        echo "\n";
        $n_cleaned_cases = 0;
        $n_uncleanable_cases = 0;
        while ( list($holder_id, $timestamp, $c) = mysql_fetch_row($res) )
        {
            echo "$holder_id_column_name=$holder_id $timestamp_column_name=$timestamp #rows=$c: ";
            $res2 = dpsql_query("
                SELECT *
                FROM $table_name
                WHERE $holder_id_column_name=$holder_id AND $timestamp_column_name=$timestamp
            ");
            assert( mysql_num_rows($res2) == $c );
            if ( all_rows_the_same($res2) )
            {
                echo "All rows the same. Deleting all but one.\n";
                $all_but_one = $c - 1;
                $delete_query = "
                    DELETE FROM $table_name
                    WHERE $holder_id_column_name=$holder_id AND $timestamp_column_name=$timestamp
                    LIMIT $all_but_one
                ";
                // echo "$delete_query\n";
                dpsql_query($delete_query) or die("Aborting");
                $n_cleaned_cases++;
            }
            else
            {
                echo "ROWS NOT ALL THE SAME!\n";
                $n_uncleanable_cases++;
            }
        }
        echo "$n_cleaned_cases cases cleaned up\n";
        echo "$n_uncleanable_cases cases could not be cleaned up\n";

        $satisfies_uniqueness = ($n_uncleanable_cases == 0);
    }

    echo "\n";
    if ( $satisfies_uniqueness )
    {
        echo "$table_name satisfies uniqueness constraint,\n";
        echo "so should be okay to transfer to past_tallies.\n";
    }
    else
    {
        echo "$table_name does not satisfy uniqueness constraint,\n";
        echo "so pointless to try to transfer it to past_tallies.\n";
        echo "You will have to fix it first.\n";
    }

    return $satisfies_uniqueness;
}

function all_rows_the_same($res)
{
    $row1 = mysql_fetch_row($res);
    while ( $rowx = mysql_fetch_row($res) )
    {
        if ( $rowx !== $row1 )
        {
            return FALSE;
        }
    }
    return TRUE;
}

// vim: sw=4 ts=4 expandtab
?>
