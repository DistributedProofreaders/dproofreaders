<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Basic Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Basic Proofreading Tutorial, Step %d"), 5) . "</h2>\n";
echo "<h3>" . _("Poetry/Epigrams") . "</h3>\n";
echo "<p>" . _("Insert a blank line at the start of the poetry or epigram and another blank line at the end, so that the formatters can clearly see the beginning and end. Leave each line left justified and maintain the line breaks. Insert a blank line between stanzas, when there is one in the image.") . "</p>\n";

echo "<h3>" . _("Font size changes") . "</h3>\n";
echo "<p>" . _("Do not mark changes in font or font size. The formatters will take care of this later in the process.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_basic_5'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
