<?php

// An elapsed-time timer with (potentially) microsecond granularity.

class Stopwatch
// Simulates a stopwatch with a start/stop button and a reset button.
// (start/stop button can also be seen as a pause/resume button)
{
    // The time between a 'start' ('resume') and the subsequent 'stop' ('pause')
    // is called a "run" (when the watch is running).

    public function __construct()
    {
        $this->running = false;
        $this->seconds_from_completed_runs = 0;
    }

    public function start() // or resume
    {
        assert(!$this->running);
        $this->run_start_tod = gettimeofday();
        $this->running = true;
    }

    public function read()
    {
        if ($this->running) {
            $now_tod = gettimeofday();
            $seconds_since_start_of_run =
                ($now_tod['sec'] - $this->run_start_tod['sec']) +
                ($now_tod['usec'] - $this->run_start_tod['usec']) / 1e6;

            return $this->seconds_from_completed_runs
                + $seconds_since_start_of_run;
        } else {
            return $this->seconds_from_completed_runs;
        }
    }

    public function stop() // or pause
    {
        assert($this->running);
        $this->seconds_from_completed_runs = $this->read();
        $this->running = false;
    }

    public function reset()
    {
        // A real stopwatch might let you push the reset button
        // while it's running. This one doesn't.
        assert(!$this->running);
        $this->seconds_from_completed_runs = 0;
    }
}

function test_Stopwatch()
{
    $watch = new Stopwatch();
    sleep(1);

    $watch->start();
    sleep(1);

    $t1 = $watch->read();
    echo "t1=$t1, should be about 1\n";

    sleep(1);
    $watch->stop();
    sleep(2);

    $t2 = $watch->read();
    echo "t2=$t2, should be about 2\n";

    sleep(3);

    $t3 = $watch->read();
    echo "t3=$t3, should be about 2 still\n";

    $watch->start();
    sleep(1);
    $watch->stop();

    $t4 = $watch->read();
    echo "t4=$t4, should be about 3\n";
}
