<?
$relPath = "../../pinc/";
include($relPath.'db_datestamp.inc');
include($relPath.'projectinfo.inc');

  // Auto-Release Project

function numpages($project) {
  $level0rows = mysql_query("SELECT fileid FROM $project");
  if ($level0rows != "") { return (mysql_num_rows($level0rows)); } else return 0;
}

function verifynew($project, $author, $nameofwork, $language, $clearance, $email) {

    $currstate = 8;
    $result = mysql_query("UPDATE $project SET state = 2 WHERE 1");
    $projectinfo = new projectinfo();

    $projectinfo->update($project, $state);

    $page_num = 0;

    if (($author == "") || ($nameofwork == "") || ($language == "") || ($clearance == "")) {
        $currstate = 0;
        mail("$email", "DP: $nameofwork has an empty field.",
             "This is an automated message from the Distributed Proofreaders site.\n\n".
             "$nameofwork has one of the following parameters blank:\n\n".
             "Title: $nameofwork\nAuthor: $author\nLanguage: $language\nClearance Line: $clearance\n\n".
             "Please correct and put back into Waiting for Release.",
             "From: charlz@lvcablemodem.com\r\nReply-To: charlz@lvcablemodem.com\r\n");
    }

    $path = "../../projects/".$project."/";

    while (($page_num < $projectinfo->avail1_pages) && ($currstate == 8)) {
        $fileid = mysql_result($projectinfo->avail1_rows, $page_num, "fileid");
        $text = mysql_result($projectinfo->avail1_rows, $page_num, "master_text");
        $filename = mysql_result($projectinfo->avail1_rows, $page_num, "image");

        if (!file_exists($path.$filename)) {
            $currstate = 0;
            mail("$email", "DP: $nameofwork has missing image $filename.",
                 "This is an automated message from the Distributed Proofreaders site.\n\n".
                 "$nameofwork has $filename image that is missing. Please correct, check other pages, and put back into Waiting for Release.",
                 "From: charlz@lvcablemodem.com\r\nReply-To: charlz@lvcablemodem.com\r\n");
        } else if (filesize($path.$filename) < 100) {
            $currstate = 0;
            mail("$email", "DP: $nameofwork has bad image $filename.",
                 "This is an automated message from the Distributed Proofreaders site.\n\n".
                 "$nameofwork has $filename image with a small file size. Please correct, check other pages, and put back into Waiting for Release.",
                 "From: charlz@lvcablemodem.com\r\nReply-To: charlz@lvcablemodem.com\r\n");
        } else if ($text == "") {
            $currstate = 0;
            mail("$email", "DP: $nameofwork has empty text for $filename.",
                 "This is an automated message from the Distributed Proofreaders site.\n\n".
                 "$nameofwork has $filename with a small text size. Please correct, check other pages, and put back into Waiting for Release.",
                 "From: charlz@lvcablemodem.com\r\nReply-To: charlz@lvcablemodem.com\r\n");
        }

        $page_num++;
    }

    $result = mysql_query("UPDATE projects SET state = $currstate, modifieddate = '$todaysdate' WHERE projectid = '$project'");

    if ($currstate == 8) {
        $pagesleft += (2 * numpages($project));
        print "Released project = $project<BR>";
    }
}

function autorelease($pagesleft) {

  $todaysdate = new db_datestamp();
  $pagesneeded = 20000;
  
  // NON-English Books

  $non_english = mysql_query("SELECT * FROM projects WHERE state = 1 AND language != 'English' ORDER BY modifieddate ASC");

  while (($pagesleft < $pagesneeded) && (mysql_num_rows($non_english) > 0)) {
    $project = mysql_result($non_english, 0, "projectid");
    $author = mysql_result($non_english, 0, "authorsname");
    $nameofwork = mysql_result($non_english, 0, "nameofwork");
    $language = mysql_result($non_english, 0, "language");
    $clearance = mysql_result($non_english, 0, "clearance");
    $mod_date = mysql_result($non_english, 0, "modifieddate");
    $pm = mysql_result($non_english, 0, "username");

    $temp = mysql_query("SELECT email FROM users WHERE username = '$pm'");
    $email = mysql_result($temp, 0, "email");

    if ((mysql_num_rows(mysql_query("SELECT projectid FROM projects WHERE state = 2 AND authorsname = '$author'")) > 0) && ($modifieddate != $todaysdate)) {
      $temp = mysql_query("UPDATE projects SET modifieddate = '$todaysdate' WHERE projectid = '$project'");
    } else {
      verifynew($project, $author, $nameofwork, $language, $clearance, $email);
    }
    $non_english = mysql_query("SELECT * FROM projects WHERE state = 1 AND language != 'English' ORDER BY modifieddate ASC");
  }

  // English Books

  $english = mysql_query("SELECT * FROM projects WHERE state = 1 AND language = 'English' ORDER BY modifieddate ASC");

  while (($pagesleft < $pagesneeded) && (mysql_num_rows($english) > 0)) {
    $project = mysql_result($english, 0, "projectid");
    $author = mysql_result($english, 0, "authorsname");
    $nameofwork = mysql_result($english, 0, "nameofwork");
    $language = mysql_result($english, 0, "language");
    $clearance = mysql_result($english, 0, "clearance");
    $mod_date = mysql_result($english, 0, "modifieddate");
    $pm = mysql_result($english, 0, "username");

    $temp = mysql_query("SELECT email FROM users WHERE username = '$pm'");
    $email = mysql_result($temp, 0, "email");

    if ((mysql_num_rows(mysql_query("SELECT projectid FROM projects WHERE state = 2 AND authorsname = '$author'")) > 0) && ($modifieddate != $todaysdate)) {
      $temp = mysql_query("UPDATE projects SET modifieddate = '$todaysdate' WHERE projectid = '$project'");
    } else {
      verifynew($project, $author, $nameofwork, $language, $clearance, $email);
    }
    $english = mysql_query("SELECT * FROM projects WHERE state = 1 AND language = 'English' ORDER BY modifieddate ASC");
  }
}
?>
