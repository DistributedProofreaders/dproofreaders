<?
// This script is actually 4 scripts in one file:
//   - Cleanup Files: Removes duplicates and checks in missing pages after 3 hours
//   - Promote Level: If a project is ready to be promoted, it sends it to round 2
//   - Complete Project: If a project has completed round 2, it sends it to post-processing or assign to the project manager
//   - Release Projects: If there are not enough projects available to end users, it will release projects waiting to be released
$relPath="./../../pinc/";

include($relPath.'connect.inc');
$db_Connection=new dbConnect();

include($relPath.'globals.php');
$projectinfo = new projectinfo();

  include 'autorelease.php';
  include 'sendtopost.php';

  $allprojects = mysql_query("SELECT projectid, state, username, nameofwork FROM projects WHERE state = 2 OR state = 8 OR state = 12 OR state = 18 OR state = 9 OR state = 19 OR state = 15");
  if ($allprojects != "") { $numrows = mysql_num_rows($allprojects); } else $numrows = 0;

  $pagesleft = 0;
  $rownum = 0;

  $todaysdate = time();

  while ($rownum < $numrows) {

    $project = mysql_result($allprojects, $rownum, "projectid");
    $state = mysql_result($allprojects, $rownum, "state");
    $username = mysql_result($allprojects, $rownum, "username");
    $nameofwork = mysql_result($allprojects, $rownum, "nameofwork");

    $projectinfo->update($project, $state);

    // Error checking
    if (($state == 12) || ($state == 15)) {
        $result = mysql_query("SELECT fileid FROM $project WHERE state != 12 AND state != 15 AND state != 18 AND state != 19");
        if ($result != "") { $badpages = mysql_num_rows($result); } else $badpages = 0;
        if ($badpages > 0) {
            $state = 10;
            mysql_query("UPDATE projects SET state = $state WHERE projectid = '$project'");
        }
        $pagesleft += $projectinfo->avail2_pages;
        $projectinfo->availablepages = $projectinfo->avail2_pages;

    } else if ($state == 2) {
        $result = mysql_query("SELECT fileid FROM $project WHERE state != 2 AND state != 5 AND state != 8 AND state != 9");
        if ($result != "") { $badpages = mysql_num_rows($result); } else $badpages = 0;
        if ($badpages > 0) {
            $state = 0;
            mysql_query("UPDATE projects SET state = $state WHERE projectid = '$project'");
        }
        $pagesleft += ($projectinfo->total_pages + $projectinfo->avail1_pages);
        $projectinfo->availablepages = $projectinfo->avail1_pages;

    }

    $projectinfo->update($project, $state);

    // Decide which round the project is in
    if ($state < 10) {
      $outtable = $projectinfo->out1_rows;
      $numoutrows = $projectinfo->out1_pages;
      $timetype = "round1_time";
      $texttype = "round1_text";
      $usertype = "round1_user";
      $newstate = 2;

    } else if ($state < 20) {
      $outtable = $projectinfo->out2_rows;
      $numoutrows = $projectinfo->out2_pages;
      $timetype = "round2_time";
      $texttype = "round2_text";
      $usertype = "round2_user";
      $newstate = 12;

    }

    if (($state == 8) || ($state == 18) ||
        (($state == 2) && ($projectinfo->availablepages == 0)) || 
        (($state == 12) && ($projectinfo->availablepages == 0))) {

        echo "Found \"$nameofwork\" to verify = $project<BR>";

        // Check in MIA pages
        $page_num = 0;
        $dietime = time() - 14400; // 4 Hour TTL

        while ($page_num < $numoutrows) {

            $fileid = mysql_result($outtable, $page_num, "fileid");
            $timestamp = mysql_result($outtable, $page_num, $timetype);

            if ($timestamp == "") $timestamp = $dietime;

            if ($timestamp <= $dietime) {
                  $sql = mysql_query("UPDATE $project SET state = $newstate, $usertype = '', $texttype = '', $timetype = '' WHERE fileid = '$fileid'");
            }
            $page_num++;
        }

        $projectinfo->update($project, $state);

        if (($state == 2) || ($state == 8)) {

            if ($projectinfo->availablepages == 0) { $state = 9; } else $state = 2;

        } else if (($state == 12) || ($state == 18)) {

            if ($projectinfo->availablepages == 0) { $state = 19; } else $state = 12;
        }

        $sql = "UPDATE projects SET state = $state WHERE projectid = '$project'";
        echo "New state = $state<P>";
        $result = mysql_query($sql);
    }

    // Promote Level
    if (($state == 9) && ($projectinfo->avail1_pages == 0)) {

        $timestamp = time();
        $updatefile = mysql_query("UPDATE $project SET state = 12, round2_time = '$timestamp'");

        echo "Found project to promote = $project<BR>";

        $updatefile = mysql_query("UPDATE projects SET state = 12 WHERE projectid = '$project'");
    }

    // Completed Level
    if ($state == 19) {
        sendtopost($project, $username, $todaysdate);
    }
    $rownum++;
  }
  print "Total pages available = ".$pagesleft."<BR>";

  autorelease($pagesleft);
?>
