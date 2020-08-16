<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Creating user_teams_membership table\n";

$sql = "
    CREATE TABLE user_teams_membership (
        u_id int unsigned NOT NULL,
        t_id int unsigned NOT NULL,
        PRIMARY KEY (u_id, t_id),
        KEY (t_id),
        FOREIGN KEY (u_id) REFERENCES users(u_id),
        FOREIGN KEY (t_id) REFERENCES user_teams(id)
    )
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "Populating new table from users table\n";

$sql = "
    INSERT INTO user_teams_membership (u_id, t_id)
        SELECT u_id, team_1
        FROM users
        WHERE team_1 <> 0
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

$sql = "
    INSERT INTO user_teams_membership (u_id, t_id)
        SELECT u_id, team_2
        FROM users
        WHERE team_2 <> 0
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

$sql = "
    INSERT INTO user_teams_membership (u_id, t_id)
        SELECT u_id, team_3
        FROM users
        WHERE team_3 <> 0
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "Dropping team_# columns from users table\n";

$sql = "
    ALTER TABLE users
        DROP COLUMN team_1,
        DROP COLUMN team_2,
        DROP COLUMN team_3
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "Dropping active_members and daily_average column from user_teams table\n";

$sql = "
    ALTER TABLE user_teams
        DROP COLUMN active_members,
        DROP COLUMN daily_average
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
