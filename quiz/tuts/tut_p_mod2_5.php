<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Moderate Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial, Page %d"), 10) . "</h2>\n";
echo "<h3>" . _("Plays: Actor Names/Stage Directions") . "</h3>\n";
echo "<p>" . _("In dialog, treat a change in speaker as a new paragraph, with one blank line before it.") . "</p>\n";
echo "<p>" . _("Stage directions are kept as they are in the original image, so if the stage direction is on a line by itself, proofread it that way; if it is at the end of a line of dialog, leave it there. Stage directions often begin with an opening bracket and omit the closing bracket. This convention is retained; do not close the brackets.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod2_5'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
