<?

// Simple Proofreading Rules text, for inclusion in other files only

echo "<font face='$theme[font_mainbody]'><b>";
echo _("Simple Proofreading Rules:");
echo "</b></font><br><br>";

echo _("1) Don't rewrap lines. Leave the ends of lines where they are in the image.");
echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;";
echo _("a) except, please put words that are broken across lines back together.");
echo "<br><br>";
echo _("2) Use a blank line between paragraphs and don't indent at the beginning of a paragraph.");
echo "<br><br>";
echo _("3) Remove extra spaces around punctuation mistakenly inserted by the OCR software.");
echo "<br><br>";
echo _("4) Don't correct the original spelling.");
echo "<br><br>";
echo _("5) When in doubt, make it look like the original and use * to flag the spot.");
echo "<br><br>\n\n";


echo "<i>";
echo sprintf(_("The <a href='%s'>Proofreading Guidelines</a> (for reference) provide much more detail."),"$code_url/faq/proofreading_guidelines.php");
echo "</i><br><br>\n";
echo _("BEGINNERS ONLY projects are reserved for new proofreaders. After you have done 5-15 pages in total from these projects, please leave them for proofreaders who are newer than you. EASY projects make a good next step.");
echo "<br><br>\n";




?>
