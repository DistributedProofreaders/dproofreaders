<?php
include_once($relPath."Quiz.inc");


$browser_title                = _("Formatting Quiz");
$intro_title                  = sprintf(_("Formatting Quiz, page %d"), 4);;
$initial_instructions         = $initial_instructions__F;
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$initial_text                 = "When listening to amateur poetry, says Wellington\nBunn:\n\nTo safeguard one's person...one must be equipped with\nall manner of defensive objects.\n\nBunn goes on to list these objects, among them\n\n1. a spoon, and\n2. two sporks.\n\nSo equipped, the reader may undertake to listen to\npoetry such as that of Skelton:\n\nGup, gorbellyd Godfrey, gup, Garnysche, gaudy fole!\nTo turney or to tante with me ye ar to fare to seke:\nFor thes twayne whypslouens calle for a coke stole:\nThow mantycore, ye marmoset, garnyshte lyke a\nGreke.\n\nTruer words ne'er were spoken.";
$solutions                    = array("\nWhen listening to amateur poetry, says Wellington\nBunn:\n\n/#\nTo safeguard one's person ... one must be equipped with\nall manner of defensive objects.\n#/\n\nBunn goes on to list these objects, among them\n\n/*\n1. a spoon, and\n2. two sporks.\n*/\n\nSo equipped, the reader may undertake to listen to\npoetry such as that of Skelton:\n\n/*\nGup, gorbellyd Godfrey, gup, Garnysche, gaudy fole!\n  To turney or to tante with me ye ar to fare to seke:\nFor thes twayne whypslouens calle for a coke stole:\n  Thow mantycore, ye marmoset, garnyshte lyke a Greke.\n*/\n\nTruer words ne'er were spoken.");


// error messages
$messages["missingline"] = array(
    "message_title" => _("Missing blank line"),
    "message_body" => _("Paragraphs are delimited by blank lines. If a page starts with a new paragraph, place a blank line at the start of the page."),
);
$messages["nobc"] = array(
    "message_title" => _("Block Quotation"),
    "message_body" => _("You have not or incorrectly marked the block quotation in the text. Enclose it with /# ... #/, with each marker on a line of its own."),
    "guideline" => "block_qt"
);
$messages["nopoetry"] = array(
    "message_title" => _("Poetry markup"),
    "message_body" => _("You have not or incorrectly marked the poem in the text. Enclose it with /* ... */, with each marker on a line of its own."),
    "guideline" => "poetry"
);
$messages["otherpindent"] = array(
    "message_title" => _("Poetry indentation not as expected"),
    "message_body" => _("For the indentation of poetry lines, indent using multiples of two spaces."),
    "guideline" => "poetry"
);
$messages["baseindent"] = array(
    "message_title" => _("Poetry indentation"),
    "message_body" => _("It seems you have indented the whole poem. Please try to represent only relative indentation, so that the leftmost lines are not indented."),
    "guideline" => "poetry"
);
$messages["plinenotjoined"] = array(
    "message_title" => _("Poetry line not joined"),
    "message_body" => _("There is one long poetry line, broken up into two lines in the image. Please join it back into one line."),
    "guideline" => "poetry"
);
$messages["pmspacing"] = array(
    "message_title" => _("Poetry markup"),
    "message_body" => _("Please leave exactly one empty line before the poetry starting marker /*. Also leave one blank line after the poetry closing marker */."),
    "guideline" => "outofline"
);
$messages["lbchange"] = array(
    "message_title" => _("Line breaks changed"),
    "message_body" => _("Please leave the line breaks of the block quote as they appear in the image."),
);
$messages["bqspacing"] = array(
    "message_title" => _("Blockquote markup"),
    "message_body" => _("Please leave exactly one empty line before the blockquote starting marker /#. Also leave one blank line after the blockquote closing marker #/. The blockquote marker should be on its own line."),
    "guideline" => "outofline"
);
$messages["bqindent"] = array(
    "message_title" => _("Block quote indented"),
    "message_body" => _("It seems you have indented the block quote. The /# #/ markup indicates that the text was printed differently; there is no need to add indentation to a block quote."),
    "guideline" => "block_qt"
);
$messages["listindent"] = array(
    "message_title" => _("List indented"),
    "message_body" => _("It seems you have indented the list. Since the whole list is indented the same amount, the /* */ markup alone is sufficient; indentation is not needed."),
);
$messages["wrongmarkuplist"] = array(
    "message_title" => _("List marked incorrectly"),
    "message_body" => _("Mark lists with the markup <kbd>/* */</kbd> to preserve the line breaks."),
    "guideline" => "lists"
);
$messages["wrongmarkuppoem"] = array(
    "message_title" => _("Poem marked incorrectly"),
    "message_body" => _("Mark poetry with the markup <kbd>/* */</kbd> to preserve the line breaks."),
    "guideline" => "poetry"
);
$messages["wrongmarkupbq"] = array(
    "message_title" => _("Block quote marked incorrectly"),
    "message_body" => _("Mark block quotes with the markup <kbd>/# #/</kbd> to allow the text to be rewrapped."),
    "guideline" => "block_qt"
);
$messages["nolist"] = array(
    "message_title" => _("List not marked"),
    "message_body" => _("Mark lists with the markup <kbd>/* */</kbd>."),
    "guideline" => "lists"
);
$messages["ellipsis"] = array(
    "message_title" => _("Ellipsis spacing incorrect"),
    "message_body" => _("Leave a space on each side of an ellipsis."),
);
$messages["nopindent"] = array(
    "message_title" => _("Poetry line(s) not indented"),
    "message_body" => _("The poem in the text has relative indentation (some lines indented more than others). Try to represent that in the formatted text, using multiples of two spaces."),
    "guideline" => "poetry"
);


// error checks
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nWhen"), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("/#\n1."), "case_sensitive" => TRUE, "error" => "wrongmarkuplist");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("/#\nGup"), "case_sensitive" => TRUE, "error" => "wrongmarkuppoem");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("/*\nTo"), "case_sensitive" => TRUE, "error" => "wrongmarkupbq");
$tests[] = array("type" => "expectedtext", "searchtext" => array("/*"), "case_sensitive" => TRUE, "error" => "nopoetry");
$tests[] = array("type" => "expectedtext", "searchtext" => array("*/"), "case_sensitive" => TRUE, "error" => "nopoetry");
$tests[] = array("type" => "expectedtext", "searchtext" => array("/#"), "case_sensitive" => TRUE, "error" => "nobc");
$tests[] = array("type" => "expectedtext", "searchtext" => array("#/"), "case_sensitive" => TRUE, "error" => "nobc");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n#/\n\n"), "case_sensitive" => TRUE, "error" => "bqspacing");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n\n/#\n"), "case_sensitive" => TRUE, "error" => "bqspacing");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n*/\n\n"), "case_sensitive" => TRUE, "error" => "pmspacing");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n\n/*\n"), "case_sensitive" => TRUE, "error" => "pmspacing");
$tests[] = array("type" => "expectedtext", "searchtext" => array("with\n"), "case_sensitive" => TRUE, "error" => "lbchange");
$tests[] = array("type" => "forbiddentext", "searchtext" => array(" To safe", " all manner"), "case_sensitive" => TRUE, "error" => "bqindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => array(" Gup, gorbellyd", " For thes"), "case_sensitive" => TRUE, "error" => "baseindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("   To turney", "   Thow mantycore"), "case_sensitive" => TRUE, "error" => "otherpindent");
$tests[] = array("type" => "expectedtext", "searchtext" => array("lyke a Greke."), "case_sensitive" => TRUE, "error" => "plinenotjoined");
$tests[] = array("type" => "forbiddentext", "searchtext" => array(" 1.", " 2."), "case_sensitive" => TRUE, "error" => "listindent");
$tests[] = array("type" => "expectedtext", "searchtext" => array("/*\n1. a spoon,"), "case_sensitive" => TRUE, "error" => "nolist");
$tests[] = array("type" => "expectedtext", "searchtext" => array("person ... one"), "case_sensitive" => TRUE, "error" => "ellipsis");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("\nTo turney", "\n To turney", "\nThow mantycore", "\n Thow mantycore"), "case_sensitive" => TRUE, "error" => "nopindent");

// vim: sw=4 ts=4 expandtab
