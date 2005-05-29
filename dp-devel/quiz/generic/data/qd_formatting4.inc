<?PHP

include_once($relPath."Quiz.inc");
function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"formatting4","pass");
}

$browser_title                = "Formatting Quiz";
$welcome                      = "<h2>Formatting Quiz, page 4</h2> Try to correct the text";
$ocr_text                     = "When listening to amateur poetry, says Wellington\nBunn:\n\nTo safeguard one's person...one must be equipped with\nall manner of defensive objects.\n\nBunn goes on to list these objects, among them\n\n1. a spoon, and\n2. two sporks.\n\nSo equipped, the reader may undertake to listen to\npoetry such as that of Skelton:\n\nGup, gorbellyd Godfrey, gup, Garnysche, gaudy fole!\nTo turney or to tante with me ye ar to fare to seke:\nFor thes twayne whypslouens calle for a coke stole:\nThow mantycore, ye marmoset, garnyshte lyke a\nGreke.\n\nTruer words ne'er were spoken.";
$solutions                    = array("\nWhen listening to amateur poetry, says Wellington\nBunn:\n\n/#\nTo safeguard one's person ... one must be equipped with\nall manner of defensive objects.\n#/\n\nBunn goes on to list these objects, among them\n\n/*\n1. a spoon, and\n2. two sporks.\n*/\n\nSo equipped, the reader may undertake to listen to\npoetry such as that of Skelton:\n\n/*\nGup, gorbellyd Godfrey, gup, Garnysche, gaudy fole!\n  To turney or to tante with me ye ar to fare to seke:\nFor thes twayne whypslouens calle for a coke stole:\n  Thow mantycore, ye marmoset, garnyshte lyke a Greke.\n*/\n\nTruer words ne'er were spoken.");
$showsolution                 = TRUE;
$solved_message               = "<h2>Quiz successfully solved</h2> Congratulations, no errors found!";
$links_out                    = "<a href='../generic/main.php?type=formatting5&quiz_id=FQ' target='_top'>Next step of quiz</a><br /><a href='../start.php' target='_top'>Quizzes home.</a>";


// error messages
$messages["missingline"] = array("message_text" => "<h2>Missing blank line</h2> Paragraphs are delimited by blank lines. If a page starts with a new paragraph, place a new line at the start of the page.", "hints" => array());
$messages["nobc"] = array("message_text" => "<h2>Block Quotation</h2>\nYou have not or incorrectly marked the block quotation in the text. Enclose it with /# ... #/, with each marker on a line of its own.", "hints" => array());
$messages["nopoetry"] = array("message_text" => "<h2>Poetry markup</h2>\nYou have not or incorrectly marked the poem in the text. Enclose it with /* ... */, with each marker on a line of its own.", "hints" => array());
$messages["otherpindent"] = array("message_text" => "<h2>Poetry indentation not as expected</h2>\nFor the indentation of poetry lines there is an unofficial semi-standard of using multiples of two spaces. Not following this is not exactly an error, but in this quiz for the sake of the dumb testing routines please use indents of two spaces.", "hints" => array());
$messages["baseindent"] = array("message_text" => "<h2>Poetry indentation</h2>\nIt seems you have indented the whole poem. Please try to represent only relative indentation, so that the leftmost lines are not indented.", "hints" => array());
$messages["plinenotjoined"] = array("message_text" => "<h2>Poetry line not joined</h2>\nThere is one long poetry line, broken up into two lines. Please join those lines.", "hints" => array());
$messages["pmspacing"] = array("message_text" => "<h2>Poetry markup</h2>\nPlease leave exactly one empty line before the poetry starting marker /*. Also leave one blank line after the poetry closing marker */.", "hints" => array());
$messages["bqspacing"] = array("message_text" => "<h2>Blockquote markup</h2>\nPlease leave exactly one empty line before the blockquote starting marker /#. Also leave one blank line after the blockquote closing marker #/.", "hints" => array());
$messages["nolist"] = array("message_text" => "<h2>List not marked</h2> Mark lists with poetry markers (<tt>/* */</tt>).", "hints" => array());
$messages["ellipsis"] = array("message_text" => "<h2>Ellipsis spacing incorrect</h2> Leave a space on each side of an ellipsis.", "hints" => array());
$messages["nopindent"] = array("message_text" => "<h2>Poetry line(s) not indented</h2>\nThe poems in the text have relative indentation. Try to represent that in the proofread text.", "hints" => array());
// error checks
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nWhen"), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" => array("/*"), "case_sensitive" => TRUE, "error" => "nopoetry");
$tests[] = array("type" => "expectedtext", "searchtext" => array("*/"), "case_sensitive" => TRUE, "error" => "nopoetry");
$tests[] = array("type" => "expectedtext", "searchtext" => array("/#"), "case_sensitive" => TRUE, "error" => "nobc");
$tests[] = array("type" => "expectedtext", "searchtext" => array("#/"), "case_sensitive" => TRUE, "error" => "nobc");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n#/\n\n","\n\n/#\n"), "case_sensitive" => TRUE, "error" => "bqspacing");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n*/\n\n","\n\n/*\n"), "case_sensitive" => TRUE, "error" => "pmspacing");
$tests[] = array("type" => "forbiddentext", "searchtext" => " Gup, gorbellyd", "case_sensitive" => TRUE, "error" => "baseindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => " For thes", "case_sensitive" => TRUE, "error" => "baseindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => "   To turney", "case_sensitive" => TRUE, "error" => "otherpindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => "   Thow mantycore", "case_sensitive" => TRUE, "error" => "otherpindent");
$tests[] = array("type" => "expectedtext", "searchtext" => array("lyke a Greke."), "case_sensitive" => TRUE, "error" => "plinenotjoined");
$tests[] = array("type" => "expectedtext", "searchtext" => array("/*\n1. a spoon,"), "case_sensitive" => TRUE, "error" => "nolist");
$tests[] = array("type" => "expectedtext", "searchtext" => array("person ... one"), "case_sensitive" => TRUE, "error" => "ellipsis");
$tests[] = array("type" => "forbiddentext", "searchtext" => "\nTo turney", "case_sensitive" => TRUE, "error" => "nopindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => "\nThow mantycore", "case_sensitive" => TRUE, "error" => "nopindent");

?>
