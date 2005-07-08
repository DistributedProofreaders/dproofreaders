<?
$relPath='./../pinc/';
include($relPath.'f_dpsql.inc');
include($relPath.'connect.inc');
new dbConnect();
header("Content-Type: text/plain");

// Clear yesterday's birthdays.
dpsql_query("DELETE FROM usersettings WHERE setting = 'birthday_today'");
echo "Cleared yesterday's ".mysql_affected_rows()." birthdays.\n\n";

$now = time();
$today = getdate($now);

$months = 6;
$max = $months * 30 * 24 * 60 * 60;
$res = dpsql_query("
    SELECT username,user_regdate FROM phpbb_users WHERE ($now - user_lastvisit) < $max
") or die("Aborting");

echo "Evaluating ".mysql_num_rows($res)." users.\n\n";

while ( list($username,$date_created) = mysql_fetch_row($res) )
{
    $user_day = getdate($date_created);

    // Evaluate the most unlikely condition on its own first.
    if ($user_day['mday'] === $today['mday'])
    {
        if (($user_day['mon'] == $today['mon']) && ($user_day['year'] != $today['year']))
        {
          $birthdays[$username] = ($today['year'] - $user_day['year']);
        }
    }
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


?>
