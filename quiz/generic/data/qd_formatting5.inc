<?php


$browser_title                = _("Formatting Quiz");
$intro_title                  = sprintf(_("Formatting Quiz, page %d"), 5);
$initial_instructions         = $initial_instructions__F;
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$initial_text                 = "CAMPFIRE GIRLS WEAR WHYPSLOVENS     191\n\nthe necessity of nursing her.\"\n\n\"Come on, girls; get your wraps; we will all go over.\nIt's only a couple[1] of blocks. Hurry, everybody!\"\n\n\"Wait, and I'll tell Kitty we're going out,\" Marion said.\nShe ran through several rooms, calling \"Kittie! Kittie!\"\nbut received no response.\nWhere is Kitty?\n\"I wonder where she is,\" the\nhostess said, in a puzzled manner.\n\"Well, we haven't time to find her.\nCome on.\"\n\n\"I think I saw her go out more\nthan half an hour ago,\" Harriet\nNewcomb said. \"She called someone up on the\ntelephone, and then put her hat and coat on and went\nout the side way, and I haven't seen her since.\"\n\n1Harriet was lying; really, the whypsloven factory was\nseveral miles away.";
$solutions                    = array("the necessity of nursing her.\"\n\n\"Come on, girls; get your wraps; we will all go over.\nIt's only a couple[1] of blocks. Hurry, everybody!\"\n\n\"Wait, and I'll tell Kitty we're going out,\" Marion said.\n\nShe ran through several rooms, calling \"Kittie! Kittie!\"\nbut received no response.\n\n[Illustration: Where is Kitty?]\n\n\"I wonder where she is,\" the\nhostess said, in a puzzled manner.\n\"Well, we haven't time to find her.\nCome on.\"\n\n\"I think I saw her go out more\nthan half an hour ago,\" Harriet\nNewcomb said. \"She called someone up on the\ntelephone, and then put her hat and coat on and went\nout the side way, and I haven't seen her since.\"\n\n[Footnote 1: Harriet was lying; really, the whypsloven factory was\nseveral miles away.]");


// error messages
$messages["sqbr"] = array(
    "message_title" => _("Incorrect brackets."),
    "message_body" => _("Please use square brackets <kbd>[]</kbd> for illustration and footnote markup."),
);
$messages["pageheader"] = array(
    "message_title" => _("Page header left in"),
    "message_body" => _("Remove the page header and the page number."),
);
$messages["illusmissing"] = array(
    "message_title" => _("Illustration markup missing."),
    "message_body" => _("It seems you haven't marked the illustration correctly. Put the illustration caption within <kbd>[Illustration: ]</kbd>"),
    "guideline" => "illust"
);
$messages["illuswrong"] = array(
    "message_title" => _("Illustration incorrectly marked."),
    "message_body" => _("Check the case of the caption, and make sure there is a blank line before and after the illustration marker."),
    "guideline" => "illust"
);
$messages["nofn"] = array(
    "message_title" => _("Footnote markup missing."),
    "message_body" => _("It seems you haven't marked the footnote at the bottom of the page correctly. Put the footnote text within <kbd>[Footnote _: ]</kbd> placing the correct marker where the underscore is."),
    "guideline" => "footnotes"
);
$messages["wrongfn"] = array(
    "message_title" => _("Footnote markup wrong."),
    "message_body" => _("It seems you haven't marked the footnote at the bottom of the page correctly. Put the footnote text within <kbd>[Footnote _: ]</kbd> placing the correct marker where the underscore is. Remember to leave a blank line before the footnote."),
    "guideline" => "footnotes"
);
$messages["wrongblank"] = array(
    "message_title" => _("Extra blank line"),
    "message_body" => _("The page starts in the middle of a paragraph, so there should not be a blank line at the top."),
);
$messages["missingline"] = array(
    "message_title" => _("Missing blank line"),
    "message_body" => _("All paragraphs are delimited by blank lines."),
);
$messages["missinglinecheckindents"] = array(
    "message_title" => _("Missing blank line"),
    "message_body" => _("All paragraphs should be separated by blank lines. <b>Hint: Look closely at the indent of each line to see whether it is a new paragraph or not.</b>"),
);
$messages["captionmoved"] = array(
    "message_title" => _("Illustration caption moved"),
    "message_body" => _("Since the illustration caption started off between paragraphs, there is no need to move it."),
    "guideline" => "illust"
);
$messages["tbmarkup"] = array(
    "message_title" => _("Thought break markup present"),
    "message_body" => _("Ignore the dividing line between the regular text and the footnote; just use a single blank line between them, without any thought break markup."),
    "guideline" => "footnotes"
);


// error checks
$tests[] = array("type" => "forbiddentext", "searchtext" => array("CAMPFIRE GIRLS WEAR WHYPSLOVENS", "191"), "case_sensitive" => TRUE, "error" => "pageheader");
$tests[] = array("type" => "forbiddentext", "searchtext" => "\nthe necessity", "case_sensitive" => FALSE, "error" => "wrongblank");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("(Footnote", "{Footnote", "(Illustration", "{Illustration"), "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Illustration: Where is Kitty?]"), "case_sensitive" => FALSE, "error" => "illusmissing");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n[Illustration: Where is Kitty?]\n\n"), "case_sensitive" => TRUE, "error" => "illuswrong");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Footnote"), "case_sensitive" => FALSE, "error" => "nofn");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n[Footnote 1:"), "case_sensitive" => true, "error" => "wrongfn");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n\"Come on"), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n\"Wait,"), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\nShe ran"), "case_sensitive" => TRUE, "error" => "missinglinecheckindents");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "Where is Kitty?", "secondtext" => "puzzled", "case_sensitive" => TRUE, "error" => "captionmoved");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "response", "secondtext" => "Where is Kitty?", "case_sensitive" => TRUE, "error" => "captionmoved");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("<tb>"), "case_sensitive" => TRUE, "error" => "tbmarkup");

// vim: sw=4 ts=4 expandtab
