<?
// This script is actually 4 scripts in one file:
//   - Cleanup Files: Removes duplicates and checks in missing pages after 3 hours
//   - Promote Level: If a project is ready to be promoted, it sends it to round 2
//   - Complete Project: If a project has completed round 2, it sends it to post-processing or assign to the project manager
//   - Release Projects: If there are not enough projects available to end users, it will release projects waiting to be released
$relPath="./../../pinc/";

include($relPath.'connect.inc');
$db_Connection=new dbConnect();

include($relPath.'projectinfo.inc');
$projectinfo = new projectinfo();

  include 'autorelease.php';
  include 'sendtopost.php';

  $allprojects = mysql_query("SELECT projectid, state, username, nameofwork FROM projects WHERE state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR state = '".PROJ_PROOF_FIRST_VERIFY."' OR state = '".PROJ_PROOF_SECOND_AVAILABLE."' OR state = '".PROJ_PROOF_SECOND_VERIFY."' OR state = '".PROJ_PROOF_FIRST_COMPLETE."' OR state = '".PROJ_PROOF_SECOND_COMPLETE."'");
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
    if ($state == PROJ_PROOF_SECOND_AVAILABLE) {
        $result = mysql_query("SELECT fileid FROM $project WHERE state != '".AVAIL_SECOND."' AND state != '".OUT_SECOND."' AND state != '".SAVE_SECOND."' AND state != '".TEMP_SECOND."' AND state != '".BAD_SECOND."'");
        if ($result != "") { $badpages = mysql_num_rows($result); } else $badpages = 0;
        if ($badpages > 0) {
            $state = PROJ_PROOF_SECOND_BAD_PROJECT;
            mysql_query("UPDATE projects SET state = '$state' WHERE projectid = '$project'");
        }
        $pagesleft += $projectinfo->avail2_pages;
        $projectinfo->availablepages = $projectinfo->avail2_pages;

    } else if ($state == PROJ_PROOF_FIRST_AVAILABLE) {
        $result = mysql_query("SELECT fileid FROM $project WHERE state != '".AVAIL_FIRST."' AND state != '".OUT_FIRST."' AND state != '".SAVE_FIRST."' AND state != '".TEMP_FIRST."' AND state != '".BAD_FIRST."'");
        if ($result != "") { $badpages = mysql_num_rows($result); } else $badpages = 0;
        if ($badpages > 0) {
            $state = PROJ_PROOF_FIRST_BAD_PROJECT;
            mysql_query("UPDATE projects SET state = '$state' WHERE projectid = '$project'");
        }
        $pagesleft += ($projectinfo->total_pages + $projectinfo->avail1_pages);
        $projectinfo->availablepages = $projectinfo->avail1_pages;

    }

    $projectinfo->update($project, $state);

    // Decide which round the project is in
    if ($state == PROJ_PROOF_FIRST_AVAILABLE ||
        $state == PROJ_PROOF_FIRST_WAITING_FOR_RELEASE ||
        $state == PROJ_PROOF_FIRST_BAD_PROJECT ||
        $state == PROJ_PROOF_FIRST_VERIFY ||
        $state == PROJ_PROOF_FIRST_COMPLETE) {
      $outtable = $projectinfo->out1_rows;
      $numoutrows = $projectinfo->out1_pages;
      $temptable = $projectinfo->temp1_rows;
      $numtemprows = $projectinfo->temp1_pages;
      $timetype = "round1_time";
      $texttype = "round1_text";
      $usertype = "round1_user";
      $newstate = AVAIL_FIRST;

    } else if ($state == PROJ_PROOF_SECOND_AVAILABLE ||
        $state == PROJ_PROOF_SECOND_WAITING_FOR_RELEASE ||
        $state == PROJ_PROOF_SECOND_BAD_PROJECT ||
        $state == PROJ_PROOF_SECOND_VERIFY ||
        $state == PROJ_PROOF_SECOND_COMPLETE) {
      $outtable = $projectinfo->out2_rows;
      $numoutrows = $projectinfo->out2_pages;
      $temptable = $projectinfo->temp2_rows;
      $numtemprows = $projectinfo->temp2_pages;
      $timetype = "round2_time";
      $texttype = "round2_text";
      $usertype = "round2_user";
      $newstate = AVAIL_SECOND;

    }

    if (($state == PROJ_PROOF_FIRST_VERIFY) ||
        ($state == PROJ_PROOF_SECOND_VERIFY) ||
        (($state == PROJ_PROOF_FIRST_AVAILABLE) && ($projectinfo->availablepages == 0)) || 
        (($state == PROJ_PROOF_SECOND_AVAILABLE) && ($projectinfo->availablepages == 0))) {

        echo "Found \"$nameofwork\" to verify = $project<BR>";

        // Check in MIA pages
        $page_num = 0;
        $dietime = time() - 14400; // 4 Hour TTL

        while ($page_num < $numoutrows) {

            $fileid = mysql_result($outtable, $page_num, "fileid");
            $timestamp = mysql_result($outtable, $page_num, $timetype);

            if ($timestamp == "") $timestamp = $dietime;

            if ($timestamp <= $dietime) {
                  $sql = mysql_query("UPDATE $project SET state = '$newstate', $timetype = '' WHERE fileid = '$fileid'");
            }
            $page_num++;
        }

        // Check in MIA temp pages
        $page_num2 = 0;

        while ($page_num2 < $numtemprows) {

            $fileid = mysql_result($temptable, $page_num2, "fileid");
            $timestamp = mysql_result($temptable, $page_num2, $timetype);

            if ($timestamp == "") $timestamp = $dietime;

            if ($timestamp <= $dietime) {
                  $sql = mysql_query("UPDATE $project SET state = '$newstate', $timetype = '' WHERE fileid = '$fileid'");
            }
            $page_num2++;
        }

        $projectinfo->update($project, $state);

        if (($state == PROJ_PROOF_FIRST_AVAILABLE) || ($state == PROJ_PROOF_FIRST_VERIFY)) {

            if ($projectinfo->done1_pages == $projectinfo->total_pages) { $state = PROJ_PROOF_FIRST_COMPLETE; } else $state = PROJ_PROOF_FIRST_AVAILABLE;

        } else if (($state == PROJ_PROOF_SECOND_AVAILABLE) || ($state == PROJ_PROOF_SECOND_VERIFY)) {

            if ($projectinfo->done2_pages == $projectinfo->total_pages) { $state = PROJ_PROOF_SECOND_COMPLETE; } else $state = PROJ_PROOF_SECOND_AVAILABLE;
        }

        $sql = "UPDATE projects SET state = '$state' WHERE projectid = '$project'";
        echo "New state = $state<P>";
        $result = mysql_query($sql);
    }

    // Promote Level
    if ($state == PROJ_PROOF_FIRST_COMPLETE) {

        $timestamp = time();
        $updatefile = mysql_query("UPDATE $project SET state = '".AVAIL_SECOND."', round2_time = '$timestamp'");

        echo "Found project to promote = $project<BR>";

        $updatefile = mysql_query("UPDATE projects SET state = '".PROJ_PROOF_SECOND_AVAILABLE."' WHERE projectid = '$project'");
    }

    // Completed Level
    if ($state == PROJ_PROOF_SECOND_COMPLETE) {
        sendtopost($project, $username, $todaysdate);
    }
    $rownum++;
  }
  print "Total pages available = ".$pagesleft."<BR>";

  autorelease($pagesleft);
?>
