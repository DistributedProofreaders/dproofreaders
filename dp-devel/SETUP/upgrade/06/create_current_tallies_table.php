<?PHP

// One-time script to create & populate 'current_tallies' table

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'f_dpsql.inc');
new dbConnect();

// -----------------------------------------------
// Create 'current_tallies' table.

dpsql_query("
    CREATE TABLE current_tallies (
        holder_type  CHAR(1)          NOT NULL,
        holder_id    INT(6)  UNSIGNED NOT NULL,
        tally_name   CHAR(2)          NOT NULL,
        tally_value  INT(8)           NOT NULL,

        PRIMARY KEY (holder_type, holder_id, tally_name),
        INDEX rank_arena (holder_type, tally_name),
    )
") or die("Aborting.");

// -----------------------------------------------
// Move user page-tallies from 'users' table.

dpsql_query("
    INSERT INTO current_tallies
    SELECT 'U', u_id, 'P', pagescompleted
    FROM users
    ORDER BY u_id
") or die("Aborting.");

// For ease of backing out during testing,
// merely rename 'pagescompleted' column, rather than dropping it.
dpsql_query("
    ALTER TABLE users
    CHANGE pagescompleted pagescompleted_obsolete MEDIUMINT(8) DEFAULT '0'
") or die("Aborting.");

// ALTER TABLE users
// DROP COLUMN pagescompleted

// -----------------------------------------------
// Move site and team page-tallies from 'user_teams' table.

dpsql_query("
    INSERT INTO current_tallies
    SELECT IF(id=1,'S','T'), id, 'P', page_count
    FROM user_teams
    ORDER BY id
") or die("Aborting.");

// For ease of backing out during testing,
// merely rename 'page_count' column, rather than dropping it.
dpsql_query("
    ALTER TABLE user_teams
    CHANGE page_count page_count_obsolete INT(20) NOT NULL DEFAULT '0';
") or die("Aborting.");

// ALTER TABLE user_teams
// DROP COLUMN page_count

// -----------------------------------------------

echo "Done!";

// vim: sw=4 ts=4 expandtab
?>
