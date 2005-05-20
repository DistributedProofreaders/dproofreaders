<?PHP
include_once($relPath."Quiz.inc");
function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"step3","pass");
}

$browser_title                = "DP -- Proofreading Quiz";
$ocr_text                     = "repentant and remorseful agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\nDEAREST mother, this is indeed\nlike some of\nOakwood's happy hours, \" exclaimed\nEmmeline , that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, arid raised her laughing eyes";  
$solutions                    = array("repentant and remorseful agony.\n\n\n\n\nCHAPTER VII.\n\nAt Oakwood\n\n\n\"Dearest mother, this is indeed\nlike some of\nOakwood's happy hours,\" exclaimed\nEmmeline, that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, and raised her laughing eyes");
$showsolution                 = TRUE;
$welcome                 = "<h2>Quiz, part 3</h2>\nTry to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.";
$solved_message               = "<h2>Part 3 of quiz successfully solved</h2>\nCongratulations, no errors found!";
$links_out                    = "<a href='../4/tut.php' target='_top'>Next step of tutorial</a><br>\n<a href='../generic/main.php?type=step4' target='_top'>Next step of quiz</a>";



$tests[] = array("type" => "expectedlinebreaks", "number" => 5, "starttext" => "agony.", "stoptext" => "CHAPTER", "case_sensitive" => TRUE, "errorhigh" => "notfour", "errorlow" => "notfour");
$tests[] = array("type" => "expectedlinebreaks", "number" => 3, "starttext" => "Oakwood", "stoptext" => "DEAREST", "case_sensitive" => FALSE, "errorhigh" => "nottwo", "errorlow" => "nottwo");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "vii.", "stoptext" => "at oakwood", "case_sensitive" => FALSE, "errorhigh" => "numberinheader", "errorlow" => "numberinheader");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\"De"), "case_sensitive" => FALSE, "error" => "missingquote");
$tests[] = array("type" => "forbiddentext", "searchtext" => "DEAREST", "case_sensitive" => TRUE, "error" => "capital");
$tests[] = array("type" => "forbiddentext", "searchtext" => "dearest", "case_sensitive" => TRUE, "error" => "lowercase");
$tests[] = array("type" => "forbiddentext", "searchtext" => ", \"", "case_sensitive" => FALSE, "error" => "spquote");
$tests[] = array("type" => "forbiddentext", "searchtext" => " ,", "case_sensitive" => FALSE, "error" => "spcomma");
$tests[] = array("type" => "forbiddentext", "searchtext" => "arid", "case_sensitive" => FALSE, "error" => "arid");
$tests[] = array("type" => "longline", "lengthlimit" => 60, "error" => "longline");


$messages["spquote"] = array("message_text" => "<h2>Spaced double quote</h2>\n<p>You've left a closing double quote with a space before it in the text.</p>", "hints" => array());
$messages["arid"] = array("message_text" => "<h2>Scanno</h2>\n<p>You've missed one typical 'scanno' in the text. A 'n' mis-read as 'ri'.</p>", "hints" => array(array("hint_text" => "<h2>Scanno: hints</h2>\n<p>Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.</p>\n<p>You are looking for an occurance of 'ri' that is wrong. There is only only words with 'ri' in the text. Once you've found it you will immediately know it is wrong.</p>\n<p>If you can't find any word with 'ri', consider copying the text into an editor and searching for 'ri'. You'll get a result, guaranteed!</p>\n<p>No, we won't give away the solution, after all this is a quiz!</p>")));
$messages["capital"] = array("message_text" => "<h2>Word in all capital letters</h2>\nYou've left a the word starting the chapter in all capital letters. While this matches the original, we still want it to be treated as normal text, so only the first letter should be a capital one.", "hints" => array());
$messages["lowercase"] = array("message_text" => "<h2>Starting word in all lowercase letters</h2>\nYou've gone one step too far in de-capitalizing the chapter starting word. Its first letter should still be a capital one.", "hints" => array());
$messages["notfour"] = array("message_text" => "<h2>Number of blank lines before chapter header incorrect</h2>\nThere should be 4 blank lines before the chapter header.", "hints" => array());
$messages["nottwo"] = array("message_text" => "<h2>Number of blank lines between chapter header section and text incorrect</h2>\nThere should be 2 blank lines before the start of the text.", "hints" => array());
$messages["numberinheader"] = array("message_text" => "<h2>Number of blank lines within chapter header section incorrect</h2>\nThere should be 1 blank line between different parts of the chapter header.", "hints" => array());
$messages["spcomma"] = array("message_text" => "<h2>Spaced comma</h2>\nYou've left a comma with a space before it in the text.", "hints" => array());
$messages["longline"] = array("message_text" => "<h2>Long line</h2>\nYou've probably joined two lines by deleting a line break. If you join words around hyphens or dashes, move only one word up to the end of the previous line.", "hints" => array());
$messages["missingquote"] = array("message_text" => "<h2>Double quote missing</h2>\nIt seems you haven't added a double quote at the beginning of the new chapter. Since from the context one can see there should be a double quote starting that sentence and this is only missing for typesetting reasons we insert one there.", "hints" => array());

?>


































