<?php
include_once($relPath."automodify.inc");
include_once($relPath."autorelease.inc");

// Run periodic project validation and transitions
class AutoModify extends BackgroundJob
{
    public bool $requires_web_context = true;

    /** @var ?resource $filehandle */
    private $filehandle = null;
    private ?string $logfile = null;

    public function work(): void
    {
        // Capture the automodify output to a log file
        $logs_dir = SiteConfig::get()->dyn_dir . "/stats/automodify_logs";
        if (! is_dir($logs_dir)) {
            mkdir($logs_dir, 0o777, true);
        }
        $this->logfile = sprintf("$logs_dir/%s.txt", date("Y-m-d_H:i:s_T"));
        if (($this->filehandle = fopen($this->logfile, "wt")) === false) {
            throw new RuntimeException(sprintf(_("Failed to open log file %s."), $this->logfile));
        }
        ob_start([$this, 'flush'], 1024);

        automodify();
        autorelease();

        ob_end_flush();
        fclose($this->filehandle);
    }

    private function flush(string $buffer, int $phase): string
    {
        if (!is_null($this->filehandle)) {
            fwrite($this->filehandle, $buffer);
        }
        return $buffer;
    }
}
