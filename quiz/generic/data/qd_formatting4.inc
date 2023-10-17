<?php


$browser_title = _("Formatting Quiz");
$intro_title = sprintf(_("Formatting Quiz, page %d"), 4);
$initial_instructions = $initial_instructions__F;
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$initial_text = "When listening to amateur poetry, says Wellington\nBunn:\n\nTo safeguard one's person...one must be equipped with\nall manner of defensive objects.\n\nBunn goes on to list these objects, among them\n\n1. a spoon, and\n2. two sporks.\n\nSo equipped, the reader may undertake to listen to\npoetry such as that of Skelton:\n\nGup, gorbellyd Godfrey, gup, Garnysche, gaudy fole!\nTo turney or to tante with me ye ar to fare to seke:\nFor thes twayne whypslouens calle for a coke stole:\nThow mantycore, ye marmoset, garnyshte lyke a\nGreke.\n\nTruer words ne'er were spoken.";
$solutions = ["\nWhen listening to amateur poetry, says Wellington\nBunn:\n\n/#\nTo safeguard one's person ... one must be equipped with\nall manner of defensive objects.\n#/\n\nBunn goes on to list these objects, among them\n\n/*\n1. a spoon, and\n2. two sporks.\n*/\n\nSo equipped, the reader may undertake to listen to\npoetry such as that of Skelton:\n\n/*\nGup, gorbellyd Godfrey, gup, Garnysche, gaudy fole!\n  To turney or to tante with me ye ar to fare to seke:\nFor thes twayne whypslouens calle for a coke stole:\n  Thow mantycore, ye marmoset, garnyshte lyke a Greke.\n*/\n\nTruer words ne'er were spoken."];


// error messages
$messages["missingline"] = [
    "message_title" => _("Missing blank line"),
    "message_body" => _("Paragraphs are delimited by blank lines. If a page starts with a new paragraph, place a blank line at the start of the page."),
];
$messages["nobc"] = [
    "message_title" => _("Block Quotation"),
    "message_body" => _("You have not marked or have incorrectly marked the block quotation in the text. Enclose it with <kbd>/#</kbd> and <kbd>#/</kbd>, with each marker on a line of its own."),
    "guideline" => "block_qt",
];
$messages["nopoetry"] = [
    "message_title" => _("Poetry markup"),
    "message_body" => _("You have not marked or have incorrectly marked the poem in the text. Enclose it with <kbd>/*</kbd> and <kbd>*/</kbd>, with each marker on a line of its own."),
    "guideline" => "poetry",
];
$messages["otherpindent"] = [
    "message_title" => _("Poetry indentation not as expected"),
    "message_body" => _("For the indentation of poetry lines, indent using multiples of two spaces."),
    "guideline" => "poetry",
];
$messages["baseindent"] = [
    "message_title" => _("Poetry indentation"),
    "message_body" => _("It seems you have indented the whole poem. Please try to represent only relative indentation, so that the leftmost lines are not indented."),
    "guideline" => "poetry",
];
$messages["plinenotjoined"] = [
    "message_title" => _("Poetry line not joined"),
    "message_body" => _("There is one long poetry line, broken up into two lines in the image. Please join it back into one line."),
    "guideline" => "poetry",
];
$messages["pmspacing"] = [
    "message_title" => _("Poetry markup"),
    "message_body" => _("Please leave exactly one empty line before the poetry starting marker <kbd>/*</kbd>. Also leave one blank line after the poetry closing marker <kbd>*/</kbd>."),
    "guideline" => "outofline",
];
$messages["lbchange"] = [
    "message_title" => _("Line breaks changed"),
    "message_body" => _("Please leave the line breaks of the block quote as they appear in the image."),
];
$messages["bqspacing"] = [
    "message_title" => _("Blockquote markup"),
    "message_body" => _("Please leave exactly one empty line before the blockquote starting marker <kbd>/#</kbd>. Also leave one blank line after the blockquote closing marker <kbd>#/</kbd>. The blockquote marker should be on its own line."),
    "guideline" => "outofline",
];
$messages["bqindent"] = [
    "message_title" => _("Block quote indented"),
    "message_body" => _("It seems you have indented the block quote. The <kbd>/# #/</kbd> markup indicates that the text was printed differently; there is no need to add indentation to a block quote."),
    "guideline" => "block_qt",
];
$messages["listindent"] = [
    "message_title" => _("List indented"),
    "message_body" => _("It seems you have indented the list. Since the whole list is indented the same amount, the <kbd>/* */</kbd> markup alone is sufficient; indentation is not needed."),
];
$messages["wrongmarkuplist"] = [
    "message_title" => _("List marked incorrectly"),
    "message_body" => _("Mark lists with the markup <kbd>/* */</kbd> to preserve the line breaks."),
    "guideline" => "lists",
];
$messages["wrongmarkuppoem"] = [
    "message_title" => _("Poem marked incorrectly"),
    "message_body" => _("Mark poetry with the markup <kbd>/* */</kbd> to preserve the line breaks."),
    "guideline" => "poetry",
];
$messages["wrongmarkupbq"] = [
    "message_title" => _("Block quote marked incorrectly"),
    "message_body" => _("Mark block quotes with the markup <kbd>/# #/</kbd> to allow the text to be rewrapped."),
    "guideline" => "block_qt",
];
$messages["nolist"] = [
    "message_title" => _("List not marked"),
    "message_body" => _("Mark lists with the markup <kbd>/* */</kbd>."),
    "guideline" => "lists",
];
$messages["ellipsis"] = [
    "message_title" => _("Ellipsis spacing incorrect"),
    "message_body" => _("Leave a space on each side of an ellipsis."),
];
$messages["nopindent"] = [
    "message_title" => _("Poetry line(s) not indented"),
    "message_body" => _("The poem in the text has relative indentation (some lines indented more than others). Try to represent that in the formatted text, using multiples of two spaces."),
    "guideline" => "poetry",
];


// error checks
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nWhen"], "case_sensitive" => true, "error" => "missingline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["/#\n1."], "case_sensitive" => true, "error" => "wrongmarkuplist"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["/#\nGup"], "case_sensitive" => true, "error" => "wrongmarkuppoem"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["/*\nTo"], "case_sensitive" => true, "error" => "wrongmarkupbq"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["/*"], "case_sensitive" => true, "error" => "nopoetry"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["*/"], "case_sensitive" => true, "error" => "nopoetry"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["/#"], "case_sensitive" => true, "error" => "nobc"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["#/"], "case_sensitive" => true, "error" => "nobc"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n#/\n\n"], "case_sensitive" => true, "error" => "bqspacing"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\n/#\n"], "case_sensitive" => true, "error" => "bqspacing"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n*/\n\n"], "case_sensitive" => true, "error" => "pmspacing"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\n/*\n"], "case_sensitive" => true, "error" => "pmspacing"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["with\n"], "case_sensitive" => true, "error" => "lbchange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" To safe", " all manner"], "case_sensitive" => true, "error" => "bqindent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" Gup, gorbellyd", " For thes"], "case_sensitive" => true, "error" => "baseindent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["   To turney", "   Thow mantycore"], "case_sensitive" => true, "error" => "otherpindent"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["lyke a Greke."], "case_sensitive" => true, "error" => "plinenotjoined"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" 1.", " 2."], "case_sensitive" => true, "error" => "listindent"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["/*\n1. a spoon,"], "case_sensitive" => true, "error" => "nolist"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["person ... one"], "case_sensitive" => true, "error" => "ellipsis"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\nTo turney", "\n To turney", "\nThow mantycore", "\n Thow mantycore"], "case_sensitive" => true, "error" => "nopindent"];
