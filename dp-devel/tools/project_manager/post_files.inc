<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'comment_inclusions.inc');

function generate_post_files( $project, $make_images_zip )
// Generate the files needed for post-processing.
{
    global $projects_dir;

    $projectpath = "$projects_dir/$project";

    if ($make_images_zip)
    {
        // Make a zip all page-images (and any other images).
        // (for "Download Zipped Images")
        $zippedimages = "{$project}images.zip";
        exec ("zip -j $projectpath/$zippedimages $projectpath/*.png $projectpath/*.jpg");
    }

    // Generate comments html file.
    $comments_path = "$projectpath/{$project}_comments.html";
    $fp = fopen($comments_path, "w");
    write_project_comments($project, $fp);
    fclose ($fp);

    // Join all the page texts into a plain text file...
    $plain_path = "$projectpath/{$project}.txt";
    $fp = fopen($plain_path, "w");
    join_proofed_text($project, $fp);
    fclose ($fp);
    //
    // and make a zip of that file (plus comments).
    // (for "Download Zipped Text")
    $plain_zip_path = "$projectpath/$project.zip";
    exec ("zip -j $plain_zip_path $plain_path $comments_path");

    // Join all the page texts into a TEIXLITE file...
    $tei_path = "$projectpath/{$project}_TEI.txt";
    $fp = fopen($tei_path, "w");
    join_proofed_text_tei($project, $fp);
    fclose ($fp);
    //
    // and make a zip of that file (plus comments).
    // (for "Download Zipped TEI text")
    $tei_zip_path = "$projectpath/{$project}_TEI.zip";
    exec ("zip -j $tei_zip_path $tei_path $comments_path");
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

function join_proofed_text ($project, $fp)
{
    // Join the round 2 page-texts of the given project,
    // and write the result to file-object $fp.
    //
    $carriagereturn = chr(13);
    $linefeed = chr(10);
    $eol = $carriagereturn.$linefeed;

    $myresult = page_info_query($project);
    while( list($text_data,$filename,$proofer_names) = page_info_fetch($myresult) )
    {
        $info_str = "-----File: $filename---\\";
        foreach ( $proofer_names as $proofer_name )
        {
            $info_str .= "$proofer_name\\";
        }
        $separator_line = str_pad( $info_str, 75, '-', STR_PAD_RIGHT );
        $fileinfo = $eol . $separator_line . $eol . $text_data;
        // SENDING PAGE-TEXT TO USER
        // It's a text-file, so no encoding is necessary.
        fputs($fp,$fileinfo);
    }
}

// -----------------------------------------------------------------------------

$carriagereturn = chr(13);   
$linefeed = chr(10);

// Using the Windows end-of-line convention.
// Tough luck for Mac or Unix users.


function join_proofed_text_tei ($project, $fp)
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

    $myresult = page_info_query($project);
    while( list($text_data,$filename,$proofer_names) = page_info_fetch($myresult) )
    {
        $proofer_attributes = "";
        $i = 0;
        foreach ( $proofer_names as $proofer_name )
        {
            $i++;
            $proofer_attributes .= " proofer$i='$proofer_name'";
        }
        fputs($fp,"\r\n\r\n<pb id='$filename'$proofer_attributes/>\r\n\r\n");

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

function startswith( $subject, $prefix )
// Return TRUE iff $subject starts with $prefix.
{
    return ( strncmp( $subject, $prefix, strlen($prefix) ) == 0 );

}

// -----------------------------------------------------------------------------

function page_info_query( $projectid )
{
    $res = mysql_query("
        SELECT image, round1_user, round2_user, round2_text
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
    $a = mysql_fetch_array($res);
    if (!$a) return FALSE;

    list($filename,$round1_user,$round2_user,$text_data) = $a;

    return array(
        $text_data,
        $filename,
        array( $round1_user, $round2_user )
    );
}

// vim: sw=4 ts=4 expandtab
?>
