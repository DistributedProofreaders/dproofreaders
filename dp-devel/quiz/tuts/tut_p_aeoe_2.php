<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

output_header(_('Ligatures Proofreading Tutorial -- Page 2'));

echo "<h2>" . _("Ligatures Proofreading Tutorial, Page 2") . "</h2>\n";

echo "<p>" . _("In other fonts, the &aelig; may have a little bump sticking up in the middle showing the vertical line of the a:") . "</p>\n";
echo "<table border='1' cellspacing='0' cellpadding='5'>\n<tr><th>&oelig;</th><th>&aelig;</th></tr>\n";
echo "<tr><td><img src='../generic/images/oelig_ital.png' alt='oe ligature' width='33' height='30'></td>\n";
echo "<td><img src='../generic/images/aelig_ital_bump.png' alt='ae ligature' width='35' height='28'></td>\n</tr></table>\n";

echo "<p>" . _("If the word is in Latin (or derived from Latin), then there is another way to distinguish these characters: Latin words often end in -&aelig;, but never in -&oelig;.") . "</p>\n";
echo "<p><a href='../generic/main.php?type=p_aeoe_2&quiz_id=OSPQ'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
