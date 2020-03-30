<?php
include_once($relPath."maybe_mail.inc");

function pp_access_change_email($user, $access_change)
{
    global $site_name, $site_abbreviation, $db_requests_email_addr;

    // only send emails on grant
    if($access_change != "grant")
        return;

    // configure gettext to translate user email
    configure_gettext_for_user($user);

    // TRANSLATORS: %s is the site abbreviation (eg: 'DP')
    $subject = sprintf(_("%s: Welcome to Post-Processing"), $site_abbreviation);

    $message[] = sprintf(_("Hello %s,"), $user->username);

    // TRANSLATORS: %s is the site abbreviation (eg: 'DP')
    $message[] = sprintf(_("Congratulations! You are now eligible to learn to transform %s projects into their final formats, ready for upload to Project Gutenberg. If you're already familiar with HTML and CSS, it may make learning Post-Processing (PP) somewhat easier, but many prolific Post-Processors (PPers) knew no HTML or CSS when they first started, so if you aren't already familiar with them, don't be discouraged!"), $site_abbreviation);

    $message[] = sprintf(_("We encourage you to start with an easy book and work with an experienced PPer as a mentor. For help in finding a PP mentor, please contact the PPV Coordinator (%s). In addition, all new Post-Processors' work must go through the Post-Processing Verification (PPV) stage, which is another opportunity to pick up valuable tips and tricks. Please avail yourself of the wealth of information in our official documentation and in the Post-Processing forum."), "ppv-coord@pgdp.net");

    $message[] = _("Here is a list of a few of our resources to get you started:");

    $message[] = "- " . sprintf(_("Official documentation page with links to the FAQs and other Post-Processing resources: %s"),
        "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV");

    $message[] = "- " . sprintf(_("\"Getting your PP Project Ready for PPV\" document: %s"),
        "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Getting_your_PP_Project_Ready_for_PPV");

    $message[] = "- " . sprintf(_("Post-Processing Workbench: %s"),
        "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Post-Processing_Workbench");

    $message[] = sprintf(_("An excellent place to ask Post-Processing-related questions is the forum topic 'The Official \"No Dumb Questions\" thread for PPers': %s"),
        "https://www.pgdp.net/phpBB3/viewtopic.php?t=15019");

    $message[] = _("It is important that books don't get stuck in the Post-Processing process, but keep moving towards being posted at Project Gutenberg. As a community, we have a lot invested in each project -- each represents many hours of volunteer work. We are all eager to see each book posted and there is also a risk that that work would be lost should other organizations produce and post a book before we finish.");

    $message[] = sprintf(_("The site software sends out reminder emails on the first of every month to all Post-Processors who have projects that have been checked out for 90 days or more since they last visited their books' Project Pages. If you receive such a notice, and are still working on the book, or plan to work on it soon, please visit the project page so that it will renew for another 90 days. This is a regular reminder, and not meant to be discouraging. We realize that it may take new Post-Processors longer than it does more experienced PPers to complete their PP work. See %s for more detailed information."),
        "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Post-Processing_FAQ#How_long_can_I_keep_a_book_checked_out.3F");

    // TRANSLATORS: %s is the site name (eg: 'Distributed Proofreaders')
    $message[] = sprintf(_("Thank you for deciding to work as a Post-Processor! Although the work can be daunting at first, it can be the most rewarding and motivating activity here at %s."), $site_name);

    $email = $user->email;
    $message_string = implode("\n\n", $message);

    $headers = [ "Reply-To: $db_requests_email_addr" ];

    $mail_accepted = maybe_mail($email, $subject, $message_string, $headers);

    // restore gettext to current user's locale
    configure_gettext_for_user();
}


function f1_access_change_email($user, $access_change)
{
    global $site_name, $site_abbreviation;

    // only send emails on grant
    if($access_change != "grant")
        return;

    // configure gettext to translate user email
    configure_gettext_for_user($user);

    // TRANSLATORS: %s is the site abbreviation (eg: 'DP')
    $subject = sprintf(_("%s: Welcome to F1 Formatting"), $site_abbreviation);

    $message[] = sprintf(_("Hello %s,"), $user->username);

    $message[] = _("Congratulations! You may now work in F1, the first round of formatting.");

    $message[] = _("Formatting is the process of adding tags to a project for items such as italics, boldface, small capitals, footnotes, illustrations, poetry and block quotes, as well as appropriate spacing for chapter and section headers, etc. The tags and spacing will assist the Post-Processor in producing an ebook.");

    $message[] = _("After all of the pages of a project are completed in F1, the project moves into F2 for review and any needed corrections. F2 is the final round of formatting before Post-Processing.");

    $message[] = "***" . _("Please keep in mind that we have a 35 page-per-day limit in F1.") . "*** " .
        _("(This is a daily limit, not an average.) The limit is there to make sure that we have a variety of projects available in the round at all times.");

    $message[] = sprintf(_("Before you begin formatting, please read through the Formatting Guidelines: %1\$s . You may also view and print our two-page Formatting Summary: %2\$s to keep on hand while you're formatting. The Guidelines contain a lot of information, so please take your time and ask questions if there's something you don't understand."),
        "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/Formatting_Guidelines",
        "https://www.pgdp.net/c/faq/formatting_summary.pdf");

    $message[] = _("If you have questions, you have several options:");

    $message[] = "- " . _("For questions specific to a project you're working on, please post your question in the project's Project Discussion. There's a link to this on each project's main Project Page on the line reading \"Forum.\"");

    $message[] = "- " . sprintf(_("If you have a general question, you can post in the \"Common Formatting Q&A\" Forum: %s"),
        "https://www.pgdp.net/phpBB3/viewforum.php?f=34");

    $message[] = "- " . _("If you don't know whether the question is a general question or specific to the project, start by asking in the Project Discussion.");

    $message[] = "- " . _("If you'd rather ask a question privately or would like more information on formatting in general, you can send a Private Message to one of our F2 Evaluators:") .
        sprintf("\ncmspence (Carol): %1\$s\nor\nK7UQT (Lisa): %2\$s",
            "https://www.pgdp.net/phpBB3/ucp.php?i=pm&mode=compose&u=69533",
            "https://www.pgdp.net/phpBB3/ucp.php?i=pm&mode=compose&u=24945");

    $message[] = "==" . _("Here is a list of important tips and resources to get you started:") . "==";

    $message[] = "- ***" . _("Please use the Format Preview tool on each page.") . "*** " .
        sprintf(_("Each time you format a page (but before you save it as Done), click on the Format Preview button. This will highlight many common formatting problems so that you may fix them before saving the page. The Format Preview button is labelled \"Preview\" and is found in the rows of buttons below the formatting window. (If your buttons display as icons, the Format Preview icon is a red \"F\" with checkmark.) For more information about this tool, see: %s"),
            "https://www.pgdp.net/wiki/Format_Preview");

    $message[] = "- " . _("Always use the formatting shortcut buttons when adding formatting tags. Manually typing in the tags is more time-consuming and prone to error. To use the shortcut buttons, highlight the text you need to add tags around (using your mouse or keyboard arrows) and click on the appropriate button. The buttons are found along the bottom of the formatting interface.");

     $message[] = "- " . _("If you accidentally add incorrect inline tags, highlight the text again, and click on the button labelled <x> with a horizontal line through the middle. This removes inline formatting only (such as italics, bold, small caps, etc.), but does not affect illustration, footnote, blockquote, nowrap, or other non-inline tagging.");

   $message[] = "- " . sprintf(_("Check out our F1 Self Evaluation Project: %s . It's an easy way to gain formatting experience without working on a 'live' project. You may want to start with this before working on a regular F1 project. Please read the instructions on the Project Page carefully before you begin."),
        "https://www.pgdp.net/c/project.php?id=projectID50cd68d39b208");

    $message[] = "- " . sprintf(_("Once you've become familiar with the Formatting Guidelines, you may want to look through our Library of Formatting Examples (LoFE), which is found in the Formatting forums here: %s . While the LoFE should not be used in place of the Guidelines, it's a useful resource for common formatting issues that may not be covered completely by the Guidelines."),
        "https://www.pgdp.net/phpBB3/viewtopic.php?f=34&t=47569");

    $message[] = "- " . sprintf(_("It's good practice to check your 'diffs' each day to see what changes have been made on your pages after they've been reviewed in F2 so that you can improve your skills. You can easily check diffs with the Review Work Tool: %s"),
        "https://www.pgdp.net/c/tools/proofers/review_work.php?work_round_id=F1&review_round_id=F2&days=100&sample_limit=0");

    // TRANSLATORS: %s is the site name (eg: 'Distributed Proofreaders')
    $message[] = sprintf(_("Again, please don't hesitate to ask if you have questions, and thanks for volunteering at %s."), $site_name);

    $email = $user->email;
    $message_string = implode("\n\n", $message);

    $mail_accepted = maybe_mail($email, $subject, $message_string);

    // restore gettext to current user's locale
    configure_gettext_for_user();
}
