<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'maybe_mail.inc');

    function sendtopost($project, $username, $todaysdate) {
        // Prepare a project for post-processing.
        //
        // $project: the unique id of the project.
        // $username: the DP username of the owner(?) of the project.
        // $todaysdate: the result of calling time() recently.
        //
        // More specifically:
        // -- Generate an images.html file that links to all of the page-images.
        //    (for "View Images Online")
        // -- Make a zip of all the page-image files.
        //    (for "Download Zipped Images")
        // -- Join all the page texts and put them in a file,
        //    and make a zip of that file.
        //    (for "Download Zipped Text")
        // -- Send mail to the project manager, notifying him/her that the
        //    project is ready for post-processing.

        global $projects_dir;

        $projectpath = "$projects_dir/$project";

        // Generate images.html that links to all page-images.
	generate_images_index( $project );

        // Zip all page-images.
        $zippedimages = $project."images.zip";
        exec ("zip -j $projectpath/$zippedimages $projectpath/*.png");

        // Generate joined text file
        $savepath = $projectpath."/".$project.".txt";
        $fp = fopen($savepath, "w");
        join_proofed_text($project, $fp);
        fclose ($fp);

        // Create a zip of that file
        exec ("zip -j $projectpath/$project.zip $savepath");

        // Generate joined text file as TEI
        $savepath = $projectpath."/".$project."_TEI".".txt";
        $fp = fopen($savepath, "w");
        join_proofed_text_tei($project, $fp);
        fclose ($fp);

        // Create a zip of that file
        exec ("zip -j $projectpath/{$project}_TEI.zip $savepath");

        // Decide whether to send to post-processing or have PM work on it.

        $result = mysql_query("SELECT value FROM usersettings WHERE setting = 'send_to_post' AND username='$username'");
        if (mysql_num_rows($result) > 0 && mysql_result($result, 0) == 'yes') {
            $new_state = PROJ_POST_FIRST_AVAILABLE;
            $extras = array();
            $body_blurb =
                "This project has completed second round proofreading\n".
                "and has been made available for someone else to do the post-processing.\n".
                "You will be notified once it has completed post-processing.";
        } else {
            $new_state = PROJ_POST_FIRST_CHECKED_OUT;
            $extras = array( 'checkedoutby' => $username );
            $body_blurb =
                "This project has completed second round proofreading\n".
                "and is ready for you to do the post-processing.\n".
                "If you do not want to work on it,\n".
                "set it to Available for Post-Processing.";
        }
        $error_msg = project_transition( $project, $new_state, $extras );
        if ( $error_msg )
        {
            echo "$error_msg<br>\n";
            return;
        }
        maybe_mail_project_manager( $project, $body_blurb );

    }

    function generate_images_index( $project )
    {
	global $projects_dir, $code_url;

        $projectpath = "$projects_dir/$project";
	$images_html = "$projectpath/images.html";

	$s =
	    "<HTML>\n" .
	    "<HEAD>  <TITLE>Image Index</TITLE>  </HEAD>\n" .
	    "<BODY>\n" .
	    "<H1 ALIGN=CENTER>Image Index</H1>\n" .
	    "<P>Here are the individual images for this project:</P>\n" .
	    "<HR>\n";

	foreach( glob("$projectpath/*.png") as $png_path )
	{
	    $size = filesize($png_path);
	    $png_filename = basename($png_path);
	    $s .= "<A HREF=\"$png_filename\"><B>$png_filename</B></A>";
	    $s .= " <I>($size bytes)</I><BR>\n";
	}
	$s .= "<HR>\n";
	$s .= "<P>Back to the <A HREF=\"$code_url/tools/post_proofers/post_proofers.php?project=$project\">Post Processing page</A>.</P>\n";
	$s .= "<P>Back to your <A HREF=\"$code_url/tools/proofers/proof_per.php\">personal page</A>.</P>\n";
	$s .= "</BODY>\n";
	$s .= "</HTML>\n";
	

	( $f = fopen( $images_html, 'w' ) ) and
	fwrite( $f, $s ) and
	fclose($f) or
	die( "Problems writing $images_html" );
    }

    function join_proofed_text ($project, $fp) {
        // Join the round 2 page-texts of the given project,
        // and write the result to file-object $fp.
        //
        $carriagereturn = chr(13);
        $linefeed = chr(10);
        $indicator1 = "-----------------------File: ";
        $indicator2 = "----------------------------";
        $pagebreak1 = $carriagereturn.$linefeed.$indicator1;
        $pagebreak2 = $indicator2.$carriagereturn.$linefeed;

        $myresult = mysql_query("SELECT image, round2_text FROM $project ORDER BY image");
        $mynumrows = mysql_numrows($myresult);
        $count = 0;
        while ($count < $mynumrows) {
            $filename = mysql_result($myresult, $count, "image");
            $text_data = mysql_result($myresult, $count, "round2_text");
            $fileinfo = $pagebreak1.$filename.$pagebreak2.$text_data;
            // SENDING PAGE-TEXT TO USER
            // It's a text-file, so no encoding is necessary.
            fputs($fp,$fileinfo);
            $count++;
        }
    }



    $carriagereturn = chr(13);   
    $linefeed = chr(10);

    // Using the Windows end-of-line convention.
    // Tough luck for Mac or Unix users.



    function join_proofed_text_tei ($project, $fp) {
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
        fputs($fp,"                <availability>Public Domain</availability>\r\n");
        fputs($fp,"            </publicationStmt>\r\n");
        fputs($fp,"            <sourceDesc>\r\n");
        fputs($fp,"                (Project Gutenberg doesn't like to be specific\r\n");
        fputs($fp,"                as to particular source edition.)\r\n");
        fputs($fp,"            </sourceDesc>\r\n");
        fputs($fp,"        </fileDesc>\r\n");
        fputs($fp,"        <encodingDesc>\r\n");
        fputs($fp,"            <projectDesc>\r\n");
        fputs($fp,"                Produced by [project manager], [post-processor], [scanner?],\r\n");
        fputs($fp,"                and the Online Distributed Proofreading Team at\r\n");
        fputs($fp,"                &lt;$code_url&gt;.\r\n");
        fputs($fp,"                Page-images available at\r\n");
        fputs($fp,"                &lt;$projects_url/$project/&gt;\r\n");
        fputs($fp,"            </projectDesc>\r\n");
        fputs($fp,"        </encodingDesc>\r\n");
        fputs($fp,"    </teiHeader>\r\n");

        fputs($fp,"    <text>\r\n");
        fputs($fp,"        <front>\r\n");
        fputs($fp,"            <titlePage>\r\n");
        fputs($fp,"                <!--\r\n");
        fputs($fp,"                Move Title Page text to here.\r\n");
        fputs($fp,"                Mark it up something like this example:\r\n");
        fputs($fp,"                <docTitle>\r\n");
        fputs($fp,"                    <titlePart type='main'>LOST ON THE MOON</titlePart>\r\n");
        fputs($fp,"                    <titlePart>OR</titlePart>\r\n");
        fputs($fp,"                    <titlePart type='alt'>IN QUEST OF THE FIELD OF DIAMONDS</titlePart>\r\n");
        fputs($fp,"                    <byline>BY<docAuthor>ROY ROCKWOOD</docAuthor>, AUTHOR OF [various other works]</byline>\r\n");
        fputs($fp,"                    <docDate>1911</docDate>\r\n");
        fputs($fp,"                </docTitle>\r\n");
        fputs($fp,"                -->\r\n");
        fputs($fp,"            </titlePage>\r\n");
        fputs($fp,"            <div type='contents'>\r\n");
        fputs($fp,"                <!--\r\n");
        fputs($fp,"                Move Table of Contents text to here.\r\n");
        fputs($fp,"                Mark it up like so:\r\n");
        fputs($fp,"                <list type='ordered'>\r\n");
        fputs($fp,"                <item>[title of chapter one]</item>\r\n");
        fputs($fp,"                <item>[title of chapter two]</item>\r\n");
        fputs($fp,"                ...\r\n");
        fputs($fp,"                </list>\r\n");
        fputs($fp,"                -->\r\n");
        fputs($fp,"            </div>\r\n");
        fputs($fp,"        </front>\r\n");
        fputs($fp,"        <body>\r\n");

        $myresult = mysql_query("SELECT image, round2_text FROM $project ORDER BY image");
        $mynumrows = mysql_numrows($myresult);
        $count = 0;
        while ($count < $mynumrows) {
            $filename = mysql_result($myresult, $count, "image");
            fputs($fp,"\r\n\r\n<pb n='$filename'/>\r\n\r\n");

            $text_data = mysql_result($myresult, $count, "round2_text");
            // SENDING PAGE-TEXT TO USER
            // It's an XML file, so the non-markup angle-brackets and ampersands
            // should be entity-encoded, but it's tricky to find them.
            put_page_text($text_data, $fp);
            $count++;
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

        $page_text = eregi_replace( "<i>", "<hi rend='i'>", $page_text );
        $page_text = eregi_replace( "</i>", "</hi>", $page_text );
        $page_text = eregi_replace( "<b>", "<hi rend='b'>", $page_text );
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
        $s = preg_replace( '/(\n *)([^ ].*) *(\r)/', '\1<l>\2</l>\3', $s );

        // And put the whole thing in an <lg> element.
        return "\r\n\r\n<!-- poem -->$s<!-- poem -->\r\n\r\n";
    }

    function startswith( $subject, $prefix )
    // Return TRUE iff $subject starts with $prefix.
    {
        return ( strncmp( $subject, $prefix, strlen($prefix) ) == 0 );

    }

?>
