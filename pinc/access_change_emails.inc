<?php
include_once($relPath."maybe_mail.inc");

function pp_access_change_email($user, $access_change)
{
    global $site_name, $site_abbreviation, $site_signoff, $db_requests_email_addr;

    // only send emails on grant
    if($access_change != "grant")
        return;

    // configure gettext to translate user email
    configure_gettext_for_user($user);

    // TRANSLATORS: %s is the site abbreviation (eg: 'DP')
    $subject = sprintf(_("%s: Welcome to Post-Processing"), $site_abbreviation);

    $message[] = sprintf(_("Hello %s,"), $user->username);
    $message[] = _("This is an automated message.");
    // TRANSLATORS: %s is the site abbreviation (eg: 'DP')
    $message[] = sprintf(_("Congratulations! You are now eligible to learn to transform %s projects into their final formats, ready for upload to Project Gutenberg. If you're already familiar with HTML and CSS, it may make learning Post-Processing (PP) somewhat easier, but many prolific Post-Processors (PPers) knew no HTML or CSS when they first started, so if you aren't already familiar with them, don't be discouraged!"), $site_abbreviation);
    
    $message[] = sprintf(_("We encourage you to start with an easy book and work with an experienced PPer as a mentor. For help in finding a PP mentor, please contact the PPV Coordinator (%s). In addition, all new Post-Processors' work must go through the Post-Processing Verification (PPV) stage, which is another opportunity to pick up valuable tips and tricks. Please avail yourself of the wealth of information in our official documentation and in the Post-Processing forum."), "ppv-coord@pgdp.net");
    $message[] = _("Here is a list of a few of our resources to get you started:");
    $message[] = "- " . sprintf(_("Official documentation page with links to the FAQs and other Post-Processing resources: %s"), "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV");
    $message[] = "- " . sprintf(_("\"Getting your PP Project Ready for PPV\" document: %s"), "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Getting_your_PP_Project_Ready_for_PPV");
    $message[] = "- " . sprintf(_("Post-Processing Workbench: %s"), "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Post-Processing_Workbench");
    $message[] = sprintf(_("An excellent place to ask Post-Processing-related questions is the forum topic 'The Official \"No Dumb Questions\" thread for PPers': %s"), "https://www.pgdp.net/phpBB3/viewtopic.php?t=15019");
    $message[] = _("It is important that books don't get stuck in the Post-Processing process, but keep moving towards being posted at Project Gutenberg. As a community, we have a lot invested in each project -- each represents many hours of volunteer work. We are all eager to see each book posted and there is also a risk that that work would be lost should other organizations produce and post a book before we finish.");
    $message[] = sprintf(_("The site software sends out reminder emails on the first of every month to all Post-Processors who have projects that have been checked out for 90 days or more since they last visited their books' Project Pages. If you receive such a notice, and are still working on the book, or plan to work on it soon, please visit the project page so that it will renew for another 90 days. This is a regular reminder, and not meant to be discouraging. We realize that it may take new Post-Processors longer than it does more experienced PPers to complete their PP work. See %s for more detailed information."), "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Post-Processing_FAQ#How_long_can_I_keep_a_book_checked_out.3F");
    
    // TRANSLATORS: %s is the site name (eg: 'Distributed Proofreaders')
    $message[] = sprintf(_("Thank you for deciding to work as a Post-Processor! Although the work can be daunting at first, it can be the most rewarding and motivating activity here at %s."), $site_name);
    $message[] = $site_signoff;

    $email = $user->email;
    $message_string = implode("\n\n", $message);

    $headers = [ "Reply-To: $db_requests_email_addr" ];

    $mail_accepted = maybe_mail($email, $subject, $message_string, $headers);

    // restore gettext to current user's locale
    configure_gettext_for_user();
}
