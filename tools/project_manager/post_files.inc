<?php
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'comment_inclusions.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'wordcheck_engine.inc');

function generate_post_files( $projectid, $limit_round_id, $which_text, $include_proofers, $base_extra )
// Generate the files needed for post-processing.
{
    global $projects_dir;

    $projectpath = "$projects_dir/$projectid";
    $pathbase = "$projectpath/$projectid$base_extra";

    // Generate comments html file.
    $comments_path = "{$pathbase}_comments.html";
    $fp = fopen($comments_path, "w");
    // if ( $fp === FALSE ) ???
    write_project_comments($projectid, $fp);
    fclose ($fp);

    $other_files = $comments_path;

    // Word files
    foreach ( array('good', 'bad') as $code )
    {
        $f = get_project_word_file($projectid, $code);
        if ( $f->size > 0 )
        {
            $other_files .= ' ' . $f->abs_path;
        }
    }

    $pages_res = page_info_query($projectid, $limit_round_id, $which_text);
    // if ($pages_res === FALSE) ???

    // Join all the page texts into a plain text file...
    $plain_path = "{$pathbase}.txt";
    $fp = fopen($plain_path, "w");
    // if ( $fp === FALSE ) ???
    join_proofed_text($projectid, $pages_res, $include_proofers, true, $fp);
    fclose ($fp);
    //
    // and make a zip of that file (plus comments).
    // (for "Download Zipped Text")
    $plain_zip_path = "{$pathbase}.zip";
    create_zip_from([$plain_path, $other_files], $plain_zip_path);

    mysqli_data_seek($pages_res, 0);

    // Join all the page texts into a TEIXLITE file...
    $tei_path = "{$pathbase}_TEI.txt";
    $fp = fopen($tei_path, "w");
    // if ( $fp === FALSE ) ???
    join_proofed_text_tei($projectid, $pages_res, $include_proofers, $fp);
    fclose ($fp);
    //
    // and make a zip of that file (plus comments).
    // (for "Download Zipped TEI text")
    $tei_zip_path = "{$pathbase}_TEI.zip";
    create_zip_from([$tei_path, $other_files], $tei_zip_path);
}

// -----------------------------------------------------------------------------
// generate a zip file on the fly and download it
function generate_interim_file($projectid, $limit_round_id, $which_text, $include_proofers)
{
    $filename = $projectid;
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

    $pages_res = page_info_query($projectid, $limit_round_id, $which_text);
    if ($pages_res === FALSE) return;

    // join the page texts together
    $filedata = join_proofed_text($projectid, $pages_res, $include_proofers, false, '');

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

function write_project_comments($projectid, $fp)
{
    $header = "<HTML><BODY>";
    fputs($fp,$header);

    $project = new Project($projectid);
    // insert e.g. templates and biographies
    $comments = parse_project_comments($project->comments);
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

function join_proofed_text ($projectid, $pages_res, $include_proofers, $save_files, $fp)
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

$carriagereturn = chr(13);   
$linefeed = chr(10);

// Using the Windows end-of-line convention.
// Tough luck for Mac or Unix users.


function join_proofed_text_tei ($projectid, $pages_res, $include_proofers, $fp)
{
    // Join the round 2 page-texts of the given project,
    // and write the result to file-object $fp.

    global $code_url, $projects_url, $charset;
   
    $project = new Project($projectid);

    fputs($fp,"<?xml version=\"1.0\" encoding=\"$charset\" ?>\r\n");
    fputs($fp,"<!DOCTYPE TEI.2 SYSTEM \"http://www.gutenberg.org/tei/marcello/0.4/dtd/pgtei.dtd\">");
    fputs($fp,"\r\n");
    fputs($fp,"<TEI.2 lang=\"en\">\r\n");
    // Dump the teiHeader.
    // The info for this might preferably come from
    // a database of MARC records.
    fputs($fp,"    <teiHeader>\r\n");
    fputs($fp,"        <fileDesc>\r\n");
    fputs($fp,"            <titleStmt>\r\n");
    fputs($fp,"                <title>{$project->nameofwork}</title>\r\n");
    fputs($fp,"                <author>{$project->authorsname}</author>\r\n");
    fputs($fp,"            </titleStmt>\r\n");
    fputs($fp,"            <publicationStmt>\r\n");
    fputs($fp,"                <publisher>Project Gutenberg</publisher>\r\n");
    fputs($fp,"                <pubPlace>Urbana</pubPlace>\r\n");
    fputs($fp,"                <date>20XX</date>\r\n");
    fputs($fp,"                <idno type='etext-no'>99999</idno>\r\n");
    fputs($fp,"                <availability>\r\n");
    fputs($fp,"                  <p>This eBook is for the use of anyone anywhere\r\n");
    fputs($fp,"                  at no cost and with almost no restrictions whatsoever.\r\n");
    fputs($fp,"                  You may copy it, give it away or re-use it under\r\n");
    fputs($fp,"                  the terms of the Project Gutenberg License online at\r\n");
    fputs($fp,"                  www.gutenberg.org/license</p>\r\n");
    fputs($fp,"                </availability>\r\n");
    fputs($fp,"            </publicationStmt>\r\n");
    fputs($fp,"            <sourceDesc>\r\n");
    fputs($fp,"                <p></p>\r\n");
    fputs($fp,"            </sourceDesc>\r\n");
    fputs($fp,"        </fileDesc>\r\n");
    fputs($fp,"        <encodingDesc>\r\n");
    fputs($fp,"        </encodingDesc>\r\n");
    fputs($fp,"        <profileDesc>\r\n");
    fputs($fp,"          <langUsage>\r\n");
    fputs($fp,"            <language id=\"it\" />\r\n");
    fputs($fp,"            <language id=\"fr\" />\r\n");
    fputs($fp,"            <language id=\"en\" />\r\n");
    fputs($fp,"            <language id=\"de\" />\r\n");
    fputs($fp,"          </langUsage>\r\n");
    fputs($fp,"        </profileDesc>\r\n");
    fputs($fp,"        <revisionDesc>\r\n");
    fputs($fp,"          <change>\r\n");
    fputs($fp,"            <date value=\"200X-XX\">October 200X</date>\r\n");
    fputs($fp,"            <respStmt>\r\n");
    fputs($fp,"                <resp>Produced by <name>[project manager]</name>, <name>[post-processor]</name>, <name>[scanner?]</name>,\r\n");
    fputs($fp,"                and the <name>Online Distributed Proofreading Team</name> at\r\n");
    fputs($fp,"                &lt;$code_url&gt;.\r\n");
    fputs($fp,"                Page-images available at\r\n");
    fputs($fp,"                &lt;$projects_url/$projectid/&gt;</resp>\r\n");
    fputs($fp,"            </respStmt>\r\n");
    fputs($fp,"            <item>Project Gutenberg TEI edition 1</item>\r\n");
    fputs($fp,"          </change>\r\n");
    fputs($fp,"        </revisionDesc>\r\n");
    fputs($fp,"    </teiHeader>\r\n");
    fputs($fp,"\r\n");
    fputs($fp,"    <pgExtensions>\r\n");
    fputs($fp,"      <pgStyleSheet>\r\n");
    fputs($fp,"        .bold        { font-weight: bold }\r\n");
    fputs($fp,"        .italic      { font-style: italic }\r\n");
    fputs($fp,"        .antiqua     { font-weight: bold }\r\n");
    fputs($fp,"        .gesperrt    { font-style: italic }\r\n");
    fputs($fp,"        .smallcaps   { font-variant: small-caps }\r\n");
    fputs($fp,"      </pgStyleSheet>\r\n");
    fputs($fp,"    </pgExtensions>\r\n");
    fputs($fp,"\r\n");
    fputs($fp,"<text>\r\n");
    fputs($fp,"<front>\r\n");
    fputs($fp,"</front>\r\n");
    fputs($fp,"<body>\r\n");

    while( list($text_data,$filename,$proofer_names) = page_info_fetch($pages_res) )
    {
        // Strip everything from the first '.' on from the filename to get the page number.
        $pageno = preg_replace("/\..*/", "", $filename);
        fputs($fp,"\r\n\r\n<pb n='$pageno'/><anchor id='Pg$pageno'/>\r\n\r\n");
        // SENDING PAGE-TEXT TO USER
        // It's an XML file, so the non-markup angle-brackets and ampersands
        // should be entity-encoded, but it's tricky to find them.
        put_page_text($text_data, $fp);
    } //end else

    fputs($fp,"        </body>\r\n");
    fputs($fp,"        <back>\r\n");
    fputs($fp,"        </back>\r\n");
    fputs($fp,"    </text>\r\n");
    fputs($fp,"</TEI.2>\r\n");
}

function put_page_text( $page_text, $fp )
{
    // global \r\n;

    $replace = array(
        "!<i>!i"   => "<hi rend='italic'>",
        "!<b>!i"   => "<hi rend='bold'>",
        "!<f>!i"   => "<hi rend='antiqua'>",
        "!<g>!i"   => "<hi rend='gesperrt'>",
        "!<sc>!i"  => "<hi rend='smallcaps'>",
        "!</i>!i"  => "</hi>",
        "!</b>!i"  => "</hi>",
        "!</f>!i"  => "</hi>",
        "!</g>!i"  => "</hi>",
        "!</sc>!i" => "</hi>",
    );

    $page_text = preg_replace(array_keys($replace), array_values($replace), $page_text);

    // Convert [Illustration...] blocks, and ensure that each
    // constitutes a separate "chunk".
    $page_text = preg_replace(
        "/\[Illustration: ([^\]]*)\]/",
        "\r\n\r\n<pgIf output='txt'><then>\r\n  <p>[Illustration: \\1]</p>\r\n</then><else>\r\n  <p><figure><head>\\1</head></figure></p>\r\n</else></pgIf>\r\n\r\n",
        $page_text );

    // Ditto [Footnote: ] blocks.
    $page_text = preg_replace(
        "/\[Footnote[^:]: ([^\]]*)\]/",
        "\r\n\r\n<note place='foot'>\\1</note>\r\n\r\n",
        $page_text );

    // Ampersands as entities
    $page_text = preg_replace( "/&([^\s;]*\s)/", "&amp;\\1", $page_text );

    // remove  [Blank Page]
    $page_text = preg_replace( "/\[Blank Page\]/", "", $page_text );

    // Ditto preformatted blocks.
    $page_text = preg_replace_callback(
        ';/\*(.*?)\*/;s', 'massage_preformatted_chunk',
        $page_text );

    // Remove whitespace (including EOLs) at the start or end of the page.
    $page_text = trim( $page_text );



    // Now split the content of the page on sequences of 2 or more EOLs
    // (i.e., sequences of 1 or more blank lines)
    
    // old line, split every character
    $chunks = preg_split( "/\r\n(\r\n)+/", $page_text );
    // $chunks = preg_split( "/\n\n+/", $page_text );
    // The only remaining EOLs must be singles, embedded within chunks.

    // Handle each chunk:
    for ( $i = 0; $i < count($chunks); $i++ )
    {
        $chunk = $chunks[$i];

        // Separate chunks with a blank line.
        if ( $i > 0 )
        {
            fputs($fp, "\r\n");
        }

        if ( startswith( $chunk, '<pgIf' ) || startswith( $chunk, '<lg>' ) || startswith( $chunk, '<note' ))
        {
            fputs($fp, "$chunk\r\n");
        }
        else
        {
            fputs($fp, "<p>\r\n$chunk\r\n</p>\r\n");
        }
    }
}

function massage_preformatted_chunk($matches)
// Handle a chunk of preformatted text.
// $matches[0] is the whole chunk, including delimiters.
// $matches[1] is the body of the chunk, between the delimiters.
{
    // global \r\n;

    $s = $matches[1];

    // On each line with non-blank content,
    // embed that content in an <l> element.
    // replace indentation with an attribute-value pair

    $s = preg_replace_callback(
        '/(\n)( *)([^ ].*) *(\r)/',
        'mark_up_poetry_line',
        $s );

    // And mark the whole thing as poetry.
    return "\r\n<lg>$s</lg>\r\n\r\n";
}

function mark_up_poetry_line($m)
{
    $nl     = $m[1];
    $indent = $m[2];
    $words  = $m[3];
    $cr     = $m[4];

    $i = strlen($indent);
    $attr = ( $i > 0 ? " rend='indent($i)'" : "" );
    return "$nl<l$attr>$words</l>$cr";
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
        echo "page_info_query(): SQL error:\n";
        echo mysqli_error(DPDatabase::get_connection());
        echo "\n";
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
