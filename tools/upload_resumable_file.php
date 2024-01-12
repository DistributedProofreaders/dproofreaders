<?php
$relPath = "../pinc/";
include_once($relPath.'base.inc');

require_login();

// This is used for asynchronous uploads via JS -- nothing
// printed here will ever be exposed to the user.

// staging directory for resumable uploads
$root_staging_dir = "/tmp/resumable_uploads";

$identifier = array_get($_REQUEST, "resumableIdentifier", "");
// create a sanitized file name from the identifier
$hashed_filename = md5($identifier);
$filename = array_get($_REQUEST, "resumableFilename", "");
$chunk_number = array_get($_REQUEST, "resumableChunkNumber", "");
$chunk_size = array_get($_REQUEST, "resumableChunkSize", "");
$total_chunks = array_get($_REQUEST, "resumableTotalChunks", 0);
$total_size = array_get($_REQUEST, "resumableTotalSize", 0);

// use a different name for directory so we can put final file
// in $root_staging_dir and delete $staging_dir
$staging_dir = "$root_staging_dir/{$hashed_filename}dir";
$chunk_filename = "$staging_dir/$hashed_filename.part.$chunk_number";

// Handle testChunks (GET) requests, this allows uploads to be restarted by
// allowing the browser to query for which parts have been uploaded
// successfully
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($chunk_filename) && filesize($chunk_filename) >= $chunk_size) {
        if ($chunk_number == $total_chunks) {
            // try to reassemble, see note in reassemble()
            reassemble();
        } else {
            http_response_code(204);
        }
    } else {
        // uploaded file didn't exist or filesize didn't match, request a re-upload
        http_response_code(204);
    }
}
// Handle a file upload request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_FILES as $file) {
        if ($file["error"] != UPLOAD_ERR_OK) {
            report_error(get_upload_err_msg($file['error']));
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
        } elseif (filesize($chunk_filename) < $chunk_size && $total_size > $chunk_size) {
            // if the filesize is smaller than expected, unlink the file
            // and report an error
            report_error("Error saving chunk $chunk_filename for $filename, uploaded size did not match $chunk_size");
            unlink($chunk_filename);
            exit;
        }
    }

    // Attempt reassemble
    reassemble();
} else {
    // something besides a GET or POST
    http_response_code(400);
}

/*
 * Attempt to reassemble a resumable-uploaded file.
 *
 * This needs to run both for testChunks (GETs) and uploads (POSTs) to handle
 * edge failure cases where all of the parts have been uploaded in a prior
 * attempt but not yet reassembled.
 */
function reassemble()
{
    global $root_staging_dir, $staging_dir, $hashed_filename;
    global $total_chunks, $total_size;

    // To prevent multiple instances from trying to do the reassembly
    // concurrently, lock the output file first. Other instances will
    // wait to get the lock and if the previous one assembled the file
    // the chunks will be gone.
    // mode 'c' opens for writing but does not truncate
    if (($fp = fopen("$root_staging_dir/$hashed_filename", "c")) !== false) {
        if (flock($fp, LOCK_EX)) { // acquire an exclusive lock
            $size_on_server = 0;

            // Ensure we have every chunk we're looking for. Chunks are
            // uploaded sequentially, so start at the top and work our way
            // down to fail early.
            $got_chunks = true;
            for ($i = $total_chunks; $i >= 1; $i--) {
                $chunk_name = "$staging_dir/$hashed_filename.part.$i";
                if (!is_file($chunk_name)) {
                    $got_chunks = false;
                    break;
                }
                $size_on_server = $size_on_server + filesize($chunk_name);
            }

            // move_uploaded_file() should be 'atomic' but check anyway that
            // file sizes add up correctly before doing the reassembly.
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
            report_error("Unable to lock $root_staging_dir/$hashed_filename", 500);
        }
        fclose($fp);
    } else {
        report_error("Unable to create $root_staging_dir/$hashed_filename", 500);
    }
}

function report_error($error, $response_code = 204)
{
    global $testing;

    // by default return a 204 which will cause the resumable upload
    // to retry the chunk upload
    http_response_code($response_code);

    // log on non-retry errors or if we're testing
    if ($response_code != 204 || $testing) {
        error_log("upload_resumable_file.php - $error");
    }
}
