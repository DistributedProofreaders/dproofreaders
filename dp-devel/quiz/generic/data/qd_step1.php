<?PHP
$browser_title                = "DP -- Proofreading Quiz";
$ocr_text                     = "Good-natured and unsuspicious,\nperhaps also not sufficiently vigilant,\nHarvey was long in discovering how\nhe was pillaged. Cartwright, the\nname of tbe person who was preying\non his employer, was not a young\nman. He was between forty and fifty\nyears of age, and had been in various\nsituations, where he had always given";  
$solutions                    = array("Good-natured and unsuspicious,\nperhaps also not sufficiently <i>vigilant</i>,\nHarvey was long in discovering how\nhe was pillaged. <b>Cartwright</b>, the\nname of the person who was preying\non his employer, was not a young\nman. He was between forty and fifty\nyears of age, and had been in various\nsituations, where he had always given");
$showsolution                 = TRUE;
$initial_text                 = "<h2>Quiz, part 1</h2>\nTry to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.";
$solved_message               = "<h2>Part 1 of quiz successfully solved</h2>\nCongratulations, no errors found!";
$links_out                    = "<a href='../2/tut.php' target='_top'>Next step of tutorial</a><br>\n<a href='../generic/main.php?type=step2' target='_top'>Next step of quiz</a>";

$tests[] = array("type" => "forbiddentext", "searchtext" => "tbe", "case_sensitive" => TRUE, "error" => "tbe");
$tests[] = array("type" => "markupmissing", "opentext" => "<i>", "closetext" => "</i>", "case_sensitive" => FALSE, "error" => "noital");
$tests[] = array("type" => "markupmissing", "opentext" => "<b>", "closetext" => "</b>", "case_sensitive" => FALSE, "error" => "nobold");
$tests[] = array("type" => "markupcorrupt", "opentext" => "<i>", "closetext" => "</i>", "case_sensitive" => FALSE, "error" => "italcorrupt");
$tests[] = array("type" => "markupcorrupt", "opentext" => "<b>", "closetext" => "</b>", "case_sensitive" => FALSE, "error" => "boldcorrupt");
$tests[] = array("type" => "forbiddentext", "searchtext" => "<b> ", "case_sensitive" => FALSE, "error" => "spacedmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" => " </b>", "case_sensitive" => FALSE, "error" => "spacedmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" => "<i> ", "case_sensitive" => FALSE, "error" => "spacedmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" => " </i>", "case_sensitive" => FALSE, "error" => "spacedmarkup");
$tests[] = array("type" => "multioccurrence", "searchtext" => "<b>", "case_sensitive" => FALSE, "error" => "multiplebold");
$tests[] = array("type" => "multioccurrence", "searchtext" => "<i>", "case_sensitive" => FALSE, "error" => "multipleital");
$tests[] = array("type" => "forbiddentext", "searchtext" => ",</i>", "case_sensitive" => FALSE, "error" => "commainital");
$tests[] = array("type" => "forbiddentext", "searchtext" => ",</i>", "case_sensitive" => FALSE, "error" => "commainbold");
$tests[] = array("type" => "expectedtext", "searchtext" => array("<b>cartwright</b>"), "case_sensitive" => FALSE, "error" => "boldwrong");
$tests[] = array("type" => "expectedtext", "searchtext" => array("<i>vigilant</i>"), "case_sensitive" => FALSE, "error" => "italwrong");



$messages["tbe"] = array("message_text" => "<h2>Scanno</h2>\n<p>You've missed one typical 'scanno' in the text. An 'h' mis-read as  a 'b'.</p>\n", "hints" => array(array("hint_text" => "<h2>Scanno: hints</h2>\n<p>Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.</p>\n<p>You are looking for a 'b' that is wrong. There are only 3 words with a 'b' in the text. Once you've found them you will immediately know which one is the culprit.</p>\n<p>If you can only find 2 words with a 'b', consider copying the text into an editor and searching for 'b'. You'll get 3 results, guaranteed!</p>\n<p>No, we won't give away the solution, after all this is a quiz!</p>\n")));
$messages["noital"] = array("message_text" => "<h2>Italics missed</h2>\nThere is one word in italics in the text, please surround it with &lt;i&gt; &lt;/i&gt;.", "hints" => array());
$messages["nobold"] = array("message_text" => "<h2>Bold text missed</h2>\nThere is one bold word in the text, please surround it with &lt;b&gt; &lt;/b&gt;.", "hints" => array());
$messages["italcorrupt"] = array("message_text" => "<h2>Error in italics markup</h2>\nSomehow the italics markup you've done seems to be corrupt. Start the italics with &lt;i&gt; and end it with &lt;/i&gt;.", "hints" => array());
$messages["boldcorrupt"] = array("message_text" => "<h2>Error in bold markup</h2>\nSomehow the bold markup you've done seems to be corrupt. Start the bold text with &lt;b&gt; and end it with &lt;/b&gt;.", "hints" => array());
$messages["spacedmarkup"] = array("message_text" => "<h2>Spaced markup</h2>\nYou've marked up italics or bold in a way that there is a space after an opening tag or before a closing tag. Put the markers directly around the italics or bold text, with no additional space in between.", "hints" => array());
$messages["multiplebold"] = array("message_text" => "<h2>Multiple bold markup</h2>\nRe-check what you've marked as bold. In the original there is only one word in bold.", "hints" => array());
$messages["multipleital"] = array("message_text" => "<h2>Multiple italics markup</h2>\nRe-check what you've marked as italics. In the original there is only one word in italics.", "hints" => array());
$messages["commainital"] = array("message_text" => "<h2>Punctuation within italics markup</h2>\nGenerally, punctuation should not be included in the italics markup.", "hints" => array());
$messages["commainbold"] = array("message_text" => "<h2>Punctuation within bold markup</h2>\nGenerally, punctuation should not be included in the bold markup.", "hints" => array());
$messages["italwrong"] = array("message_text" => "<h2>Problem with italics markup</h2>\nThere is probably a problem connected with italics markup. The only word which should be marked as italics is 'vigilant'.", "hints" => array());
$messages["boldwrong"] = array("message_text" => "<h2>Problem with bold markup</h2>\nThere is probably a problem connected with bold markup. The only word which should be marked as bold is 'Cartwright'.", "hints" => array());


?>


















