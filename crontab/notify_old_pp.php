<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'User.inc');

// check that caller is localhost or bail
if(!requester_is_localhost())
    die("You are not authorized to perform this request.");

$old_date = time() - 7776000; // 90 days ago.

// send reminder email to PPers with projects checked out for longer than
// 90 days

//get projects that have been checked out longer than old_date
$sql = "
    SELECT
        nameofwork,
        checkedoutby,
        projects.projectid,
        authorsname,
        DATE_FORMAT(FROM_UNIXTIME(user_project_info.t_latest_home_visit), '%e %M  %Y') as nicedate
    FROM projects LEFT OUTER JOIN user_project_info ON
        projects.projectid = user_project_info.projectid AND
        projects.checkedoutby = user_project_info.username
    WHERE state = '".PROJ_POST_FIRST_CHECKED_OUT."' AND
        user_project_info.t_latest_home_visit <= $old_date
    ORDER BY checkedoutby, user_project_info.t_latest_home_visit;
";
$result = mysqli_query(DPDatabase::get_connection(), $sql);

$rownum = 0;

$PPinQuestion = "";
$lastwork = "";
$projectslist = "";
$numprojs = 0;
$urlbase = "$code_url/project.php?expected_state=proj_post_first_checked_out&id=";

while ($row = mysqli_fetch_assoc($result)) {

    $nameofwork = $row["nameofwork"];
    $authorsname = $row["authorsname"];
    $checkedoutby = $row["checkedoutby"];
    $projectid = $row["projectid"];
    $nicedate = $row["nicedate"];

    if ($PPinQuestion != $checkedoutby) {
        // have finished the last PPer. Send email to them
        if ($rownum > 0) {

            $user = new User($PPinQuestion);
            $email = $user->email;

            echo $PPinQuestion . "\n" . $projectslist ."\n\n";

            if ($numprojs == 1) {
                $message = "Hello $PPinQuestion,\n\nThis is an automated message.\n\n
Our database indicates that you have had a PP project checked out for more than 90 days:\n\n
$projectslist\n\n
If you haven't yet finished and wish to continue working on this book, please log in to $site_url and visit $url . This will update the status of the project. If you need help please forward a copy of this email (quoting the information on the book, above) with a brief description of the status to $general_help_email_addr.\n\n
If you have completed your work on the book, please log in to $site_url and visit $url. Select the 'Upload for verification' option and follow the prompts. You will be able to leave a message for the verifier during this process, if you have any special information or comments to pass on.\n\n
If you are waiting on missing images or page scans, please add the details to the Missing Page Wiki at: $forums_url/viewtopic.php?t=7584\n\n
If you no longer wish to have this text assigned to you please visit the $site_name website Post Processing section and select Return to Available for this book, or forward this email to $general_help_email_addr and state that you would no longer like to have the book in question assigned to you so that we may return it to the available pool for someone else to work on.\n\n
$site_signoff";
            } else {
                $message = "Hello $PPinQuestion,\n\nThis is an automated message.\n\n
Our database indicates that you have had $numprojs PP projects checked out for more than 90 days:\n\n
$projectslist\n\n
If you wish to continue working on some or all of these books, please log in to $site_url and visit each such project's home-page (copy the URL listed with the project above and paste it into your browser's address-field). Doing this will update the status of the project and let us know that you are still working on it. If you need help please forward this email, quoting the list of books, with a brief description of the status for each of the various books listed above that you need help with to $general_help_email_addr.\n\n
If you have completed your work on any of these books, please log in to $site_url and visit each such project's home-page (copy the URL listed with the project above and paste it into your browser's address-field). Select the 'Upload for verification' option and follow the prompts. You will be able to leave a message for the verifier during this process, if you have any special information or comments to pass on.\n\n
If you are waiting on missing images or page scans, please add the details to the Missing Page Wiki at: $forums_url/viewtopic.php?t=7584\n\n
If you no longer wish to have some or all of these books assigned to you please visit the $site_name website Post Processing section and select Return to Available for the books in question or forward this email to $general_help_email_addr and state that you would no longer like to have the books in question assigned to you so that we may return them to the available pool for someone else to work on.\n\n
$site_signoff";
            }

            maybe_mail("$email", "$subject","$message");

            $projectslist = "";
            $numprojs = 0;
         }
        $PPinQuestion = $checkedoutby;
    }

    $numprojs++;

    $url = $urlbase . $projectid;

    $projectslist .= "$nameofwork by $authorsname ($projectid), out since $nicedate\n$url\n\n";
    if ($numprojs == 1) {
        $subject = "$site_abbreviation: Status update needed for 1 project checked out for PPing over 90 days";
    } else {
        $subject = "$site_abbreviation: Status updates needed for $numprojs projects checked out for PPing over 90 days";
    }

    $rownum++;
}

// vim: sw=4 ts=4 expandtab
