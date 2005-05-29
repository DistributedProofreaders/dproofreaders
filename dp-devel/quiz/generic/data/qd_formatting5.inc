<?PHP

include_once($relPath."Quiz.inc");
function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"formatting5","pass");
}

$browser_title                = "Formatting Quiz";
$welcome                      = "<h2>Formatting Quiz, page 5</h2> Try to correct the text.";
$ocr_text                     = "CAMPFIRE GIRLS WEAR WHYPSLOVENS     191\n\nthe necessity of nursing her.\"\n\n\"Come on, girls; get your wraps; we will all go over.\nIt's only a couple[1] of blocks. Hurry, everybody!\"\n\n\"Wait, and I'll tell Kitty we're going out,\" Marion said.\nShe ran through several rooms, calling \"Kittie! Kittie!\"\nbut received no response.\n\n\"I wonder where she is,\" the\nhostess said, in a puzzled manner.\n\"Well, we haven't time to find her.\nCome on.\"\nWhere is Kitty?\n\"I think I saw her go out more\nthan half an hour ago,\" Harriet\nNewcomb said. \"She called someone up on the\ntelephone, and then put her hat and coat on and went\nout the side way, and I haven't seen her since.\"\n\n1Harriet was lying; really, the whypsloven factory was\nseveral miles away.";
$solutions                    = array("the necessity of nursing her.\"\n\n\"Come on, girls; get your wraps; we will all go over.\nIt's only a couple[1] of blocks. Hurry, everybody!\"\n\n\"Wait, and I'll tell Kitty we're going out,\" Marion said.\n\nShe ran through several rooms, calling \"Kittie! Kittie!\"\nbut received no response.\n\n\"I wonder where she is,\" the\nhostess said, in a puzzled manner.\n\"Well, we haven't time to find her.\nCome on.\"\n\n[Illustration: Where is Kitty?]\n\n\"I think I saw her go out more\nthan half an hour ago,\" Harriet\nNewcomb said. \"She called someone up on the\ntelephone, and then put her hat and coat on and went\nout the side way, and I haven't seen her since.\"\n\n[Footnote 1: Harriet was lying; really, the whypsloven factory was\nseveral miles away.]", "the necessity of nursing her.\"\n\n\"Come on, girls; get your wraps; we will all go over.\nIt's only a couple[1] of blocks. Hurry, everybody!\"\n\n\"Wait, and I'll tell Kitty we're going out,\" Marion said.\nShe ran through several rooms, calling \"Kittie! Kittie!\"\nbut received no response.\n\n[Illustration: Where is Kitty?]\n\n\"I wonder where she is,\" the\nhostess said, in a puzzled manner.\n\"Well, we haven't time to find her.\nCome on.\"\n\n\"I think I saw her go out more\nthan half an hour ago,\" Harriet\nNewcomb said. \"She called someone up on the\ntelephone, and then put her hat and coat on and went\nout the side way, and I haven't seen her since.\"\n\n[Footnote 1: Harriet was lying; really, the whypsloven factory was\nseveral miles away.]");
$showsolution                 = TRUE;
$solved_message               = "<h2>Quiz successfully solved</h2> Congratulations, no errors found!";
$links_out                    = "<br /><a href='../start.php' target='_top'>Quizzes home.</a>";


// error messages
$messages["sqbr"] = array("message_text" => "<h2>Incorrect brackets.</h2>\nPlease use square brackets [] for illustration and footnote markup.", "hints" => array());
$messages["pageheader"] = array("message_text" => "<h2>Page header left in</h2>\nRemove the page header and the page number.", "hints" => array());
$messages["illusmissing"] = array("message_text" => "<h2>Illustration missing.</h2>\nIt seems you haven't marked the illustration correctly. Put the illustration caption within [Illustration: ]", "hints" => array());
$messages["illuswrong"] = array("message_text" => "<h2>Illustration incorrectly marked.</h2>\nCheck the case of the caption, and make sure there is a blank line before and after the illustration marker.", "hints" => array());
$messages["nofn"] = array("message_text" => "<h2>Footnote markup missing.</h2>\nIt seems you haven't marked the footnote at the bottom correctly. Put the footnote text within [Footnote _: ] placing the correct marker where the underscore is.", "hints" => array());
$messages["wrongfn"] = array("message_text" => "<h2>Footnote markup wrong.</h2>\nIt seems you haven't marked the footnote at the bottom correctly. Put the footnote text within [Footnote _: ] placing the correct marker where the underscore is. Remember to leave a blank line before the footnote.", "hints" => array());
$messages["wrongblank"] = array("message_text" => "<h2>Extra blank line.</h2>\nThe page starts in the middle of a paragraph, so there should not be a blank line at the top.", "hints" => array());
$messages["missingline"] = array("message_text" => "<h2>Missing blank line</h2> Paragraphs are delimited by blank lines. If a page starts with a new paragraph, place a new line at the start of the page.", "hints" => array());



// error checks
$tests[] = array("type" => "forbiddentext", "searchtext" => "CAMPFIRE GIRLS WEAR WHYPSLOVENS", "case_sensitive" => TRUE, "error" => "pageheader");
$tests[] = array("type" => "forbiddentext", "searchtext" => "\nthe necessity", "case_sensitive" => FALSE, "error" => "wrongblank");
$tests[] = array("type" => "forbiddentext", "searchtext" => "191", "case_sensitive" => TRUE, "error" => "pageheader");
$tests[] = array("type" => "forbiddentext", "searchtext" => "(Footnote", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "{Footnote", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "(Illustration", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "{Illustration", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Illustration: Where is Kitty?]"), "case_sensitive" => FALSE, "error" => "illusmissing");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n[Illustration: Where is Kitty?]\n\n"), "case_sensitive" => TRUE, "error" => "illuswrong");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Footnote"), "case_sensitive" => FALSE, "error" => "nofn");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n[Footnote 1:"), "case_sensitive" => FALSE, "error" => "wrongfn");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n\"Come on"), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n\"Wait,"), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\nShe ran"), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n\"Come on"), "case_sensitive" => TRUE, "error" => "missingline");




?>
