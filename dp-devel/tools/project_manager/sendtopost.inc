<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
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
	  global $auto_email_addr;

        $projectpath = "$projects_dir/$project";

        // Generate images.html that links to all page-images.
        exec ("perl images.pl $project *.png");

        // Zip all page-images.
        $zippedimages = $project."images.zip";
        exec ("zip -j $projectpath/$zippedimages $projectpath/*.png");

        // Generate joined text file, and a zip of it.
        $savepath = $projectpath."/".$project.".txt";
        $fp = fopen($savepath, "w"); //open file for writing

		join_proofed_text ($project, $fp);

        // close the file
        fclose ($fp);

        //create zip copy of file
        exec ("zip -j $projectpath/$project.zip $savepath");

        // Genereate Joing text file as TEI and a zip of it.
        $savepath = $projectpath."/".$project."_TEI".".txt";
        $fp = fopen($savepath, "w"); //open file for writing

                join_proofed_text_tei ($project, $fp);
    
    // close the file
        fclose ($fp);

        // Decide whether to send to post-processing or have user work on it.

        $result = mysql_query("SELECT value FROM usersettings WHERE setting = 'send_to_post' AND username='$username'");
        if ($result != "") { $send_to_post = mysql_result($result, 0, "value"); } else $send_to_post = "no";

        $result = mysql_query("SELECT email FROM users WHERE username = '$username'");
        $email = mysql_result($result, 0, "email");

        $result = mysql_query("SELECT nameofwork FROM projects WHERE projectid = '$project'");
        $nameofwork = mysql_result($result, 0, "nameofwork");

        // Change state based on user's settings
        if ($send_to_post == "yes") {
            $sql = "UPDATE projects SET state='".PROJ_POST_AVAILABLE."', modifieddate = '$todaysdate' WHERE projectid = '$project'";
            $result = mysql_query($sql);
            maybe_mail("$email", "DP: $nameofwork Sent To Post-Processing",
                 "This is an automated message from the Distributed Proofreaders site.\n\n".
                 "$nameofwork has been sent to post-processing for others to do the post-processing. You will be notified once it has completed post-processing.",
                 "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");
        } else {
            $result = mysql_query("UPDATE projects SET state = '".PROJ_POST_CHECKED_OUT."', modifieddate = '$todaysdate', checkedoutby = '$username' WHERE projectid = '$project'");
            maybe_mail("$email", "DP: $nameofwork Ready For You To Post-Process",
                 "This is an automated message from the Distributed Proofreaders site.\n\n".
                 "$nameofwork has completed second round proofreading and is ready for you to do the post-processing. If you do not want to work on it, set it to Available for Post-Processing.",
                 "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");
        }
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
            $decodetext = html_entity_decode($text_data);
            $fileinfo = $pagebreak1.$filename.$pagebreak2.$decodetext;
            fputs($fp,$fileinfo);
            $count++;
        } //end else
	}



        $carriagereturn = chr(13);   
        $linefeed = chr(10);


    // Using the Windows end-of-line convention.
    // Tough luck for Mac or Unix users.



    function join_proofed_text_tei ($project, $fp) {
        // Join the round 2 page-texts of the given project,
        // and write the result to file-object $fp.
       



        $result = mysql_query("SELECT nameofwork, authorsname, language, postednum FROM projects WHERE projectid = '$project'");
//echo mysql_errno().": ".mysql_error()."<BR>";
        $row = mysql_fetch_row($result);

        $nameofwork  = $row[0];
        $authorsname = $row[1];
        $language    = $row[2];
        $postednum   = $row[3];

//        fputs($fp,"<TEI.2>\r\n");
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
        fputs($fp,"                <$code_url>.\r\n");
        fputs($fp,"                Page-images available at\r\n");
        fputs($fp,"                <$projects_url/$project/\r\n");
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
        fputs($fp,"                    <byline>BY<docAuthor>ROY ROCKWOOD</docAuthor>, AUTHOR OF [various other 
works]</byline>\r\n");
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
            $decodetext = html_entity_decode($text_data);
            $fileinfo = $pagebreak1.$filename.$pagebreak2.$decodetext;
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
//        global \r\n;

        $page_text = eregi_replace( "<i>", "<hi rend='i'>", $page_text );
        $page_text = eregi_replace( "</i>", "</hi>", $page_text );
        $page_text = eregi_replace( "<b>", "<hi rend='b'>", $page_text );
        $page_text = eregi_replace( "</b>", "</hi>", $page_text );

        // Convert [Illustration...] blocks, and ensure that each
        // constitutes a separate "chunk".
//        $page_text = eregi_replace(
//            "\[Illustration\]",
//            "\r\n\r\n<figure></figure>\r\n\r\n",
//            $page_text );
        $page_text = eregi_replace(
//              "\[Illustration: (.*?)\]",
            "\[Illustration: (.*)\]",
            "\r\n\r\n<figure><head>\1</head></figure>\r\n\r\n",
            $page_text );

        // Ditto [Footnote: ] blocks.
//        $page_text = eregi_replace(
//           "\[Footnote: (*)\]",
//            "\[Footnote: (.*?)\]",
//            "\r\n\r\n<note place='foot'>\1</note>\r\n\r\n",
//            $page_text );

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
    //    $chunks = preg_split( "/\n\n+/", $page_text );
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
    //    global \r\n;

        $s = $matches[1];

        // On each line with non-blank content,
        // embed that content in an <l> element.
        $s = preg_replace( '/(\n *)([^ ].*) *(\r)/', '\1<l>\2</l>\3', $s );

        // And put the whole thing in an <lg> element.
        return "\r\n\r\n<lg>$s</lg>\r\n\r\n";
    }

    function startswith( $subject, $prefix )
    // Return TRUE iff $subject starts with $prefix.
    {
        return ( strncmp( $subject, $prefix, strlen($prefix) ) == 0 );

    }



?>