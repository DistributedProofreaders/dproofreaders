<?PHP
$browser_title                = "DP -- Proofreading Quiz";
$ocr_text                     = "printing would be good for nothing but\nwaste paper, might not\nbe realised. The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where lie revised the\nfinal proofs. \n\nSo far as the reception of the work was\n\nWallace, p. 108.";  
$solutions                    = array("printing would be good for nothing but\nwaste paper, might not\nbe realised.[A] The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where he revised the\nfinal proofs.\n\n[Illustration: High art.]\n\nSo far as the reception of the work was\n\n[Footnote A: Wallace, p. 108.]", "printing would be good for nothing but\nwaste paper, might not\nbe realised.[A] The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where he revised the\nfinal proofs.\n\n[Illustration: High art.]\n\nSo far as the reception of the work was\n\n[Footnote A:\nWallace, p. 108.]", "printing would be good for nothing but\nwaste paper, might not\nbe realised.[A] The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where he revised the\nfinal proofs.\n\n[Illustration:\nHigh art.]\n\nSo far as the reception of the work was\n\n[Footnote A: Wallace, p. 108.]", "printing would be good for nothing but\nwaste paper, might not\nbe realised.[A] The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where he revised the\nfinal proofs.\n\n[Illustration:\nHigh art.]\n\nSo far as the reception of the work was\n\n[Footnote A:\nWallace, p. 108.]");
$showsolution                 = TRUE;
$initial_text                 = "<h2>Quiz, part 4</h2>\nTry to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.";
$solved_message               = "<h2>Part 4 of quiz successfully solved</h2>\nCongratulations, no errors found!";
$links_out                    = "<a href='../5/tut.php' target='_top'>Next step of tutorial</a><br>\n<a href='../generic/main.php?type=step5' target='_top'>Next step of quiz</a>";



$tests[] = array("type" => "longline", "lengthlimit" => 60, "error" => "longline");
$tests[] = array("type" => "forbiddentext", "searchtext" => "(A)", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "{A}", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "(1)", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "{1}", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "(Footnote", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "{Footnote", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "(Illustration", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "{Illustration", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "expectedtext", "searchtext" => array("[illustration"), "case_sensitive" => FALSE, "error" => "noillu");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "final proofs", "secondtext" => "[illustration", "case_sensitive" => FALSE, "error" => "illupos");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "[illustration", "secondtext" => "so far as", "case_sensitive" => FALSE, "error" => "illupos");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "final proofs.", "stoptext" => "[illustration", "case_sensitive" => FALSE, "errorhigh" => "illumuchspace", "errorlow" => "illunospace");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "high art.]", "stoptext" => "so far as", "case_sensitive" => FALSE, "errorhigh" => "illumuchspace", "errorlow" => "illunospace");
$tests[] = array("type" => "expectedtext", "searchtext" => array("high art"), "case_sensitive" => FALSE, "error" => "nocaption");
$tests[] = array("type" => "expectedtext", "searchtext" => array("[Illustration: High art.]", "[Illustration:\nHigh art.]"), "case_sensitive" => FALSE, "error" => "illuother");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "[", "secondtext" => "The", "case_sensitive" => TRUE, "error" => "fnmarkermissing");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "]", "secondtext" => "The", "case_sensitive" => TRUE, "error" => "fnmarkermissing");
$tests[] = array("type" => "forbiddentext", "searchtext" => "].", "case_sensitive" => TRUE, "error" => "fnmarkerbefore");
$tests[] = array("type" => "forbiddentext", "searchtext" => "realised. [", "case_sensitive" => TRUE, "error" => "spacedfnmarker");
$tests[] = array("type" => "forbiddentext", "searchtext" => "[1]", "case_sensitive" => TRUE, "error" => "fnmarkerone");
$tests[] = array("type" => "expectedtext", "searchtext" => array("[A]"), "case_sensitive" => TRUE, "error" => "fnmarkerwrong");
$tests[] = array("type" => "expectedtext", "searchtext" => array("realised.[A] The"), "case_sensitive" => TRUE, "error" => "");
$tests[] = array("type" => "expectedtext", "searchtext" => array("[Footnote"), "case_sensitive" => TRUE, "error" => "nofn");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "work was", "secondtext" => "[Footnote", "case_sensitive" => TRUE, "error" => "fnpos");
$tests[] = array("type" => "forbiddentext", "searchtext" => "[Footnote:", "case_sensitive" => TRUE, "error" => "fnwomarker");
$tests[] = array("type" => "forbiddentext", "searchtext" => "Footnote AWallace", "case_sensitive" => TRUE, "error" => "fncolonmissing");
$tests[] = array("type" => "forbiddentext", "searchtext" => "[Footnote A ", "case_sensitive" => TRUE, "error" => "fncolonmissing");
$tests[] = array("type" => "expectedtext", "searchtext" => array("[Footnote A:"), "case_sensitive" => TRUE, "error" => "fnfalsemarker");
$tests[] = array("type" => "forbiddentext", "searchtext" => "lie", "case_sensitive" => TRUE, "error" => "lie");




$messages["sqbr"] = array("message_text" => "<h2>Incorrect brackets.</h2>\nPlease use square brackets [] for illustration and footnote markup.", "hints" => array());
$messages["longline"] = array("message_text" => "<h2>Long line</h2>\nYou've probably joined two lines by deleting a line break. If you join words around hyphens or dashes, move only one word up to the end of the previous line.", "hints" => array());
$messages["fncolonmissing"] = array("message_text" => "<h2>Footnote markup without colon.</h2>\nInsert a colon between the footnote marker 'A' and the footnote text.", "hints" => array());
$messages["fnfalsemarker"] = array("message_text" => "<h2>Incorrect footnote tag.</h2>\nIt seems you have used different footnote tags. Please refer to the tutorial, or the Proofreading Guidelines.", "hints" => array());
$messages["illupos"] = array("message_text" => "<h2>Illustration position incorrect.</h2>\n<p>The illustration should be moved outside the paragraph, but next to the paragraph it was in.</p>\n<p>It can be put either below or above the paragraph it was originally in. In this case the paragraph in question starts on the previous page, so moving it above is not an option here.</p>", "hints" => array());
$messages["fnmarkermissing"] = array("message_text" => "<h2>Footnote marker missing</h2>\nIt seems you haven't inserted the required footnote marker. The marker should be in square brackets.", "hints" => array());
$messages["fnmarkerone"] = array("message_text" => "<h2>Footnote marker wrong.</h2>\nThe footnote marker you've inserted is very nearly correct. But since the original marker is a symbol, you should choose an upper case letter: [A].", "hints" => array());
$messages["fnmarkerother"] = array("message_text" => "<h2>Problem with footnote marker.</h2>\nSomething not exactly detectable doesn't seem to be right with the footnote marker. The line containing it should look like this:<br><tt>be realised.[A] The work</tt>", "hints" => array());
$messages["fnmarkerwrong"] = array("message_text" => "<h2>Footnote marker wrong.</h2>\nThe footnote marker should look like this: <tt>[A]</tt>.", "hints" => array());
$messages["fnpos"] = array("message_text" => "<h2>Footnote at wrong position.</h2>\nIt seems you have moved the footnote. Please leave it at the end of the page.", "hints" => array());
$messages["fnwomarker"] = array("message_text" => "<h2>Footnote without marker.</h2>\nInsert the footnote marker 'A' between the word 'Footnote' and the colon.", "hints" => array());
$messages["illumuchspace"] = array("message_text" => "<h2>Multiple blank lines before or after illustration.</h2>\nPlease leave not more than one blank line before and after an illustration.", "hints" => array());
$messages["illunospace"] = array("message_text" => "<h2>Blank line before or after illustration missing.</h2>\nPlease leave one blank line before and after an illustration.", "hints" => array());
$messages["illuother"] = array("message_text" => "<h2>Problem with illustration.</h2>\nSomething not exactly detectable doesn't seem to be right with the illustration. It should look like this: <br><tt>[Illustration: High art.]</tt>", "hints" => array());
$messages["illupos"] = array("message_text" => "<h2>Illustration position incorrect.</h2>\nThe illustration should be moved outside the paragraph, but next to the paragraph it was in.", "hints" => array());
$messages["lie"] = array("message_text" => "<h2>Scanno</h2>\n<p>You've missed one typical 'scanno' in the text. An 'h' mis-read as 'li'.</p>", "hints" => array(array("hint_text" => "<h2>Scanno: hints</h2>\n<p>Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.</p>\n<p>You are looking for an occurance of 'li' that is wrong. There are only 2 words with 'li' in the text. Once you've found them you will immediately know which one is the culprit.</p>\n<p>If you can only find 1 word with a 'li', consider copying the text into an editor and searching for 'li'. You'll get 2 results, guaranteed!</p>\n<p>No, we won't give away the solution, after all this is a quiz!</p>")));
$messages["nocaption"] = array("message_text" => "<h2>Illustration caption missing.</h2>\nIt seems you haven't included the illustration caption. Put the illustration caption within [Illustration: ]", "hints" => array());
$messages["nofn"] = array("message_text" => "<h2>Footnote markup missing.</h2>\nIt seems you haven't marked the footnote at the bottom correctly. Put the footnote text within [Footnote _: ] placing the correct marker where the underscore is.", "hints" => array());
$messages["noillu"] = array("message_text" => "<h2>Illustration missing.</h2>\nIt seems you haven't marked the illustration correctly. Put the illustration caption within [Illustration: ]", "hints" => array());
$messages["spacedfnmarker"] = array("message_text" => "<h2>Spaced footnote marker.</h2>\nThe footnote marker should go immediately after the word, without a space in between.", "hints" => array());
$messages["fnmarkerbefore"] = array("message_text" => "<h2>Footnote marker position wrong.</h2>\nThe footnote marker should go after the period, not before it, because this is how it appears in the original.", "hints" => array());

?>




































