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
    global $code_url, $site_url, $forums_url, $wiki_url;
    global $site_name, $site_abbreviation, $ELR_round;
    global $general_help_email_addr;

    $welcome = "Welcome to the $site_name Site!";

    $reset_password_url = get_reset_password_url();

    // SITE-SPECIFIC
    // Much of the contents of the email below is DP site-specific, however
    // not so much that it would be horrible for non-DP sites to use it without
    // customizing it.
    $dp_wiki_url = "http://www.pgdp.net/wiki";

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


Log into the $site_name Site
----------------------------

1. Go to $site_url .

2. Enter your user name (ID) and password in the two boxes in the upper
   right-hand portion of the window and click on the "Sign In" button. If
   you don't see the log-in boxes, you are already logged in.

3. Once you've logged in, please look at the navigation bar at the top of
   the screen. This bar has links to the major areas of the site, including
   {$ELR_round->id} (the first proofreading round), SR (the Smooth Reading pool where you
   can access pre-released books), Forums, Wiki (documentation), and Prefs,
   (your personal preferences). Near the bottom of the right-hand side of
   the screen is a list of Key Help Documents.


Computer Requirements
---------------------

If you experience any problems when using the site, please make sure that
your computer's time and date are correct and that your browser settings
for $site_name are as follows:

- javascript enabled
- cookies accepted
- popup windows allowed


Problems Logging In
-------------------

If your password doesn't work, please try resetting it by visiting:
$reset_password_url

If you cannot log in to the site or access the forums, please send an
email describing the issue to $general_help_email_addr and include
your user name in the message.


Start Proofreading
------------------

1. Before proofreading, please review the Proofreading Guidelines Summary
   $code_url/faq/proofing_summary.pdf

2. Log into the site and select the {$ELR_round->id} link at the top of the page.

3. Scroll down the page to the list of Projects Currently Available.

4. Choose a project and select it via the title link.

   If a "BEGINNERS ONLY" project is available in a language in which you
   are proficient, it's best to start with that. BEGINNERS ONLY projects
   have been specially prepared to teach you our style of proofing. New
   errors may have been introduced in some places so that you may gain
   experience in locating the various types of errors that you may encounter
   in regular projects. You can expect to receive feedback from a mentor on
   pages you proofread in BEGINNERS ONLY projects. This feedback will likely
   come at least a few days after you have completed the pages.

5. A Project Page will appear. Scroll down the page and read the Project
   Comments which contain instructions specific to the book you have chosen.

6. Once you're ready to start proofreading, scroll down the page to the
   "Start Proofreading" link under the Project Comments section. When you
   select that link, a proofreading interface window will open.

7. Compare the text in the text box with what is in the image above it,
   changing the text to match the image if it doesn't already do so. If
   you need to refer to the Project Comments, open the link which appears
   below the text box-it's best to open this in a new window or tab.

8. When you've finished proofreading the page, click on either the
   "Save as 'Done'" or "Save as 'Done' & Proofread Next Page" button.
   Congratulations--you've just proofread your first page!

9. If you have questions, please don't hesitate to ask them in the
   project's associated discussion topic in the forums (the link to the
   forum is on the Project Page above the Project Comments).

10. Once you've proofread several pages, it's a good idea to:
    * Read the full Proofreading Guidelines at
        $code_url/faq/proofreading_guidelines.php
    * Work through the automated interactive Basic Proofreading Quizzes
      and Tutorials at
        $code_url/quiz/start.php?show_only=PQ
      to get instant feedback and gain confidence in your understanding of
      the $site_name proofreading process.


$feedback_paragraph

Start Smooth Reading
--------------------

Smooth Reading involves reading a book that is almost ready for posting
to Project Gutenberg and reporting anything that disrupts the sense or
flow of the book.

1. Log into the site and select the SR link on the navigation bar.

2. Read the instructions and examples at the top of the page. For more
   detailed information read the Smooth Reading section of the wiki
   ( $dp_wiki_url/DP_Official_Documentation:Smooth_Reading ).

3. When you're ready, scroll down to the list of Projects Currently Available.

4. Choose a project by selecting its title.

5. A Project Page will appear. Scroll down the page to the "Smooth Reading"
   section and click on the "Volunteer to SR" button to inform us that you
   have volunteered to Smooth read that project. (You may withdraw your
   commitment to Smooth Read the project at any time by clicking on the
   "Withdraw from SR" button.)

6. To download a copy of the text for reading, select the "Download zipped
   text for Smooth Reading" link in the "Smooth Reading" section of the
   Project Page. A download window will appear.

7. Save the compressed zip file to your computer. Unzip the file, open the
   book and read it, making a note of anything you wish to report.

8. When you've finished, save and zip your annotated file, and upload it
   to the project page using the "Upload a smooth-read text" link in the
   "Smooth Reading" section of the Project Page.


Questions
---------

For general questions about $site_name, please send an email to
$general_help_email_addr or ask in our forums at $forums_url .


General Overview
----------------

For a general overview of $site_name and other resource material,
including a list of other ways you can help at $site_name,
please check out our New Volunteer Frequently Asked Questions at
$dp_wiki_url/DP_Official_Documentation:General/New_Volunteer_Frequently_Asked_Questions
and browse the resources in our Information for New and
Returning Volunteers area at
$dp_wiki_url/DP_Official_Documentation:General#Information_For_New_and_Returning_Volunteers .

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
