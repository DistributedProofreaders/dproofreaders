<?php

// General-purpose default messages

$default_hintlink         = _("Desperate? Can't find it?");
$default_challenge        = _("Try to correct that or press 'restart'.");
$default_feedbacktext     = _("If you can't find the error(s) remaining on the page, please visit <a href='%s' target='_blank'>this forum topic</a>, click on the \"post reply\" button to post a feedback request. In your post, provide the link to the quiz you're having trouble with, your version of the quiz text, and the exact error message.");


// urls for links

$default_feedbackurl      = "$forums_url/viewtopic.php?t=55121";
$old_texts_url            = "$wiki_url/Proofing_old_texts";
$Greek_translit_url       = "$wiki_url/Transliterating_Greek";
$ae_oe_ligatures_url      = "$wiki_url/Ae_and_oe_ligatures";
$thorn_url                = "$wiki_url/Thorn";
$fraktur_url              = "$wiki_url/Common_Fraktur_OCR_errors";
$blackletter_url          = "$wiki_url/Proofing_blackletter";
$latex_url                = "$wiki_url/LaTeX_formatting_manual";
// XXX localization bug:
// The preceding URLs point to English-language pages in the wiki.
// We would generally want to point to a locale-appropriate page, if one exists.
// E.g. $wiki_url/French/Relecture_des_textes_anciens
//      $wiki_url/Italian/Correggere_testi_antichi


// Common initial instructions:

$initial_instructions__P     = _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.");
$initial_instructions__F     = _("Try to correct the text.");
$initial_instructions__greek = _("Use the Greek Transliterator (link below) to proofread the Greek text.  There is no need to proofread the English text, as it is already correct. When done click 'check'.");


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
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please leave the text as the author wrote it, including older or alternative spellings."),
);
$messages["P_commascanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a period (<kbd>.</kbd>) in the text where there is a comma (<kbd>,</kbd>) in the image."),
);
$messages["P_accentbracket"] = array(
    "message_title" => _("Accent"),
    "message_body" => _("Where possible, proofread accented letters using the actual characters, like <kbd>á</kbd>. You can insert accented letters using the character picker below."),
    "guideline" => "a_chars"
);
$messages["P_oe"] = array(
    "message_title" => _("Ligature"),
    "message_body" => _("Proofread the 'oe' ligature (<kbd>œ</kbd>) using the symbol directly, not as <kbd>oe</kbd> or <kbd>[oe]</kbd>. You can insert the symbol using the character picker below."),
    "guideline" => "a_chars"
);
$messages["P_macron"] = array(
    "message_title" => _("Macron"),
    "message_body" => _("Following the proofreading guidelines, please use the appropriate Unicode character for a letter with a macron (straight line) over it.  For instance, proofread an '<kbd>a</kbd>' with a macron over it as <kbd>ā</kbd>.  If the macron appears over an '<kbd>e</kbd>' it would be <kbd>ē</kbd>, and so forth. If the project's character suites do not include the character, use the diacritical markup as described in the Proofreading Guidelines."),
    "guideline" => "d_chars"
);
$messages["P_aebracket"] = array(
    "message_title" => _("Ligature"),
    "message_body" => _("Proofread the 'ae' ligature (<kbd>æ</kbd>) using the symbol directly. You can insert the symbol using the character picker below."),
    "guideline" => "a_chars"
);
$messages["P_ae"] = array(
    "message_title" => _("Ligature"),
    "message_body" => _("Proofread the 'ae' ligature (<kbd>æ</kbd>) using the symbol directly, not as <kbd>ae</kbd> or <kbd>[ae]</kbd>. You can insert the symbol using the character picker below."),
    "guideline" => "a_chars"
);
$messages["P_contracspace"] = array(
    "message_title" => _("Contraction"),
    "message_body" => _("Remove any extra space in contractions. For example, <kbd>would&nbsp;n't</kbd> should be proofread as <kbd>wouldn't</kbd>."),
    "guideline" => "contract"
);

// Paragraphs, etc.
$messages["P_lbchange"] = array(
    "message_title" => _("Line breaks"),
    "message_body" => _("You do not need to rearrange the words to make the line endings even. Please leave all line breaks in the text so that other volunteers can easily compare the lines in the text to the lines in the image."),
    "guideline" => "line_br"
);
$messages["P_para"] = array(
    "message_title" => _("Paragraph spacing"),
    "message_body" => _("Leave a single blank line before each paragraph."),
    "guideline" => "para_space"
);
$messages["P_paratop"] = array(
    "message_title" => _("Paragraph spacing"),
    "message_body" => _("Leave a single blank line before each paragraph, even at the top of the page."),
    "guideline" => "para_space"
);
$messages["P_header"] = array(
    "message_title" => _("Page header not removed"),
    "message_body" => _("Remove page headers and page footers, but <i>not</i> footnotes, from the text.  The page header is normally at the top of the image and has a page number opposite it.  Remove the entire header, including the page number."),
    "guideline" => "page_hf"
);
$messages["P_headerline"] = array(
    "message_title" => _("Blank line from page header"),
    "message_body" => _("When removing the page header, you can also remove the blank line that follows it."),
    "guideline" => "page_hf"
);
$messages["P_footer"] = array(
    "message_title" => _("Page footer not removed"),
    "message_body" => _("Remove all page footers, but <i>not</i> footnotes, from the text.  The page footer may contain a page number or other words or characters that are not part of what the author wrote."),
    "guideline" => "page_hf"
);
$messages["P_spacedchap"] = array(
    "message_title" => _("Spacing of chapter heading"),
    "message_body" => _("Leave the formatting of the chapter heading to the formatting rounds."),
    "guideline" => "chap_head"
);
$messages["P_missinglb"] = array(
    "message_title" => _("Spacing of chapter heading"),
    "message_body" => _("Leave a single blank line before and after each part of the heading."),
);
$messages["P_missingquote"] = array(
    "message_title" => _("Double quote missing"),
    "message_body" => _("Since from the context one can see there should be a double quote at the start of the chapter, and this is only missing for typesetting reasons (due to the large capital letter), we insert one there."),
    "guideline" => "chap_head"
);
$messages["P_decappedchap"] = array(
    "message_title" => _("First word of chapter decapitalised"),
    "message_body" => _("Leave this to the formatting rounds, and concentrate on making sure that the text is correct."),
);

// Hyphens, dashes
$messages["P_longline"] = array(
    "message_title" => _("Long line"),
    "message_body" => _("You've probably joined two lines by deleting a line break. If you join words around hyphens or em-dashes, make sure to put the line break back in after the word or partial word that was moved up one line."),
);
$messages["P_eolhyphen"] = array(
    "message_title" => _("End-of-line Hyphenation"),
    "message_body" => _("Sometimes words are hyphenated at the end of a line. Join the two parts of the divided word by moving the bottom part up to the previous line and putting the line break back in after the word. Remove the hyphen unless it really is a hyphenated word like 'well-meaning'."),
    "guideline" => "eol_hyphen"
);
$messages["P_eolhyphenaster"] = array(
    "message_title" => _("Questionable hyphenation"),
    "message_body" => _("Words that we don't commonly hyphenate now were often hyphenated in the old books we are working on. Leave them hyphenated the way the author did. When rejoining a hyphenated word, if you're not sure whether the author hyphenated it or not, leave the hyphen, put an <kbd>*</kbd> after it, and join the word together like this: <kbd>to-*day</kbd>."),
    "guideline" => "eol_hyphen"
);
$messages["P_hyphenlower"] = array(
    "message_title" => _("End-of-line Hyphenation"),
    "message_body" => _("Please rejoin the hyphenated word on the upper line, not the lower one."),
    "guideline" => "eol_hyphen"
);
$messages["P_hyphenleft"] = array(
    "message_title" => _("End-of-line Hyphenation"),
    "message_body" => _("When rejoining end-of-line hyphenation, remove the hyphen unless it really is a hyphenated word like 'well-meaning'."),
    "guideline" => "eol_hyphen"
);
$messages["P_eophyphen"] = array(
    "message_title" => _("End-of-page Hyphenation"),
    "message_body" => _("If there is a hyphen at the end of a page, it should be marked by an '<kbd>*</kbd>' directly following the hyphen."),
    "guideline" => "eop_hyphen"
);
$messages["P_sophyphen"] = array(
    "message_title" => _("Start-of-page Hyphenation"),
    "message_body" => _("If a page starts with part of a word continued from the previous page, place an '<kbd>*</kbd>' before the partial word."),
    "guideline" => "eop_hyphen"
);
$messages["P_em_long"] = array(
    "message_title" => _("Em-dash proofread as long dash"),
    "message_body" => _("There is an em-dash in the image that has been marked with four hyphens.  Proofread em-dashes using only two hyphens (<kbd>--</kbd>).  Four hyphens (<kbd>----</kbd>) are only used for very long dashes, which are about as long as 4-5 regular letters."),
    "guideline" => "em_dashes"
);
$messages["P_long_em"] = array(
    "message_title" => _("Long dash proofread as em-dash"),
    "message_body" => _("There is a long dash in the image that has been marked with two hyphens.  Proofread long dashes using four hyphens (<kbd>----</kbd>).  Two hyphens (<kbd>--</kbd>) are only used for em-dashes, which are about as long as 2-3 regular letters."),
    "guideline" => "em_dashes"
);
$messages["P_spacedlongdash"] = array(
    "message_title" => _("Spaced Long Dash"),
    "message_body" => _("There should not be any spaces around a long dash (<kbd>----</kbd>)."),
    "guideline" => "em_dashes"
);
$messages["P_spacedemdash"] = array(
    "message_title" => _("Spaced Em-dash"),
    "message_body" => _("There should not be any spaces around an em-dash (<kbd>--</kbd>)."),
    "guideline" => "em_dashes"
);
$messages["P_eoldash"] = array(
    "message_title" => _("End-of-line Em-dash"),
    "message_body" => _("There is an em-dash at the end of a line. Move the first word of the next line up to follow the em-dash and put the line break back in after the word that was moved up."),
    "guideline" => "eol_hyphen"
);
$messages["P_soldash"] = array(
    "message_title" => _("Start-of-line Em-dash"),
    "message_body" => _("There is an em-dash at the beginning of a line. Move the em-dash and the word following it up to the previous line and put the line break back in after that word."),
    "guideline" => "eol_hyphen"
);
$messages["P_lowerlinedash"] = array(
    "message_title" => _("Em-dash placement"),
    "message_body" => _("When an em-dash appears at the beginning of a line, move it and the following word up to the <i>previous</i> line and put the line break back in after that word."),
    "guideline" => "eol_hyphen"
);
$messages["P_dashneedspace"] = array(
    "message_title" => _("Omitted word"),
    "message_body" => _("When an em-dash or a long dash represents a whole word, leave space around it as for a real word. If it is only part of a word, join it to the rest of the letters in the word."),
    "guideline" => "em_dashes"
);

// Punctuation
$messages["P_ellspaced"] = array(
    "message_title" => _("Ellipsis Spacing"),
    "message_body" => _("Please do not leave spaces between the dots of an ellipsis."),
    "guideline" => "period_p"
);
$messages["P_midellips"] = array(
    "message_title" => _("Mid-sentence Ellipsis"),
    "message_body" => _("In the middle of a sentence an ellipsis should have three dots with a space on either side."),
    "guideline" => "period_p"
);
$messages["P_endellips"] = array(
    "message_title" => _("End-of-sentence Ellipsis"),
    "message_body" => _("At the end of a sentence an ellipsis should have four dots, or three dots plus a '<kbd>!</kbd>' or '<kbd>?</kbd>', and no space before it."),
    "guideline" => "period_p"
);
$messages["P_openquotespace"] = array(
    "message_title" => _("Punctuation spacing"),
    "message_body" => _("An opening quotation mark has space after it.  Please remove the space."),
    "guideline" => "punctuat"
);
$messages["P_closequotespace"] = array(
    "message_title" => _("Punctuation spacing"),
    "message_body" => _("A closing double quote has a space before it in the text."),
    "guideline" => "punctuat"
);
$messages["P_colonspace"] = array(
    "message_title" => _("Punctuation spacing"),
    "message_body" => _("There is a semicolon or colon with a space before it in the text."),
    "guideline" => "punctuat"
);
$messages["P_commaspace"] = array(
    "message_title" => _("Punctuation spacing"),
    "message_body" => _("There is a comma or period with a space before it in the text."),
    "guideline" => "punctuat"
);
$messages["P_addedclosequote"] = array(
    "message_title" => _("Close quote added"),
    "message_body" => _("Often there is no closing quotation mark until the very end of a quoted section of text, which may not be on the same page you are proofreading. Leave it that way&mdash;do not add closing quotation marks that are not in the page image."),
);
$messages["P_addedquotes"] = array(
    "message_title" => _("Quote marks added"),
    "message_body" => _("Please keep the punctuation as the author wrote it; there is no need to add quote marks to this text."),
);

// Footnotes
$messages["P_fnneedaster"] = array(
    "message_title" => _("Footnote marker"),
    "message_body" => _("When footnotes are marked with a series of special characters (<kbd>*</kbd>, <kbd>†</kbd>, <kbd>‡</kbd>, <kbd>§</kbd>, etc.) we replace them all with <kbd>[*]</kbd> in the text, and <kbd>*</kbd> next to the footnote itself."),
    "guideline" => "footnotes"
);
$messages["P_fnmarkermissing"] = array(
    "message_title" => _("Footnote marker"),
    "message_body" => _("Use the same footnote marker as the original book does.  The exception is for footnotes marked with symbols, where we replace them with <kbd>*</kbd>."),
    "guideline" => "footnotes"
);
$messages["P_fnbottombrackets"] = array(
    "message_title" => _("Footnote marker bracketed at footnote text"),
    "message_body" => _("Precede the footnote text with the same mark you used in the text, but <b>without</b> square brackets."),
    "guideline" => "footnotes"
);
$messages["P_fnbottomsuper"] = array(
    "message_title" => _("Footnote marker"),
    "message_body" => _("There is no need for superscript markup for footnote markers.  Instead, precede the footnote text with the same mark you used above in the regular text, but without square brackets."),
    "guideline" => "footnotes"
);
$messages["P_fnbottomnospace"] = array(
    "message_title" => _("Footnote marker not spaced from footnote text"),
    "message_body" => _("Precede the footnote text with the same mark you used in the text, without square brackets but <b>with a space between them</b>."),
    "guideline" => "footnotes"
);
$messages["P_fnmarkup"] = array(
    "message_title" => _("Footnote markup inserted"),
    "message_body" => _("Leave the insertion of the footnote markup for the formatting rounds."),
    "guideline" => "footnotes"
);
$messages["P_fnbottommarker"] = array(
    "message_title" => _("Footnote marker missing at footnote text"),
    "message_body" => _("Precede the footnote text with the same mark you used in the text, but without square brackets."),
    "guideline" => "footnotes"
);
$messages["P_fnspace"] = array(
    "message_title" => _("Space around footnote"),
    "message_body" => _("Leave a blank line between the regular text and the footnote to separate them."),
    "guideline" => "footnotes"
);
$messages["P_fnspace2"] = array(
    "message_title" => _("Footnote layout"),
    "message_body" => _("Place each footnote on a separate line in order of appearance. Place a blank line between each footnote if there is more than one."),
    "guideline" => "footnotes"
);
$messages["P_fncontinued"] = array(
    "message_title" => _("Footnote continued on next page"),
    "message_body" => _("There is no need to mark a footnote that continues onto the next page; the formatters will do that.  Just proofread the text as it appears on this page."),
);

// Sidenotes
$messages["P_snmove"] = array(
    "message_title" => _("Sidenote moved"),
    "message_body" => _("Leave any sidenotes where they appear in the OCR text, as long as they are not mixed in with the paragraph text.  The formatters will move them to the correct locations."),
    "guideline" => "para_side"
);
$messages["P_snmarkup"] = array(
    "message_title" => _("Sidenote markup inserted"),
    "message_body" => _("Leave the insertion of sidenote markup for the formatting rounds."),
    "guideline" => "para_side"
);
$messages["P_snlinebreak"] = array(
    "message_title" => _("Sidenote text"),
    "message_body" => _("Proofread the text of sidenotes just like normal text; rejoin any hyphenation, but otherwise preserve the line breaks."),
    "guideline" => "para_side"
);
$messages["P_columns"] = array(
    "message_title" => _("Multiple columns"),
    "message_body" => _("Proofread text that has been printed in two columns as a single column.  Place the left-most column first, the next column below that, and so on. You do not need to mark where the columns were split, just rejoin them."),
    "guideline" => "mult_col"
);
$messages["P_supnomark"] = array(
    "message_title" => _("Superscript not marked"),
    "message_body" => _("Proofread superscripted text by inserting a caret (<kbd>^</kbd>) before the raised letter or letters."),
    "guideline" => "supers"
);
$messages["P_supnobraces"] = array(
    "message_title" => _("Superscript markup"),
    "message_body" => _("If more than one character is superscripted, surround the raised characters with curly braces <kbd>{</kbd> and <kbd>}</kbd>."),
    "guideline" => "supers"
);
$messages["P_subnomark"] = array(
    "message_title" => _("Subscript not marked"),
    "message_body" => _("Proofread subscripted text by inserting an underscore (<kbd>_</kbd>) at the start and curly braces <kbd>{</kbd> and <kbd>}</kbd> around the subscript."),
    "guideline" => "subscr"
);
$messages["P_subnobraces"] = array(
    "message_title" => _("Subscript markup"),
    "message_body" => _("Surround subscripted characters with curly braces <kbd>{</kbd> and <kbd>}</kbd>."),
    "guideline" => "subscr"
);

// Markup
$messages["P_italmarkup"] = array(
    "message_title" => _("Italics markup inserted"),
    "message_body" => _("Leave the insertion of the italics markup for the formatting rounds."),
    "guideline" => "formatting"
);
$messages["P_boldmarkup"] = array(
    "message_title" => _("Bold markup inserted"),
    "message_body" => _("Leave the insertion of the bold markup for the formatting rounds."),
    "guideline" => "formatting"
);
$messages["P_scmarkup"] = array(
    "message_title" => _("Small caps markup inserted"),
    "message_body" => _("Leave the insertion of the small caps markup for the formatting rounds."),
    "guideline" => "formatting"
);
$messages["P_casechange"] = array(
    "message_title" => _("Small caps case changed"),
    "message_body" => _("Please proofread only the characters in <span style='font-variant:small-caps;'>Small Caps</span>, without making any case changes.  Leave the case as it is in the OCR text, whether that is UPPER CASE, lower case, or Title Case."),
    "guideline" => "small_caps"
);
$messages["P_illmarkup"] = array(
    "message_title" => _("Illustration markup added"),
    "message_body" => _("Leave the insertion of the illustration markup for the formatting rounds."),
    "guideline" => "formatting"
);
$messages["P_captionmove"] = array(
    "message_title" => _("Illustration caption moved"),
    "message_body" => _("Leave any illustration captions where they appear in the OCR text.  The formatters will move them to the correct locations."),
    "guideline" => "illust"
);
$messages["P_captionspace"] = array(
    "message_title" => _("Space around caption"),
    "message_body" => _("Leave the illustration caption as it appears in the image, but separate it from the regular text with a blank line before and after."),
    "guideline" => "illust"
);
$messages["P_indent"] = array(
    "message_title" => _("Indentation"),
    "message_body" => _("Leave the indentation of text for the formatting rounds.  During proofreading, leave the text left aligned."),
    "guideline" => "poetry"
);
$messages["P_joinline"] = array(
    "message_title" => _("Long line of poetry joined"),
    "message_body" => _("Leave the joining of long poetry lines to the formatting rounds.  During proofreading, please keep the line breaks as they appear in the image."),
    "guideline" => "poetry"
);
$messages["P_poetryblanklines"] = array(
    "message_title" => _("Space around poetry"),
    "message_body" => _("Leave a blank line before and after a block of poetry to separate it from the regular text."),
    "guideline" => "poetry"
);
$messages["P_poemmarkup"] = array(
    "message_title" => _("Poetry markup inserted"),
    "message_body" => _("Leave the insertion of the poetry markup for the formatting rounds."),
);
$messages["P_blockmarkup"] = array(
    "message_title" => _("Block quote markup inserted"),
    "message_body" => _("Leave the insertion of the block quote markup for the formatting rounds."),
);
$messages["P_tbmarkup"] = array(
    "message_title" => _("Thought break markup added"),
    "message_body" => _("Ignore any decorative marks or extra spaces between paragraphs.  The formatters will mark them later on."),
    "guideline" => "formatting"
);
$messages["P_nowrapmarkup"] = array(
    "message_title" => _("Markup inserted"),
    "message_body" => _("Leave the insertion of the /* */ markup for the formatting rounds."),
);

// Proofreading old texts
$messages["P_longs"] = array(
    "message_title" => _("Long s"),
    "message_body" => _("There is an '<kbd>f</kbd>' in the text that is actually a '<kbd>long s</kbd>' in the image.  Although the letters look similar, the <kbd>long s</kbd> is a different letter: it has a missing or incomplete crossbar, while the <kbd>f</kbd> has a full crossbar.  Proofread the <kbd>long s</kbd> just like a normal <kbd>s</kbd>."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Long_s' target='_blank'>Long s</a> section of the wiki page for details."), $old_texts_url),
);
$messages["P_longsbracket"] = array(
    "message_title" => _("Long s"),
    "message_body" => _("Unless the project comments instruct otherwise, please proofread the <kbd>long s</kbd> as plain <kbd>s</kbd> without any brackets."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Long_s' target='_blank'>Long s</a> section of the wiki page for details."), $old_texts_url),
);
$messages["P_catchword"] = array(
    "message_title" => _("Catchword not removed"),
    "message_body" => _("Delete the catchword (a single word at the bottom of the page), even if it's the second half of a hyphenated word."),
    "guideline" => "next_word"
);
$messages["P_ctlig"] = array(
    "message_title" => _("Decorative ligature"),
    "message_body" => _("A decorative mark connecting the letters '<kbd>ct</kbd>' can be ignored when proofreading."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Ligatures' target='_blank'>Ligatures</a> section of the wiki page for details."), $old_texts_url),
);
$messages["P_uvchange"] = array(
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please do not modernize the spelling when proofreading.  Old texts often use the letters '<kbd>u</kbd>' and '<kbd>v</kbd>' in different ways than we do today, but we keep what the author wrote."),
);
$messages["P_italu"] = array(
    "message_title" => _("Primary Rule"),
    "message_body" => sprintf(_("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please do not modernize the spelling when proofreading.  Old texts often use the letters '<kbd>u</kbd>' and '<kbd>v</kbd>' in different ways than we do today, but we keep what the author wrote.  In italicized text, often the <kbd>v</kbd> looks like %1\$s while the <kbd>u</kbd> looks like %2\$s."), "<img src='images/v_ital.png' width='32' height='29'>", "<img src='images/u_ital.png' width='24' height='28'>"),
);
$messages["P_ijchange"] = array(
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please do not modernize the spelling when proofreading.  Old texts often use the letters '<kbd>i</kbd>' and '<kbd>j</kbd>' in different ways than we do today, but we keep what the author wrote."),
);


//// Greek transliteration ////
// Basics
$messages["G_unicode"] = array(
    "message_title" => _("Greek not transliterated"),
    "message_body" => _("Please transliterate the Greek text by converting each Greek letter into an equivalent Latin one.  The Greek Transliterator (link below) allows you to click on Greek letters and obtain their transliterations."),
);
$messages["G_nochange"] = array(
    "message_title" => _("Greek transliteration"),
    "message_body" => _("Please transliterate the Greek text that appears in the image; use the Greek Transliterator (link below) to identify the characters.  Remove or correct any OCR junk."),
    "guideline" => "f_chars"
);
$messages["G_lcgreek"] = array(
    "message_title" => _("Greek markup"),
    "message_body" => _("Please capitalize the word 'Greek' in your markup."),
);
$messages["G_nomarkup"] = array(
    "message_title" => _("Greek markup"),
    "message_body" => _("When transliterating Greek, put '<kbd>[Greek:</kbd>' at the start and '<kbd>]</kbd>' at the end."),
    "guideline" => "f_chars"
);
$messages["G_markupline"] = array(
    "message_title" => _("Greek markup"),
    "message_body" => _("The '<kbd>[Greek:</kbd>' markup should be placed directly before the Greek transliteration, on the same line."),
);
$messages["G_markupspace"] = array(
    "message_title" => _("Greek markup"),
    "message_body" => _("There should be a space between the colon of '<kbd>[Greek:</kbd>' and the Greek transliteration."),
);
$messages["G_endbracket"] = array(
    "message_title" => _("Greek markup"),
    "message_body" => _("The '<kbd>]</kbd>' that marks the end of the Greek transliteration is missing."),
);
$messages["G_markupline2"] = array(
    "message_title" => _("Greek markup"),
    "message_body" => _("The closing bracket '<kbd>]</kbd>' should be placed directly after the end of the Greek transliteration, on the same line."),
);
$messages["G_markuprepeat"] = array(
    "message_title" => _("Greek markup"),
    "message_body" => _("You do not need to stop and start the Greek markup on each line.  Just put '<kbd>[Greek:</kbd>' at the beginning of the Greek transliteration and '<kbd>]</kbd>' at the very end."),
);
$messages["G_missingcirc"] = array(
    "message_title" => _("Missing circumflex accent"),
    "message_body" => _("Two Greek vowels should be transliterated with circumflex accents, to distinguish them from other similar vowels: <kbd>ω</kbd> (omega) is transliterated as '<kbd>ô</kbd>' and <kbd>η</kbd> (eta) as '<kbd>ê</kbd>'.  Please do not transliterate these letters as plain '<kbd>o</kbd>' and '<kbd>e</kbd>'."),
);
$messages["G_punctplace"] = array(
    "message_title" => _("Punctuation placement"),
    "message_body" => _("If a whole sentence is Greek, place ending punctuation inside the '<kbd>]</kbd>'. If the Greek text is only a few words or phrase, place the ending punctuation outside the '<kbd>]</kbd>'."),
);
$messages["G_missingcomma"] = array(
    "message_title" => _("Missing punctuation"),
    "message_body" => _("A comma in the image is missing in your transliteration."),
);
$messages["G_missinglb"] = array(
    "message_title" => _("Line breaks"),
    "message_body" => _("Please keep the line breaks in the Greek transliteration, just as in normal proofreading."),
);

// Individual characters
$messages["G_n_u"] = array(
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is a '<kbd>u</kbd>' in your transliteration that should be an '<kbd>n</kbd>'.  The Greek letter <kbd>υ</kbd> (upsilon, transliterated '<kbd>u</kbd>' or '<kbd>y</kbd>') is rounded at the bottom, while the Greek letter <kbd>ν</kbd> (nu, transliterated '<kbd>n</kbd>') has a sharp point."),
);
$messages["G_u_n"] = array(
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is an '<kbd>n</kbd>' in your transliteration that should be a '<kbd>u</kbd>' or '<kbd>y</kbd>'.  The Greek letter <kbd>υ</kbd> (upsilon, transliterated '<kbd>u</kbd>' or '<kbd>y</kbd>') is rounded at the bottom, while the Greek letter <kbd>ν</kbd> (nu, transliterated '<kbd>n</kbd>') has a sharp point."),
);
$messages["G_s_o"] = array(
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is an '<kbd>o</kbd>' in your transliteration that should be an '<kbd>s</kbd>'.  The Greek letter <kbd>ο</kbd> (omicron, transliterated '<kbd>o</kbd>') is a plain circle, while the Greek letter <kbd>σ</kbd> (sigma, transliterated '<kbd>s</kbd>') has a bump sticking out on the upper right part of the letter."),
);
$messages["G_u_o"] = array(
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is an '<kbd>o</kbd>' in your transliteration that should be a '<kbd>u</kbd>' or '<kbd>y</kbd>'.  The Greek letter <kbd>υ</kbd> (upsilon, transliterated '<kbd>u</kbd>' or '<kbd>y</kbd>') may be nearly closed at the top, but there should be a little gap, while the Greek letter <kbd>ο</kbd> (omicron, transliterated '<kbd>o</kbd>') is a full circle."),
);
$messages["G_k_ch"] = array(
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is a '<kbd>ch</kbd>' in your transliteration that should be a '<kbd>k</kbd>'.  The Greek letters <kbd>χ</kbd> (chi, transliterated '<kbd>ch</kbd>') and <kbd>κ</kbd> (kappa, transliterated '<kbd>k</kbd>') both often look like an x, but chi is larger, and both of its 'legs' always drop down below the line. Kappa is much smaller, and normally sits on the line of text."),
);
$messages["G_ph_ps"] = array(
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is a '<kbd>ps</kbd>' in your transliteration that should be '<kbd>ph</kbd>'.  The Greek letter <kbd>φ</kbd> (phi, transliterated '<kbd>ph</kbd>') has a circular shape in the center, while the Greek letter <kbd>ψ</kbd> (psi, transliterated '<kbd>ps</kbd>') has 'arms' curving out away from the center."),
);
$messages["G_s_c"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>c</kbd>' in the text where there is the Greek letter <kbd>ς</kbd> (sigma, transliterated '<kbd>s</kbd>') in the image."),
);
$messages["G_r_p"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>p</kbd>' in the text where there is the Greek letter <kbd>ρ</kbd> (rho, transliterated '<kbd>r</kbd>') in the image."),
);
$messages["G_n_v"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>v</kbd>' in the text where there is the Greek letter <kbd>ν</kbd> (nu, transliterated '<kbd>n</kbd>') in the image."),
);
$messages["G_k_x"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>x</kbd>' in the text where there is the Greek letter <kbd>κ</kbd> (kappa, transliterated '<kbd>k</kbd>') in the image."),
);
$messages["G_ph_f"] = array(
    "message_title" => _("Phi transliterated as f"),
    "message_body" => _("We do not use the letter '<kbd>f</kbd>' in Greek transliteration.  Please transliterate <kbd>φ</kbd> (phi) as '<kbd>ph</kbd>', as shown in the Greek transliteration tool."),
);
$messages["G_phicase"] = array(
    "message_title" => _("Capital/lower case letter"),
    "message_body" => _("There is a '<kbd>Ph</kbd>' in your transliteration that should be '<kbd>ph</kbd>'.  The lower case Greek letter <kbd>φ</kbd> (phi, transliterated '<kbd>ph</kbd>') drops below the line, while the capital letter <kbd>Φ</kbd> does not."),
);
$messages["G_thetacase"] = array(
    "message_title" => _("Capital/lower case letter"),
    "message_body" => _("There is a '<kbd>Th</kbd>' in your transliteration that should be '<kbd>th</kbd>'.  The Greek letter <kbd>Θ</kbd> (capital theta, transliterated '<kbd>Th</kbd>') is rounder, and its horizontal line does not go all the way across as it does in the lower case form <kbd>θ</kbd> (transliterated '<kbd>th</kbd>')."),
);
$messages["G_chicase"] = array(
    "message_title" => _("Capital/lower case letter"),
    "message_body" => _("There is a '<kbd>Ch</kbd>' in your transliteration that should be '<kbd>ch</kbd>'.  The lower case Greek letter <kbd>χ</kbd> (chi, transliterated '<kbd>ch</kbd>') drops below the line, while the capital letter <kbd>Χ</kbd> does not."),
);
$messages["G_upsilon"] = array(
    "message_title" => _("Upsilon transliteration"),
    "message_body" => _("The letter <kbd>υ</kbd> (upsilon) can be transliterated as either u or y. If upsilon follows another vowel, use '<kbd>u</kbd>'. Otherwise, use '<kbd>y</kbd>'."),
);
$messages["G_gg"] = array(
    "message_title" => _("Gamma exception"),
    "message_body" => _("The letter <kbd>γ</kbd> (gamma) is usually transliterated as '<kbd>g</kbd>', but '<kbd>n</kbd>' is used instead when it occurs before certain letters.  These exceptions are listed at the right in the Greek Transliteration tool."),
);

// Accents
$messages["G_missingh"] = array(
    "message_title" => _("Rough breathing not marked"),
    "message_body" => sprintf(_("If a vowel has the rough breathing mark: %s over it, indicate this by placing an '<kbd>h</kbd>' at the start of the word."), "<br><img src='images/roughbr.png' width='12' height='30'> <br>"),
    "wiki_ref" => sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_extraaccent"] = array(
    "message_title" => _("Greek accent not ignored"),
    "message_body" => _("When transliterating Greek, you should ignore all accents in the original except for the rough breathing mark:") . "<br><img src='images/roughbr.png' width='12' height='30'>",
    "wiki_ref" => sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_extrah"] = array(
    "message_title" => _("Accent treated as rough breathing"),
    "message_body" => sprintf(
        _("Vowels in Greek words can have the following diacritical marks (accents): %s When transliterating Greek, you should ignore all of these except for the first one (the 'rough breathing' mark), which is curved like a C."),
        "\n<br><img src='images/Greek_accents.png' width='200' height='33'><br>\n"
        ),
    "wiki_ref" => sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_smooth_rough"] = array(
    "message_title" => _("Smooth breathing not ignored"),
    "message_body" => _("If the word begins with a vowel, it should have either a rough breathing mark (curls like a C) or a smooth breathing mark (curls like a comma).  If it's rough breathing, add an '<kbd>h</kbd>' at the beginning of the word in the transliteration. If it's smooth breathing, ignore it."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_rough_apos"] = array(
    "message_title" => _("Rough breathing treated as apostrophe"),
    "message_body" => _("If a word begins with a capitalized letter, the accents and breathing marks are often printed to the left of the letter, rather than above it.  Treat the rough breathing mark just as if it appeared over the vowel.  It may look like an apostrophe, but in Greek an apostrophe can only appear at the end of a word, so at the start of a word it is a breathing mark."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_smooth_apos"] = array(
    "message_title" => _("Breathing mark treated as apostrophe"),
    "message_body" => _("If a word begins with a capitalized letter, the accents and breathing marks are often printed to the left of the letter, rather than above it.  Treat the rough or smooth breathing mark just as if it appeared over the vowel.  It may look like an apostrophe, but in Greek an apostrophe can only appear at the end of a word, so at the start of a word it is a breathing mark."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_capmissingh"] = array(
    "message_title" => _("Rough breathing not marked"),
    "message_body" => _("If a word begins with a capitalized letter, the accents and breathing marks are often printed to the left of the letter, rather than above it.  Treat the rough breathing mark just as if it appeared over the letter."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_rbrho"] = array(
    "message_title" => _("Rough breathing on rho"),
    "message_body" => _("When the rough breathing mark appears over the letter <kbd>ρ</kbd> (rho, transliterated '<kbd>r</kbd>'), place the '<kbd>h</kbd>' <i>after</i> the '<kbd>r</kbd>', not before it like for vowels."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_rbcap"] = array(
    "message_title" => _("Rough breathing and capitalization"),
    "message_body" => _("If a word starts with a capital letter and has rough breathing, adjust the capitalization to follow the normal English practice: <kbd>hÔs</kbd> becomes <kbd>Hôs</kbd>."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_rbdiph"] = array(
    "message_title" => _("Rough breathing with diphthong"),
    "message_body" => _("If a word begins with a diphthong (two vowels together), the breathing mark will appear over the second vowel, but the '<kbd>h</kbd>' for rough breathing still goes at the very beginning of the word in the transliteration."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Diacritical_Marks' target='_blank'>Diacritical Marks</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_iotasub"] = array(
    "message_title" => _("Iota subscript not ignored"),
    "message_body" => _("Unless the Project Comments say otherwise, you should ignore the iota subscript just like all other accents. (An iota subscript is a small letter iota printed under another vowel.)"),
);

// Other
$messages["G_missingapos"] = array(
    "message_title" => _("Apostrophe omitted"),
    "message_body" => _("Greek words sometimes end with an apostrophe; please include this in your transliteration."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Punctuation' target='_blank'>Punctuation</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_missingpunct"] = array(
    "message_title" => _("Greek punctuation"),
    "message_body" => _("A vertically centered single dot in Greek text represents a medium stop; replace that with an English semicolon (<kbd>;</kbd>)."),
    "wiki_ref" => sprintf(_("See the <a href='%s#Punctuation' target='_blank'>Punctuation</a> section of the wiki page for details."), $Greek_translit_url),
);
$messages["G_oulig"] = array(
    "message_title" => _("Ou ligature"),
    "message_body" => sprintf(_("This letter is a shorthand way of writing ou: %s.  Transliterate it as plain '<kbd>ou</kbd>'."), "<img src='images/ou_lig.png' width='20' height='23'>"),
);
$messages["G_varrho"] = array(
    "message_title" => _("Variant form"),
    "message_body" => sprintf(_("This letter is a variant form of <kbd>ρ</kbd> (rho, transliterated '<kbd>r</kbd>'): %s.  Transliterate it like a normal rho."), "<img src='images/rho_var.png' width='16' height='30'>"),
);
$messages["G_stigma"] = array(
    "message_title" => _("Stigma"),
    "message_body" => _("This form of sigma: <kbd>ς</kbd> normally only occurs at the end of a word. If you meet one in the middle of a word, it's almost always a letter called 'stigma' and the top bit of it usually extends further to the right. It gets transliterated as '<kbd>st</kbd>'."),
);

