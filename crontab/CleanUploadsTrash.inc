<?php

// Clean the Uploads/TRASH subdirectory.
class CleanUploadsTrash extends BackgroundJob
{
    public bool $requires_web_context = true;

    public function work()
    {
        global $uploads_dir, $uploads_subdir_trash;

        $trash_dir = realpath("$uploads_dir/$uploads_subdir_trash");
        if (! is_dir($trash_dir)) {
            $this->stop_message = "No trash directory found, nothing to do.";
            return;
        }

        // remove files from TRASH subdirectory older than 30 days
        $cmd = join(" ", [
            "/usr/bin/find",
            escapeshellarg($trash_dir),
            "-type f",
            "-mtime +30",
            "-delete",
        ]);
        $output = $return = null;
        exec($cmd, $output, $return);
        if ($return != 0) {
            echo "An error occurred while cleaning up files.\n";
            echo "Return value: $return\n";
            echo "Command output:\n";
            foreach ($output as $line) {
                echo "    $line\n";
            }
            throw new RuntimeException("An error occurred while removing files");
        }

        // remove empty directories
        $output = $return = null;
        $cmd = join(" ", [
            "/usr/bin/find",
            escapeshellarg($trash_dir),
            "-type d",
            "-empty",
            "-delete",
        ]);
        exec($cmd, $output, $return);
        if ($return != 0) {
            echo "An error occurred while cleaning up files.\n";
            echo "Return value: $return\n";
            echo "Command output:\n";
            foreach ($output as $line) {
                echo "    $line\n";
            }
            throw new RuntimeException("An error occurred while removing empty directories");
        }
    }
}
