<?php
 $relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // array_get()

require_login();

// This is used for asynchronous uploads via JS -- nothing
// printed here will ever be exposed to the user.

// this appears to assume only one file (not multiple)

// staging directory for resumable uploads
$root_staging_dir = "/tmp/resumable_uploads";

$identifier = array_get($_REQUEST, "resumableIdentifier", "");
$filename = array_get($_REQUEST, "resumableFilename", "");
$hashed_filename = md5($filename);
$chunk_number = array_get($_REQUEST, "resumableChunkNumber", "");
$total_chunks = array_get($_REQUEST, "resumableTotalChunks", 0);
$chunk_size = array_get($_REQUEST, "resumableChunkSize", 0);
$total_size = array_get($_REQUEST, "resumableTotalSize", 0);

// use a different name from final file so we can put final file
// in $root_staging_dir and delete $staging_dir
$staging_dir = "$root_staging_dir/{$hashed_filename}dir";
$chunk_filename = "$staging_dir/$hashed_filename.part.$chunk_number";

// handle testChunks request, this allows uploads to be restarted by
// allowing the browser to query for which parts have been uploaded
// successfully
if($_SERVER['REQUEST_METHOD'] === 'GET')
{
    if(file_exists($chunk_filename))
        header("HTTP/1.0 200 Ok");
    else
        header("HTTP/1.0 404 Not Found");
}

// handle a file upload request
foreach($_FILES as $file)
{
    if($file["error"] != UPLOAD_ERR_OK)
    {
        error_log(get_upload_err_msg($file['error']));
        exit;
    }

    if(!is_dir($staging_dir))
    {
        mkdir($staging_dir, 0777, true);
    }

    if(!move_uploaded_file($file['tmp_name'], $chunk_filename))
    {
        error_log("Error saving chunk $chunk_filename for $filename");
        exit;
    }
}

if(!is_dir($staging_dir))
    return;

// attempt to assemble any completed files; this needs to run both for
// testChunks and uploads to handle edge failure cases where all of the
// parts have been uploaded but not reassembled

// Ensure we have every chunk we're looking for and the entire file's
// worth of data. Chunks are uploaded sequentially, so start at the top
// and work our way down to fail early.
$size_on_server = 0;
for($i=$total_chunks; $i>=1; $i--)
{
    $chunk_name = "$staging_dir/$hashed_filename.part.$i";

    // if the chunk doesn't exist, return
    if(!is_file($chunk_name))
        return;

    $size_on_server = $size_on_server + filesize($chunk_name);
}

// We have all the chunks, but we need to confirm all of them have finished
// uploading. This can happen if they are being uploaded concurrently.
if($size_on_server >= $total_size)
{
    // To prevent multiple instances from trying to do the reassembly
    // concurrently, use a lock file. This should be rare, but we have seen
    // what looks like this behavior and it's easy to work around.
    $lock_filename = "$root_staging_dir/$hashed_filename.lock";
    if(is_file($lock_filename))
        return;
    else
        touch($lock_filename);

    if(($fp = fopen("$root_staging_dir/$hashed_filename", "w")) !== FALSE)
    {
        for($i=1; $i<=$total_chunks; $i++)
        {
            $chunk_name = "$staging_dir/$hashed_filename.part.$i";
            fwrite($fp, file_get_contents($chunk_name));
            unlink($chunk_name);
        }
        fclose($fp);
        rmdir($staging_dir);
    }
    else
    {
        error_log("Unable to create $root_staging_dir/$hashed_filename");
    }

    unlink($lock_filename);
}
