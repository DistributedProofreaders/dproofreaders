<?php

// The 'queue_defns' table defines the various release queues that the autorelease code
// polls every hour in case any can release a new book for proofreading.

// SPECIAL DAY queues are those that open on specific days only. They are defined in the 
// 'special_days' table. This script opens and closes these queues based upon the dates
// stored in those tables.

// (Originally, this script updated the project_selectors for Birthday/Otherday
// queues, hence the original name of the script, update_birthday_queues.php.)

$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

require_localhost_request();

header('Content-type: text/plain');

$testing_this_script=@$_GET['testing'];

/*
We want the queue for a special day to be open
as long as it's that special day *somewhere*
in the world, not just in the server's timezone.
Thus, in terms of GMT, we want to open it at about noon
the day before, and close it at about noon the day after.

In the special_days table, each event has an "opening day"
and a "closing day". The convention we use is that the opening
day is the (first) day of the event, and the closing day is the
(first) day *after* the event. (Not the last day of the event.)

So, combining these two ideas: in terms of GMT, we want to open
the queue at about noon of the day before the event's "opening day",
and close the queue at about noon on the "closing day".

We assume that this script is run daily at about noon GMT.
*/ 

echo "Script starts at " . gmdate('Y-m-d H:i:s') . " GMT\n";

$today_month = gmdate('m');
$today_day   = gmdate('d');

$tomorrow = gmmktime( 0,0,0, $today_month, $today_day+1 );

$tomorrow_month = gmdate('m', $tomorrow);
$tomorrow_day   = gmdate('d', $tomorrow);

echo "today:    $today_month-$today_day\n";
echo "tomorrow: $tomorrow_month-$tomorrow_day\n";

foreach ( array('open', 'close') as $which )
{
    echo "
        Looking for special events to $which...
    ";

    switch ( $which )
    {
        case 'open':
            // Looking for special events whose opening day is tomorrow.
            $event_condition = "open_month = $tomorrow_month AND open_day = $tomorrow_day";
            // For which we will enable any corresponding queue.
            $value_for_queue_enable = 1;
            break;

        case 'close':
            // Looking for special events whose closing day is today.
            $event_condition = "close_month = $today_month AND close_day = $today_day";
            // For which we will disable any corresponding queue.
            $value_for_queue_enable = 0;
            break;

        default:
            assert( 0 );
    }

    $specials_query = "
        SELECT spec_code
        FROM special_days
        WHERE $event_condition
            AND enable = 1
    ";
    echo $specials_query, "\n";

    $res = mysqli_query(DPDatabase::get_connection(), $specials_query) or die(DPDatabase::log_error());
    $n = mysqli_num_rows($res);
    echo "
        Found $n special events which '$which' now.
    ";

    while ( list($spec_code) = mysqli_fetch_row($res) )
    {
        echo "
            Looking for queues that deal with special event '$spec_code'...
        ";
        $w = '[[:space:]]*';
        $selector_pattern = "{$w}special_code{$w}={$w}[\"\\']{$spec_code}[\"\\']";
        $update_query = "
            UPDATE queue_defns
            SET enabled = $value_for_queue_enable
            WHERE project_selector REGEXP
                '$selector_pattern'
        ";
        echo $update_query, "\n";

        if (!$testing_this_script)
        {    
            mysqli_query(DPDatabase::get_connection(), $update_query) or die(DPDatabase::log_error());

            $n = mysqli_affected_rows(DPDatabase::get_connection());
            echo "
                $n queues $which'd.
            ";
        }
    }
}

// vim: sw=4 ts=4 expandtab
