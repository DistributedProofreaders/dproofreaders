<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Altering current_tallies and creating tally_snapshot_times...\n";

$sql = "
    ALTER TABLE `current_tallies`
        ADD COLUMN `last_snap_timestamp` int(10) unsigned NOT NULL default '0',
        ADD COLUMN `last_snap_tally_delta` int(8) NOT NULL default '0',
        ADD COLUMN `last_snap_tally_value` int(8) NOT NULL default '0'
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// The "IF NOT EXISTS" allows the script to be re-run easily if necessary
$sql = "
    CREATE TABLE IF NOT EXISTS `tally_snapshot_times` (
      `timestamp` int(10) unsigned NOT NULL default '0',
      PRIMARY KEY (`timestamp`)
    )
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

echo "Populating new current_tallies fields and tally_snapshot_times table...\n";

echo "Note: Progress will be reported every 100 holders per tallyboard\n";

// iterate through all tallyboards
foreach (get_all_current_tallyboards() as $tallyboard) {
    $tally_name = $tallyboard->tally_name;
    $holder_type = $tallyboard->holder_type;
    echo "Populating for $holder_type, $tally_name\n";

    // iterate over all of our holders, this should be fast because of the index
    $sql = sprintf(
        "
        SELECT holder_id, tally_value, tally_delta, timestamp
        FROM past_tallies
        WHERE tally_name = '%s' AND holder_type = '%s' AND timestamp = %d
        ORDER BY holder_id
        ",
        DPDatabase::escape($tally_name),
        DPDatabase::escape($holder_type),
        get_time_of_latest_snapshot($tallyboard)
    );
    $res = DPDatabase::query($sql);
    $progress_index = 0;
    while ($row = mysqli_fetch_assoc($res)) {
        $holder_id = intval($row['holder_id']);

        if ($progress_index % 100 == 0) {
            echo sprintf("    %s: holder_id %d\n", $progress_index, $holder_id);
        }

        $sql = sprintf(
            "
            UPDATE current_tallies
            SET
                last_snap_timestamp = %d,
                last_snap_tally_value = %d,
                last_snap_tally_delta = %d
            WHERE
                tally_name = '%s'
                AND holder_type = '%s'
                AND holder_id = %d
            ",
            $row['timestamp'],
            $row['tally_value'],
            $row['tally_delta'],
            DPDatabase::escape($tally_name),
            DPDatabase::escape($holder_type),
            $row['holder_id']
        );
        DPDatabase::query($sql);
        $progress_index += 1;
    }
}

// Keep track of all the timestamps we've seen and inserted into
// tally_snapshot_times so far.
$found_timestamps = [];

// Create a list of distinct tally names for holder_type = 'S'
$tally_names = [];
foreach (get_all_current_tallyboards() as $tallyboard) {
    if ($tallyboard->holder_type == 'S') {
        $tally_names[] = $tallyboard->tally_name;
    }
}
array_unique($tally_names);

foreach ($tally_names as $tally_name) {
    echo "Finding distinct timestamps for site tally_name $tally_name\n";
    $holder_type = 'S';
    $holder_id = 1;

    $sql = sprintf(
        "
        SELECT DISTINCT timestamp
        FROM past_tallies
        WHERE tally_name = '%s' AND holder_type = '%s' AND holder_id = 1
        ",
        DPDatabase::escape($tally_name),
        DPDatabase::escape($holder_type),
    );

    $res = DPDatabase::query($sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $timestamp = $row['timestamp'];
        if (isset($found_timestamps[$timestamp])) {
            continue;
        }

        // we handle duplicates in case this script was restarted
        $sql = sprintf(
            "
            INSERT INTO tally_snapshot_times
            SET
                timestamp = %d
            ON DUPLICATE KEY UPDATE
                timestamp = timestamp
            ",
            $timestamp
        );
        DPDatabase::query($sql);

        $found_timestamps[$timestamp] = 1;
    }
}

// ------------------------------------------------------------

echo "\nDone!\n";

// We have to use our own function in the upgrade script rather than the one
// in Tallyboard because it will already be trying to use the table we're
// trying to populate!
function get_time_of_latest_snapshot($tallyboard)
{
    $sql = sprintf(
        "
        SELECT MAX(timestamp) as max_timestamp
        FROM past_tallies
        WHERE
            tally_name      = '%s'
            AND holder_type = '%s'
        ",
        DPDatabase::escape($tallyboard->tally_name),
        DPDatabase::escape($tallyboard->holder_type)
    );
    $result = DPDatabase::query($sql);

    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        return null;
    }

    return $row["max_timestamp"];
}
