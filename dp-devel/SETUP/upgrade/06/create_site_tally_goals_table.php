<?PHP

// One-time script to create and seed 'site_tally_goals' table.

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'f_dpsql.inc');
new dbConnect();

header('Content-type: text/plain');

dpsql_query("
    CREATE TABLE site_tally_goals (
        date         DATE     NOT NULL,
        tally_name   CHAR(2)  NOT NULL,
        goal         INT(6)   NOT NULL,

        PRIMARY KEY (date, tally_name)
    )
") or die("Aborting");

// ------------------------------------------------------

// You can change these goal values to whatever you want,
// either here (before you run the script),
// or in the table (after you run the script).
dpsql_query("
    INSERT INTO site_tally_goals
    VALUES
        ( CURDATE(),                  'P1', 2000 ),
        ( CURDATE(),                  'P2',  100 ),
        ( CURDATE(),                  'F1',  100 ),
        ( CURDATE(),                  'F2',  100 ),
        ( CURDATE() + INTERVAL 1 DAY, 'P1', 3000 ),
        ( CURDATE() + INTERVAL 1 DAY, 'P2',  200 ),
        ( CURDATE() + INTERVAL 1 DAY, 'F1',  200 ),
        ( CURDATE() + INTERVAL 1 DAY, 'F2',  200 )
") or die("Aborting");

// Now that there's some data in the table, the nightly
// run of the 'extend_site_tally_goals.php' script
// will ensure that there are goals for the next month or so.

echo "Script done!";

// vim: sw=4 ts=4 expandtab
?>
