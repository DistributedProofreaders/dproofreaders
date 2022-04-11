<?php
 $relPath = "../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // array_get()

require_login();

// This is used for asynchronous uploads via JS -- nothing
// printed here will ever be exposed to the user.

// staging directory for resumable uploads
$root_staging_dir = "/tmp/resumable_uploads";

$identifier = array_get($_REQUEST, "resumableIdentifier", "");
$filename = array_get($_REQUEST, "resumableFilename", "");
// create a sanitised file name
$hashed_filename = md5($identifier);
$chunk_number = array_get($_REQUEST, "resumableChunkNumber", "");
$total_chunks = array_get($_REQUEST, "resumableTotalChunks", 0);
$total_size = array_get($_REQUEST, "resumableTotalSize", 0);

// use a different name for directory so we can put final file
// in $root_staging_dir and delete $staging_dir
$staging_dir = "$root_staging_dir/{$hashed_filename}dir";
$chunk_filename = "$staging_dir/$hashed_filename.part.$chunk_number";

// handle testChunks request, this allows uploads to be restarted by
// allowing the browser to query for which parts have been uploaded
// successfully
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($chunk_filename)) {
        header("HTTP/1.0 200 Ok");
    // continue to try to reassemble
    } else {
        header("HTTP/1.0 404 Not Found");
        exit;
    }
}

// handle a file upload request
foreach ($_FILES as $file) {
    if ($file["error"] != UPLOAD_ERR_OK) {
        report_error(get_upload_err_msg($file['error']), false);
        exit;
    }

    if (!is_dir($staging_dir)) {
        // Suppress warning by mkdir because despite the check above
        // race conditions from other processes can result in the
        // directory existing when we try to create it here.
        @mkdir($staging_dir, 0777, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $chunk_filename)) {
        report_error("Error saving chunk $chunk_filename for $filename");
        exit;
    }
}
// attempt to assemble any completed files; this needs to run both for
// testChunks and uploads to handle edge failure cases where all of the
// parts have been uploaded but not reassembled
// move_uploaded_file() should be 'atomic'
// but check anyway that file sizes add up correctly
// Ensure we have every chunk we're looking for.
// Chunks are uploaded sequentially, so start at the top
// and work our way down to fail early.
// To prevent multiple instances from trying to do the reassembly
// concurrently, lock the output file first. Other instances will
// wait to get the lock and if the previous one assembled the file
// the chunks will be gone.

// mode 'c' opens for writing but does not truncate
if (($fp = fopen("$root_staging_dir/$hashed_filename", "c")) !== false) {
    if (flock($fp, LOCK_EX)) { // acquire an exclusive lock
        $size_on_server = 0;
        $got_chunks = true;
        for ($i = $total_chunks; $i >= 1; $i--) {
            $chunk_name = "$staging_dir/$hashed_filename.part.$i";
            if (!is_file($chunk_name)) {
                $got_chunks = false;
                break;
            }
            $size_on_server = $size_on_server + filesize($chunk_name);
        }
        if ($got_chunks && ($size_on_server >= $total_size)) {
            for ($i = 1; $i <= $total_chunks; $i++) {
                $chunk_name = "$staging_dir/$hashed_filename.part.$i";
                fwrite($fp, file_get_contents($chunk_name));
                unlink($chunk_name);
            }
            rmdir($staging_dir);
        }
        flock($fp, LOCK_UN); // release the lock
    } else {
        report_error("Unable to lock $root_staging_dir/$hashed_filename");
    }
    fclose($fp);
} else {
    report_error("Unable to create $root_staging_dir/$hashed_filename");
}

function report_error($error, $log_error = true)
{
    http_response_code(500);
    echo $error;
    if ($log_error) {
        error_log("upload_resumable_file.php - $error");
    }
}
