<?PHP

// One-time script to create & populate 'current_tallies' table

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'f_dpsql.inc');
new dbConnect();

header('Content-type: text/plain');

// -----------------------------------------------
// Create 'current_tallies' table.

dpsql_query("
    CREATE TABLE current_tallies (
        tally_name   CHAR(2)          NOT NULL,
        holder_type  CHAR(1)          NOT NULL,
        holder_id    INT(6)  UNSIGNED NOT NULL,
        PRIMARY KEY (tally_name, holder_type, holder_id),

        tally_value  INT(8)           NOT NULL,
    )
") or die("Aborting.");

// -----------------------------------------------
// Move user page-tallies from 'users' table.

dpsql_query("
    INSERT INTO current_tallies
    SELECT 'P', 'U', u_id, pagescompleted
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
    SELECT 'P', IF(id=1,'S','T'), id, page_count
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
// Delete team #1 (team of the whole),
// but save its data first, just in case.

$res = dpsql_query("
    SELECT *
    FROM user_teams
    WHERE id=1
") or die("Aborting.");

if ( mysql_num_rows($res) == 0 )
{
    echo "No team #1 entry in user_teams, which is unusual.\n";
}
else
{
    $info = '';
    foreach ( mysql_fetch_assoc($res) as $key => $value )
    {
        $info .= "$key: $value\n";
    }

    echo "This script is about to delete the team #1 entry from user_teams\n";
    echo "(because the teams code would no longer treat it as a special case).\n";
    echo "Here is data from the entry:\n";
    echo "\n";
    echo $info;
    echo "\n";
    echo "The page_count has already been copied into the current_tallies table.\n";
    echo "In case there's anything else useful in the above data, we're going to write\n";
    echo "it to a file (which should appear in the directory containing this script).\n";
    echo "...\n";

    $time = strftime('%Y%m%d_%H%M%S');
    $filename = "team_1_data_$time.txt";

    $fp = fopen($filename, 'w');
    if (!$fp)
    {
        die("Failed to open $filename, so aborting");
    }
    $n = fwrite($fp, $info);
    if (!$n)
    {
        die("Failed to write to $filename, so aborting");
    }
    $r = fclose($fp);
    if (!$r)
    {
        die("Failed to close $filename, so aborting");
    }

    echo "The data appears to have been successfully saved in $filename.\n";
    echo "\n";
    echo "Proceeding to delete the row...\n";

    dpsql_query("
        DELETE
        FROM user_teams
        WHERE id=1
    ") or die("Aborting.");
    echo "\n";

    echo "Deleted.\n";
    echo "Maybe save this page too, just in case.\n";
    echo "\n";
}


echo "Script done!";

// vim: sw=4 ts=4 expandtab
?>
