<?php
include_once($relPath."Stopwatch.inc");
include_once($relPath."job_log.inc");

abstract class BackgroundJob
{
    private ?object $watch = null;
    private ?string $output = null;

    protected ?string $start_message = null;
    protected ?string $stop_message = null;

    public bool $stdout_on_error = false;
    public bool $stdout_on_success = false;
    public bool $requires_web_context = false;

    public function __construct()
    {
        $this->watch = new Stopwatch();
    }

    public function start(?string $message = null)
    {
        $this->watch->start();
        ob_start();
        if ($message) {
            insert_job_log_entry(get_class($this), "BEGIN", $message);
        }
    }

    public function stop(?string $message = null, ?bool $succeeded = null)
    {
        $this->output = ob_get_contents();
        ob_end_clean();
        $this->watch->stop();
        $append = sprintf("took %d seconds", $this->watch->read());
        if (!$message) {
            $message = $append;
        } else {
            $message .= " ($append)";
        }
        insert_job_log_entry(get_class($this), "END", $message, $succeeded);
    }

    public function go()
    {
        $hit_error = false;
        try {
            $this->start($this->start_message);
            $this->work();
            $status = $this->stop_message ?? "";
        } catch (Exception | Error $exception) {
            $hit_error = true;
            $status = "ERROR: " . $exception->getMessage();
        } finally {
            $this->stop($status, !$hit_error);
        }

        if ($this->stdout_on_success || $hit_error && $this->stdout_on_error) {
            echo "Background job: " . get_class($this) . "\n";
            echo "Succeeded: " . (!$hit_error ? "true" : "false") . "\n";
            if ($status) {
                echo "Message: $status\n";
            }
            if ($this->output) {
                echo "Output:\n";
                echo $this->output;
            }
        }
    }

    // work() should throw an exception on failure
    abstract public function work();
}
