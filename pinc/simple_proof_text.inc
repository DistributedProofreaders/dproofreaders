<?php

// Simple Proofreading Rules text, for inclusion in other files only

echo "<div id='simple-proofreading-rules'>";
// This page is pulled into other pages, both as a top-level page as well
// as mid-page. So we start this page with <h2> tags instead of <h1> tags.
echo "<h2>";
echo _("Simple Proofreading Rules");
echo "</h2>";

echo "<p>";
echo _("1) Don't rewrap lines. Leave the ends of lines where they are in the image.");
echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;";
echo _("a) except, please put words that are broken across lines back together.");
echo "<br><br>";
echo _("2) Use a blank line before each paragraph and don't indent at the beginning of a paragraph.");
echo "<br><br>";
echo _("3) Remove extra spaces around punctuation mistakenly inserted by the OCR software.");
echo "<br><br>";
echo _("4) Don't correct the original spelling.");
echo "<br><br>";
// TRANSLATORS: PM = project manager
echo _("5) When in doubt, make it look like the original and use [** <i>Notes for the next proofreader or PM would go here</i>] to flag the spot.");
echo "</p>\n\n";


echo "<p class='italic'>";
echo sprintf(_("The <a href='%s'>Proofreading Guidelines</a> (for reference) provide much more detail."), "$code_url/faq/proofreading_guidelines.php");
echo "</p><p>\n";
echo _("BEGINNERS ONLY projects are reserved for new proofreaders. After you have done 5-15 pages in total from these projects, please leave them for proofreaders who are newer than you. EASY projects make a good next step.");
echo "</p></div>\n";
