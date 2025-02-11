<?php
include_once($relPath."automodify.inc");
include_once($relPath."autorelease.inc");

// Run periodic project validation and transitions
class AutoModify extends BackgroundJob
{
    private $filehandle = null;
    private ?string $logfile = null;

    public function work(): void
    {
        global $dyn_dir;

        // Capture the automodify output to a log file
        $logs_dir = "$dyn_dir/stats/automodify_logs";
        if (! is_dir($logs_dir)) {
            mkdir($logs_dir, 0777, true);
        }
        $this->logfile = sprintf("$logs_dir/%s.txt", date("Y-m-d_H:i:s"));
        $this->filehandle = fopen($this->logfile, "wt");
        ob_start([$this, 'flush'], 1024);

        automodify();
        autorelease();

        ob_end_flush();
        fclose($this->filehandle);
    }

    private function flush(string $buffer, int $phase): string
    {
        fwrite($this->filehandle, $buffer);
        return $buffer;
    }
}
