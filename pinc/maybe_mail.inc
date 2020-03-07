<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'Project.inc');
include_once($relPath.'User.inc');

function maybe_mail( $to, $subject, $message, $additional_headers = null )
// If this is for real, send the message.
// If we're testing, just report what would have been sent.
{
    global $testing, $auto_email_addr;

    assert(is_null($additional_headers) || is_array($additional_headers));
    if (empty($additional_headers))
    {
        $additional_headers = array();
    }

    if (!preg_grep('/^From: /', $additional_headers))
    {
        array_unshift($additional_headers, "From: $auto_email_addr");
        if (!preg_grep('/^Reply-To: /', $additional_headers))
        {
            array_unshift($additional_headers, "Reply-To: $auto_email_addr");
        }
    }

    $additional_headers = implode($additional_headers, "\r\n") . "\r\n";

    if ( $testing )
    {
        echo "\n<hr>\n";
        echo "\$testing is $testing. If it were FALSE, the following mail would have been sent:\n";
        echo "<pre>\n";
        echo html_safe("To: $to\n");
        echo html_safe("Subject: $subject\n");
        echo html_safe("$additional_headers");
        echo "</pre>";
        echo "<span class='mono'>";
        echo nl2br(html_safe("$message\n"));
        echo "</span>\n";
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
    global $code_url, $site_name, $site_signoff, $site_abbreviation;
    global $charset, $dyn_locales_dir, $system_locales_dir;

    $projectid = $project->projectid;
    $nameofwork = $project->nameofwork;
    $username = $project->username;
    $user = new User($username);
    $locale = get_valid_locale_for_translation($user->u_intlang);

    // restore user locale for any future messages.
    configure_gettext($charset, get_desired_language(), $dyn_locales_dir, $system_locales_dir);

    $body =
_("Hello")." $username,\n".
sprintf(_("This is an automated message from the %s site."),$site_name)."

"._("Regarding").":
\"$nameofwork\"
  ($projectid)
  $code_url/project.php?id=$projectid

$info

$site_signoff
";
    configure_gettext($charset, $locale, $dyn_locales_dir, $system_locales_dir);
    return maybe_mail($user->email, "$site_abbreviation: $prefix: \"$nameofwork\"", $body);
}

// vim: sw=4 ts=4 expandtab
