<?php
$relPath='./../pinc/';
include($relPath.'site_vars.php');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

// Each L_<interval> field gives the number of distinct users
// who logged in sometime in the <interval> preceding the row's timestamp.

// Each A_<interval> field gives the number of distinct users
// who were active sometime in the <interval> preceding the row's timestamp.

mysql_query("
    INSERT INTO user_active_log
        ( year, month, day, hour, time_stamp,
          L_hour, L_day, L_week, L_4wks,
          A_hour, A_day, A_week, A_4wks )
    SELECT
        YEAR(NOW()),
        MONTH(NOW()),
        DAYOFMONTH(NOW()),
        HOUR(NOW()),
        UNIX_TIMESTAMP(),

        SUM( last_login > UNIX_TIMESTAMP() - 60 * 60 ),
        SUM( last_login > UNIX_TIMESTAMP() - 60 * 60 * 24 ),
        SUM( last_login > UNIX_TIMESTAMP() - 60 * 60 * 24 * 7 ),
        SUM( last_login > UNIX_TIMESTAMP() - 60 * 60 * 24 * 7 * 4 ),

        SUM( t_last_activity > UNIX_TIMESTAMP() - 60 * 60 ),
        SUM( t_last_activity > UNIX_TIMESTAMP() - 60 * 60 * 24 ),
        SUM( t_last_activity > UNIX_TIMESTAMP() - 60 * 60 * 24 * 7 ),
        SUM( t_last_activity > UNIX_TIMESTAMP() - 60 * 60 * 24 * 7 * 4 )

    FROM users
    WHERE    t_last_activity > UNIX_TIMESTAMP() - 60 * 60 * 24 * 7 * 4
") or die(mysql_error());

# vim: sw=4 ts=4 expandtab
