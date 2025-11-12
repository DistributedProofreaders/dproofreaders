<?php
use Symfony\Component\Process\Process;

// Clean the download temp subdirectory.
class CleanDownloadTemp extends BackgroundJob
{
    public bool $requires_web_context = true;

    public function work()
    {
        $download_temp_dir = realpath(SiteConfig::get()->dyn_dir . "/download_tmp");
        if (! is_dir($download_temp_dir)) {
            $this->stop_message = "No download temp directory found, nothing to do.";
            return;
        }

        // remove files older than 7 days
        $process = new Process([
            "/usr/bin/find",
            $download_temp_dir,
            "-type", "f",
            "-mtime", "+7",
            "-delete",
        ]);
        $process->run();
        if (!$process->isSuccessful()) {
            echo "An error occurred while cleaning up files.\n";
            echo "Return value: {$process->getExitCode()}\n";
            echo "Command output:\n";
            echo $process->getOutput();
            throw new RuntimeException("An error occurred while removing files");
        }
    }
}
