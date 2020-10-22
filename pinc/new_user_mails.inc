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


Log in to the $site_name Site
----------------------------

1. Go to $site_url .

2. Enter your user name (ID) and password in the two boxes in the upper
   right-hand portion of the window and click on the "Sign In" button. If
   you don't see the log-in boxes, but do see your username on the far right
   of the navigation bar, you are already logged in.

3. Once you've logged in, please look at the navigation bar at the top of
   the screen. This bar has links to the major areas of the site, including
   {$ELR_round->id} (the first proofreading round), SR (the Smooth Reading pool where you
   can access pre-released books), Forums, Wiki (documentation), and Prefs
   (your personal preferences). Near the top of the right-hand side of
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

Note: If you use iOS 11 or later to proofread and haven't already disabled
"Smart" punctuation, your device may insert characters such as curly quotes
that are not valid at $site_name for proofreading. This will cause an error
when you try to save the page. To correct this problem, please go to
Preferences -> General -> Keyboard and turn off Smart Punctuation.

1. Before proofreading, please review the Proofreading Guidelines Summary:
   $code_url/faq/proofing_summary.pdf

2. Log in to the site and select the {$ELR_round->id} link at the top of the page.

3. Scroll down the page to the list of Projects Currently Available.

4. Select a project by clicking on the project's title link. It's
   best to select a "BEGINNERS ONLY" project if one is available in
   a language in which you are proficient. BEGINNERS ONLY projects
   have been specially prepared to teach you our style of proofing. You
   can expect to receive feedback from a mentor on pages you proofread
   in BEGINNERS ONLY projects. This feedback will likely come at least
   a few days after you have completed the pages.

5. Once you click on the project's title, a Project Page will appear.
   Scroll down the page and read the Project Comments which contain
   instructions specific to the book you have chosen.

6. Once you're ready to start proofreading, locate the "Start Proofreading"
   link at the bottom of the Project Comments section. Click on the link,
   and the next available page will open in a proofreading interface window
   ready for proofreading.

7. Compare the text in the text box with what is in the associated image.
   Change the text to match the image if it doesn't already do so, taking
   into account the instructions in the Proofreading Guidelines and the
   Project Comments. As you proofread, please keep in mind that you should
   not change what the author wrote. If the author spelled words oddly, we
   leave them spelled that way. If the author wrote outrageous racist or
   biased statements, we leave them that way. If the author used commas
   after every third word, we keep the commas. We are proofreaders, not
   editors or modernizers.

8. When you've finished proofreading the page, click on either the
   "Save as 'Done'" or "Save as 'Done' & Proofread Next Page" button.
   Congratulations--you've just proofread your first page!

9. If you have questions, please don't hesitate to ask them in the
   project's associated discussion topic in the forums (the link to the
   project's topic is on the Project Page above the Project Comments).

10. Once you've proofread several pages, it's a good idea to:
    * Watch for new messages in your Distributed Proofreaders Inbox
      ( $forums_url/ucp.php?i=pm&folder=inbox ) since
      that is how our mentors generally contact volunteers with
      proofreading feedback.
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

2. Read the information on the Smooth Reading pool page. For more detailed
   information read the Smooth Reading section of the wiki
   ( $dp_wiki_url/DP_Official_Documentation:Smooth_Reading ).

3. When you're ready to choose a book to smooth read, scroll down to the
   list of Projects Currently Available.

4. Choose a project by selecting its title. This will take you to the
   book's Project Page.

5. Scroll to the "Smooth Reading" section of the page. It is a good idea
   to let the Post-Processor know that you have volunteered to Smooth Read
   this book. To do this, click on the "Volunteer to SR" button.

6. Choose which file type you want to Smooth Read under the "Download a
   Smooth Reading file" heading on the Project Page, or you may wish to
   simply select "Download all formats". Formats available may be text,
   epub, mobi and HTML. Text, epub and mobi can be used directly by the
   appropriate application, but the HTML version will need to be unzipped
   (For information about zip files, please read $dp_wiki_url/ZIP_file ).
   You may also view Smooth Reading text and HTML in your browser (however,
   there is currently no way for you to annotate these files with any
   possible errors that you notice if you try to Smooth Read them from
   the browser).

7. As you read the book, please mark any issues you find according to the
   instructions given here:
   $dp_wiki_url/DP_Official_Documentation:Smooth_Reading/Smooth_Reading_FAQ#What_are_the_basic_steps_for_Smooth_Reading.3F .
   Because of the difficulties with annotating epub, mobi and HTML files,
   recording questionable areas in the text file is often easiest way to go.

8. Once you have finished reading the book, you should zip the text file
   (For information about zip files, please read $dp_wiki_url/ZIP_file )
   and upload it back to the project page from which you downloaded it.

9. Please keep an eye on the Smooth Reading deadline -- if you are running
   out of time, don't hesitate to send the Post-Processor a Private Message
   and request additional time. Post-Processors are generally very happy to
   give you more time to finish.


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
