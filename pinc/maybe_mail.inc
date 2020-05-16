<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'Project.inc');
include_once($relPath.'User.inc');

function maybe_mail( $to, $subject, $message, $additional_headers = null )
// If this is for real, send the message.
// If we're testing, just report what would have been sent.
{
    global $testing, $auto_email_addr, $site_name, $site_url;

    assert(is_null($additional_headers) || is_array($additional_headers));
    if (empty($additional_headers))
    {
        $additional_headers = array();
    }

    // All emails are sent as UTF-8
    $additional_headers[] = "Content-Type: text/plain; charset=UTF-8";

    // Ensure that the From and Reply-To headers are set
    if (!preg_grep('/^From: /', $additional_headers))
    {
        array_unshift($additional_headers, "From: $auto_email_addr");
        if (!preg_grep('/^Reply-To: /', $additional_headers))
        {
            array_unshift($additional_headers, "Reply-To: $auto_email_addr");
        }
    }

    $additional_headers = implode($additional_headers, "\r\n") . "\r\n";

    // Append a standard footer to all emails sent out by the system.
    // This replaces the $site_signoff in prior versions that was not
    // translatable.
    if(!endswith($message, "\n"))
    {
        $message .= "\n";
    }

    $message .= implode("\n", [
        "",
        "--",
        $site_name,
        $site_url,
        "",
        _("This is an automated message."),
    ]);

    if ( $testing )
    {
        echo "\n<hr>\n";
        echo "\$testing is $testing. If it were FALSE, the following mail would have been sent:\n";
        echo "<pre style='white-space: pre-wrap'>\n";
        echo html_safe("To: $to\n");
        echo html_safe("Subject: $subject\n");
        echo html_safe("$additional_headers");
        echo "</pre>";
        echo "<pre style='white-space: pre-wrap'>";
        echo html_safe("$message\n");
        echo "</pre>\n";
        echo "<hr>\n";

        return True;
    }
    else
    {
        return mail( $to, $subject, $message, $additional_headers );
    }
}

function maybe_mail_project_manager( $project, $info, $prefix)
{
    global $code_url, $site_abbreviation, $site_name;

    $projectid = $project->projectid;
    $nameofwork = $project->nameofwork;
    $username = $project->username;
    configure_gettext_for_user($username);

    $body = implode("\n", [
        sprintf(_("Hello %s,"), $username),
        $project->email_introduction(),
        $info,
        sprintf(_("Thank you for volunteering with %s!"), $site_name),
    ]);

    configure_gettext_for_user();
    $user = new User($username); 
    return maybe_mail($user->email, "$site_abbreviation: $prefix: \"$nameofwork\"", $body);
}

// vim: sw=4 ts=4 expandtab
