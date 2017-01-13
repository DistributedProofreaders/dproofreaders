<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Creating user_project_info table...\n";
$sql = "
    CREATE TABLE user_project_info
    (
        username    VARCHAR(25) NOT NULL,
        projectid   VARCHAR(22) NOT NULL,
        iste_round_available TINYINT(1)  NOT NULL,
        iste_round_complete  TINYINT(1)  NOT NULL,
        iste_pp_enter        TINYINT(1)  NOT NULL,
        iste_sr_available    TINYINT(1)  NOT NULL,
        iste_ppv_enter       TINYINT(1)  NOT NULL,
        iste_posted          TINYINT(1)  NOT NULL,

        PRIMARY KEY (username,projectid),
        INDEX (projectid)
    )
";
// "iste" = "is subscribed to event"
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );


echo "Populating it with data from the usersettings table...\n";
$sql = "
    INSERT INTO user_project_info
    SELECT DISTINCT
        username,
        value AS projectid,
        0 AS iste_round_available,
        0 AS iste_round_complete,
        0 AS iste_pp_enter,
        0 AS iste_sr_available,
        0 AS iste_ppv_enter,
        1 AS iste_posted
    FROM usersettings
    WHERE setting='posted_notice'
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// Here, we could probably just
//     DELETE FROM usersettings WHERE setting='posted_notice'
// but instead, we use the data in user_project_info
// to drive the deletes from usersettings,
// just in case something odd happened with the INSERT,
// or in case someone added a 'posted_notice' entry
// to usersettings just after the INSERT...SELECT.

echo "Removing that data from the usersettings table...\n";
$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT username, projectid
    FROM user_project_info
    WHERE iste_posted=1
") or die(mysqli_error(DPDatabase::get_connection()));
while ( list($username,$projectid) = mysqli_fetch_row($res) )
{
    mysqli_query(DPDatabase::get_connection(), "
        DELETE FROM usersettings
        WHERE username='$username'
            AND value='$projectid'
            AND setting='posted_notice'
    ") or die(mysqli_error(DPDatabase::get_connection()));
}

// At this point, there shouldn't be any 'posted_notice' entries
// left in usersettings.
$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT username, value
    FROM usersettings
    WHERE setting='posted_notice'
") or die(mysqli_error(DPDatabase::get_connection()));
if ( mysqli_num_rows($res) != 0 )
{
    echo "This is odd: there are 'posted_notice' entries left in usersettings:\n";
    while ( list($username,$projectid) = mysqli_fetch_row($res) )
    {
        echo "    ", str_pad($username,25), " ", $projectid, "\n";
    }
    echo "Please investigate!\n";
    exit;
}

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
