<?php
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

// DAK 6/18/04 The following two statements should accomplish much more quickly (0.002 sec. on test machine).
// NOT parallel-tested to make sure results are identical - deferred until test machine is open again.
// No fixes, just replacing 5 sql queries (4 with table scans) with one without a table scan.
/*
$sql_insert = "
        INSERT INTO user_active_log
            ( year, month, day, hour,  time_stamp,  U_lasthour ,  U_day,  U_week, U_4wks)
        SELECT YEAR(NOW()) , MONTH(NOW()), DAYOFMONTH(NOW()), HOUR(NOW()), UNIX_TIMESTAMP(),
                SUM( CASE WHEN last_login > UNIX_TIMESTAMP( ) - 60 * 60 THEN 1 ELSE 0 END ) ,
                SUM( CASE WHEN last_login > UNIX_TIMESTAMP( ) - 60 * 60 * 24 THEN 1 ELSE 0 END ) ,
                SUM( CASE WHEN last_login > UNIX_TIMESTAMP( ) - 7 * 60 * 60 * 24 THEN 1 ELSE 0 END ) ,
                SUM( CASE WHEN last_login > UNIX_TIMESTAMP( ) - 4 * 7 * 60 * 60 * 24 THEN 1 ELSE 0 END )
        FROM users
        WHERE last_login > UNIX_TIMESTAMP( ) - 4 * 7 * 60 * 60 * 24 " ;

mysql_query($sql_insert);
*/

$now = time();

$lasthour = $now - (60 * 60);
$last24h = $now - (60 * 60 * 24);
$lastweek = $now - (7 * 60 * 60 * 24);
$last28d = $now - (4 * 7 * 60 * 60 * 24);


//get total users active in the last hour
$users = mysql_query("SELECT count(*) AS numusers FROM users WHERE last_login > $lasthour");
$Users_lasthour = mysql_result($users,0,"numusers");

//get total users active in the last 24 hours
$users = mysql_query("SELECT count(*) AS numusers FROM users WHERE last_login > $last24h");
$Users_24h = mysql_result($users,0,"numusers");

//get total users active in the last week
$users = mysql_query("SELECT count(*) AS numusers FROM users WHERE last_login > $lastweek");
$Users_lastweek = mysql_result($users,0,"numusers");

//get total users active in the last 28 days (4 weeks)
$users = mysql_query("SELECT count(*) AS numusers FROM users WHERE last_login > $last28d");
$Users_last28d = mysql_result($users,0,"numusers");

$yr = date('Y');
$mth = date('m');
$day = date('d');
$hr = date('H');

$sql_insert = "
	INSERT INTO user_active_log ( year, month, day, hour,  time_stamp,  U_lasthour ,  U_day,  U_week, U_4wks)
        VALUES  ($yr, $mth, $day, $hr,".time().", $Users_lasthour, $Users_24h, $Users_lastweek, $Users_last28d)
";

echo $sql_insert;
mysql_query($sql_insert);
