<?

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

        $projectpath = "../../projects/".$project;

        // Generate images.html that links to all page-images.
        exec ("images.pl $project *.png");

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

        // Decide whether to send to post-processing or have user work on it.

        $result = mysql_query("SELECT value FROM usersettings WHERE setting = 'send_to_post' AND username='$username'");
        if ($result != "") { $send_to_post = mysql_result($result, 0, "value"); } else $send_to_post = "no";

        $result = mysql_query("SELECT email FROM users WHERE username = '$username'");
        $email = mysql_result($result, 0, "email");

        $result = mysql_query("SELECT nameofwork FROM projects WHERE projectid = '$project'");
        $nameofwork = mysql_result($result, 0, "nameofwork");

        // Change state based on user's settings
        if ($send_to_post == "yes") {
            $sql = "UPDATE projects SET state=61, modifieddate = '$todaysdate' WHERE projectid = '$project'";
            $result = mysql_query($sql);
            mail("$email", "DP: $nameofwork Sent To Post-Processing",
                 "This is an automated message from the Distributed Proofreaders site.\n\n".
                 "$nameofwork has been sent to post-processing for others to do the post-processing. You will be notified once it has completed post-processing.",
                 "From: charlz@lvcablemodem.com\r\nReply-To: charlz@lvcablemodem.com\r\n");
        } else {
            $result = mysql_query("UPDATE projects SET state = 65, modifieddate = '$todaysdate', checkedoutby = '$username' WHERE projectid = '$project'");
            mail("$email", "DP: $nameofwork Ready For You To Post-Process",
                 "This is an automated message from the Distributed Proofreaders site.\n\n".
                 "$nameofwork has completed second round proofreading and is ready for you to do the post-processing. If you do not want to work on it, set it to Available for Post-Processing.",
                 "From: charlz@lvcablemodem.com\r\nReply-To: charlz@lvcablemodem.com\r\n");
        }
    }

    $EOL="\r\n";
    // Using the Windows end-of-line convention.
    // Tough luck for Mac or Unix users.

    function join_proofed_text ($project, $fp) {
        // Join the round 2 page-texts of the given project,
        // and write the result to file-object $fp.
        //

        global $EOL;

        $result = mysql_query("SELECT nameofwork, authorsname, language, postednum FROM projects WHERE projectid = '$project'");
        $row = mysql_fetch_row($result);

        $nameofwork  = $row[0];
        $authorsname = $row[1];
        $language    = $row[2];
        $postednum   = $row[3];

        fputs($fp,"<TEI.2>$EOL");

        // Dump the teiHeader.
        // The info for this might preferably come from
        // a database of MARC records.
        fputs($fp,"    <teiHeader>$EOL");
        fputs($fp,"        <fileDesc>$EOL");
        fputs($fp,"            <titleStmt>$EOL");
        fputs($fp,"                <title>$nameofwork</title>$EOL");
        fputs($fp,"                <author>$authorsname</author>$EOL");
        fputs($fp,"            </titleStmt>$EOL");
        fputs($fp,"            <publicationStmt>$EOL");
        fputs($fp,"                <publisher>Project Gutenberg</publisher>$EOL");
        fputs($fp,"                <pubPlace>Urbana</pubPlace>$EOL");
        fputs($fp,"                <date>2003</date>$EOL");
        fputs($fp,"                <idno type='PGnum'>$postednum</idno>$EOL");
        fputs($fp,"                <idno type='DPid'>$project</idno>$EOL");
        fputs($fp,"                <availability>Public Domain</availability>$EOL");
        fputs($fp,"            </publicationStmt>$EOL");
        fputs($fp,"            <sourceDesc>$EOL");
        fputs($fp,"                (Project Gutenberg doesn't like to be specific$EOL");
        fputs($fp,"                as to particular source edition.)$EOL");
        fputs($fp,"            </sourceDesc>$EOL");
        fputs($fp,"        </fileDesc>$EOL");
        fputs($fp,"        <encodingDesc>$EOL");
        fputs($fp,"            <projectDesc>$EOL");
        fputs($fp,"                Produced by [project manager], [post-processor], [scanner?],$EOL");
        fputs($fp,"                and the Online Distributed Proofreading Team at$EOL");
        fputs($fp,"                <http://texts01.archive.org/dp>.$EOL");
        fputs($fp,"                Page-images available at$EOL");
        fputs($fp,"                <http://texts01.archive.org/dp/projects/$project/$EOL");
        fputs($fp,"            </projectDesc>$EOL");
        fputs($fp,"        </encodingDesc>$EOL");
        fputs($fp,"    </teiHeader>$EOL");

        fputs($fp,"    <text>$EOL");
        fputs($fp,"        <front>$EOL");
        fputs($fp,"            <titlePage>$EOL");
        fputs($fp,"                <!--$EOL");
        fputs($fp,"                Move Title Page text to here.$EOL");
        fputs($fp,"                Mark it up something like this example:$EOL");
        fputs($fp,"                <docTitle>$EOL");
        fputs($fp,"                    <titlePart type='main'>LOST ON THE MOON</titlePart>$EOL");
        fputs($fp,"                    <titlePart>OR</titlePart>$EOL");
        fputs($fp,"                    <titlePart type='alt'>IN QUEST OF THE FIELD OF DIAMONDS</titlePart>$EOL");
        fputs($fp,"                    <byline>BY<docAuthor>ROY ROCKWOOD</docAuthor>, AUTHOR OF [various other works]</byline>$EOL");
        fputs($fp,"                    <docDate>1911</docDate>$EOL");
        fputs($fp,"                </docTitle>$EOL");
        fputs($fp,"                -->$EOL");
        fputs($fp,"            </titlePage>$EOL");
        fputs($fp,"            <div type='contents'>$EOL");
        fputs($fp,"                <!--$EOL");
        fputs($fp,"                Move Table of Contents text to here.$EOL");
        fputs($fp,"                Mark it up like so:$EOL");
        fputs($fp,"                <list type='ordered'>$EOL");
        fputs($fp,"                <item>[title of chapter one]</item>$EOL");
        fputs($fp,"                <item>[title of chapter two]</item>$EOL");
        fputs($fp,"                ...$EOL");
        fputs($fp,"                </list>$EOL");
        fputs($fp,"                -->$EOL");
        fputs($fp,"            </div>$EOL");
        fputs($fp,"        </front>$EOL");
        fputs($fp,"        <body>$EOL");

        $myresult = mysql_query("SELECT image, round2_text FROM $project ORDER BY image");
        $mynumrows = mysql_numrows($myresult);
        $count = 0;
        while ($count < $mynumrows) {
            $filename = mysql_result($myresult, $count, "image");
            fputs($fp,"<pb n='$filename'/>$EOL");

            $text_data = mysql_result($myresult, $count, "round2_text");
            put_page_text($text_data, $fp);

            $count++;
        } //end else

        fputs($fp,"        </body>$EOL");
        fputs($fp,"        <back>$EOL");
        fputs($fp,"        </back>$EOL");
        fputs($fp,"    </text>$EOL");
        fputs($fp,"</TEI.2>$EOL");
    }

    function put_page_text( $page_text, $fp )
    {
        global $EOL;

        $page_text = eregi_replace( "<i>", "<hi rend='i'>", $page_text );
        $page_text = eregi_replace( "</i>", "</hi>", $page_text );
        $page_text = eregi_replace( "<b>", "<hi rend='b'>", $page_text );
        $page_text = eregi_replace( "</b>", "</hi>", $page_text );

        // Convert [Illustration...] blocks, and ensure that each
        // constitutes a separate "chunk".
        $page_text = eregi_replace(
            "\[Illustration\]",
            "$EOL$EOL<figure></figure>$EOL$EOL",
            $page_text );
        $page_text = eregi_replace(
            "\[Illustration: (.*?)\]",
            "$EOL$EOL<figure><head>\1</head></figure>$EOL$EOL",
            $page_text );

        // Ditto [Footnote: ] blocks.
        $page_text = eregi_replace(
            "\[Footnote: (.*?)\]",
            "$EOL$EOL<note place='foot'>\1</note>$EOL$EOL",
            $page_text );

        // Ditto preformatted blocks.
        $page_text = preg_replace_callback(
            ';/\*(.*?)\*/;s', 'massage_preformatted_chunk',
            $page_text );

        // Remove whitespace (including EOLs) at the start or end of the page.
        $page_text = trim( $page_text );

        // Now split the content of the page on sequences of 2 or more EOLs
        // (i.e., sequences of 1 or more blank lines)
        $chunks = preg_split( "/$EOL($EOL)+/", $page_text );
        // The only remaining EOLs must be singles, embedded within chunks.

        // Handle each chunk:
        for ( $i = 0; $i < count($chunks); $i++ )
        {
            $chunk = $chunks[$i];

            // Separate chunks with a blank line.
            if ( $i > 0 )
            {
                fputs($fp, $EOL);
            }

            if ( startswith( $chunk, '<figure>' ) || startswith( $chunk, '<lg>' ) )
            {
                fputs($fp, "$chunk$EOL");
            }
            else
            {
                fputs($fp, "<p>$EOL$chunk$EOL</p>$EOL");
            }
        }
    }

    function massage_preformatted_chunk($matches)
    // Handle a chunk of preformatted text.
    // $matches[0] is the whole chunk, including delimiters.
    // $matches[1] is the body of the chunk, between the delimiters.
    {
        global $EOL;

        $s = $matches[1];

        // On each line with non-blank content,
        // embed that content in an <l> element.
        $s = preg_replace( '/(\n *)([^ ].*) *(\r)/', '\1<l>\2</l>\3', $s );

        // And put the whole thing in an <lg> element.
        return "$EOL$EOL<lg>$s</lg>$EOL$EOL";
    }

    function startswith( $subject, $prefix )
    // Return TRUE iff $subject starts with $prefix.
    {
        return ( strncmp( $subject, $prefix, strlen($prefix) ) == 0 );

    }

?>
