<?PHP
$browser_title                = "DP -- Proofreading Quiz";
$ocr_text                     = "We ask ourselves how Byron's poem\n\nYou have the Pyrrhic dance as yet,\nWhere is the Pyrrhic phalanx\n                 gone?\nOf two such lessons, why forget\nThe nobler and the manlier one?\n\nis related to these well known words:\n\nWhen in the Course of human events, it\nbecomes necessary for one people to dissolve the\npolitical hands which have connected ...\n\nNot at all we suspect.";  
$solutions                    = array("We ask ourselves how Byron's poem\n\n/*\nYou have the Pyrrhic dance as yet,\n  Where is the Pyrrhic phalanx gone?\nOf two such lessons, why forget\n  The nobler and the manlier one?\n*/\n\nis related to these well known words:\n\n/#\nWhen in the Course of human events, it\nbecomes necessary for one people to dissolve the\npolitical bands which have connected ...\n#/\n\nNot at all we suspect.");
$showsolution                 = TRUE;
$initial_text                 = "<h2>Quiz, part 5</h2>\nTry to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.";
$solved_message               = "<h2>Part 5 of quiz successfully solved</h2>\nCongratulations, no errors found!";
$links_out                    = "";




$tests[] = array("type" => "expectedtext", "searchtext" => array("/*"), "case_sensitive" => TRUE, "error" => "nopoetry");
$tests[] = array("type" => "expectedtext", "searchtext" => array("*/"), "case_sensitive" => TRUE, "error" => "nopoetry");
$tests[] = array("type" => "expectedtext", "searchtext" => array("/#"), "case_sensitive" => TRUE, "error" => "nobc");
$tests[] = array("type" => "expectedtext", "searchtext" => array("#/"), "case_sensitive" => TRUE, "error" => "nobc");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "words", "secondtext" => "/#", "case_sensitive" => TRUE, "error" => "bqtoomuch");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "#/", "secondtext" => "Not at", "case_sensitive" => TRUE, "error" => "bqtoomuch");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "Byron's poem", "stoptext" => "/*", "case_sensitive" => TRUE, "errorhigh" => "pmspacing", "errorlow" => "pmspacing");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "*/", "stoptext" => "is related", "case_sensitive" => TRUE, "errorhigh" => "pmspacing", "errorlow" => "pmspacing");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "known words:", "stoptext" => "/#", "case_sensitive" => TRUE, "errorhigh" => "bqspacing", "errorlow" => "bqspacing");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "#/", "stoptext" => "Not at", "case_sensitive" => TRUE, "errorhigh" => "bqspacing", "errorlow" => "bqspacing");
$tests[] = array("type" => "expectedlinebreaks", "number" => 0, "starttext" => "phalanx", "stoptext" => "gone?", "case_sensitive" => FALSE, "errorhigh" => "plinenotjoined", "errorlow" => "");
$tests[] = array("type" => "expectedtext", "searchtext" => array(" Where is"), "case_sensitive" => TRUE, "error" => "nopindent");
$tests[] = array("type" => "expectedtext", "searchtext" => array(" The nobler"), "case_sensitive" => TRUE, "error" => "nopindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => "/*You", "case_sensitive" => TRUE, "error" => "poetrymarkerown");
$tests[] = array("type" => "forbiddentext", "searchtext" => "one?*/", "case_sensitive" => TRUE, "error" => "poetrymarkerown");
$tests[] = array("type" => "forbiddentext", "searchtext" => "/#When", "case_sensitive" => TRUE, "error" => "poetrymarkerown");
$tests[] = array("type" => "forbiddentext", "searchtext" => "...#/", "case_sensitive" => TRUE, "error" => "poetrymarkerown");
$tests[] = array("type" => "forbiddentext", "searchtext" => " You have", "case_sensitive" => TRUE, "error" => "baseindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => " Of two", "case_sensitive" => TRUE, "error" => "baseindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => "   Where is", "case_sensitive" => TRUE, "error" => "otherpindent");
$tests[] = array("type" => "forbiddentext", "searchtext" => "   The nobler", "case_sensitive" => TRUE, "error" => "otherpindent");
$tests[] = array("type" => "expectedtext", "searchtext" => array("  Where is"), "case_sensitive" => FALSE, "error" => "otherpindent");
$tests[] = array("type" => "expectedtext", "searchtext" => array("  The nobler"), "case_sensitive" => FALSE, "error" => "otherpindent");
$tests[] = array("type" => "expectedtext", "searchtext" => array("bands"), "case_sensitive" => FALSE, "error" => "hands");




$messages["bqspacing"] = array("message_text" => "<h2>Block Quotes</h2>\nPlease leave exactly one empty line before the block quote starting marker /#. Also leave one blank line after the block quote closing marker #/.", "hints" => array());
$messages["hands"] = array("message_text" => "<h2>Scanno</h2>\n<p>You've missed one typical 'scanno' in the text. A 'b' mis-read as an 'h'.</p>", "hints" => array(array("hint_text" => "<h2>Scanno: hints</h2>\n<p>Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.</p>\n<p>You are looking for an 'h' that is wrong. There are 21 words with an 'h' in the text. Two of those words would also make sense if you replaced the 'h' by a 'b'. Check them with the original and you'll know.</p>\n<p>If you can't find all 21 words with an 'h', consider copying the text into an editor and searching for 'h'.</p>\n<p>No, we won't give away the solution, after all this is a quiz!</p>")));
$messages["nobc"] = array("message_text" => "<h2>Block Quotation</h2>\nYou have not or incorrectly marked the block quotation in the text. Enclose it with /# ... #/, with each marker on a line of its own.", "hints" => array());
$messages["nopindent"] = array("message_text" => "<h2>Poetry line(s) not indented</h2>\nThe poems in the text have relative indentation. Try to represent that in the proofreaded text.", "hints" => array());
$messages["nopoetry"] = array("message_text" => "<h2>Poetry markup</h2>\nYou have not or incorrectly marked the poem in the text. Enclose it with /* ... */, with each marker on a line of its own.", "hints" => array());
$messages["otherpindent"] = array("message_text" => "<h2>Poetry indentation not as expected</h2>\nFor the indentation of poetry lines there is an unofficial semi-standard of using multiples of two spaces. Not following this is not exactly an error, but in this quiz for the sake of the dumb testing routines please use indents of two spaces.", "hints" => array());
$messages["baseindent"] = array("message_text" => "<h2>Poetry indentation</h2>\nIt seems you have indented the whole poem. Please try to represent only relative indentation, so that the leftmost lines are not indented.", "hints" => array());
$messages["plinenotjoined"] = array("message_text" => "<h2>Poetry line not joined</h2>\nThere is one long poetry line, broken up into two lines. Please join those lines.", "hints" => array());
$messages["pmspacing"] = array("message_text" => "<h2>Poetry markup</h2>\nPlease leave exactly one empty line before the poetry starting marker /*. Also leave one blank line after the poetry closing marker */.", "hints" => array());
$messages["poetrymarkerown"] = array("message_text" => "<h2>Problem with Poetry or Block Quotation markup</h2>\nPlease put the poetry markers /* and */ and block quotation markers /# and #/ each on a line of their own.", "hints" => array());
$messages["bqtoomuch"] = array("message_text" => "<h2>Block quotation markup wrong</h2>\nYou've included too much text in the block quotation.", "hints" => array());

?>






































