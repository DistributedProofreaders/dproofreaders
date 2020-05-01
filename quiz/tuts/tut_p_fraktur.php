<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

output_header(_('Fraktur Proofreading Tutorial'));

echo "<h2>" . _("Fraktur Proofreading Tutorial") . "</h2>\n";

echo "<p>" . _("<b>Fraktur</b> is a particular kind of blackletter font, used principally for German, but occasionally for Scandinavian languages. Modern readers often find it difficult to read.  The alphabet is:") . "</p>\n";

echo "<p><img src='../generic/images/Fraktur_alphabet.png' alt='" . _("Fraktur alphabet") . "' width='592' height='208'></p>\n";

echo "<p>" . _("There are two different forms of the lower case letter 's'.  The 'normal' (or 'round') s is used at the end of a syllable, the 'long s' (ſ) elsewhere. The two forms both get proofread as plain 's'.") . "</p>\n";

echo "<p>" . sprintf(_("If you have difficulty identifying letters in fraktur, there is a <a target='_blank' href='%s'>fraktur tool</a> similar to the Greek Transliterator but using the fraktur alphabet."), "http://www.kurald-galain.com/fraktur2ascii.html") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_fraktur'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
