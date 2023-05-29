<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'Project.inc');
include_once($relPath.'User.inc');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_mail($to, $subject, $message, $from = null, $reply_to = null)
// If this is for real, send the message.
// If we're testing, just report what would have been sent.
// Optional $from and $reply_to arguments may either take the form
// "user@example.com" or "Example User <user@example.com>"
{
    global $testing, $phpmailer_smtp_config, $auto_email_addr, $site_name, $site_url;

    $from = $from ?? $auto_email_addr;
    $reply_to = $reply_to ?? $auto_email_addr;

    if (!endswith($message, "\n")) {
        $message .= "\n";
    }

    // Append a standard footer to all emails sent out by the system.
    [$text_footer, $html_footer] = get_standard_email_footers();

    $text_message = $message . $text_footer;

    $Parsedown = new ParsedownExtra();
    $Parsedown->setSafeMode(true);
    $html_message = $Parsedown->text($message) . $html_footer;

    if ($testing) {
        echo "\n<hr style='border-style:double;'>\n";
        echo "\$testing is $testing. If it were FALSE, the following Markdown mail would have been sent:\n";
        echo "<pre style='white-space: pre-wrap'>\n";
        echo html_safe("To: $to\n");
        echo html_safe("Subject: $subject\n");
        echo html_safe("From: $from\n");
        echo html_safe("Reply-To: $reply_to\n");
        echo "</pre>";
        echo "<pre style='white-space: pre-wrap'>";
        echo html_safe("$text_message\n");
        echo "</pre>\n";
        echo "<hr style='border-style:dashed;'>\n";
        echo "$html_message\n"   ;
        echo "<hr style='border-style:double;'>\n";
        // Comment out line below to allow sending of emails when testing
        return true;
    }

    $success = true;
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        foreach ($phpmailer_smtp_config as $key => $value) {
            $mail->$key = $value;
        }

        // Recipients
        [$name, $address] = get_name_address($from);
        $mail->setFrom($address, $name);
        [$name, $address] = get_name_address($reply_to);
        $mail->addReplyTo($address, $name);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html_message;
        $mail->AltBody = $text_message;

        $mail->send();
    } catch (Exception $e) {
        error_log("send_email.inc - Message could not be sent. PHPMailer Error: {$mail->ErrorInfo}");
        $success = false;
    }

    return $success;
}

function send_mail_project_manager($project, $info, $prefix)
{
    global $code_url, $site_abbreviation, $site_name;

    $projectid = $project->projectid;
    $nameofwork = $project->nameofwork;
    $username = $project->username;
    configure_gettext_for_user($username);

    $body = implode("\n", [
        sprintf(_("Hello %s,"), $username),
        "",
        $project->email_introduction(),
        $info,
        "",  // spacer for a blank line
        sprintf(_("Thank you for volunteering with %s!"), $site_name),
    ]);

    configure_gettext_for_user();
    $user = new User($username);
    return send_mail($user->email, "$site_abbreviation: $prefix: \"$nameofwork\"", $body);
}

// Return text and HTML versions of standard email footer
// We intentionally don't translate anything because we don't know
// what the recipient's language is at this point.
function get_standard_email_footers()
{
    global $site_url, $site_name;

    $automated_message = "This is an automated message.";
    $text_footer = implode("\n", [
        "",
        str_repeat("-", strlen($site_name)), // make <hr> from hyphens
        $site_name,
        $site_url,
        "",
        $automated_message,
        "",
    ]);
    $html_footer = implode("\n", [
        "<hr style='width:12em; margin-left:0; margin-top:2em;'>",
        "<a href='$site_url'>$site_name</a><br>",
        "<small>$automated_message</small><br>",
    ]);
    return [$text_footer, $html_footer];
}

// Split a string of form "user@example.com" or "Example User <user@example.com>"
// into the name and the email address.
// Name will be null if not specified.
function get_name_address($string)
{
    $string = rtrim($string, '>');
    $results = explode(' <', $string);
    if (sizeof($results) === 1) {
        $name = null;
        $address = $results[0];
    } else {
        [$name, $address] = $results;
    }
    return [$name, $address];
}

// Add linebreak to end of line for a Markdown email
// Appends two spaces which are then rendered as <br> in HTML
function mdmail_append_linebreak($line)
{
    return $line . '  ';
}

// Return string for horizontal rule for a Markdown email
function mdmail_horizontal_rule()
{
    return '---';
}

// Cause line to be indented for a Markdown email
// Prepends 4 non-breaking spaces
function mdmail_indent_line($line)
{
    return str_repeat("\u{a0}", 4) . $line;
}

// Convert line to a heading in a Markdown email
// Prepends number of hashes corresponding to level
function mdmail_heading($level, $line)
{
    return str_repeat('#', $level) . ' ' . $line;
}
