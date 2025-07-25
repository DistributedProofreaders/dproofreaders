<?php
include_once($relPath.'Project.inc');
include_once($relPath.'User.inc');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Send an email
 *
 * If this is for real, send the message. If we're testing, just report what
 * would have been sent.
 *
 * @param string $to
 *   Email address of the recipient
 * @param string $subject
 *   Email subject
 * @param string $message
 *   Email body
 * @param string $from
 *   Optional argument for who the email is from. May either take the form
 *   `user@example.com` or `Example User <user@example.com>`.
 * @param string $reply_to
 *   Optional argument for who the email is from. May either take the form
 *   `user@example.com` or `Example User <user@example.com>`.
 */
function send_mail($to, $subject, $message, $from = null, $reply_to = null)
{
    // If From & Reply-To given, use them
    // If only From given, use it, and don't include Reply-To at all
    // If only Reply-To given, use it, and use default email address for From
    // If neither given, use default email address for From and Reply-To
    if (is_null($from)) {
        $from = SiteConfig::get()->auto_email_addr;
        if (is_null($reply_to)) {
            $reply_to = SiteConfig::get()->auto_email_addr;
        }
    }

    if (!str_ends_with($message, "\n")) {
        $message .= "\n";
    }

    // Append a standard footer to all emails sent out by the system.
    [$text_footer, $html_footer] = get_standard_email_footers();

    $text_message = $message . $text_footer;

    $html_message = render_markdown_as_html($message) . $html_footer;

    if (SiteConfig::get()->testing) {
        echo "\n<hr style='border-style:double;'>\n";
        echo "\$testing is TRUE. If it were FALSE, the following Markdown mail would have been sent:\n";
        echo "<pre style='white-space: pre-wrap'>\n";
        echo html_safe("To: $to\n");
        echo html_safe("Subject: $subject\n");
        echo html_safe("From: $from\n");
        if (!is_null($reply_to)) {
            echo html_safe("Reply-To: $reply_to\n");
        }
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
        foreach (SiteConfig::get()->phpmailer_smtp_config as $key => $value) {
            $mail->$key = $value;
        }

        // Recipients
        [$name, $address] = get_name_address($from);
        $mail->setFrom($address, $name);
        if (!is_null($reply_to)) {
            [$name, $address] = get_name_address($reply_to);
            $mail->addReplyTo($address, $name);
        }
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
        sprintf(_("Thank you for volunteering with %s!"), SiteConfig::get()->site_name),
    ]);

    configure_gettext_for_user();
    $user = new User($username);
    return send_mail($user->email, SiteConfig::get()->site_abbreviation . ": $prefix: \"$nameofwork\"", $body);
}

/**
 * Return text and HTML versions of standard email footer
 *
 * We intentionally don't translate anything because we don't know
 * what the recipient's language is at this point.
 */
function get_standard_email_footers()
{
    $automated_message = "This is an automated message.";
    $text_footer = implode("\n", [
        "",
        str_repeat("-", strlen(SiteConfig::get()->site_name)), // make <hr> from hyphens
        SiteConfig::get()->site_name,
        SiteConfig::get()->site_url,
        "",
        $automated_message,
        "",
    ]);
    $html_footer = implode("\n", [
        "<hr style='width:12em; margin-left:0; margin-top:2em;'>",
        sprintf("<a href='%s'>%s</a><br>", SiteConfig::get()->site_url, SiteConfig::get()->site_name),
        "<small>$automated_message</small><br>",
    ]);
    return [$text_footer, $html_footer];
}

/**
 * Split a string into an email and name.
 *
 * Split a string of form `user@example.com` or `Example User <user@example.com>`
 * into the name and the email address. Name will be null if not specified.
 */
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

/**
 * Add linebreak to end of line for a Markdown email
 *
 * Appends two spaces which are then rendered as <br> in HTML
 */
function mdmail_append_linebreak($line)
{
    return $line . '  ';
}

/**
 * Return string for horizontal rule for a Markdown email
 */
function mdmail_horizontal_rule()
{
    return '---';
}

/**
 * Cause line to be indented for a Markdown email
 *
 * Prepends 4 non-breaking spaces
 */
function mdmail_indent_line($line)
{
    return str_repeat("\u{a0}", 4) . $line;
}

/**
 * Convert line to a heading in a Markdown email
 *
 * Prepends number of hashes corresponding to level
 */
function mdmail_heading($level, $line)
{
    return str_repeat('#', $level) . ' ' . $line;
}
