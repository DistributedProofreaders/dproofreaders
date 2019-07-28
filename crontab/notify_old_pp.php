<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'User.inc');
include_once($relPath.'post_processing.inc'); // get_pp_projects_past_threshold

// check that caller is localhost or bail
if(!requester_is_localhost())
    die("You are not authorized to perform this request.");

$projects_by_PPer = get_pp_projects_past_threshold();

// now send them notifications
foreach($projects_by_PPer as $PPer => $projects)
{
    send_pp_reminders($PPer, $projects);
}

//---------------------------------------------------------------------------

function send_pp_reminders($PPer, $projects)
{
    global $code_url, $general_help_email_addr, $forums_url,
           $site_name, $site_signoff, $site_abbreviation;
    global $pp_alert_threshold_days;

    $urlbase = "$code_url/project.php?id=";

    $numprojs = count($projects);

    $projects_list = "";
    foreach($projects as $project)
    {
        $nameofwork = $project["nameofwork"];
        $authorsname = $project["authorsname"];
        $projectid = $project["projectid"];
        $modifieddate = strftime("%e %B %Y", $project["modifieddate"]);
        $lastvisitdate = strftime("%e %B %Y", $project["lastvisitdate"]);

        $url = $urlbase . $projectid;
        $work_details  = "$nameofwork by $authorsname ($projectid)";
        $time_details = "[checked out since $modifieddate, project last visited $lastvisitdate]";
        $projects_list .= "$work_details\n$time_details\n    $url\n\n";
    }

    echo "\nNotifying $PPer about $numprojs project(s):\n$projects_list";

    $user = new User($PPer);
    $email = $user->email;

    if($numprojs == 1)
    {
        // only one project
        $url = $urlbase . $projects[0]["projectid"];

        $subject = "$site_abbreviation: Status update needed for 1 project checked out for PPing over $pp_alert_threshold_days days";

        $message = <<<EOF
Hello $PPer,

Our database indicates that you have had a PP project checked out for more than $pp_alert_threshold_days days:

$projects_list
If you haven't yet finished and wish to continue working on this book, please visit $url. This will update the status of the project. If you need help please forward a copy of this email (quoting the information on the book, above) with a brief description of the status to $general_help_email_addr.

If you have completed your work on the book, please visit $url. Select the 'Upload for verification' option and follow the prompts. You will be able to leave a message for the verifier during this process, if you have any special information or comments to pass on.

If you are waiting on missing images or page scans, please add the details to the Missing Page Wiki at: $forums_url/viewtopic.php?t=7584

If you no longer wish to have this text assigned to you please visit the $site_name website Post Processing section and select Return to Available for this book, or forward this email to $general_help_email_addr and state that you would no longer like to have the book in question assigned to you so that we may return it to the available pool for someone else to work on.

$site_signoff
EOF;
    }
    else
    {
        // more than one project
        $subject = "$site_abbreviation: Status updates needed for $numprojs projects checked out for PPing over $pp_alert_threshold_days days";

        $message = <<<EOF
Hello $PPer,

Our database indicates that you have had $numprojs PP projects checked out for more than $pp_alert_threshold_days days:

$projects_list
If you wish to continue working on some or all of these books, please visit each such project's home-page (copy the URL listed with the project above and paste it into your browser's address-field). Doing this will update the status of the project and let us know that you are still working on it. If you need help please forward this email, quoting the list of books, with a brief description of the status for each of the various books listed above that you need help with to $general_help_email_addr.

If you have completed your work on any of these books, please visit each such project's home-page (copy the URL listed with the project above and paste it into your browser's address-field). Select the 'Upload for verification' option and follow the prompts. You will be able to leave a message for the verifier during this process, if you have any special information or comments to pass on.

If you are waiting on missing images or page scans, please add the details to the Missing Page Wiki at: $forums_url/viewtopic.php?t=7584

If you no longer wish to have some or all of these books assigned to you please visit the $site_name website Post Processing section and select Return to Available for the books in question or forward this email to $general_help_email_addr and state that you would no longer like to have the books in question assigned to you so that we may return them to the available pool for someone else to work on.

$site_signoff
EOF;
    }

    $mail_accepted = maybe_mail($email, $subject, $message);
    if(!$mail_accepted)
    {
        echo "WARNING: Email failed to send for $PPer <$email>\n";
    }
}

// vim: sw=4 ts=4 expandtab
