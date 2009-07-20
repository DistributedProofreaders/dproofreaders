<?php
$relPath='./../pinc/';
include($relPath.'dpsql.inc');
include($relPath.'connect.inc');
include_once($relPath.'misc.inc');

// check that caller is localhost or bail
if(!requester_is_localhost())
    die("You are not authorized to perform this request.");

new dbConnect();
header("Content-Type: text/plain");

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

// Clear yesterday's birthdays.
dpsql_query("DELETE FROM usersettings WHERE setting = 'birthday_today'");
echo "Cleared yesterday's ".mysql_affected_rows()." birthdays.\n\n";

$now = time();
$today = getdate($now);

$months = 6;
$max = $months * 2592000;
$res = dpsql_query("
    SELECT username,date_created 
    FROM users
    WHERE
        FROM_UNIXTIME(date_created, '%m-%d') = FROM_UNIXTIME($now,'%m-%d')
        AND ($now - t_last_activity) < $max
        AND ($now - date_created) > 28512000
") or die("Aborting");

while ( list($username,$date_created) = mysql_fetch_row($res) )
{
    $user_day = getdate($date_created);
    $birthdays[$username] = ($today['year'] - $user_day['year']);
}

if (count($birthdays) == 0)
{
    echo "No user birthdays today.";
    die;
}
else
{
    echo "Today's birthdays:\n";
}


foreach ($birthdays as $user => $years)
{
    dpsql_query("INSERT INTO usersettings VALUES('$user','birthday_today','$years')");
    echo "  $user ($years)\n";
}

echo "\nBirthdays updated. Mazel tov!";

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$time = ($mtime - $starttime);
echo "\n\nTook ".round($time,4)." seconds to run.";
?>
