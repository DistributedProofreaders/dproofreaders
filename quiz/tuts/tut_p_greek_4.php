<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Greek Transliteration Tutorial'));

echo "<h2>" . sprintf(_("Greek Transliteration Tutorial, Page %d"), 4) . "</h2>\n";

echo "<h3>" . _("Punctuation") . "</h3>\n";
echo "<p>" . _("The apostrophes at the ends of some words are, in fact, apostrophes, and serve the same purpose that they do in English: The author is leavin' some letters off the ends o' words. Please include apostrophes in your transliteration.") . "</p>\n";
echo "<p>" . _("There are usually two punctuation marks that differ from English: a question mark, and a medium stop. Modern printings of Greek often use a semicolon \";\" to indicate a question. Use a question mark \"<kbd>?</kbd>\" instead. A vertically centered single dot usually represents a medium stop; replace that with an English semicolon \"<kbd>;</kbd>\".") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_greek_4'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
