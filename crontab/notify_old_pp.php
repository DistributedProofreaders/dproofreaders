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

// if the script is run before the 14th of the month, send out the first email
// otherwise send out the second one
if(strftime("%d", time()) < 14)
{
    $which_message = "first";
}
else
{
    $which_message = "second";
}

// now send them notifications
foreach($projects_by_PPer as $PPer => $projects)
{
    send_pp_reminders($PPer, $projects, $which_message);
}

//---------------------------------------------------------------------------

function send_pp_reminders($PPer, $projects, $which_message)
{
    global $code_url, $db_requests_email_addr,
           $site_signoff, $site_abbreviation;
    global $pp_alert_threshold_days;
    global $charset, $dyn_locales_dir, $system_locales_dir;

    $user = new User($PPer);
    $locale = get_valid_locale_for_translation($user->u_intlang);

    // configure gettext to translate user email
    configure_gettext($charset, $locale, $dyn_locales_dir, $system_locales_dir);

    $projects_list = [];
    foreach($projects as $project)
    {
        $nameofwork = $project["nameofwork"];
        $authorsname = $project["authorsname"];
        $projectid = $project["projectid"];
        $modifieddate = strftime("%e %B %Y", $project["modifieddate"]);
        $lastvisitdate = strftime("%e %B %Y", $project["lastvisitdate"]);

        // TRANSLATORS: %1$s is a project title, %2%s is the author, %3%s is the projectid
        $work_details = sprintf(_('%1$s by %2%s (%3$s)'), $nameofwork, $authorsname, $projectid);
        // TRANSLATORS: %1$s and %2$s are already-translated date strings
        $time_details = sprintf(_('[checked out since %1$s, project last visited %2$s]'), $modifieddate, $lastvisitdate);;
        $projects_list[] = "$work_details\n$time_details\n    $code_url/project.php?id=$projectid";
    }

    $projects_list_string = implode("\n\n", $projects_list);

    $numprojs = count($projects);
    echo "\nNotifying $PPer about $numprojs project(s):\n$projects_list_string\n\n";

    $message = [];
    if($which_message == "first")
    {
        // TRANSLATORS: %s is the site abbreviation (eg: 'DP')
        $subject = sprintf(_("%s: Renew or Return Post-Processing Projects"), $site_abbreviation);

        $message[] = sprintf(_("Hello %s,"), $PPer);
        $message[] = _("This is an automated message.");
        $message[] = sprintf(_("Our database system indicates that you have had one or more projects checked out for Post-Processing for more than %d days:"), $pp_alert_threshold_days);
        $message[] = $projects_list_string;
        $message[] = _("It is important that books don't get stuck in the Post-Processing process but keep moving towards being posted at Project Gutenberg. As a community, we have a lot vested in each project -- each represents many hours of volunteer work. We are all eager to see each book posted and there is also a risk that that work would be lost should other organizations produce and post a book before we finish.");

        $message[] = '==' . _("Do you wish to continue or to stop working on these books?") . "==";
        $message[] = _("If you haven't yet finished and wish to continue working on these books, please visit the project pages listed above. This will update the status of the projects.");
        $message[] = _("If you no longer wish to have these projects assigned to you, please visit the project pages and select 'Return to Available', or reply to this email and state that you no longer wish to have the projects in question assigned to you so that we may return them to the pool for someone else to work on.");

        $message[] = "==" . _("Have you completed your work?") . "==";
        $message[] = _("If you have completed your post-processing work, please visit the appropriate link listed above, select the 'Upload for verification' option on the Project Page, and follow the prompts. If you have any special information or comments to pass on, you will be able to leave a message for the verifier during this process. If you have Direct Upload capability and no longer use Post-Processing Verification, please upload each completed project directly to PG.");

        $message[] = "==" . _("Do you need help with a particular technical aspect of the book?") . "==";
        $message[] = _("If you have questions about how to handle a difficult table or need some help tidying up awkward illustrations, remember that the DP Post-Processing forum is full of volunteers with a wide range of skills and interests who are able and willing to help.");

        $message[] = "==" . _("Are there any problems?") . "==";
        $message[] = sprintf(_("If any project is missing page images or illustrations, please contact the Project Manager (PM) for assistance. If the PM does not respond within a reasonable amount of time, please email %s and request help concerning any problems."), $db_requests_email_addr);
        $message[] = $site_signoff;
    }
    else
    {
        // TRANSLATORS: %s is the site abbreviation (eg: 'DP')
        $subject = sprintf(_("%s Second Notice: Renew or Return Post-Processing Projects"), $site_abbreviation);

        $message[] = sprintf(_("Hello %s,"), $PPer);
        $message[] = _("An automated reminder was sent to you on the first of the month to ask whether you wished to renew the projects you have checked out for Post-Processing. You are receiving this second automated notice because you have not renewed or returned the projects.");
        $message[] = $projects_list_string;
        $message[] = sprintf(_("If you have already notified %s of an expected absence, you may safely ignore this message. Otherwise, any projects in your queue for which a previous notice has been sent may be reclaimed if you do not respond or renew within the next 7 days."), $db_requests_email_addr);
        $message[] = $site_signoff;
    }

    $email = $user->email;
    $message_string = implode("\n\n", $message);

    $headers = [ "Reply-To: $db_requests_email_addr" ];

    $mail_accepted = maybe_mail($email, $subject, $message_string, $headers);
    if(!$mail_accepted)
    {
        echo "WARNING: Email failed to send for $PPer <$email>\n";
    }
}

// vim: sw=4 ts=4 expandtab
