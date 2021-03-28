<?php
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'comment_inclusions.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'wordcheck_engine.inc');

function generate_post_files($project, $limit_round_id, $which_text, $include_proofers, $base_extra)
// Generate the files needed for post-processing.
{
    $pathbase = $project->dir . "/" . $project->projectid . $base_extra;

    // Generate comments html file.
    $comments_path = "{$pathbase}_comments.html";
    $fp = fopen($comments_path, "w");
    // if ( $fp === FALSE ) ???
    write_project_comments($project, $fp);
    fclose ($fp);

    $other_files = [$comments_path];

    // Word files
    foreach ( array('good', 'bad') as $code )
    {
        $f = get_project_word_file($project->projectid, $code);
        if ( $f->size > 0 )
        {
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
    fclose ($fp);
    //
    // and make a zip of that file (plus comments).
    // (for "Download Zipped Text")
    $plain_zip_path = "{$pathbase}.zip";

    create_zip_from(array_merge([$plain_path], $other_files), $plain_zip_path);

    mysqli_data_seek($pages_res, 0);
}

// -----------------------------------------------------------------------------
// generate a zip file on the fly and download it
function generate_interim_file($project, $limit_round_id, $which_text, $include_proofers)
{
    $filename = $project->projectid;
    if ('[OCR]' == $limit_round_id) 
    {
        $filename .= '_OCR';
    }
    else
    {
        $filename .= '_' . $limit_round_id;
        if ('EQ' == $which_text) {
            $filename .= '_saved';
        }
        else 
        {
            $filename .= '_latest';
        }
    }

    $pages_res = page_info_query($project->projectid, $limit_round_id, $which_text);
    if ($pages_res === FALSE) return;

    // join the page texts together
    $filedata = join_proofed_text($pages_res, $include_proofers, false, '');

    // zip it all up

    // first find a unique place to operate in:
    $dirname = "/tmp/".uniqid("post_files");
    mkdir($dirname, 0777);      // and make the directory

    $textfile_path = "{$dirname}/{$filename}.txt";
    $zip_path = "{$dirname}/{$filename}.zip";

    // make sure that everything will be cleaned up on exit
    register_shutdown_function("clean_up_temp", $dirname, $textfile_path, $zip_path);

    // now write the text file
    $handle = fopen($textfile_path,"w");
    fwrite($handle,$filedata);
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
    fputs($fp,$header);

    // insert e.g. templates and biographies
    $comments = parse_project_comments($project);
    fputs($fp,$comments);

    $footer = "</BODY></HTML>";
    fputs($fp,$footer);
}

// -----------------------------------------------------------------------------

function get_page_texts( $pages_res )
// Return an array whose values are the page-texts in the query-result.
{
    $page_texts = array();
    while( list($page_text,$imagename,$proofer_names) = page_info_fetch($pages_res) )
    {
        $page_texts[] = $page_text;
    }
    return $page_texts;
}

function join_page_texts( $pages_res )
// Return the concatenation of the page-texts in the query-result.
// (Similar to join_proofed_text, but without the page-separators.)
{
    $text = "";
    while( list($text_data,$filename,$proofer_names) = page_info_fetch($pages_res) )
    {
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
    while( list($text_data,$filename,$proofer_names) = page_info_fetch($pages_res) )
    {
        $info_str = "-----File: $filename---";
        if ($include_proofers) {
            $info_str .= "\\";
            foreach ( $proofer_names as $proofer_name )
            {
                $info_str .= "$proofer_name\\";
            }
        }
        
        // This will ensure that the separator line ends with a '-'
        // (in case some PPing software or regex expect that last dash),
        // and pad the string to length 75, if not wider than 75 chars.
        $separator_line = str_pad( $info_str, 74, '-', STR_PAD_RIGHT ) . '-';
        $fileinfo = $eol . $separator_line . $eol . $text_data;
        if ($save_files) 
        {
            // SENDING PAGE-TEXT TO USER
            // It's a text-file, so no encoding is necessary.
            fputs($fp,$fileinfo);
        }
        else
        {
            $filedata .= $fileinfo;
        }
    }
    // add a final newline
    if ($save_files)
    {
        fputs($fp, $eol);
    }
    else
    {
        $filedata .= $eol;
    }
    return $filedata;
}

// -----------------------------------------------------------------------------

function page_info_query( $projectid, $limit_round_id, $which_text )
{
    if ( $limit_round_id == '[OCR]' ) // somewhat kludgey
    {
        // doesn't matter what $which_text is.
        $text_column_expr = 'master_text';
        $user_fields = "";
    }
    else
    {
        $limit_round = get_Round_for_round_id($limit_round_id);
        if (is_null($limit_round))
        {
            die("'$limit_round_id' is not a valid round-id\n");
        }

        if ( $which_text == 'EQ' )
        {
            // For each page, fetch the text (if any) saved in the selected round.
            $text_column_expr = $limit_round->text_column_name;
        }
        else if ( $which_text == 'LE' )
        {
            // For each page, fetch the latest text saved in any round up to and
            // including the selected round.
            $text_column_expr = "CASE";
            for ( $rn = $limit_round->round_number; $rn > 0; $rn-- )
            {
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
        }
        else
        {
            die("bad value for which_text: '$which_text'");
        }

        $user_fields = "";
        for ( $rn = 1; $rn <= $limit_round->round_number; $rn++ )
        {
            $round = get_Round_for_round_number($rn);
            $user_fields .= ", {$round->user_column_name}";
        }
    }

    $res = mysqli_query(DPDatabase::get_connection(), "
        SELECT $text_column_expr, image $user_fields
        FROM $projectid
        ORDER BY image
    ");
    if ($res === FALSE)
    {
        echo DPDatabase::log_error();
        return FALSE;
    }

    return $res;
}

function page_info_fetch( $res )
// For the next page in the query-result,
// return an array consisting of (in order):
// -- the page text resulting from the page-editing rounds;
// -- the filename of the page image;
// -- an array of the usernames of the users who worked on the page in the rounds.
// If there's no next page, return FALSE;
{
    $a = mysqli_fetch_row($res);
    if (!$a) return FALSE;

    $text_data = $a[0];
    $filename  = $a[1];
    $proofer_names = array_slice( $a, 2 );

    return array(
        $text_data,
        $filename,
        $proofer_names
    );
}

// vim: sw=4 ts=4 expandtab
?>
