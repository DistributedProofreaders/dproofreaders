<?PHP
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'comment_inclusions.inc');
include_once($relPath.'stages.inc');

function generate_post_files( $project, $limit_round_id, $which_text, $include_proofers, $base_extra )
// Generate the files needed for post-processing.
{
    global $projects_dir;

    $projectpath = "$projects_dir/$project";
    $pathbase = "$projectpath/$project$base_extra";

    // Generate comments html file.
    $comments_path = "{$pathbase}_comments.html";
    $fp = fopen($comments_path, "w");
    write_project_comments($project, $fp);
    fclose ($fp);

    $pages_res = page_info_query($project, $limit_round_id, $which_text);

    // Join all the page texts into a plain text file...
    $plain_path = "{$pathbase}.txt";
    $fp = fopen($plain_path, "w");
    join_proofed_text($project, $pages_res, $include_proofers, true, $fp);
    fclose ($fp);
    //
    // and make a zip of that file (plus comments).
    // (for "Download Zipped Text")
    $plain_zip_path = "{$pathbase}.zip";
    exec ("zip -j $plain_zip_path $plain_path $comments_path");

    mysql_data_seek($pages_res, 0);

    // Join all the page texts into a TEIXLITE file...
    $tei_path = "{$pathbase}_TEI.txt";
    $fp = fopen($tei_path, "w");
    join_proofed_text_tei($project, $pages_res, $include_proofers, $fp);
    fclose ($fp);
    //
    // and make a zip of that file (plus comments).
    // (for "Download Zipped TEI text")
    $tei_zip_path = "{$pathbase}_TEI.zip";
    exec ("zip -j $tei_zip_path $tei_path $comments_path");
}

// -----------------------------------------------------------------------------
// generate a zip file on the fly and download it
function generate_interim_file($project, $limit_round_id, $which_text, $include_proofers)
{
    $filename = $project;
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
    $pages_res = page_info_query($project, $limit_round_id, $which_text);
    // join the page texts together
    $filedata = join_proofed_text($project, $pages_res, $include_proofers, false, '');

    // zip it all up

    // first find a unique place to operate in:
    $dirname = "/tmp/".uniqid("post_files");
    mkdir($dirname, 0777);      // and make the directory

    $textfile_path = "{$dirname}/{$filename}.txt";

    // make sure that everything will be cleaned up on exit
    register_shutdown_function("clean_up_temp", $dirname, $textfile_path);

    // now write the text file
    $handle = fopen($textfile_path,"w");
    fwrite($handle,$filedata);
    fclose($handle);
    // make the zip and sent it to the user
    header("Content-type: application/octet-stream");   
    header("Content-disposition: attachment; filename={$filename}.zip");   
    passthru("zip -q -j - $textfile_path", $return_code);
    if ($return_code != 0)
    {
        echo "post_files: the zip command failed.";
    }
    // cleanup will get called whatever happens  
}

function clean_up_temp($dirname, $textfile_path) 
{
    // now we must clean up behind ourselves
    unlink($textfile_path);
    rmdir($dirname);
}

// -----------------------------------------------------------------------------

function write_project_comments($project, $fp)
{
    $header = "<HTML><BODY>";
    fputs($fp,$header);

    $myresult = mysql_query("SELECT comments FROM projects WHERE projectid = '$project'");
    $comments = mysql_result($myresult, 0, "comments");
    $comments = parse_project_comments($comments); // insert e.g. templates and biographies
    fputs($fp,$comments);

    $footer = "</BODY></HTML>";
    fputs($fp,$footer);
}

// -----------------------------------------------------------------------------

function join_proofed_text ($project, $pages_res, $include_proofers, $save_files, $fp)
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
        $separator_line = str_pad( $info_str, 75, '-', STR_PAD_RIGHT );
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
    return $filedata;
}

// -----------------------------------------------------------------------------

$carriagereturn = chr(13);   
$linefeed = chr(10);

// Using the Windows end-of-line convention.
// Tough luck for Mac or Unix users.


function join_proofed_text_tei ($project, $pages_res, $include_proofers, $fp)
{
    // Join the round 2 page-texts of the given project,
    // and write the result to file-object $fp.

    global $code_url, $projects_url;
   
    $result = mysql_query("SELECT nameofwork, authorsname, language, postednum FROM projects WHERE projectid = '$project'");
    //echo mysql_errno().": ".mysql_error()."<BR>";
    $row = mysql_fetch_row($result);

    $nameofwork  = $row[0];
    $authorsname = $row[1];
    $language    = $row[2];
    $postednum   = $row[3];

    // fputs($fp,"<TEI.2>\r\n");
    fputs($fp,"<TEI.2>\r\n");
    // Dump the teiHeader.
    // The info for this might preferably come from
    // a database of MARC records.
    fputs($fp,"    <teiHeader>\r\n");
    fputs($fp,"        <fileDesc>\r\n");
    fputs($fp,"            <titleStmt>\r\n");
    fputs($fp,"                <title>$nameofwork</title>\r\n");
    fputs($fp,"                <author>$authorsname</author>\r\n");
    fputs($fp,"            </titleStmt>\r\n");
    fputs($fp,"            <publicationStmt>\r\n");
    fputs($fp,"                <publisher>Project Gutenberg</publisher>\r\n");
    fputs($fp,"                <pubPlace>Urbana</pubPlace>\r\n");
    fputs($fp,"                <date>2003</date>\r\n");
    fputs($fp,"                <idno type='PGnum'>$postednum</idno>\r\n");
    fputs($fp,"                <idno type='DPid'>$project</idno>\r\n");
    fputs($fp,"                <availability><p>Public Domain</p></availability>\r\n");
    fputs($fp,"            </publicationStmt>\r\n");
    fputs($fp,"            <sourceDesc>\r\n");
    fputs($fp,"                <p>(Project Gutenberg doesn't like to be specific\r\n");
    fputs($fp,"                as to particular source edition.)</p>\r\n");
    fputs($fp,"            </sourceDesc>\r\n");
    fputs($fp,"        </fileDesc>\r\n");
    fputs($fp,"        <encodingDesc>\r\n");
    fputs($fp,"            <projectDesc>\r\n");
    fputs($fp,"                <p>Produced by [project manager], [post-processor], [scanner?],\r\n");
    fputs($fp,"                and the Online Distributed Proofreading Team at\r\n");
    fputs($fp,"                &lt;$code_url&gt;.</p>\r\n");
    fputs($fp,"                <p>Page-images available at\r\n");
    fputs($fp,"                &lt;$projects_url/$project/&gt;</p>\r\n");
    fputs($fp,"            </projectDesc>\r\n");
    fputs($fp,"        </encodingDesc>\r\n");
    fputs($fp,"    </teiHeader>\r\n");
    fputs($fp,"\r\n");
    fputs($fp,"<text>\r\n");
    fputs($fp,"<front>\r\n");
    fputs($fp,"<titlePage>\r\n");
    fputs($fp,"<!-- \r\n");
    fputs($fp,"Move Title Page text to here.\r\n");
    fputs($fp,"Mark it up something like this example:\r\n");
    fputs($fp,"<docTitle>\r\n");
    fputs($fp,"<titlePart type='main'>LOST ON THE MOON</titlePart>\r\n");
    fputs($fp,"<titlePart>OR</titlePart>\r\n");
    fputs($fp,"<titlePart type='alt'>IN QUEST OF THE FIELD OF DIAMONDS</titlePart>\r\n");
    fputs($fp,"<docDate>1911</docDate>\r\n");
    fputs($fp,"</docTitle>\r\n");
    fputs($fp,"<byline>BY<docAuthor>ROY ROCKWOOD</docAuthor>, AUTHOR OF [various other works]</byline>\r\n");
    fputs($fp," -->\r\n");
    fputs($fp,"</titlePage>\r\n");
    fputs($fp,"<div type='contents'>\r\n");
    fputs($fp,"<!-- \r\n");
    fputs($fp,"Move Table of Contents text to here.\r\n");
    fputs($fp,"Mark it up like so:\r\n");
    fputs($fp,"<list type='ordered'>\r\n");
    fputs($fp,"<item>[title of chapter one]</item>\r\n");
    fputs($fp,"<item>[title of chapter two]</item>\r\n");
    fputs($fp,"...\r\n");
    fputs($fp,"</list>\r\n");
    fputs($fp," -->\r\n");
    fputs($fp,"</div>\r\n");
    fputs($fp,"</front>\r\n");
    fputs($fp,"<body>\r\n");

    while( list($text_data,$filename,$proofer_names) = page_info_fetch($pages_res) )
    {

        $proofer_attributes = "";
        if ($include_proofers) {            $i = 0;
            foreach ( $proofer_names as $proofer_name )
                {
                    $i++;
                    $proofer_attributes .= " proofer$i='$proofer_name'";
                }
            fputs($fp,"\r\n\r\n<pb id='$filename'$proofer_attributes/>\r\n\r\n");
        }
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

    $page_text = eregi_replace( "<i>", "<hi rend='italic'>", $page_text );
    $page_text = eregi_replace( "</i>", "</hi>", $page_text );
    $page_text = eregi_replace( "<b>", "<hi rend='bold'>", $page_text );
    $page_text = eregi_replace( "</b>", "</hi>", $page_text );

    // Convert [Illustration...] blocks, and ensure that each
    // constitutes a separate "chunk".
    // $page_text = eregi_replace(
    //     "\[Illustration\]",
    //     "\r\n\r\n<figure></figure>\r\n\r\n",
    //     $page_text );
    $page_text = eregi_replace(
        // "\[Illustration: (.*?)\]",
        "\[Illustration: (.*)\]",
        "\r\n\r\n<figure><head>\1</head></figure>\r\n\r\n",
        $page_text );

    // Ditto [Footnote: ] blocks.
    // $page_text = eregi_replace(
    //     "\[Footnote: (*)\]",
    //     "\[Footnote: (.*?)\]",
    //     "\r\n\r\n<note place='foot'>\1</note>\r\n\r\n",
    //     $page_text );

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

        if ( startswith( $chunk, '<figure>' ) || startswith( $chunk, '<lg>' ) )
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
    return "\r\n\r\n<!-- poem -->$s<!-- poem -->\r\n\r\n";
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

    $res = mysql_query("
        SELECT $text_column_expr, image $user_fields
        FROM $projectid
        ORDER BY image
    ");
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
    $a = mysql_fetch_row($res);
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
