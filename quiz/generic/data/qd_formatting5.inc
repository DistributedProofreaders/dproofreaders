<?php


$browser_title = _("Formatting Quiz");
$intro_title = sprintf(_("Formatting Quiz, page %d"), 5);
$initial_instructions = $initial_instructions__F;
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$initial_text = "CAMPFIRE GIRLS WEAR WHYPSLOVENS     191\n\nthe necessity of nursing her.\"\n\n\"Come on, girls; get your wraps; we will all go over.\nIt's only a couple[1] of blocks. Hurry, everybody!\"\n\n\"Wait, and I'll tell Kitty we're going out,\" Marion said.\nShe ran through several rooms, calling \"Kittie! Kittie!\"\nbut received no response.\nWhere is Kitty?\n\"I wonder where she is,\" the\nhostess said, in a puzzled manner.\n\"Well, we haven't time to find her.\nCome on.\"\n\n\"I think I saw her go out more\nthan half an hour ago,\" Harriet\nNewcomb said. \"She called someone up on the\ntelephone, and then put her hat and coat on and went\nout the side way, and I haven't seen her since.\"\n\n1Harriet was lying; really, the whypsloven factory was\nseveral miles away.";
$solutions = ["the necessity of nursing her.\"\n\n\"Come on, girls; get your wraps; we will all go over.\nIt's only a couple[1] of blocks. Hurry, everybody!\"\n\n\"Wait, and I'll tell Kitty we're going out,\" Marion said.\n\nShe ran through several rooms, calling \"Kittie! Kittie!\"\nbut received no response.\n\n[Illustration: Where is Kitty?]\n\n\"I wonder where she is,\" the\nhostess said, in a puzzled manner.\n\"Well, we haven't time to find her.\nCome on.\"\n\n\"I think I saw her go out more\nthan half an hour ago,\" Harriet\nNewcomb said. \"She called someone up on the\ntelephone, and then put her hat and coat on and went\nout the side way, and I haven't seen her since.\"\n\n[Footnote 1: Harriet was lying; really, the whypsloven factory was\nseveral miles away.]"];


// error messages
$messages["sqbr"] = [
    "message_title" => _("Incorrect brackets."),
    "message_body" => _("Please use square brackets <kbd>[]</kbd> for illustration and footnote markup."),
];
$messages["pageheader"] = [
    "message_title" => _("Page header left in"),
    "message_body" => _("Remove the page header and the page number."),
];
$messages["illusmissing"] = [
    "message_title" => _("Illustration markup missing."),
    "message_body" => _("It seems you haven't marked the illustration correctly. Put the illustration caption within <kbd>[Illustration: ]</kbd>"),
    "guideline" => "illust",
];
$messages["illuswrong"] = [
    "message_title" => _("Illustration incorrectly marked."),
    "message_body" => _("Check the case of the caption, and make sure there is a blank line before and after the illustration marker."),
    "guideline" => "illust",
];
$messages["nofn"] = [
    "message_title" => _("Footnote markup missing."),
    "message_body" => _("It seems you haven't marked the footnote at the bottom of the page correctly. Put the footnote text within <kbd>[Footnote _: ]</kbd> placing the correct marker where the underscore is."),
    "guideline" => "footnotes",
];
$messages["wrongfn"] = [
    "message_title" => _("Footnote markup wrong."),
    "message_body" => _("It seems you haven't marked the footnote at the bottom of the page correctly. Put the footnote text within <kbd>[Footnote _: ]</kbd> placing the correct marker where the underscore is. Remember to leave a blank line before the footnote."),
    "guideline" => "footnotes",
];
$messages["wrongblank"] = [
    "message_title" => _("Extra blank line"),
    "message_body" => _("The page starts in the middle of a paragraph, so there should not be a blank line at the top."),
];
$messages["missingline"] = [
    "message_title" => _("Missing blank line"),
    "message_body" => _("All paragraphs are delimited by blank lines."),
];
$messages["missinglinecheckindents"] = [
    "message_title" => _("Missing blank line"),
    "message_body" => _("All paragraphs should be separated by blank lines. <b>Hint: Look closely at the indent of each line to see whether it is a new paragraph or not.</b>"),
];
$messages["captionmoved"] = [
    "message_title" => _("Illustration caption moved"),
    "message_body" => _("Since the illustration caption started off between paragraphs, there is no need to move it."),
    "guideline" => "illust",
];
$messages["tbmarkup"] = [
    "message_title" => _("Thought break markup present"),
    "message_body" => _("Ignore the dividing line between the regular text and the footnote; just use a single blank line between them, without any thought break markup."),
    "guideline" => "footnotes",
];


// error checks
$tests[] = ["type" => "forbiddentext", "searchtext" => ["CAMPFIRE GIRLS WEAR WHYPSLOVENS", "191"], "case_sensitive" => true, "error" => "pageheader"];
$tests[] = ["type" => "forbiddentext", "searchtext" => "\nthe necessity", "case_sensitive" => false, "error" => "wrongblank"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["(Footnote", "{Footnote", "(Illustration", "{Illustration"], "case_sensitive" => false, "error" => "sqbr"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Illustration: Where is Kitty?]"], "case_sensitive" => false, "error" => "illusmissing"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\n[Illustration: Where is Kitty?]\n\n"], "case_sensitive" => true, "error" => "illuswrong"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Footnote"], "case_sensitive" => false, "error" => "nofn"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\n[Footnote 1:"], "case_sensitive" => true, "error" => "wrongfn"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\n\"Come on"], "case_sensitive" => true, "error" => "missingline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\n\"Wait,"], "case_sensitive" => true, "error" => "missingline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\nShe ran"], "case_sensitive" => true, "error" => "missinglinecheckindents"];
$tests[] = ["type" => "wrongtextorder", "firsttext" => "Where is Kitty?", "secondtext" => "puzzled", "case_sensitive" => true, "error" => "captionmoved"];
$tests[] = ["type" => "wrongtextorder", "firsttext" => "response", "secondtext" => "Where is Kitty?", "case_sensitive" => true, "error" => "captionmoved"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<tb>"], "case_sensitive" => true, "error" => "tbmarkup"];
