<?

// The 'queue_defns' table defines the various release queues that the autorelease code
// polls every hour in case any can release a new book for proofing.

// SPECIAL DAY queues are those that open on specific days only. They are defined in the 
// 'special_days' table. This script opens and closes these queues based upon the dates
// stored in those tables.

// (Originally, this script updated the project_selectors for Birthday/Otherday
// queues, hence the [now misleading] name of the script.)

$relPath='../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

$testing_this_script=$_GET['testing'];


$today = date('md');
$tomorrow = date ('md', mktime (0,0,0,date("m")  ,date("d")+1,date("Y")));


// if run in last half hour of day, use tomorrow's dates  -
// this allows the queue to be redefined in time for the special books to come out
// at midnight or soon after
if (date('H') == "23") {
  if ((int)date('i') > 29) {
     $today = $tomorrow;
  }
}






$today_month = substr($today, 0, 2);
$today_day = substr($today,2);

foreach ( array('open', 'close') as $which )
{
    echo "
        Looking for special events to $which...
    ";

    switch ( $which )
    {
        case 'open':
            $month_column_name = 'open_month';
            $day_column_name   = 'open_day';
            $value_for_queue_enable  = 1;
            break;

        case 'close':
            $month_column_name = 'close_month';
            $day_column_name   = 'close_day';
            $value_for_queue_enable  = 0;
            break;

        default:
            assert( 0 );
    }

    $specials_query = "
        SELECT spec_code
        FROM special_days
        WHERE $month_column_name = $today_month AND $day_column_name = $today_day
            AND enable = 1
    ";
    echo $specials_query, "\n";

    $res = mysql_query($specials_query) or die(mysql_error());
    $n = mysql_num_rows($res);
    echo "
        Found $n special events for which today is '$which' day.
    ";

    while ( list($spec_code) = mysql_fetch_row($res) )
    {
        echo "
            Looking for queues that deal with special event '$spec_code'...
        ";
        $w = '[[:space:]]*';
        $selector_pattern = "^{$w}special_code{$w}={$w}[\"\\']{$spec_code}[\"\\']{$w}\$";
        $update_query = "
            UPDATE queue_defns
            SET enabled = $value_for_queue_enable
            WHERE project_selector REGEXP
                '$selector_pattern'
        ";
        echo $update_query, "\n";

        if (!$testing_this_script)
        {    
            mysql_query($update_query) or die(mysql_error());

            $n = mysql_affected_rows();
            echo "
                $n queues $which'd.
            ";
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
