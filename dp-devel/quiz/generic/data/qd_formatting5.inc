<?php
include_once($relPath."Quiz.inc");

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"formatting5","pass");
}

$browser_title                = _("Formatting Quiz");
$intro_title                  = _("Formatting Quiz, page 5");
$initial_instructions         = _("Try to correct the text.");
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$ocr_text                     = "CAMPFIRE GIRLS WEAR WHYPSLOVENS     191\n\nthe necessity of nursing her.\"\n\n\"Come on, girls; get your wraps; we will all go over.\nIt's only a couple[1] of blocks. Hurry, everybody!\"\n\n\"Wait, and I'll tell Kitty we're going out,\" Marion said.\nShe ran through several rooms, calling \"Kittie! Kittie!\"\nbut received no response.\nWhere is Kitty?\n\"I wonder where she is,\" the\nhostess said, in a puzzled manner.\n\"Well, we haven't time to find her.\nCome on.\"\n\n\"I think I saw her go out more\nthan half an hour ago,\" Harriet\nNewcomb said. \"She called someone up on the\ntelephone, and then put her hat and coat on and went\nout the side way, and I haven't seen her since.\"\n\n1Harriet was lying; really, the whypsloven factory was\nseveral miles away.";
$solutions                    = array("the necessity of nursing her.\"\n\n\"Come on, girls; get your wraps; we will all go over.\nIt's only a couple[1] of blocks. Hurry, everybody!\"\n\n\"Wait, and I'll tell Kitty we're going out,\" Marion said.\n\nShe ran through several rooms, calling \"Kittie! Kittie!\"\nbut received no response.\n\n[Illustration: Where is Kitty?]\n\n\"I wonder where she is,\" the\nhostess said, in a puzzled manner.\n\"Well, we haven't time to find her.\nCome on.\"\n\n\"I think I saw her go out more\nthan half an hour ago,\" Harriet\nNewcomb said. \"She called someone up on the\ntelephone, and then put her hat and coat on and went\nout the side way, and I haven't seen her since.\"\n\n[Footnote 1: Harriet was lying; really, the whypsloven factory was\nseveral miles away.]");
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>" . _("Congratulations, no errors found!");


// error messages
$messages["sqbr"] = array(
    "message_text" => "<h2>" . _("Incorrect brackets.") . "</h2>" .
        _("Please use square brackets [] for illustration and footnote markup."),
    "hints" => array()
);
$messages["pageheader"] = array(
    "message_text" => "<h2>" . _("Page header left in") . "</h2>" .
        _("Remove the page header and the page number."),
    "hints" => array()
);
$messages["illusmissing"] = array(
    "message_text" => "<h2>" . _("Illustration markup missing.") . "</h2>" .
        _("It seems you haven't marked the illustration correctly. Put the illustration caption within <tt>[Illustration: ]</tt>"),
    "hints" => array(),
    "guideline" => "illust"
);
$messages["illuswrong"] = array(
    "message_text" => "<h2>" . _("Illustration incorrectly marked.") . "</h2>" .
        _("Check the case of the caption, and make sure there is a blank line before and after the illustration marker."),
    "hints" => array(),
    "guideline" => "illust"
);
$messages["nofn"] = array(
    "message_text" => "<h2>" . _("Footnote markup missing.") . "</h2>" .
        _("It seems you haven't marked the footnote at the bottom correctly. Put the footnote text within <tt>[Footnote _: ]</tt> placing the correct marker where the underscore is."),
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["wrongfn"] = array(
    "message_text" => "<h2>" . _("Footnote markup wrong.") . "</h2>" .
        _("It seems you haven't marked the footnote at the bottom correctly. Put the footnote text within <tt>[Footnote _: ]</tt> placing the correct marker where the underscore is. Remember to leave a blank line before the footnote."),
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["wrongblank"] = array(
    "message_text" => "<h2>" . _("Extra blank line.") . "</h2>" .
        _("The page starts in the middle of a paragraph, so there should not be a blank line at the top."),
    "hints" => array()
);
$messages["missingline"] = array(
    "message_text" => "<h2>" . _("Missing blank line") . "</h2>" .
        _("All paragraphs are delimited by blank lines."),
    "hints" => array()
);
$messages["missinglinecheckindents"] = array(
    "message_text" => "<h2>" . _("Missing blank line") . "</h2>" .
        _("All paragraphs should be separated by blank lines. <b>Hint: Look closely at the indent of each line to see whether it is a new paragraph or not.</b>"),
    "hints" => array()
);
$messages["captionmoved"] = array(
    "message_text" => "<h2>" . _("Illustration caption moved") . "</h2>" .
        _("Since the illustration caption started off between paragraphs, there is no need to move it."),
    "hints" => array(),
    "guideline" => "illust"
);
$messages["tbmarkup"] = array(
    "message_text" => "<h2>" . _("Thought break markup present") . "</h2>" .
        _("Ignore the dividing line between the regular text and the footnote; just use a single blank line between them, without any thought break markup."),
    "hints" => array(),
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
