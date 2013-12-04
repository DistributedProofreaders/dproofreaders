<?php
include_once($relPath.'base.inc');

// General-purpose default messages

$default_hintlink         = _("Desperate? Can't find it?");
$default_challenge        = _("Try to correct that or press 'restart'.");
$default_feedbacktext     = _("The algorithm for finding errors in this quiz is a quite simple one. If you feel the message doesn't make any sense, please post a feedback message in <a href='%s' target='_blank'>this forum topic</a>.");


// urls for links

$default_feedbackurl      = "$forums_url/viewtopic.php?t=9165";
$old_texts_url            = "$wiki_url/Proofing_old_texts";
$Greek_translit_url       = "$wiki_url/Transliterating_Greek";
$ae_oe_ligatures_url      = "$wiki_url/Ae_and_oe_ligatures";
$thorn_url                = "$wiki_url/Thorn";
$fraktur_url              = "$wiki_url/Common_Fraktur_OCR_errors";
$blackletter_url          = "$wiki_url/Proofing_blackletter";
$custom_font_url          = "$wiki_url/DPCustomMono2";
$latex_url                = "$wiki_url/LaTeX_formatting_manual";



//// Default error messages ////

// The cross-quiz error messages below have prefixes identifying
// what sort of quiz they apply to:
//    P_ = proofreading
//    G_ = Greek transliteration
// Quiz-specific error messages (stored in individual /data/qd_* files)
// are named without these prefixes, to prevent accidental duplication of names.

// An additional underscore in a message name usually separates the correct and
// incorrect forms respectively--in other words:
//    X_Y means "X is erroneously marked as Y"


//// Proofreading messages ////
// Individual characters
$messages["P_spellingchange"] = array(
    "message_text" => "<h2>" . _("Primary Rule") . "</h2>\n" .
        "<p>" . _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please leave the text as the author wrote it, including older or alternative spellings.") . "</p>",
    "hints" => array()
);
$messages["P_commascanno"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a 'scanno': a period (<tt>.</tt>) in the text where there is a comma (<tt>,</tt>) in the image.") . "</p>",
    "hints" => array()
);

if(!$utf8_site)
{
    $messages["P_accentbracket"] = array(
        "message_text" => "<h2>" . _("Accent") . "</h2>\n" .
            "<p>" . _("Where possible, proofread accented letters using the actual characters, like <tt>&aacute;</tt>. You can insert accented letters using the drop-down menus below, labelled \"A\" \"E\" \"I\" \"O\" \"U\" \"+\".  Only use brackets, such as <tt>['n]</tt>, when it is not available as a single character.") . "</p>",
        "hints" => array(),
        "guideline" => "a_chars"
    );
    $messages["P_oe"] = array(
        "message_text" => "<h2>" . _("Ligature") . "</h2>\n" .
            "<p>" . _("Proofread the 'oe' ligature (&oelig;) using brackets: <tt>[oe]</tt>.") . "</p>",
        "hints" => array(),
        "guideline" => "a_chars"
    );
    $messages["P_macron"] = array(
        "message_text" => "<h2>" . _("Macron") . "</h2>\n" .
            "<p>" . _("Following the proofreading guidelines, you would proofread an '<tt>a</tt>' with a macron (straight line) over it as <tt>[=a]</tt>.  If the macron appears over an '<tt>e</tt>' it would be <tt>[=e]</tt>, and so forth.") . "</p>",
        "hints" => array(),
        "guideline" => "d_chars"
    );
    $messages["P_macron2"] = array(
        "message_text" => "<h2>" . _("Macron") . "</h2>\n" .
            "<p>" . _("A letter in the text has a macron (horizontal line) over it.  Following the proofreading guidelines, you would proofread an '<tt>a</tt>' with a macron (straight line) over it as <tt>[=a]</tt>.  If the macron appears over an '<tt>e</tt>' it would be <tt>[=e]</tt>, and so forth.") . "</p>",
        "hints" => array(),
        "guideline" => "d_chars"
    );
}
else
{
    $messages["P_accentbracket"] = array(
        "message_text" => "<h2>" . _("Accent") . "</h2>\n" .
            "<p>" . _("Where possible, proofread accented letters using the actual characters, like <tt>&aacute;</tt>. You can insert accented letters using the drop-down menus below, labelled \"A\" \"E\" \"I\" \"O\" \"U\" \"+\".") . "</p>",
        "hints" => array(),
        "guideline" => "a_chars"
    );
    $messages["P_oe"] = array(
        "message_text" => "<h2>" . _("Ligature") . "</h2>\n" .
            "<p>" . _("Proofread the 'oe' ligature (&oelig;) using the symbol directly, not as <tt>oe</tt> or <tt>[oe]</tt>.  You can insert the symbol using the \"O\" drop-down menu below.") . "</p>",
        "hints" => array(),
        "guideline" => "a_chars"
    );
    $messages["P_macron"] = array(
        "message_text" => "<h2>" . _("Macron") . "</h2>\n" .
            "<p>" . _("Following the proofreading guidelines, please use the appropriate Unicode character for a letter with a macron (straight line) over it.  For instance, proofread an '<tt>a</tt>' with a macron over it as <tt>&#257;</tt>.  If the macron appears over an '<tt>e</tt>' it would be <tt>&#275</tt>, and so forth.") . "</p>",
          "hints" => array(),
        "guideline" => "d_chars"
    );
}

$messages["P_aebracket"] = array(
    "message_text" => "<h2>" . _("Ligature") . "</h2>\n" .
        "<p>" . _("Proofread the 'ae' ligature (<tt>&aelig;</tt>) using the symbol directly; brackets are not necessary for this character.  You can insert the symbol using the \"A\" drop-down menu below.") . "</p>",
    "hints" => array(),
    "guideline" => "a_chars"
);
$messages["P_ae"] = array(
    "message_text" => "<h2>" . _("Ligature") . "</h2>\n" .
        "<p>" . _("Proofread the 'ae' ligature (&aelig;) using the symbol directly, not as <tt>ae</tt> or <tt>[ae]</tt>.  You can insert the symbol using the \"A\" drop-down menu below.") . "</p>",
    "hints" => array(),
    "guideline" => "a_chars"
);
$messages["P_oelig"] = array(
    "message_text" => "<h2>" . _("Ligature") . "</h2>\n" .
        "<p>" . _("Proofread the 'oe' ligature (&oelig;) using brackets: <tt>[oe]</tt>, not the actual &oelig; character.") . "</p>",
    "hints" => array(),
    "guideline" => "a_chars"
);
$messages["P_contracspace"] = array(
    "message_text" => "<h2>" . _("Contraction") . "</h2>\n" .
        "<p>" . _("Remove any extra space in contractions. For example, <tt>would&nbsp;n't</tt> should be proofread as <tt>wouldn't</tt>.") . "</p>",
    "hints" => array(),
    "guideline" => "contract"
);

// Paragraphs, etc.
$messages["P_lbchange"] = array(
    "message_text" => "<h2>" . _("Line breaks") . "</h2>\n" .
        "<p>" . _("You do not need to rearrange the words to make the line endings even. Please leave all line breaks in the text so that other volunteers can easily compare the lines in the text to the lines in the image.") . "</p>",
    "hints" => array(),
    "guideline" => "line_br"
);
$messages["P_para"] = array(
    "message_text" => "<h2>" . _("Paragraph spacing") . "</h2>\n" .
        "<p>" . _("Leave a single blank line before each paragraphs.") . "</p>",
    "hints" => array(),
    "guideline" => "para_space"
);
$messages["P_paratop"] = array(
    "message_text" => "<h2>" . _("Paragraph spacing") . "</h2>\n" .
        "<p>" . _("Leave a single blank line before each paragraph, even at the top of the page.") . "</p>",
    "hints" => array(),
    "guideline" => "para_space"
);
$messages["P_toomanylb"] = array(
    "message_text" => "<h2>" . _("Too many line breaks inserted") . "</h2>\n" .
        "<p>" . _("Paragraphs should be divided by exactly one blank line.") . "</p>",
    "hints" => array(),
    "guideline" => "para_space"
);
$messages["P_header"] = array(
    "message_text" => "<h2>" . _("Page header not removed") . "</h2>\n" .
        "<p>" . _("Remove page headers and page footers, but <i>not</i> footnotes, from the text.  The page header is normally at the top of the image and has a page number opposite it.  Remove the entire header, including the page number.") . "</p>",
    "hints" => array(),
    "guideline" => "page_hf"
);
$messages["P_headerline"] = array(
    "message_text" => "<h2>" . _("Blank line from page header") . "</h2>\n" .
        "<p>" . _("When removing the page header, you can also remove the blank line that follows it.") . "</p>",
    "hints" => array(),
    "guideline" => "page_hf"
);
$messages["P_footer"] = array(
    "message_text" => "<h2>" . _("Page footer not removed") . "</h2>\n" .
        "<p>" . _("Remove all page footers, but <i>not</i> footnotes, from the text.  The page footer may contain a page number or other words or characters that are not part of what the author wrote.") . "</p>",
    "hints" => array(),
    "guideline" => "page_hf"
);
$messages["P_spacedchap"] = array(
    "message_text" => "<h2>" . _("Spacing of chapter heading") . "</h2>\n" .
        "<p>" . _("Leave the formatting of the chapter heading to the formatting rounds.") . "</p>",
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["P_missinglb"] = array(
    "message_text" => "<h2>" . _("Spacing of chapter heading") . "</h2>\n" .
        "<p>" . _("Leave a single blank line before and after each part of the heading.") . "</p>",
    "hints" => array()
);
$messages["P_missingquote"] = array(
    "message_text" => "<h2>" . _("Double quote missing") . "</h2>\n" .
        "<p>" . _("Since from the context one can see there should be a double quote at the start of the chapter, and this is only missing for typesetting reasons (due to the large capital letter), we insert one there.") . "</p>",
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["P_decappedchap"] = array(
    "message_text" => "<h2>" . _("First word of chapter decapitalised") . "</h2>\n" .
        "<p>" . _("Leave this to the formatting rounds, and concentrate on making sure that the text is correct.") . "</p>",
    "hints" => array()
);

// Hyphens, dashes
$messages["P_longline"] = array(
    "message_text" => "<h2>" . _("Long line") . "</h2>\n" .
        "<p>" . _("You've probably joined two lines by deleting a line break. If you join words around hyphens or em-dashes, make sure to put the line break back in after the word or partial word that was moved up one line.") . "</p>",
    "hints" => array()
);
$messages["P_eolhyphen"] = array(
    "message_text" => "<h2>" . _("End-of-line Hyphenation") . "</h2>\n" .
        "<p>" . _("Sometimes words are hyphenated at the end of a line. Join the two parts of the divided word by moving the bottom part up to the previous line and putting the line break back in after the word. Remove the hyphen unless it really is a hyphenated word like 'well-meaning'.") . "</p>",
    "hints" => array(),
    "guideline" => "eol_hyphen"
);
$messages["P_eolhyphenaster"] = array(
    "message_text" => "<h2>" . _("Questionable hyphenation") . "</h2>\n" .
        "<p>" . _("Words that we don't commonly hyphenate now were often hyphenated in the old books we are working on. Leave them hyphenated the way the author did. When rejoining a hyphenated word, if you're not sure whether the author hyphenated it or not, leave the hyphen, put an * after it, and join the word together like this: <tt>to-*day</tt>.") . "</p>",
    "hints" => array(),
    "guideline" => "eol_hyphen"
);
$messages["P_hyphenlower"] = array(
    "message_text" => "<h2>" . _("End-of-line Hyphenation") . "</h2>\n" .
        "<p>" . _("Please rejoin the hyphenated word on the upper line, not the lower one.") . "</p>",
    "hints" => array(),
    "guideline" => "eol_hyphen"
);
$messages["P_hyphenleft"] = array(
    "message_text" => "<h2>" . _("End-of-line Hyphenation") . "</h2>\n" .
        "<p>" . _("When rejoining end-of-line hyphenation, remove the hyphen unless it really is a hyphenated word like 'well-meaning'.") . "</p>",
    "hints" => array(),
    "guideline" => "eol_hyphen"
);
$messages["P_eophyphen"] = array(
    "message_text" => "<h2>" . _("End-of-page Hyphenation") . "</h2>\n" .
        "<p>" . _("If there is a hyphen at the end of a page, it should be marked by a '<tt>*</tt>' directly following the hyphen.") . "</p>",
    "hints" => array(),
    "guideline" => "eop_hyphen"
);
$messages["P_sophyphen"] = array(
    "message_text" => "<h2>" . _("Start-of-page Hyphenation") . "</h2>\n" .
        "<p>" . _("If a page starts with part of a word continued from the previous page, place a '<tt>*</tt>' before the partial word.") . "</p>",
    "hints" => array(),
    "guideline" => "eop_hyphen"
);
$messages["P_em_long"] = array(
    "message_text" => "<h2>" . _("Em-dash proofread as long dash") . "</h2>\n" .
        "<p>" . _("There is an em-dash in the image that has been marked with four hyphens.  Proofread em-dashes using only two hyphens (<tt>--</tt>).  Four hyphens (<tt>----</tt>) are only used for very long dashes, which are about as long as 4-5 regular letters.") . "</p>",
    "hints" => array(),
    "guideline" => "em_dashes"
);
$messages["P_long_em"] = array(
    "message_text" => "<h2>" . _("Long dash proofread as em-dash") . "</h2>\n" .
        "<p>" . _("There is a long dash in the image that has been marked with two hyphens.  Proofread long dashes using four hyphens (<tt>----</tt>).  Two hyphens (<tt>--</tt>) are only used for em-dashes, which are about as long as 2-3 regular letters.") . "</p>",
    "hints" => array(),
    "guideline" => "em_dashes"
);
$messages["P_longdashwrong"] = array(
    "message_text" => "<h2>" . _("Long dash missing") . "</h2>\n" .
        "<p>" . _("Proofread long dashes using four hyphens (<tt>----</tt>).") . "</p>",
    "hints" => array(),
    "guideline" => "em_dashes"
);
$messages["P_spacedlongdash"] = array(
    "message_text" => "<h2>" . _("Spaced Long Dash") . "</h2>\n" .
        "<p>" . _("There should not be any spaces around a long dash (<tt>----</tt>).") . "</p>",
    "hints" => array(),
    "guideline" => "em_dashes"
);
$messages["P_spacedemdash"] = array(
    "message_text" => "<h2>" . _("Spaced Em-dash") . "</h2>\n" .
        "<p>" . _("There should not be any spaces around an em-dash (<tt>--</tt>).") . "</p>",
    "hints" => array(),
    "guideline" => "em_dashes"
);
$messages["P_eoldash"] = array(
    "message_text" => "<h2>" . _("End-of-line Em-dash") . "</h2>\n" .
        "<p>" . _("There is an em-dash at the end of a line. Move the first word of the next line up to that line and put the line break back in after that word.") . "</p>",
    "hints" => array(),
    "guideline" => "eol_hyphen"
);
$messages["P_soldash"] = array(
    "message_text" => "<h2>" . _("Start-of-line Em-dash") . "</h2>\n" .
        "<p>" . _("There is an em-dash at the beginning of a line. Move the em-dash and the word following it up to the previous line and put the line break back in after that word.") . "</p>",
    "hints" => array(),
    "guideline" => "eol_hyphen"
);
$messages["P_lowerlinedash"] = array(
    "message_text" => "<h2>" . _("Em-dash placement") . "</h2>\n" .
        "<p>" . _("When an em-dash appears at the beginning of a line, move it and the following word up to the <i>previous</i> line and put the line break back in after that word.") . "</p>",
    "hints" => array(),
    "guideline" => "eol_hyphen"
);
$messages["P_dashneedspace"] = array(
    "message_text" => "<h2>" . _("Omitted word") . "</h2>\n" .
        "<p>" . _("When an em-dash or a long dash represents a whole word, leave space around it as for a real word. If it is only part of a word, join it to the rest of the letters in the word.") . "</p>",
    "hints" => array(),
    "guideline" => "em_dashes"
);

// Punctuation
$messages["P_ellspaced"] = array(
    "message_text" => "<h2>" . _("Ellipsis Spacing") . "</h2>\n" .
        "<p>" . _("Please do not leave spaces between the dots of an ellipsis.") . "</p>",
    "hints" => array(),
    "guideline" => "period_p"
);
$messages["P_midellips"] = array(
    "message_text" => "<h2>" . _("Mid-sentence Ellipsis") . "</h2>\n" .
        "<p>" . _("In the middle of a sentence an ellipsis should have three dots with a space on either side.") . "</p>",
    "hints" => array(),
    "guideline" => "period_p"
);
$messages["P_endellips"] = array(
    "message_text" => "<h2>" . _("End-of-sentence Ellipsis") . "</h2>\n" .
        "<p>" . _("At the end of a sentence an ellipsis should have four dots, or three dots plus a '<tt>!</tt>' or '<tt>?</tt>', and no space before it.") . "</p>",
    "hints" => array(),
    "guideline" => "period_p"
);
$messages["P_openquotespace"] = array(
    "message_text" => "<h2>" . _("Punctuation spacing") . "</h2>\n" .
        "<p>" . _("An opening quotation mark has space after it.  Please remove the space.") . "</p>",
    "hints" => array(),
    "guideline" => "punctuat"
);
$messages["P_closequotespace"] = array(
    "message_text" => "<h2>" . _("Punctuation spacing") . "</h2>\n" .
        "<p>" . _("A closing double quote has a space before it in the text.") . "</p>",
    "hints" => array(),
    "guideline" => "punctuat"
);
$messages["P_colonspace"] = array(
    "message_text" => "<h2>" . _("Punctuation spacing") . "</h2>\n" .
        "<p>" . _("There is a semicolon or colon with a space before it in the text.") . "</p>",
    "hints" => array(),
    "guideline" => "punctuat"
);
$messages["P_commaspace"] = array(
    "message_text" => "<h2>" . _("Punctuation spacing") . "</h2>\n" .
        "<p>" . _("There is a comma or period with a space before it in the text.") . "</p>",
    "hints" => array(),
    "guideline" => "punctuat"
);
$messages["P_addedclosequote"] = array(
    "message_text" => "<h2>" . _("Close quote added") . "</h2>\n" .
        "<p>" . _("Often there is no closing quotation mark until the very end of a quoted section of text, which may not be on the same page you are proofreading. Leave it that way&mdash;do not add closing quotation marks that are not in the page image.") . "</p>",
    "hints" => array()
);
$messages["P_addedquotes"] = array(
    "message_text" => "<h2>" . _("Quote marks added") . "</h2>\n" .
        "<p>" . _("Please keep the punctuation as the author wrote it; there is no need to add quote marks to this text.") . "</p>",
    "hints" => array()
);

// Footnotes
$messages["P_fnneedaster"] = array(
    "message_text" => "<h2>" . _("Footnote marker") . "</h2>\n" .
        "<p>" . _("When footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;, etc.) we replace them all with <tt>[*]</tt> in the text, and <tt>*</tt> next to the footnote itself.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["P_fnmarkermissing"] = array(
    "message_text" => "<h2>" . _("Footnote marker") . "</h2>\n" .
        "<p>" . _("Use the same footnote marker as the original book does.  The exception is for footnotes marked with symbols, where we replace them with <tt>*</tt>.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["P_fnbottombrackets"] = array(
    "message_text" => "<h2>" . _("Footnote marker bracketed at footnote text") . "</h2>\n" .
        "<p>" . _("Precede the footnote text with the same mark you used in the text, but <b>without</b> square brackets.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["P_fnbottomsuper"] = array(
    "message_text" => "<h2>" . _("Footnote marker") . "</h2>\n" .
        "<p>" . _("There is no need for superscript markup for footnote markers.  Instead, precede the footnote text with the same mark you used above in the regular text, but without square brackets.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["P_fnbottomnospace"] = array(
    "message_text" => "<h2>" . _("Footnote marker not spaced from footnote text") . "</h2>\n" .
        "<p>" . _("Precede the footnote text with the same mark you used in the text, without square brackets but <b>with a space between them</b>.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["P_fnmarkup"] = array(
    "message_text" => "<h2>" . _("Footnote markup inserted") . "</h2>\n" .
        "<p>" . _("Leave the insertion of the footnote markup for the formatting rounds.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["P_fnbottommarker"] = array(
    "message_text" => "<h2>" . _("Footnote marker missing at footnote text") . "</h2>\n" .
        "<p>" . _("Precede the footnote text with the same mark you used in the text, but without square brackets.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["P_fnspace"] = array(
    "message_text" => "<h2>" . _("Space around footnote") . "</h2>\n" .
        "<p>" . _("Leave a blank line between the regular text and the footnote to separate them.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["P_fnspace2"] = array(
    "message_text" => "<h2>" . _("Footnote layout") . "</h2>\n" .
        "<p>" . _("Place each footnote on a separate line in order of appearance. Place a blank line between each footnote if there is more than one.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes"
);
$messages["P_fncontinued"] = array(
    "message_text" => "<h2>" . _("Footnote continued on next page") . "</h2>\n" .
        "<p>" . _("There is no need to mark a footnote that continues onto the next page; the formatters will do that.  Just proofread the text as it appears on this page.") . "</p>",
    "hints" => array()
);

// Sidenotes
$messages["P_snmove"] = array(
    "message_text" => "<h2>" . _("Sidenote moved") . "</h2>\n" .
        "<p>" . _("Leave any sidenotes where they appear in the OCR text, as long as they are not mixed in with the paragraph text.  The formatters will move them to the correct locations.") . "</p>",
    "hints" => array(),
    "guideline" => "para_side"
);
$messages["P_snmarkup"] = array(
    "message_text" => "<h2>" . _("Sidenote markup inserted") . "</h2>\n" .
        "<p>" . _("Leave the insertion of sidenote markup for the formatting rounds.") . "</p>",
    "hints" => array(),
    "guideline" => "para_side"
);
$messages["P_snlinebreak"] = array(
    "message_text" => "<h2>" . _("Sidenote text") . "</h2>\n" .
        "<p>" . _("Proofread the text of sidenotes just like normal text; rejoin any hyphenation, but otherwise preserve the line breaks.") . "</p>",
    "hints" => array(),
    "guideline" => "para_side"
);
$messages["P_columns"] = array(
    "message_text" => "<h2>" . _("Multiple columns") . "</h2>\n" .
        "<p>" . _("Proofread text that has been printed in two columns as a single column.  Place the left-most column first, the next column below that, and so on. You do not need to mark where the columns were split, just rejoin them.") . "</p>",
    "hints" => array(),
    "guideline" => "mult_col"
);
$messages["P_supnomark"] = array(
    "message_text" => "<h2>" . _("Superscript not marked") . "</h2>\n" .
        "<p>" . _("Proofread superscripted text by inserting a caret (<tt>^</tt>) before the raised letter or letters.") . "</p>",
    "hints" => array(),
    "guideline" => "supers"
);
$messages["P_supnobraces"] = array(
    "message_text" => "<h2>" . _("Superscript markup") . "</h2>\n" .
        "<p>" . _("If more than one character is superscripted, surround the raised characters with curly braces <tt>{</tt> and <tt>}</tt>.") . "</p>",
    "hints" => array(),
    "guideline" => "supers"
);
$messages["P_subnomark"] = array(
    "message_text" => "<h2>" . _("Subscript not marked") . "</h2>\n" .
        "<p>" . _("Proofread subscripted text by inserting an underscore (<tt>_</tt>) at the start and curly braces <tt>{</tt> and <tt>}</tt> around the subscript.") . "</p>",
    "hints" => array(),
    "guideline" => "subscr"
);
$messages["P_subnobraces"] = array(
    "message_text" => "<h2>" . _("Subscript markup") . "</h2>\n" .
        "<p>" . _("Surround subscripted characters with curly braces <tt>{</tt> and <tt>}</tt>.") . "</p>",
    "hints" => array(),
    "guideline" => "subscr"
);

// Markup
$messages["P_italmarkup"] = array(
    "message_text" => "<h2>" . _("Italics markup inserted") . "</h2>\n" .
        "<p>" . _("Leave the insertion of the italics markup for the formatting rounds.") . "</p>",
    "hints" => array(),
    "guideline" => "formatting"
);
$messages["P_boldmarkup"] = array(
    "message_text" => "<h2>" . _("Bold markup inserted") . "</h2>\n" .
        "<p>" . _("Leave the insertion of the bold markup for the formatting rounds.") . "</p>",
    "hints" => array(),
    "guideline" => "formatting"
);
$messages["P_scmarkup"] = array(
    "message_text" => "<h2>" . _("Small caps markup inserted") . "</h2>\n" .
        "<p>" . _("Leave the insertion of the small caps markup for the formatting rounds.") . "</p>",
    "hints" => array(),
    "guideline" => "formatting"
);
$messages["P_casechange"] = array(
    "message_text" => "<h2>" . _("Small caps case changed") . "</h2>\n" .
        "<p>" . _("Please proofread only the characters in <span style='font-variant:small-caps;'>Small Caps</span>, without making any case changes.  Leave the case as it is in the OCR text, whether that is UPPER CASE, lower case, or Title Case.") . "</p>",
    "hints" => array(),
    "guideline" => "small_caps"
);
$messages["P_illmarkup"] = array(
    "message_text" => "<h2>" . _("Illustration markup added") . "</h2>\n" .
        "<p>" . _("Leave the insertion of the illustration markup for the formatting rounds.") . "</p>",
    "hints" => array(),
    "guideline" => "formatting"
);
$messages["P_captionmove"] = array(
    "message_text" => "<h2>" . _("Illustration caption moved") . "</h2>\n" .
        "<p>" . _("Leave any illustration captions where they appear in the OCR text.  The formatters will move them to the correct locations.") . "</p>",
    "hints" => array(),
    "guideline" => "illust"
);
$messages["P_captionspace"] = array(
    "message_text" => "<h2>" . _("Space around caption") . "</h2>\n" .
        "<p>" . _("Leave the illustration caption as it appears in the image, but separate it from the regular text with a blank line before and after.") . "</p>",
    "hints" => array(),
    "guideline" => "illust"
);
$messages["P_indent"] = array(
    "message_text" => "<h2>" . _("Indentation") . "</h2>\n" .
        "<p>" . _("Leave the indentation of text for the formatting rounds.  During proofreading, leave the text left aligned.") . "</p>",
    "hints" => array(),
    "guideline" => "poetry"
);
$messages["P_joinline"] = array(
    "message_text" => "<h2>" . _("Long line of poetry joined") . "</h2>\n" .
        "<p>" . _("Leave the joining of long poetry lines to the formatting rounds.  During proofreading, please keep the line breaks as they appear in the image.") . "</p>",
    "hints" => array(),
    "guideline" => "poetry"
);
$messages["P_poetryblanklines"] = array(
    "message_text" => "<h2>" . _("Space around poetry") . "</h2>\n" .
        "<p>" . _("Leave a blank line before and after a block of poetry to separate it from the regular text.") . "</p>",
    "hints" => array(),
    "guideline" => "poetry"
);
$messages["P_poemmarkup"] = array(
    "message_text" => "<h2>" . _("Poetry markup inserted") . "</h2>\n" .
        "<p>" . _("Leave the insertion of the poetry markup for the formatting rounds.") . "</p>",
    "hints" => array()
);
$messages["P_blockmarkup"] = array(
    "message_text" => "<h2>" . _("Block quote markup inserted") . "</h2>\n" .
        "<p>" . _("Leave the insertion of the block quote markup for the formatting rounds.") . "</p>",
    "hints" => array()
);
$messages["P_tbmarkup"] = array(
    "message_text" => "<h2>" . _("Thought break markup added") . "</h2>\n" .
        "<p>" . _("Ignore any decorative marks or extra spaces between paragraphs.  The formatters will mark them later on.") . "</p>",
    "hints" => array(),
    "guideline" => "formatting"
);
$messages["P_nowrapmarkup"] = array(
    "message_text" => "<h2>" . _("Markup inserted") . "</h2>\n" .
        "<p>" . _("Leave the insertion of the /* */ markup for the formatting rounds.") . "</p>",
    "hints" => array()
);

// Proofreading old texts
$messages["P_longs"] = array(
    "message_text" => "<h2>" . _("Long s") . "</h2>\n" .
        "<p>" . _("There is an 'f' in the text that is actually a 'long s' in the image.  Although the letters look similar, the long s is a different letter: it has a missing or incomplete crossbar, while the f has a full crossbar.  Proofread the long s just like a normal s.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Long_s' target='_blank'>Long s</a> section of the wiki page for details."), $old_texts_url) . "</p>",
    "hints" => array()
);
$messages["P_longsbracket"] = array(
    "message_text" => "<h2>" . _("Long s") . "</h2>\n" .
        "<p>" . _("Unless the project comments instruct otherwise, please proofread the long s as plain s without any brackets.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Long_s' target='_blank'>Long s</a> section of the wiki page for details."), $old_texts_url) . "</p>",
    "hints" => array()
);
$messages["P_catchword"] = array(
    "message_text" => "<h2>" . _("Catchword not removed") . "</h2>\n" .
        "<p>" . _("Delete the catchword (a single word at the bottom of the page), even if it's the second half of a hyphenated word.") . "</p>",
    "hints" => array(),
    "guideline" => "next_word"
);
$messages["P_ctlig"] = array(
    "message_text" => "<h2>" . _("Decorative ligature") . "</h2>\n" .
        "<p>" . _("A decorative mark connecting the letters '<tt>ct</tt>' can be ignored when proofreading.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Ligatures' target='_blank'>Ligatures</a> section of the wiki page for details."), $old_texts_url) . "</p>",
    "hints" => array()
);
$messages["P_uvchange"] = array(
    "message_text" => "<h2>" . _("Primary Rule") . "</h2>\n" .
        "<p>" . _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please do not modernize the spelling when proofreading.  Old texts often use the letters 'u' and 'v' in different ways than we do today, but we keep what the author wrote.") . "</p>",
    "hints" => array()
);
$messages["P_italu"] = array(
    "message_text" => "<h2>" . _("Primary Rule") . "</h2>\n" .
        "<p>" . sprintf(_("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please do not modernize the spelling when proofreading.  Old texts often use the letters 'u' and 'v' in different ways than we do today, but we keep what the author wrote.  In italicized text, often the v looks like %1\$s while the u looks like %2\$s."), "<img src='images/v_ital.png' width='32' height='29'>", "<img src='images/u_ital.png' width='24' height='28'>") . "</p>",
    "hints" => array()
);
$messages["P_ijchange"] = array(
    "message_text" => "<h2>" . _("Primary Rule") . "</h2>\n" .
        "<p>" . _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please do not modernize the spelling when proofreading.  Old texts often use the letters 'i' and 'j' in different ways than we do today, but we keep what the author wrote.") . "</p>",
    "hints" => array()
);


//// Greek transliteration ////
// Basics
$messages["G_unicode"] = array(
    "message_text" => "<h2>" . _("Greek not transliterated") . "</h2>\n" .
        "<p>" . _("Please transliterate the Greek text by converting each Greek letter into an equivalent Latin one.  The Greek Transliterator (link below) allows you to click on Greek letters and obtain their transliterations.") . "</p>",
    "hints" => array()
);
$messages["G_nochange"] = array(
    "message_text" => "<h2>" . _("Greek transliteration") . "</h2>\n" .
        "<p>" . _("Please transliterate the Greek text that appears in the image; use the Greek Transliterator (link below) to identify the characters.  Remove or correct any OCR junk.") . "</p>",
    "hints" => array(),
    "guideline" => "f_chars"
);
$messages["G_lcgreek"] = array(
    "message_text" => "<h2>" . _("Greek markup") . "</h2>\n" .
        "<p>" . _("Please capitalize the word 'Greek' in your markup.") . "</p>",
    "hints" => array()
);
$messages["G_nomarkup"] = array(
    "message_text" => "<h2>" . _("Greek markup") . "</h2>\n" .
        "<p>" . _("When transliterating Greek, put '<tt>[Greek:</tt>' at the start and '<tt>]</tt>' at the end.") . "</p>",
    "hints" => array(),
    "guideline" => "f_chars"
);
$messages["G_markupline"] = array(
    "message_text" => "<h2>" . _("Greek markup") . "</h2>\n" .
        "<p>" . _("The '<tt>[Greek:</tt>' markup should be placed directly before the Greek transliteration, on the same line.") . "</p>",
    "hints" => array()
);
$messages["G_markupspace"] = array(
    "message_text" => "<h2>" . _("Greek markup") . "</h2>\n" .
        "<p>" . _("There should be a space between the colon of '<tt>[Greek:</tt>' and the Greek transliteration.") . "</p>",
    "hints" => array()
);
$messages["G_endbracket"] = array(
    "message_text" => "<h2>" . _("Greek markup") . "</h2>\n" .
        "<p>" . _("The '<tt>]</tt>' that marks the end of the Greek transliteration is missing.") . "</p>",
    "hints" => array()
);
$messages["G_markupline2"] = array(
    "message_text" => "<h2>" . _("Greek markup") . "</h2>\n" .
        "<p>" . _("The closing bracket '<tt>]</tt>' should be placed directly after the end of the Greek transliteration, on the same line.") . "</p>",
    "hints" => array()
);
$messages["G_markuprepeat"] = array(
    "message_text" => "<h2>" . _("Greek markup") . "</h2>\n" .
        "<p>" . _("You do not need to stop and start the Greek markup on each line.  Just put '<tt>[Greek:</tt>' at the beginning of the Greek transliteration and '<tt>]</tt>' at the very end.") . "</p>",
    "hints" => array()
);
$messages["G_missingcirc"] = array(
    "message_text" => "<h2>" . _("Missing circumflex accent") . "</h2>\n" .
        "<p>" . _("Two Greek vowels should be transliterated with circumflex accents, to distinguish them from other similar vowels: &omega; (omega) is transliterated as '<tt>&ocirc;</tt>' and &eta; (eta) as '<tt>&ecirc;</tt>'.  Please do not transliterate these letters as plain '<tt>o</tt>' and '<tt>e</tt>'.") . "</p>",
    "hints" => array()
);
$messages["G_punctplace"] = array(
    "message_text" => "<h2>" . _("Punctuation placement") . "</h2>\n" .
        "<p>" . _("If a whole sentence is Greek, place ending punctuation inside the '<tt>]</tt>'. If the Greek text is only a few words or phrase, place the ending punctuation outside the '<tt>]</tt>'.") . "</p>",
    "hints" => array()
);
$messages["G_missingcomma"] = array(
    "message_text" => "<h2>" . _("Missing punctuation") . "</h2>\n" .
        "<p>" . _("A comma in the image is missing in your transliteration.") . "</p>",
    "hints" => array()
);
$messages["G_missinglb"] = array(
    "message_text" => "<h2>" . _("Line breaks") . "</h2>\n" .
        "<p>" . _("Please keep the line breaks in the Greek transliteration, just as in normal proofreading.") . "</p>",
    "hints" => array()
);

// Individual characters
$messages["G_n_u"] = array(
    "message_text" => "<h2>" . _("Commonly confused letters") . "</h2>\n" .
        "<p>" . _("There is a '<tt>u</tt>' in your transliteration that should be an '<tt>n</tt>'.  The Greek letter &upsilon; (upsilon, transliterated '<tt>u</tt>' or '<tt>y</tt>') is rounded at the bottom, while the Greek letter &nu; (nu, transliterated '<tt>n</tt>') has a sharp point.") . "</p>",
    "hints" => array()
);
$messages["G_u_n"] = array(
    "message_text" => "<h2>" . _("Commonly confused letters") . "</h2>\n" .
        "<p>" . _("There is an '<tt>n</tt>' in your transliteration that should be a '<tt>u</tt>' or '<tt>y</tt>'.  The Greek letter &upsilon; (upsilon, transliterated '<tt>u</tt>' or '<tt>y</tt>') is rounded at the bottom, while the Greek letter &nu; (nu, transliterated '<tt>n</tt>') has a sharp point.") . "</p>",
    "hints" => array()
);
$messages["G_s_o"] = array(
    "message_text" => "<h2>" . _("Commonly confused letters") . "</h2>\n" .
        "<p>" . _("There is an '<tt>o</tt>' in your transliteration that should be an '<tt>s</tt>'.  The Greek letter &omicron; (omicron, transliterated '<tt>o</tt>') is a plain circle, while the Greek letter &sigma; (sigma, transliterated '<tt>s</tt>') has a bump sticking out on the upper right part of the letter.") . "</p>",
    "hints" => array()
);
$messages["G_u_o"] = array(
    "message_text" => "<h2>" . _("Commonly confused letters") . "</h2>\n" .
        "<p>" . _("There is an '<tt>o</tt>' in your transliteration that should be a '<tt>u</tt>' or '<tt>y</tt>'.  The Greek letter &upsilon; (upsilon, transliterated '<tt>u</tt>' or '<tt>y</tt>') may be nearly closed at the top, but there should be a little gap, while the Greek letter &omicron; (omicron, transliterated '<tt>o</tt>') is a full circle.") . "</p>",
    "hints" => array()
);
$messages["G_k_ch"] = array(
    "message_text" => "<h2>" . _("Commonly confused letters") . "</h2>\n" .
        "<p>" . _("There is a '<tt>ch</tt>' in your transliteration that should be a '<tt>k</tt>'.  The Greek letters &chi; (chi, transliterated '<tt>ch</tt>') and &kappa; (kappa, transliterated '<tt>k</tt>') both often look like an x, but chi is larger, and both of its 'legs' always drop down below the line. Kappa is much smaller, and normally sits on the line of text.") . "</p>",
    "hints" => array()
);
$messages["G_ph_ps"] = array(
    "message_text" => "<h2>" . _("Commonly confused letters") . "</h2>\n" .
        "<p>" . _("There is a '<tt>ps</tt>' in your transliteration that should be '<tt>ph</tt>'.  The Greek letter &phi; (phi, transliterated '<tt>ph</tt>') has a circular shape in the center, while the Greek letter &psi; (psi, transliterated '<tt>ps</tt>') has 'arms' curving out away from the center.") . "</p>",
    "hints" => array()
);
$messages["G_s_c"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>c</tt>' in the text where there is the Greek letter &sigmaf; (sigma, transliterated '<tt>s</tt>') in the image.") . "</p>",
    "hints" => array()
);
$messages["G_r_p"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>p</tt>' in the text where there is the Greek letter &rho; (rho, transliterated '<tt>r</tt>') in the image.") . "</p>",
    "hints" => array()
);
$messages["G_n_v"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>v</tt>' in the text where there is the Greek letter &nu; (nu, transliterated '<tt>n</tt>') in the image.") . "</p>",
    "hints" => array()
);
$messages["G_k_x"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: an '<tt>x</tt>' in the text where there is the Greek letter &kappa; (kappa, transliterated '<tt>k</tt>') in the image.") . "</p>",
    "hints" => array()
);
$messages["G_ph_f"] = array(
    "message_text" => "<h2>" . _("Phi transliterated as f") . "</h2>\n" .
        "<p>" . _("We do not use the letter '<tt>f</tt>' in Greek transliteration.  Please transliterate &phi; (phi) as '<tt>ph</tt>', as shown in the Greek transliteration tool.") . "</p>",
    "hints" => array()
);
$messages["G_phicase"] = array(
    "message_text" => "<h2>" . _("Capital/lower case letter") . "</h2>\n" .
        "<p>" . _("There is a '<tt>Ph</tt>' in your transliteration that should be '<tt>ph</tt>'.  The lower case Greek letter &phi; (phi, transliterated '<tt>ph</tt>') drops below the line, while the capital letter &Phi; does not.") . "</p>",
    "hints" => array()
);
$messages["G_thetacase"] = array(
    "message_text" => "<h2>" . _("Capital/lower case letter") . "</h2>\n" .
        "<p>" . _("There is a '<tt>Th</tt>' in your transliteration that should be '<tt>th</tt>'.  The Greek letter &Theta; (capital theta, transliterated '<tt>Th</tt>') is rounder, and its horizontal line does not go all the way across as it does in the lower case form &theta; (transliterated '<tt>th</tt>').") . "</p>",
    "hints" => array()
);
$messages["G_chicase"] = array(
    "message_text" => "<h2>" . _("Capital/lower case letter") . "</h2>\n" .
        "<p>" . _("There is a '<tt>Ch</tt>' in your transliteration that should be '<tt>ch</tt>'.  The lower case Greek letter &chi; (chi, transliterated '<tt>ch</tt>') drops below the line, while the capital letter &Chi; does not.") . "</p>",
    "hints" => array()
);
$messages["G_upsilon"] = array(
    "message_text" => "<h2>" . _("Upsilon transliteration") . "</h2>\n" .
        "<p>" . _("The letter &upsilon; (upsilon) can be transliterated as either u or y. If upsilon follows another vowel, use '<tt>u</tt>'. Otherwise, use '<tt>y</tt>'.") . "</p>",
    "hints" => array()
);
$messages["G_gg"] = array(
    "message_text" => "<h2>" . _("Gamma exception") . "</h2>\n" .
        "<p>" . _("The letter &gamma; (gamma) is usually transliterated as '<tt>g</tt>', but '<tt>n</tt>' is used instead when it occurs before certain letters.  These exceptions are listed at the right in the Greek Transliteration tool.") . "</p>",
    "hints" => array()
);

// Accents
$messages["G_missingh"] = array(
    "message_text" => "<h2>" . _("Rough breathing not marked") . "</h2>\n" .
        "<p>" . sprintf(_("If a vowel has the rough breathing mark: %s over it, indicate this by placing an '<tt>h</tt>' at the start of the word."), "<br><img src='images/roughbr.png' width='12' height='30'> <br>") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_extraaccent"] = array(
    "message_text" => "<h2>" . _("Greek accent not ignored") . "</h2>\n" .
        "<p>" . _("When transliterating Greek, you should ignore all accents in the original except for the rough breathing mark:") . "<br><img src='images/roughbr.png' width='12' height='30'></p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_extrah"] = array(
    "message_text" => "<h2>" . _("Accent treated as rough breathing") . "</h2>\n" .
        "<p>" . _("Vowels in Greek words can have the following diacritical marks (accents):") . "<br><img src='images/Greek_accents.png' width='200' height='33'></p>\n" .
        "<p>" . _("When transliterating Greek, you should ignore all of these except for the first one (the 'rough breathing' mark), which is curved like a C.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_smooth_rough"] = array(
    "message_text" => "<h2>" . _("Smooth breathing not ignored") . "</h2>\n" .
        "<p>" . _("If the word begins with a vowel, it should have either a rough breathing mark (curls like a C) or a smooth breathing mark (curls like a comma).  If it's rough breathing, add an '<tt>h</tt>' at the beginning of the word in the transliteration. If it's smooth breathing, ignore it.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_rough_apos"] = array(
    "message_text" => "<h2>" . _("Rough breathing treated as apostrophe") . "</h2>\n" .
        "<p>" . _("If a word begins with a capitalized letter, the accents and breathing marks are often printed to the left of the letter, rather than above it.  Treat the rough breathing mark just as if it appeared over the vowel.  It may look like an apostrophe, but in Greek an apostrophe can only appear at the end of a word, so at the start of a word it is a breathing mark.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_smooth_apos"] = array(
    "message_text" => "<h2>" . _("Breathing mark treated as apostrophe") . "</h2>\n" .
        "<p>" . _("If a word begins with a capitalized letter, the accents and breathing marks are often printed to the left of the letter, rather than above it.  Treat the rough or smooth breathing mark just as if it appeared over the vowel.  It may look like an apostrophe, but in Greek an apostrophe can only appear at the end of a word, so at the start of a word it is a breathing mark.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_capmissingh"] = array(
    "message_text" => "<h2>" . _("Rough breathing not marked") . "</h2>\n" .
        "<p>" . _("If a word begins with a capitalized letter, the accents and breathing marks are often printed to the left of the letter, rather than above it.  Treat the rough breathing mark just as if it appeared over the letter.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_rbrho"] = array(
    "message_text" => "<h2>" . _("Rough breathing on rho") . "</h2>\n" .
        "<p>" . _("When the rough breathing mark appears over the letter &rho; (rho, transliterated '<tt>r</tt>'), place the '<tt>h</tt>' <i>after</i> the '<tt>r</tt>', not before it like for vowels.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_rbcap"] = array(
    "message_text" => "<h2>" . _("Rough breathing and capitalization") . "</h2>\n" .
        "<p>" . _("If a word starts with a capital letter and has rough breathing, adjust the capitalization to follow the normal English practice: <tt>h&Ocirc;s</tt> becomes <tt>H&ocirc;s</tt>.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_rbdiph"] = array(
    "message_text" => "<h2>" . _("Rough breathing with diphthong") . "</h2>\n" .
        "<p>" . _("If a word begins with a diphthong (two vowels together), the breathing mark will appear over the second vowel, but the '<tt>h</tt>' for rough breathing still goes at the very beginning of the word in the transliteration.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_iotasub"] = array(
    "message_text" => "<h2>" . _("Iota subscript not ignored") . "</h2>\n" .
        "<p>" . _("Unless the Project Comments say otherwise, you should ignore the iota subscript just like all other accents. (An iota subscript is a small letter iota printed under another vowel.)") . "</p>",
    "hints" => array()
);

// Other
$messages["G_missingapos"] = array(
    "message_text" => "<h2>" . _("Apostrophe omitted") . "</h2>\n" .
        "<p>" . _("Greek words sometimes end with an apostrophe; please include this in your transliteration.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Punctuation' target='_blank'>Punctuation</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_missingpunct"] = array(
    "message_text" => "<h2>" . _("Greek punctuation") . "</h2>\n" .
        "<p>" . _("A vertically centered single dot in Greek text represents a medium stop; replace that with an English semicolon (<tt>;</tt>).") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Punctuation' target='_blank'>Punctuation</a> section of the wiki page for details."), $Greek_translit_url) . "</p>",
    "hints" => array()
);
$messages["G_oulig"] = array(
    "message_text" => "<h2>" . _("Ou ligature") . "</h2>\n" .
        "<p>" . sprintf(_("This letter is a shorthand way of writing ou: %s.  Transliterate it as plain '<tt>ou</tt>'."), "<img src='images/ou_lig.png' width='20' height='23'>") . "</p>",
    "hints" => array()
);
$messages["G_varrho"] = array(
    "message_text" => "<h2>" . _("Variant form") . "</h2>\n" .
        "<p>" . sprintf(_("This letter is a variant form of &rho; (rho, transliterated '<tt>r</tt>'): %s.  Transliterate it like a normal rho."), "<img src='images/rho_var.png' width='16' height='30'>") . "</p>",
    "hints" => array()
);
$messages["G_stigma"] = array(
    "message_text" => "<h2>" . _("Stigma") . "</h2>\n" .
        "<p>" . _("This form of sigma: &sigmaf; normally only occurs at the end of a word. If you meet one in the middle of a word, it's almost always a letter called 'stigma' and the top bit of it usually extends further to the right. It gets transliterated as '<tt>st</tt>'.") . "</p>",
    "hints" => array()
);

// vim: sw=4 ts=4 expandtab
