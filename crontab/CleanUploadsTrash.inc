<?php
use Symfony\Component\Process\Process;

// Clean the Uploads/TRASH subdirectory.
class CleanUploadsTrash extends BackgroundJob
{
    public bool $requires_web_context = true;

    public function work()
    {
        $trash_dir = realpath(SiteConfig::get()->uploads_dir . "/" . SiteConfig::get()->uploads_subdir_trash);
        if (! is_dir($trash_dir)) {
            $this->stop_message = "No trash directory found, nothing to do.";
            return;
        }

        // remove files from TRASH subdirectory older than 30 days
        $process = new Process([
            "/usr/bin/find",
            $trash_dir,
            "-type", "f",
            "-mtime", "+30",
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

        // remove empty directories
        $process = new Process([
            "/usr/bin/find",
            $trash_dir,
            "-type", "d",
            "-empty",
            "-delete",
        ]);
        $process->run();
        if (!$process->isSuccessful()) {
            echo "An error occurred while cleaning up files.\n";
            echo "Return value: {$process->getExitCode()}\n";
            echo "Command output:\n";
            echo $process->getOutput();
            throw new RuntimeException("An error occurred while removing empty directories");
        }
    }
}
