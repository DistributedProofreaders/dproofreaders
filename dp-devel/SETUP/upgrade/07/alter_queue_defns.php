<?php

// NOTE:
// This script must be run before rename_rounds.php.

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

echo "This script alters the 'queue_defns' table,\n";
echo "replacing the 'round_number' column with a 'round_id' column.\n";
echo "\n";
echo "It assumes the following mapping from round_number to round_id:\n";

$map_round_number_to_round_id = array(
    '1' => 'P1',
    '2' => 'P2',
    '3' => 'F1',
    '4' => 'F2'
);
print_r( $map_round_number_to_round_id );

echo "
If this correctly reflects the current state of your queue_defns table
(i.e., the mapping that was in effect for your site before this upgrade)
you can allow this script to continue.

(Note that, typically, the queue_defns table won't (yet) reflect the mapping
defined by pinc/stages.inc in the site code that you presumably just installed.
That's okay. Running the various upgrade scripts should make it so.)

If the above mapping isn't the mapping that has till now been in effect at your site,
answer 'no' at the following prompt, then edit this script to make it so.
";
// Things would be easier if we weren't making this change in the same
// release that we change the mapping (insert a round, rename rounds).
// Then we could just use the mapping in ping/stages.inc. Ah well.

while (TRUE)
{
    echo "\n";
    echo "continue? [yes/no] ";
    $response = trim(fgets(STDIN));
    switch ($response)
    {
        case 'yes':
            break 2; // break from the switch and from the while.
        case 'no': 
            die( "Okay, exiting this script.\n" );
        default:
            echo "You response was '$response'. Please type 'yes' or 'no'.\n";
            break;
    }
}

echo "\n";
echo "----------------------------------------------------------------------\n";
echo "Adding 'round_id' column...\n";
$sql = "
    ALTER TABLE queue_defns
        ADD COLUMN round_id CHAR(2) NOT NULL AFTER round_number
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

echo "\n";
echo "----------------------------------------------------------------------\n";
echo "Populating 'round_id' column...\n";

$when_thens = "\n";
foreach ( $map_round_number_to_round_id as $round_number => $round_id )
{
    $when_thens .= "WHEN $round_number THEN '$round_id'\n";
}
$sql = "
    UPDATE queue_defns
    SET round_id =
        CASE round_number
            $when_thens
            ELSE round_number
        END
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

echo "\n";
echo "----------------------------------------------------------------------\n";
echo "Dropping 'round_num' column and replacing the keys...\n";
$sql = "
    ALTER TABLE queue_defns
        DROP COLUMN round_number,
        DROP KEY ordering,
        DROP KEY name,
        ADD UNIQUE KEY ordering (round_id,ordering),
        ADD UNIQUE KEY name     (round_id,name)
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
