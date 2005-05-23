<?PHP
include_once($relPath."Quiz.inc");
function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"step2","pass");
}

$browser_title                = "DP -- Proofreading Quiz";
$ocr_text                     = "a detective, why was he watching? There was\nindeed no reward offered whatsoever for his arrest.\nPerhaps he belonged to the wretched type of beings\nwho do pride themselves on their public spirit--\nmen who wrote letters to the newspapers and\ninterfered in <i>other</i> people's business. He might now\nwell have wanted to show his public spirit by hand-\ning him over to the police. The newspaper in his\nhand! Of course. He had read his description there,\nand identified him.\nCharles now found himself conjecturing how the\nman would set about carrying out his task of pub-";  
$solutions                    = array("a detective, why was he watching? There was\nindeed no reward offered whatsoever for his arrest.\nPerhaps he belonged to the wretched type of beings\nwho do pride themselves on their public spirit--men\nwho wrote letters to the newspapers and\ninterfered in other people's business. He might now\nwell have wanted to show his public spirit by handing\nhim over to the police. The newspaper in his\nhand! Of course. He had read his description there,\nand identified him.\n\nCharles now found himself conjecturing how the\nman would set about carrying out his task of pub-*", "a detective, why was he watching? There was\nindeed no reward offered whatsoever for his arrest.\nPerhaps he belonged to the wretched type of beings\nwho do pride themselves on their public\nspirit--men who wrote letters to the newspapers and\ninterfered in other people's business. He might now\nwell have wanted to show his public spirit by handing\nhim over to the police. The newspaper in his\nhand! Of course. He had read his description there,\nand identified him.\n\nCharles now found himself conjecturing how the\nman would set about carrying out his task of pub-*");
$showsolution                 = TRUE;
$welcome                 = "<h2>Quiz, part 2</h2>\nTry to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.";
$solved_message               = "<h2>Part 2 of quiz successfully solved</h2>\nCongratulations, no errors found!";
$links_out                    = "<a href='../tuts/tut3.php' target='_top'>Next step of tutorial</a><br>\n<a href='../generic/main.php?type=step3' target='_top'>Next step of quiz</a>";



$tests[] = array("type" => "expectedtext", "searchtext" => array("pub-*"), "case_sensitive" => TRUE, "error" => "eophyphen");
$tests[] = array("type" => "expectedtext", "searchtext" => array("handing"), "case_sensitive" => TRUE, "error" => "hyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" => "-\n", "case_sensitive" => TRUE, "error" => "eolhyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" => " --", "case_sensitive" => TRUE, "error" => "spacedem");
$tests[] = array("type" => "forbiddentext", "searchtext" => "-- ", "case_sensitive" => TRUE, "error" => "spacedem");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "him.", "stoptext" => "Charles now", "case_sensitive" => TRUE, "errorhigh" => "toomanylb", "errorlow" => "para");
$tests[] = array("type" => "forbiddentext", "searchtext" => "<i>", "case_sensitive" => FALSE, "error" => "extraital");
$tests[] = array("type" => "forbiddentext", "searchtext" => "</i>", "case_sensitive" => FALSE, "error" => "extraital");
$tests[] = array("type" => "longline", "lengthlimit" => 60, "error" => "longline");


$messages["para"] = array("message_text" => "<h2>Paragraph not marked</h2>\n<p>Paragraphs should be divided by a blank line.</p>", "hints" => array());
$messages["toomanylb"] = array("message_text" => "<h2>Too many linebreaks inserted</h2>\n<p>Paragraphs should be divided by exactly one blank line.</p>", "hints" => array());
$messages["eolhyphen"] = array("message_text" => "<h2>End-of-line hyphen or dash</h2>\nYou've left a hyphen or dash at the end of a line. The first word of the next line should be moved up to that line (and possibly the word should be joined and the hyphen deleted.)", "hints" => array());
$messages["eophyphen"] = array("message_text" => "<h2>End-of-page hyphen</h2>\nIf there is a hyphen or dash at the end of a page, it should be marked by a '*' directly following the dash or hyphen.", "hints" => array());
$messages["hyphen"] = array("message_text" => "<h2>End-of-line Hyphenation</h2>\nYou've left a hyphen at the end of a line. Join the two parts of the divided word by moving the bottom part up to the previous line. Remove the hyphen unless it really is a hyphenated word like 'well-meaning'.", "hints" => array());
$messages["spacedem"] = array("message_text" => "<h2>Spaced em-dash</h2>\nYou have inserted spaces around the em-dash (--). Please remove them.", "hints" => array());
$messages["longline"] = array("message_text" => "<h2>Long line</h2>\nYou've probably joined two lines by deleting a line break. If you join words around hyphens or dashes, move only one word up to the end of the previous line.", "hints" => array());
$messages["extraital"] = array("message_text" => "<h2>Normal text marked up as italics</h2>\nSometimes our preprocessing software inserts mark-ups (like &lt;i&gt; &lt;/i&gt;) when they are unnecessary. You just found an instance of this.", "hints" => array());

?>

























