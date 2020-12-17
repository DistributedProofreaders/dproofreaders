<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'forum_interface.inc');

// Two mails are sent out to new users. First an activation mail where they
// need to follow a link in order to complete the registration. Then,
// a welcome mail.

// This method should not use _(...) to localize.
// This is because this function may be called by a site admin to send
// a mail to a completely different user (e.g. a Polish user shouldn't
// get a Spanish mail because one of the site admins is Spanish).
function maybe_activate_mail($email, $real_name, $ID, $username, $u_intlang)
{
    global $code_url, $site_name;

    $activate = "Please activate your account at the $site_name site!";

    $body = <<<EOMAIL

Hello $real_name

This is an automated response to a request for a new account at
$site_name with the user name: $username

To complete your registration, please visit this URL:

$code_url/accounts/activate.php?id=$ID

We require this confirmation step to prevent someone from registering
an account in your name without your knowledge. If you do not follow
this URL, the account will not be activated.

Once you have activated your account, you will receive a new email
which contains basic information, tips and tricks to help make
your experience of the site pleasant. At the bottom of that email
is a collection of common questions which new volunteers may have,
and their answers.

Thanks,

The $site_name Team

EOMAIL;

    maybe_mail($email, $activate, $body);
}

function maybe_welcome_mail($email, $real_name, $username)
{
    global $code_url, $site_url, $forums_url;
    global $site_name, $site_abbreviation, $ELR_round;
    global $general_help_email_addr;

    $welcome = "Welcome to the $site_name Site!";

    $reset_password_url = get_reset_password_url();

    // SITE-SPECIFIC
    // Much of the contents of the email below is DP site-specific, however
    // not so much that it would be horrible for non-DP sites to use it without
    // customizing it.
    $dp_wiki_url = "https://www.pgdp.net/wiki";

    // only include the feedback paragraph if we are running at pgdp.*
    if(stripos($code_url, '//www.pgdp.') !== FALSE)
    {
        $feedback_pm_url = get_url_to_compose_message_to_user('dp-feedback');
        $feedback_paragraph = <<<EOPARA
For feedback on projects other than Beginners Only, please send a message
to dp-feedback via email ( dp-feedback@pgdp.net ) or private message
( $feedback_pm_url ).

EOPARA;
     }
     else
     {
         $feedback_paragraph = "";
     }

    $body = <<<EOMAIL

$real_name, welcome to $site_name at $site_url
and thank you for registering as a volunteer!

Your user name is: $username

Please retain this email for future reference.

The information below is also available on our website at
$dp_wiki_url/DP_Official_Documentation:General/DP_Welcome_Email

-------------------------

Here's how to get started:

Before working on the site or posting to the forums,
please read our Code of Conduct at
$dp_wiki_url/DP_Official_Documentation:General/Code_of_Conduct
and our Mission Statement at
$dp_wiki_url/DP_Official_Documentation:General/Distributed_Proofreaders_Mission_Statement

Computer Requirements
---------------------

If you experience any problems when using the site, please make sure that
your computer's time and date are correct and that your browser settings
for $site_name are as follows:

- javascript enabled
- cookies accepted
- popup windows allowed


Start Proofreading
------------------

1. Log in at $site_url

2. The site will walk you through getting started. Look for boxes labeled
   'Welcome New Proofreader!'.

To understand the basics of proofreading at $site_name,
you should review the Proofreading Guidelines Summary:
$code_url/faq/proofing_summary.pdf

Later you should also read the full Proofreading Guidelines:
$dp_wiki_url/DP_Official_Documentation:Proofreading/Proofreading_Guidelines

$feedback_paragraph

Start Smooth Reading
--------------------

Smooth Reading involves reading a book that is almost ready for posting
to Project Gutenberg and reporting anything that disrupts the sense or
flow of the book.

For detailed information on how to Smooth Read, please visit the
Smooth Readers FAQ:
$dp_wiki_url/DP_Official_Documentation:Smooth_Reading/Smooth_Reading_FAQ


Questions
---------

For general questions about $site_name, please send an email to
$general_help_email_addr or ask in our forums at $forums_url


General Overview
----------------

For a general overview of $site_name and other resource material,
including a list of other ways you can help at $site_name,
please check out our New Volunteer Frequently Asked Questions at
$dp_wiki_url/DP_Official_Documentation:General/New_Volunteer_Frequently_Asked_Questions
and browse the resources in our Information for New and
Returning Volunteers area at
$dp_wiki_url/DP_Official_Documentation:General#Information_For_New_and_Returning_Volunteers

-------------------------

Thank you again for joining $site_name. Every page you work on
helps us make our books available to the public more rapidly. We hope you
will enjoy volunteering here and we appreciate your contribution towards
our effort to provide an ever larger and richer collection of free quality
e-books to the world.

Thanks,

The $site_name Team

EOMAIL;

    maybe_mail($email, $welcome, $body);
}

// vim: sw=4 ts=4 expandtab
