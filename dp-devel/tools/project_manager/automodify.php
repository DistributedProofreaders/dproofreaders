<?
// This script is actually 4 scripts in one file:
//   - Cleanup Files: Removes duplicates and checks in missing pages after 3 hours
//   - Promote Level: If a project is ready to be promoted, it sends it to round 2
//   - Complete Project: If a project has completed round 2, it sends it to post-processing or assign to the project manager
//   - Release Projects: If there are not enough projects available to end users, it will release projects waiting to be released

  ///connect to database
  include '../../connect.php';

  $allprojects = mysql_query("SELECT projectid, state, username, nameofwork FROM projects WHERE state = 2 OR state = 8 OR state = 12 OR state = 18 OR state = 9 OR state = 19");
  if ($allprojects != "") { $numrows = mysql_num_rows($allprojects); } else $numrows = 0;

  $pagesleft = 0;
  $rownum = 0;

  //create date stamp
  $year  = date("Y");
  $month = date("m");
  $day = date("d");
  $todaysdate = $year.$month.$day;

  while ($rownum < $numrows) {

    $project = mysql_result($allprojects, $rownum, "projectid");
    $state = mysql_result($allprojects, $rownum, "state");
    $username = mysql_result($allprojects, $rownum, "username");
    $nameofwork = mysql_result($allprojects, $rownum, "nameofwork");

    $tempsql = mysql_query("SELECT * FROM $project WHERE Image_Filename = ''");
    if (mysql_num_rows($tempsql) > 0) { echo "Bad project - $project"; 
       if ($state < 10) { $state = 0; } else $state = 10;
       $updatefile = mysql_query("UPDATE projects SET state = $state WHERE projectid = '$project'");
    }

    $level0rows = mysql_query("SELECT * FROM $project WHERE prooflevel = '0' ORDER BY Image_Filename ASC, timestamp ASC");
    if ($level0rows != "") { $level0pages = (mysql_num_rows($level0rows)); } else $level0pages = 0;

    $level1rows = mysql_query("SELECT * FROM $project WHERE prooflevel = '1' ORDER BY Image_Filename ASC, timestamp ASC");
    if ($level1rows != "") { $level1pages = (mysql_num_rows($level1rows)); } else $level1pages = 0;

    $level2rows = mysql_query("SELECT * FROM $project WHERE prooflevel = '2' ORDER BY Image_Filename ASC, timestamp ASC");
    if ($level2rows != "") { $level2pages = (mysql_num_rows($level2rows)); } else $level2pages = 0;

    $level3rows = mysql_query("SELECT * FROM $project WHERE prooflevel = '3' ORDER BY Image_Filename ASC, timestamp ASC");
    if ($level3rows != "") { $level3pages = (mysql_num_rows($level3rows)); } else $level3pages = 0;

    // Error checking
    if ($state == 12) {
        $result = mysql_query("SELECT fileid FROM $project WHERE prooflevel = '0' AND checkedout = 'no'");
        if ($result != "") { $availablepages = mysql_num_rows($result); } else $availablepages = 0;
        if (($availablepages > 0) || ($level2pages != $level0pages)) {
            $state = 10;
            mysql_query("UPDATE projects SET state = $state WHERE projectid = '$project'");
        }
    } else if ($state == 2) {
        $result = mysql_query("SELECT fileid FROM $project WHERE prooflevel = '2' AND checkedout = 'no'");
        if ($result != "") { $availablepages = mysql_num_rows($result); } else $availablepages = 0;
        if ($availablepages > 0) {
            $state = 0;
            mysql_query("UPDATE projects SET state = $state WHERE projectid = '$project'");
        }
    }

    if ($state == 2) {
        $result = mysql_query("SELECT fileid FROM $project WHERE prooflevel = '0' AND checkedout = 'no'");
        if ($result != "") { $availablepages = mysql_num_rows($result); } else $availablepages = 0;
    } else if ($state == 12) {
        $result = mysql_query("SELECT fileid FROM $project WHERE prooflevel = '2' AND checkedout = 'no'");
        if ($result != "") { $availablepages = mysql_num_rows($result); } else $availablepages = 0;
    }

    if ($state < 10) {
        $pagesleft += (2 * $level0pages - $level1pages);
    } else {
        $pagesleft += ($level0pages - $level3pages);
    }

    // Decide which round the project is in
    if ($state < 10) {
      $prooflevel = 1;
      $oldtable = $level0rows;
      $numoldrows = $level0pages;
      $newtable = $level1rows;
      $numnewrows = $level1pages;
    } else if ($state < 20) {
      $prooflevel = 3;
      $oldtable = $level2rows;
      $numoldrows = $level2pages;
      $newtable = $level3rows;
      $numnewrows = $level3pages;
    }

    // Cleanup Duplicate Files
    if (($state == 8) || ($state == 18) ||
        (($state == 2) && ($availablepages == 0)) || 
        (($state == 12) && ($availablepages == 0))) {

        echo "Found \"$nameofwork\" to verify = $project<BR>";

        // Remove Duplicates
        $lastfilename = "0";
        $newrownum = 0;
        $count = 0;

        while ($newrownum < $numnewrows) {
            $filename = mysql_result($newtable, $newrownum, "Image_Filename");
            $fileid = mysql_result($newtable, $newrownum, "fileid");
            $badname = mysql_result($newtable, $newrownum, "proofedby");

            // If a duplicate is found, it will remove the page and reduce their page count.
            if ($filename == $lastfilename) {
                echo "Removed duplicate $project/$filename by bad user $badname, good user $lastuser page was left.<BR>";
#                $sql = mysql_query("UPDATE users SET pagescompleted = pagescompleted-1 WHERE username = '$pguser'");

                $sql = mysql_query("DELETE from $project WHERE fileid = '$fileid'");
                $count++;
            }

            $lastuser = $badname;
            $lastfilename = $filename;
            $newrownum++;
        }

        //echo "Found $count duplicates<BR>";
        $level1rows = mysql_query("SELECT Image_Filename, text_data, checkedout, fileid, timestamp FROM $project WHERE prooflevel = '1' ORDER BY Image_Filename ASC");
        if ($level1rows != "") { $level1pages = (mysql_num_rows($level1rows)); } else $level1pages = 0;

        $level2rows = mysql_query("SELECT Image_Filename, text_data, checkedout, fileid, timestamp FROM $project WHERE prooflevel = '2' ORDER BY Image_Filename ASC");
        if ($level2rows != "") { $level2pages = (mysql_num_rows($level2rows)); } else $level2pages = 0;

        $level3rows = mysql_query("SELECT Image_Filename, text_data, checkedout, fileid, timestamp FROM $project WHERE prooflevel = '3' ORDER BY Image_Filename ASC");
        if ($level3rows != "") { $level3pages = (mysql_num_rows($level3rows)); } else $level3pages = 0;

        // Decide which round the project is in
        if ($state < 10) {
            $oldtable = $level0rows;
            $numoldrows = $level0pages;
            $newtable = $level1rows;
            $numnewrows = $level1pages;
        } else if ($state < 20) {
            $oldtable = $level2rows;
            $numoldrows = $level2pages;
            $newtable = $level3rows;
            $numnewrows = $level3pages;
        }

        // Check in MIA pages
        $oldrownum = 0;
        $newrownum = 0;
        $pagesout = 0;
        $badcount = 0;
        $dietime = time() - 1800; // 30 Minute TTL

        while ($oldrownum < $numoldrows) {

            $oldfile = mysql_result($oldtable, $oldrownum, "Image_Filename");
            if ($newrownum < $numnewrows) $newfile = mysql_result($newtable, $newrownum, "Image_Filename");
            $oldstate = mysql_result($oldtable, $oldrownum, "checkedout");
            $oldid = mysql_result($oldtable, $oldrownum, "fileid");
            $timestamp = mysql_result($oldtable, $oldrownum, "timestamp");

            if ($timestamp == "") $timestamp = $dietime;

            if ($oldfile != $newfile) {
                // Checks to see if already set checked out, no need resetting again
                if ($oldstate != "no") {
                    if ($timestamp <= $dietime) {
                        $sql = mysql_query("UPDATE $project SET checkedout = 'no' WHERE fileid = '$oldid'");
                    }
                    $pagesout++;
                }
            } else { 
                $newrownum++;
                 $sql = mysql_query("UPDATE $project SET checkedout = 'yes' WHERE fileid = '$oldid'");
            }

            $oldrownum++;
        }

        if (($badcount > 0) && ($state < 10)) {
            $state = 0;
        } else if (($badcount > 0) && ($state < 20)) {
            $state = 10;
        } else if (($state == 2) || ($state == 8)) {
            $result = mysql_query("SELECT fileid FROM $project WHERE (prooflevel = '0' AND checkedout = 'no') OR (prooflevel = '0' AND checkedout = 'yes' AND timestamp > '$dietime')");
            if ($result != "") { $availablepages = mysql_num_rows($result); } else $availablepages = 0;

            if ($availablepages == 0) { $state = 9; } else $state = 2;
        } else if (($state == 12) || ($state == 18)) {
            $result = mysql_query("SELECT fileid FROM $project WHERE (prooflevel = '2' AND checkedout = 'no') OR (prooflevel = '2' AND checkedout = 'yes' AND timestamp > '$dietime')");
            if ($result != "") { $availablepages = mysql_num_rows($result); } else $availablepages = 0;

            if ($availablepages == 0) { $state = 19; } else $state = 12;
        }
        $sql = "UPDATE projects SET state = $state WHERE projectid = '$project'";
        echo "New state = $state<P>";
        $result = mysql_query($sql);
    }
    //print ("$project at state = $state<BR>");
    // Promote Level
    if (($state == 9) && ($level2pages == 0)) {

        // Get updated data for promote level
        $level0rows = mysql_query("SELECT prooflevel FROM $project WHERE prooflevel = '0'");
        if ($level0rows != "") { $level0pages = (mysql_num_rows($level0rows)); } else $level0pages = 0;

        $level1rows = mysql_query("SELECT Image_Filename, text_data, checkedout, fileid, timestamp FROM $project WHERE prooflevel = '1' ORDER BY Image_Filename ASC");
        if ($level1rows != "") { $level1pages = (mysql_num_rows($level1rows)); } else $level1pages = 0;

        echo "Found project to promote = $project<BR>";
        $row1num = 0;

        while ($row1num < $level1pages) {
            $newfileid = uniqid("fileID");
            $imagename = mysql_result($level1rows, $row1num, "Image_Filename");
            $textdata = addslashes(mysql_result($level1rows, $row1num, "text_data"));
            $timestamp = time();

            $sql = "INSERT INTO $project (Image_Filename, prooflevel, checkedout, text_data, fileid, timestamp) VALUES ('$imagename','2','no','$textdata','$newfileid', '$timestamp')";
            $newresult = mysql_query($sql);
            $row1num++;
        }
        $updatefile = mysql_query("UPDATE projects SET state = 12 WHERE projectid = '$project'");
    } else $updatefile = mysql_query("UPDATE projects SET state = 9 WHERE projectid = '$project'");

    // Completed Level
    if ($state == 19) {
        // Generate Joined Text File
        $extension = ".txt";
        $zipextension = ".zip";
        $images = "images";
        $outfile = $project.$extension;
        $zippedimages = $project.$images.$zipextension;
        $zipfilename = $project.$zipextension;

        //generate index.html for project images

        // zip all images for project
        exec ("zip -j /home/charlz/public_html/dproofreaders/projects/$project/$zippedimages /home/charlz/public_html/dproofreaders/projects/$project/*.png");

        $myresult = mysql_query("SELECT Image_filename, text_data FROM $project WHERE prooflevel = '3' ORDER BY Image_Filename");

        $count = 0;
        $mynumrows = mysql_numrows($myresult);
        $slashes = chr(47);
        $projectdir = "/home/charlz/public_html/dproofreaders/projects/";
        $projectpath = $projectdir.$project;
        $slashedpath = $projectpath.$slashes;
        $savepath = $slashedpath.$outfile;

        $fp = fopen($savepath, "w"); //open file for writing

        $carriagereturn = chr(13);
        $linefeed = chr(10);
        $indicator1 = "-----------------------File: ";
        $indicator2 = "----------------------------";
        $pagebreak1 = $carriagereturn.$linefeed.$indicator1;
        $pagebreak2 = $indicator2.$carriagereturn.$linefeed;

        while ($count < $mynumrows) {
            $filename = mysql_result($myresult, $count, "Image_filename");
            $text_data = mysql_result($myresult, $count, "text_data");

            $fileinfo = $pagebreak1.$filename.$pagebreak2.$text_data;
            fputs($fp,$fileinfo);
            $count++;
        } //end else

        // close the file
        fclose ($fp);

        //zip up the images
        $imagesdir = $projectdir.$project;
        exec ("/home/charlz/bin/images.pl $project *.png");

        //create zip copy of file
        exec ("zip -j /home/charlz/public_html/dproofreaders/projects/$project/$zipfilename /home/charlz/public_html/dproofreaders/projects/$project/$outfile");

        // Decide whether to send to post-processing or have user work on it.
        $result = mysql_query("SELECT value FROM usersettings WHERE setting = 'send_to_post' AND username='$username'");
        if ($result != "") { $send_to_post = mysql_result($result, 0, "value"); } else $send_to_post = "no";

        $result = mysql_query("SELECT email FROM users WHERE username = '$username'");
        $email = mysql_result($result, 0, "email");

        // Change state based on user's settings
        if ($send_to_post == "yes") {
            $sql = "UPDATE projects SET state=20, modifieddate = '$todaysdate' WHERE projectid = '$project'";
            $result = mysql_query($sql);
            mail("$email", "DP: $nameofwork Sent To Post-Processing",
                 "This is an automated message from the Distributed Proofreaders site.\n\n".
                 "$nameofwork has been sent to post-processing for others to do the post-processing. You will be notified once it has completed post-processing.",
                 "From: charlz@lvcablemodem.com\r\nReply-To: charlz@lvcablemodem.com\r\n");
//            exec("perl mailpost.pl '$project' '$email'");
        } else {
            $result = mysql_query("UPDATE projects SET state = 25, modifieddate = '$todaysdate', checkedoutby = '$username' WHERE projectid = '$project'");
            mail("$email", "DP: $nameofwork Ready For You To Post-Process",
                 "This is an automated message from the Distributed Proofreaders site.\n\n".
                 "$nameofwork has completed second round proofreading and is ready for you to do the post-processing. Contact the project manager once you have completed it.",
                 "From: charlz@lvcablemodem.com\r\nReply-To: charlz@lvcablemodem.com\r\n");
//            exec("perl mailsendto.pl '$project' '$email'");
        }
    }
    $rownum++;
  }
  print "Total pages available = ".$pagesleft."<BR>";

  // Auto-Release Project
  $pagesneeded = 15000;
  while ($pagesleft < $pagesneeded) {
    $result = mysql_query("SELECT projectid FROM projects WHERE state = 1 ORDER BY modifieddate ASC LIMIT 1");
    if (mysql_num_rows($result) > 0) {
      $project = mysql_result($result, 0, "projectid");

      $level0rows = mysql_query("SELECT fileid FROM $project WHERE prooflevel = '0' ORDER BY Image_Filename ASC");
      if ($level0rows != "") { $level0pages = (mysql_num_rows($level0rows)); } else $level0pages = 0;

      $result = mysql_query("UPDATE projects SET state = 8, modifieddate = '$todaysdate' WHERE projectid = '$project'");
      $pagesleft += (2 * $level0pages);

      print "Released project = $project<BR>";
    } else {
      $pagesleft = $pagesneeded;
    }
  }
?>
