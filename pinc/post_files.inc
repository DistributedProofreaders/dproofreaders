<?php
include_once($relPath.'comment_inclusions.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'wordcheck_engine.inc');

/**
 * Generate the files needed for post-processing.
 */
function generate_post_files($project, $limit_round_id, $which_text, $include_proofers, $base_extra)
{
    if (!$project->pages_table_exists) {
        throw new Exception(_("Project table not found, it may have been deleted or archived."));
    }

    if (!$project->dir_exists) {
        throw new Exception(_("Project directory not found, it may have been deleted or archived."));
    }

    $pathbase = $project->dir . "/" . $project->projectid . $base_extra;

    // Generate comments html file.
    $comments_path = "{$pathbase}_comments.html";
    $fp = fopen($comments_path, "w");
    // if ( $fp === FALSE ) ???
    write_project_comments($project, $fp);
    fclose($fp);

    $other_files = [$comments_path];

    // Word files
    foreach (['good', 'bad'] as $code) {
        $f = get_project_word_file($project->projectid, $code);
        if ($f->size > 0) {
            $other_files[] = $f->abs_path;
        }
    }

    $pages_res = page_info_query($project->projectid, $limit_round_id, $which_text);
    // if ($pages_res === FALSE) ???

    // Join all the page texts into a plain text file...
    $plain_path = "{$pathbase}.txt";
    $fp = fopen($plain_path, "w");
    // if ( $fp === FALSE ) ???
    join_proofed_text($pages_res, $include_proofers, true, $fp);
    fclose($fp);
    //
    // and make a zip of that file (plus comments).
    // (for "Download Zipped Text")
    $plain_zip_path = "{$pathbase}.zip";

    create_zip_from(array_merge([$plain_path], $other_files), $plain_zip_path);

    mysqli_data_seek($pages_res, 0);
}

// -----------------------------------------------------------------------------

/**
 * Generate a zip file on the fly and download it
 */
function generate_interim_file($project, $limit_round_id, $which_text, $include_proofers)
{
    if (!$project->pages_table_exists) {
        throw new Exception(_("Project table not found, it may have been deleted or archived."));
    }

    $filename = $project->projectid;
    if ('[OCR]' == $limit_round_id) {
        $filename .= '_OCR';
    } else {
        $filename .= '_' . $limit_round_id;
        if ('EQ' == $which_text) {
            $filename .= '_saved';
        } else {
            $filename .= '_latest';
        }
    }

    $pages_res = page_info_query($project->projectid, $limit_round_id, $which_text);
    if ($pages_res === false) {
        return;
    }

    // join the page texts together
    $filedata = join_proofed_text($pages_res, $include_proofers, false, '');

    // zip it all up

    // first find a unique place to operate in:
    $dirname = sys_get_temp_dir() . "/" . uniqid("post_files");
    mkdir($dirname, 0777);

    $textfile_path = "{$dirname}/{$filename}.txt";
    $zip_path = "{$dirname}/{$filename}.zip";

    // make sure that everything will be cleaned up on exit
    register_shutdown_function("clean_up_temp", $dirname, $textfile_path, $zip_path);

    // now write the text file
    $handle = fopen($textfile_path, "w");
    fwrite($handle, $filedata);
    fclose($handle);
    // make the zip and sent it to the user
    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment; filename={$filename}.zip");

    create_zip_from([$textfile_path], $zip_path);
    readfile($zip_path);

    // cleanup will get called whatever happens
}

function clean_up_temp($dirname, $textfile_path, $zip_path)
{
    // now we must clean up behind ourselves
    unlink($zip_path);
    unlink($textfile_path);
    rmdir($dirname);
}

// -----------------------------------------------------------------------------

function write_project_comments($project, $fp)
{
    $header = "<HTML><BODY>";
    fputs($fp, $header);

    // format Markdown to HTML
    $comments = parse_project_comments($project);
    fputs($fp, $comments);

    $footer = "</BODY></HTML>";
    fputs($fp, $footer);
}

// -----------------------------------------------------------------------------

/**
 * Return an array whose values are the page-texts in the query-result.
 */
function get_page_texts($pages_res)
{
    $page_texts = [];
    while ([$page_text, $imagename, $proofer_names] = page_info_fetch($pages_res)) {
        $page_texts[] = $page_text;
    }
    return $page_texts;
}

/**
 * Return the concatenation of the page-texts in the query-result.
 *
 * Similar to join_proofed_text, but without the page-separators.
 */
function join_page_texts($pages_res)
{
    $text = "";
    while ([$text_data, $filename, $proofer_names] = page_info_fetch($pages_res)) {
        $text .= $text_data . "\r\n";
    }
    return $text;
}

// -----------------------------------------------------------------------------

function join_proofed_text($pages_res, $include_proofers, $save_files, $fp)
{
    // Join the round 2 page-texts of the given project,
    // and write the result to file-object $fp.
    //
    $carriagereturn = chr(13);
    $linefeed = chr(10);
    $eol = $carriagereturn.$linefeed;

    $filedata = "";
    while ([$text_data, $filename, $proofer_names] = page_info_fetch($pages_res)) {
        $info_str = "-----File: $filename---";
        if ($include_proofers) {
            $info_str .= "\\";
            foreach ($proofer_names as $proofer_name) {
                $info_str .= "$proofer_name\\";
            }
        }

        // This will ensure that the separator line ends with a '-'
        // (in case some PPing software or regex expect that last dash),
        // and pad the string to length 75, if not wider than 75 chars.
        $separator_line = str_pad($info_str, 74, '-', STR_PAD_RIGHT) . '-';
        $fileinfo = $eol . $separator_line . $eol . $text_data;
        if ($save_files) {
            // SENDING PAGE-TEXT TO USER
            // It's a text-file, so no encoding is necessary.
            fputs($fp, $fileinfo);
        } else {
            $filedata .= $fileinfo;
        }
    }
    // add a final newline
    if ($save_files) {
        fputs($fp, $eol);
    } else {
        $filedata .= $eol;
    }
    return $filedata;
}

// -----------------------------------------------------------------------------

function page_info_query($projectid, $limit_round_id, $which_text)
{
    if ($limit_round_id == '[OCR]') { // somewhat kludgey
        // doesn't matter what $which_text is.
        $text_column_expr = 'master_text';
        $user_fields = "";
    } else {
        $limit_round = get_Round_for_round_id($limit_round_id);
        if (is_null($limit_round)) {
            throw new UnexpectedValueException("'$limit_round_id' is not a valid round-id\n");
        }

        if ($which_text == 'EQ') {
            // For each page, fetch the text (if any) saved in the selected round.
            $text_column_expr = $limit_round->text_column_name;
        } elseif ($which_text == 'LE') {
            // For each page, fetch the latest text saved in any round up to and
            // including the selected round.
            $text_column_expr = "CASE";
            for ($rn = $limit_round->round_number; $rn > 0; $rn--) {
                $round = get_Round_for_round_number($rn);
                $text_column_expr .=
                    " WHEN LENGTH({$round->text_column_name}) THEN {$round->text_column_name}";

                // Originally the condition was:
                //    WHEN LENGTH({$round->user_column_name})
                // The problem is that the user column is filled in as soon as
                // the page is checked out to the user, but until the user does
                // a save (in progress or as done), the associated text is empty.
                // (So you get a blank page in the output, rather than getting
                // the text from the previous round.)
                // One possible fix might be to copy the starting text into the
                // text column when the page is checked out. But I'm not sure
                // what repercussions that would have elsewhere.
                //
                // Instead, I changed user_column_name to text_column_name. I.e.,
                // we simply look for the latest non-empty text for the page.
                // The problem with that is, if the user has intentionally saved
                // an empty text, we'll skip over it. Supposedly, this shouldn't
                // happen -- the text should at least be '[Blank Page]' or
                // '[Illustration]' or something -- but (a) mistakes happen, and
                // (b) some other DP site might say empty pages are fine.
                // It's possible to distinguish between an unsaved text and a
                // saved-empty text, but the resulting SQL would be complicated,
                // and there's a fair chance I'd get it wrong.
                //
                // On balance, I think this is an okay solution for now.
            }
            $text_column_expr .= " ELSE master_text";
            $text_column_expr .= " END";
        } else {
            throw new UnexpectedValueException("bad value for which_text: '$which_text'");
        }

        $user_fields = "";
        for ($rn = 1; $rn <= $limit_round->round_number; $rn++) {
            $round = get_Round_for_round_number($rn);
            $user_fields .= ", {$round->user_column_name}";
        }
    }

    $res = DPDatabase::query("
        SELECT $text_column_expr, image $user_fields
        FROM $projectid
        ORDER BY image
    ");

    return $res;
}

/**
 * Return information about a page
 *
 * For the next page in the query-result, return an array consisting of (in order):
 * - the page text resulting from the page-editing rounds
 * - the filename of the page image
 * - an array of the usernames of the users who worked on the page in the rounds.
 *
 * If there's no next page, return FALSE
 */
function page_info_fetch($res)
{
    $a = mysqli_fetch_row($res);
    if (!$a) {
        return false;
    }

    $text_data = $a[0];
    $filename = $a[1];
    $proofer_names = array_slice($a, 2);

    return [
        $text_data,
        $filename,
        $proofer_names,
    ];
}

/** @return array{0: string, 1: string} */
function generate_project_images_zip(string $projectid): array
{
    global $projects_dir;

    $zipfile_path = SiteConfig::get()->dyn_dir . "/download_tmp/{$projectid}_images.zip";
    $zipfile_url = SiteConfig::get()->dyn_url . "/download_tmp/{$projectid}_images.zip";
    $project_path = "$projects_dir/$projectid";

    // If there's already a zip file, check if its contents are up to
    // date. If they are, we can just return it. If it's stale, delete the
    // zip and rebuilt it.
    if (file_exists($zipfile_path)) {
        $cache_stale = false;

        // List all the images in the zip file image cache
        $zipfile_modified = filemtime($zipfile_path);
        $zipfile_images = list_files_in_zip($zipfile_path);

        // List all the images in the project directory
        $proj_images = get_filelist($project_path, ['.png', '.jpg']);
        if ($proj_images === false) {
            throw new RuntimeException("Could not list project images.");
        }

        // If either list contains something not in the other, then files have
        // been added, removed or renamed in the project and the cache is stale
        if (count(array_diff($zipfile_images, $proj_images)) != 0) {
            $cache_stale = true;
        }
        if (count(array_diff($proj_images, $zipfile_images)) != 0) {
            $cache_stale = true;
        }

        // If the modification date of any of the image files in the project
        // directory is newer than that of the zip file, the cache is stale.
        foreach ($proj_images as $i) {
            if (filemtime("$project_path/$i") > $zipfile_modified) {
                $cache_stale = true;
                break;
            }
        }

        // If the cache is stale, remove it, else return it.
        if ($cache_stale) {
            unlink($zipfile_path);
        } else {
            return [$zipfile_path, $zipfile_url];
        }
    }

    if (!is_dir($project_path)) {
        throw new RuntimeException("Project directory does not exist.");
    }

    if (!is_dir(dirname($zipfile_path))) {
        mkdir(dirname($zipfile_path), 0777, true /* recursive */);
    }

    // Get a list of image filenames
    $image_files = get_filelist($project_path, ['.png', '.jpg'], /*with_path*/ true);
    if ($image_files === false) {
        throw new RuntimeException("Could not list project images.");
    }

    create_zip_from($image_files, $zipfile_path);

    return [$zipfile_path, $zipfile_url];
}
