<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'Stopwatch.inc');

header('Content-type: text/plain');

echo <<<EOF
    WARNING: Do not run this script until 20240414_update_current_tallies.php
    has completed successfully. This will ensure that the new tally_snapshot_times
    table is fully-populated before we start removing tally_delta=0 records from
    past_tallies. Edit this file and comment out the exit() to continue.
    
    EOF;
exit(1);

// ------------------------------------------------------------

echo "Deleting rows from past_tallies where tally_delta=0...\n";
echo "Note: Progress will be reported every 100 holders per tallyboard\n";

$watch = new Stopwatch();

// iterate through all tallyboards
foreach (get_all_current_tallyboards() as $tallyboard) {
    $tally_name = $tallyboard->tally_name;
    $holder_type = $tallyboard->holder_type;
    echo "Working on $holder_type, $tally_name\n";

    // iterate over all of our holders, this should be fast because of the index
    $sql = sprintf(
        "
        SELECT DISTINCT holder_id
        FROM past_tallies
        WHERE tally_name = '%s' AND holder_type = '%s'
        ORDER BY holder_id
        ",
        DPDatabase::escape($tally_name),
        DPDatabase::escape($holder_type),
    );
    $res = DPDatabase::query($sql);
    $progress_index = 0;
    while ($row = mysqli_fetch_assoc($res)) {
        $holder_id = intval($row['holder_id']);

        if ($progress_index % 100 == 0) {
            echo sprintf("    %s: holder_id %d\n", $progress_index, $holder_id);
        }

        // time how long the delete takes and sleep for the same amount of time
        // to not overwhelm the DB
        $watch->start();
        $sql = sprintf(
            "
            DELETE FROM past_tallies
            WHERE
                tally_name = '%s'
                AND holder_type = '%s'
                AND holder_id = %d
                AND tally_delta = 0
            ",
            DPDatabase::escape($tally_name),
            DPDatabase::escape($holder_type),
            $row['holder_id'],
        );
        DPDatabase::query($sql);
        $watch->stop();
        sleep(round($watch->read()));
        $progress_index += 1;
    }
}

// ------------------------------------------------------------

echo "\nDone!\n";
