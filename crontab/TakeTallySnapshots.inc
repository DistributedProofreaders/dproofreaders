<?php
include_once($relPath.'TallyBoard.inc');
include_once($relPath.'Stopwatch.inc');

// Timing requirements: This script should run once a day shortly after midnight

/*
 * Take a snapshot of all current TallyBoards.
 *
 * This assumes that you want to snapshot all of them on the same schedule.
 */
class TakeTallySnapshots extends BackgroundJob
{
    private int $midnight;

    public function __construct()
    {
        parent::__construct();
        $today = getdate();
        $this->midnight = mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']);
        $this->start_message = "Tally snapshot ascribed as $this->midnight";
    }

    public function work()
    {
        $watch = new Stopwatch();

        $results = [];
        foreach (get_all_current_tallyboards() as $tallyboard) {
            $id_for_logs = "[$tallyboard->tally_name,$tallyboard->holder_type]";
            echo "$id_for_logs:\n";

            $watch->start();
            $err = $tallyboard->take_snapshot($this->midnight);
            $watch->stop();
            $duration = $watch->read();
            $watch->reset();

            if ($err) {
                echo "    This tallyboard has already had a snapshot taken for $this->midnight!\n";
                $results[] = "$id_for_logs: already taken";
            } else {
                echo "    Snapshot completed in $duration seconds.\n";
                $results[] = "$id_for_logs: {$duration}s";
            }
            echo "\n";
        }
        $this->stop_message = join("; ", $results);
    }
}
