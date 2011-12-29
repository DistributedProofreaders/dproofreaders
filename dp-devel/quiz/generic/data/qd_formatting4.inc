<?php
include_once($relPath."Quiz.inc");

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"formatting4","pass");
}

$browser_title                = _("Formatting Quiz");
$welcome                      = "<h2>" . _("Formatting Quiz, page 4") . "</h2>" . _("Try to correct the text");
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$ocr_text                     = "When listening to amateur poetry, says Wellington\nBunn:\n\nTo safeguard one's person...one must be equipped with\nall manner of defensive objects.\n\nBunn goes on to list these objects, among them\n\n1. a spoon, and\n2. two sporks.\n\nSo equipped, the reader may undertake to listen to\npoetry such as that of Skelton:\n\nGup, gorbellyd Godfrey, gup, Garnysche, gaudy fole!\nTo turney or to tante with me ye ar to fare to seke:\nFor thes twayne whypslouens calle for a coke stole:\nThow mantycore, ye marmoset, garnyshte lyke a\nGreke.\n\nTruer words ne'er were spoken.";
$solutions                    = array("\nWhen listening to amateur poetry, says Wellington\nBunn:\n\n/#\nTo safeguard one's person ... one must be equipped with\nall manner of defensive objects.\n#/\n\nBunn goes on to list these objects, among them\n\n/*\n1. a spoon, and\n2. two sporks.\n*/\n\nSo equipped, the reader may undertake to listen to\npoetry such as that of Skelton:\n\n/*\nGup, gorbellyd Godfrey, gup, Garnysche, gaudy fole!\n  To turney or to tante with me ye ar to fare to seke:\nFor thes twayne whypslouens calle for a coke stole:\n  Thow mantycore, ye marmoset, garnyshte lyke a Greke.\n*/\n\nTruer words ne'er were spoken.");
$showsolution                 = TRUE;
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>" . ("Congratulations, no errors found!");
$links_out                    = sprintf("<a href='../generic/main.php?type=formatting5&quiz_id=FQ' target='_top'>%s</a><br><a href='../start.php' target='_top'>%s</a>", _("Next step of quiz"), _("Quizzes home."));


// error messages
$messages["missingline"] = array(
    "message_text" => "<h2>" . _("Missing blank line") . "</h2>" .
        _("Paragraphs are delimited by blank lines. If a page starts with a new paragraph, place a new line at the start of the page."),
    "hints" => array()
);
$messages["nobc"] = array(
    "message_text" => "<h2>" . _("Block Quotation") . "</h2>" .
        _("You have not or incorrectly marked the block quotation in the text. Enclose it with /# ... #/, with each marker on a line of its own."),
    "hints" => array()
);
$messages["nopoetry"] = array(
    "message_text" => "<h2>" . _("Poetry markup") . "</h2>" .
        _("You have not or incorrectly marked the poem in the text. Enclose it with /* ... */, with each marker on a line of its own."),
    "hints" => array()
);
$messages["otherpindent"] = array(
    "message_text" => "<h2>" . _("Poetry indentation not as expected") . "</h2>" .
        _("For the indentation of poetry lines, use indents multiples of two spaces."),
    "hints" => array()
);
$messages["baseindent"] = array(
    "message_text" => "<h2>" . _("Poetry indentation") . "</h2>" .
        _("It seems you have indented the whole poem. Please try to represent only relative indentation, so that the leftmost lines are not indented."),
    "hints" => array()
);
$messages["plinenotjoined"] = array(
    "message_text" => "<h2>" . _("Poetry line not joined") . "</h2>" .
        _("There is one long poetry line, broken up into two lines. Please join those lines."),
    "hints" => array()
);
$messages["pmspacing"] = array(
    "message_text" => "<h2>" . _("Poetry markup") . "</h2>" .
        _("Please leave exactly one empty line before the poetry starting marker /*. Also leave one blank line after the poetry closing marker */."),
    "hints" => array()
);
$messages["bqspacing"] = array(
    "message_text" => "<h2>" . _("Blockquote markup") . "</h2>" .
        _("Please leave exactly one empty line before the blockquote starting marker /#. Also leave one blank line after the blockquote closing marker #/. The blockquote marker should be on its own line."),
    "hints" => array()
);
$messages["nolist"] = array(
    "message_text" => "<h2>" . _("List not marked") . "</h2>" .
        _("Mark lists with the markup <tt>/* */</tt>."),
    "hints" => array()
);
$messages["ellipsis"] = array(
    "message_text" => "<h2>" . _("Ellipsis spacing incorrect") . "</h2>" .
        _("Leave a space on each side of an ellipsis."),
    "hints" => array()
);
$messages["nopindent"] = array(
    "message_text" => "<h2>" . _("Poetry line(s) not indented") . "</h2>" .
        _("The poems in the text have relative indentation. Try to represent that in the proofread text, using multiples of two spaces."),
    "hints" => array()
);


// error checks
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nWhen"), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" => array("/*"), "case_sensitive" => TRUE, "error" => "nopoetry");
$tests[] = array("type" => "expectedtext", "searchtext" => array("*/"), "case_sensitive" => TRUE, "error" => "nopoetry");
$tests[] = array("type" => "expectedtext", "searchtext" => array("/#"), "case_sensitive" => TRUE, "error" => "nobc");
$tests[] = array("type" => "expectedtext", "searchtext" => array("#/"), "case_sensitive" => TRUE, "error" => "nobc");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n#/\n\n"), "case_sensitive" => TRUE, "error" => "bqspacing");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n\n/#\n"), "case_sensitive" => TRUE, "error" => "bqspacing");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n*/\n\n"), "case_sensitive" => TRUE, "error" => "pmspacing");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n\n/*\n"), "case_sensitive" => TRUE, "error" => "pmspacing");
$tests[] = array("type" => "forbiddentext", "searchtext" => array(" Gup, gorbellyd", " For thes"), "case_sensitive" => TRUE, "error" => "baseindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("   To turney", "   Thow mantycore"), "case_sensitive" => TRUE, "error" => "otherpindent");
$tests[] = array("type" => "expectedtext", "searchtext" => array("lyke a Greke."), "case_sensitive" => TRUE, "error" => "plinenotjoined");
$tests[] = array("type" => "expectedtext", "searchtext" => array("/*\n1. a spoon,"), "case_sensitive" => TRUE, "error" => "nolist");
$tests[] = array("type" => "expectedtext", "searchtext" => array("person ... one"), "case_sensitive" => TRUE, "error" => "ellipsis");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("\nTo turney", "\n To turney", "\nThow mantycore", "\n Thow mantycore"), "case_sensitive" => TRUE, "error" => "nopindent");

// vim: sw=4 ts=4 expandtab
