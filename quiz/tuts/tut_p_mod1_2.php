<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Moderate Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial, page %d"), 2) . "</h2>\n";
echo "<h3>" . _("End-of-line Hyphenation") . "</h3>\n";
echo "<p>" . _("Words like to-day and to-morrow that we don't commonly hyphenate now were often hyphenated in the old books we are working on. Leave them hyphenated the way the author did. If you're not sure if the author hyphenated it or not, leave the hyphen, put an <kbd>*</kbd> after it, and join the word together like this: <kbd>to-*day</kbd>. The asterisk will bring it to the attention of the post-processor, who has access to all the pages and can determine how the author typically wrote this word.") . "</p>\n";

echo "<h3>" . _("Contractions") . "</h3>\n";
echo "<p>" . _("In English, remove any extra space in contractions. For example, <kbd>would&nbsp;n't</kbd> should be proofread as <kbd>wouldn't</kbd> and <kbd>'t&nbsp;is</kbd> as <kbd>'tis</kbd>.") . "</p>\n";

echo "<h3>" . _("Dashes") . "</h3>\n";
echo "<p><i>" . _("Dashes in Deliberately Omitted or Censored Words or Names.") . "</i><br>\n";
echo _("If represented by a dash in the image, proofread these as two hyphens or four hyphens as described for em-dashes &amp; long dashes. When it represents a word, we leave appropriate space around it like it's really a word. If it's only part of a word, then no spaces&mdash;join it with the rest of the word.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod1_2'>" . _("Continue to quiz page") . "</a></p>\n";
