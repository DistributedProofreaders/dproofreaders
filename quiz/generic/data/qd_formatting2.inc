<?php


$browser_title                = _("Formatting Quiz");
$intro_title                  = sprintf(_("Formatting Quiz, page %d"), 2);;
$initial_instructions         = $initial_instructions__F;
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$initial_text                 = "a box of St. Louis candy for his wife, and spins back\nhome, where he listens to music \"canned\" in New Jersey.\n\nTHE BETTER WAY\nCharles M. Schwab, congratulated in Pittsburgh on a\nlarge wired telegraph order contract that had been re-\nceived from one of the warring nations, said:\n\"Some call it luck, but they are mistaken -- whatever\nsuccess I have is due to hard work and not to luck.";
$solutions                    = array("a box of St. Louis candy for his wife, and spins back\nhome, where he listens to music \"canned\" in New Jersey.\n\n\nTHE BETTER WAY\n\nCharles M. Schwab, congratulated in Pittsburgh on a\nlarge wired telegraph order contract that had been received\nfrom one of the warring nations, said:\n\n\"Some call it luck, but they are mistaken--whatever\nsuccess I have is due to hard work and not to luck.");


// error messages
$messages["sectionspacing"] = array(
    "message_title" => _("Section heading spacing incorrect"),
    "message_body" => _("Leave two blank lines before a section heading, and one after it."),
    "guideline" => "sect_head"
);
$messages["sectionmarkup"] = array(
    "message_title" => _("Markup in section heading"),
    "message_body" => sprintf(_('Please do not add bold or small caps markup to the heading. (<span style="%1$s">Mixed Case</span> small caps would be marked, but not <span style="%1$s">all small caps</span> on their own line.)'), 'font-variant: small-caps;'),
    "guideline" => "sect_head"
);
$messages["sectionchange"] = array(
    "message_title" => _("Section heading changed"),
    "message_body" => _("Please leave the text of the section heading the way it was printed; do not change ALL CAPS to Mixed Case."),
    "guideline" => "sect_head"
);
$messages["hyphenrej"] = array(
    "message_title" => _("Hyphen not rejoined"),
    "message_body" => _("If any hyphens have not been rejoined by the pre-processing software or the proofreaders, bring the hyphenated word up so that it is all on one line."),
);
$messages["spacedem"] = array(
    "message_title" => _("Spaced em-dash in text"),
    "message_body" => "<kbd>" . _("Remove the spaces surrounding any dashes--like this!") . "</kbd>",
);
$messages["missingline"] = array(
    "message_title" => _("Missing blank line"),
    "message_body" => _("Paragraphs should be separated by blank lines."),
);
$messages["longline"] = array(
    "message_title" => _("Long line"),
    "message_body" => _("You've probably joined two lines by deleting a line break. If you join words around hyphens or dashes, move only one word up to the end of the previous line."),
);


// error checks
$tests[] = array("type" => "forbiddentext", "searchtext" => array(" --", "-- "), "case_sensitive" => TRUE, "error" => "spacedem");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("<sc>", "<b>"), "case_sensitive" => TRUE, "error" => "sectionmarkup");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("THE BETTER WAY"), "case_sensitive" => TRUE, "error" => "sectionchange");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array(".\n\n\nTHE BETTER WAY\n\nC"), "case_sensitive" => TRUE, "error" => "sectionspacing");
$tests[] = array("type" => "forbiddentext", "searchtext" => "re-", "case_sensitive" => TRUE, "error" => "hyphenrej");
$tests[] = array("type" => "forbiddentext", "searchtext" => ":\n\"Some", "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "longline", "lengthlimit" => 80, "error" => "longline");

// vim: sw=4 ts=4 expandtab
