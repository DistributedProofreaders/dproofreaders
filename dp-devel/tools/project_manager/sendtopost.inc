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
        $myresult = mysql_query("SELECT image, round2_text FROM $project ORDER BY image");

        $count = 0;
        $mynumrows = mysql_numrows($myresult);
        $savepath = $projectpath."/".$project.".txt";

        $fp = fopen($savepath, "w"); //open file for writing

        $carriagereturn = chr(13);
        $linefeed = chr(10);
        $indicator1 = "-----------------------File: ";
        $indicator2 = "----------------------------";
        $pagebreak1 = $carriagereturn.$linefeed.$indicator1;
        $pagebreak2 = $indicator2.$carriagereturn.$linefeed;

        while ($count < $mynumrows) {
            $filename = mysql_result($myresult, $count, "image");
            $text_data = mysql_result($myresult, $count, "round2_text");

            $fileinfo = $pagebreak1.$filename.$pagebreak2.$text_data;
            fputs($fp,$fileinfo);
            $count++;
        } //end else

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


?>
