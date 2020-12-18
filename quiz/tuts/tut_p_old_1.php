<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once('../generic/quiz_defaults.inc'); // $old_texts_url

require_login();

output_header(_('Old Texts Proofreading Tutorial'));

echo "<h1>" . _("Proofreading Tutorial") . "</h1>\n";
echo "<h2>" . _("Intro") . "</h2>\n";
echo "<p>" . sprintf(_("In this tutorial you will be presented extracts from the Proofreading Guidelines and the %1\$s Wiki article on <a href='%2\$s' target='_blank'>Proofing old texts</a>. After each part you will be led to a quiz page, where you can try out the newly learned rules."), $site_abbreviation, $old_texts_url) . "</p>\n";

echo "<h2>" . sprintf(_("Old Texts Proofreading Tutorial, page %d"), 1) . "</h2>\n";
echo "<h3>" . _("Long s") . "</h3>\n";
echo "<p>" . _("The fonts in older texts are generally similar to modern fonts, but one major difference is in the letter s.  In older texts there are two different forms of the lower-case letter s: long s and round s. The round s looks like the modern letter, while the long s looks more like the letter f, except that part or all of the crossbar is missing.") . "</p>\n";
echo "<p>" . _("The long s is usually proofread simply as s, without marking it specially.") . "</p>\n";

echo "<h3>" . _("Ligatures") . "</h3>\n";
echo "<p>" . _("In older texts there are often flourishes connecting letters, particularly the letters 'ct' and sometimes 'st'.  These are not noted specially in proofreading, so just proofread them as the two letters.") . "</p>\n";

echo "<h3>" . _("Single word at bottom of page") . "</h3>\n";
echo "<p>" . _("Proofread this by deleting the word, even if it's the second half of a hyphenated word.") . "</p>\n";
echo "<p>" . _("In some older books, the single word at the bottom of the page (called a \"catchword\", usually printed near the right margin) indicates the first word on the next page of the book (called an \"incipit\"). It was used to alert the printer to print the correct reverse (called \"verso\"), to make it easier for printers' helpers to make up the pages prior to binding, and to help the reader avoid turning over more than one page.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_old_1'>" . _("Continue to quiz page") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
