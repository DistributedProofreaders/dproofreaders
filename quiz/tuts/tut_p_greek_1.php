<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once('../generic/quiz_defaults.inc'); // $Greek_translit_url

require_login();

output_header(_('Greek Transliteration Tutorial'));

echo "<h1>" . _("Proofreading Tutorial") . "</h1>\n";
echo "<h2>" . _("Intro") . "</h2>\n";
echo "<p>" . sprintf(_("In this tutorial you will be presented extracts from the Proofreading Guidelines and the %1\$s Wiki article on <a href='%2\$s' target='_blank'>Transliterating Greek</a>. After each part you will be led to a quiz page, where you can try out  the newly learned rules."), $site_abbreviation, $Greek_translit_url) . "</p>\n";

echo "<h2>" . sprintf(_("Greek Transliteration Tutorial, page %d"), 1) . "</h2>\n";
echo "<h3>" . _("Non-Latin Characters") . "</h3>\n";
echo "<p>" . _("Some projects contain text printed in non-Latin characters; that is, characters other than the Latin A-Z&mdash;for example, Greek, Cyrillic, Hebrew, or Arabic characters. How these characters are handled depends on the project. Some Project Managers will ask you to include the characters directly while others may ask you to transliterate them. This tutorial covers Greek transliteration.") . "</p>\n";
echo "<p>" . _("Transliteration involves converting each character of the foreign text into the equivalent Latin letter(s). A Greek transliteration tool is provided in the proofreading interface to make this task much easier.") . "</p>\n";
echo "<p>" . sprintf(_("Press the \"Greek Transliterator\" link near the bottom of the proofreading interface to open the tool. In the tool, click on the Greek characters that match those in the word or phrase you are transliterating, and the appropriate Latin-1 characters will appear in the text box. When you are done, simply copy and paste this transliterated text into the page you are proofreading. Surround the transliterated text with the Greek markers <kbd>[Greek:&nbsp;</kbd> and <kbd>]</kbd>. For example, <span style='font-size:115%%;'>Βιβλος</span> would become <kbd>[Greek: Biblos]</kbd>. (\"Book\"&mdash;so appropriate for %s!)"), $site_abbreviation) . "</p>\n";
echo "<p>" . sprintf(_("If the transliteration tool does not appear when you click on the button, your computer may be blocking pop-ups. Make sure that your software allows pop-ups from the %s site."), $site_abbreviation) . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_greek_1'>" . _("Continue to quiz page") . "</a></p>\n";

