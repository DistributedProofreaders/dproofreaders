<?php
include_once($relPath."Quiz.inc");

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"formatting1","pass");
}

$browser_title                = _("Formatting Quiz");
$welcome                      = "<h2>" . _("Formatting Quiz, page 1") . "</h2>" . _("Try to correct the text.");
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$ocr_text                     = "Accordingly, he suggested to the manager of the\nhouse that the front be brightened up at night by\nelectrical signs, one row of lights spelling his name\n\"BURTON\" and another row of lights spelling the\nname \"HOLMES.\"\nThe manager told him it was too much of an \ninnovation for him to authorize and referred him to\nthe owner of the theatre. Mr. Holmes travelled\nseveral hours into the country to consult with the\nowner, who referred him to his agent in the city. The\nagent in turn sent Mr. Holmes to the janitor of the\ntheatre.";
$solutions                    = array("\nAccordingly, he suggested to the manager of the\nhouse that the front be brightened up at night by\nelectrical signs, one row of lights spelling his name\n\"<sc>Burton</sc>\" and another row of lights spelling the\nname \"<sc>Holmes</sc>.\"\n\nThe <b>manager</b> told him it was too much of an\ninnovation for <i>him</i> to authorize and referred him to\nthe owner of the theatre. Mr. Holmes travelled\nseveral hours into the country to consult with the\nowner, who referred him to his agent in the city. The\nagent in turn sent Mr. Holmes to the janitor of the\ntheatre.");
$showsolution                 = TRUE;
$solved_message               = "<h2>" . _("Quiz solved!") . "</h2>" . _("Congratulations, no errors found!");
$links_out                    = sprintf("<a href='../generic/main.php?type=formatting2&quiz_id=FQ' target='_top'>%s</a><br><a href='../start.php' target='_top'>%s</a>", _("Next step of quiz"), _("Quizzes home."));


// error messages
$messages["nosmallcaps"] = array(
    "message_text" => "<h2>" . _("<span style='font-variant: small-caps;'>Small Caps</span> not marked") . "</h2>",
    "hints" => array()
);
$messages["noital"] = array(
    "message_text" => "<h2>" . _("Italics missed") . "</h2>" . 
        _("There is one word in italics in the text, please surround it with &lt;i&gt; &lt;/i&gt;."),
    "hints" => array()
);
$messages["nobold"] = array(
    "message_text" => "<h2>" . _("Bold text missed") . "</h2>" . 
        _("There is one bold word in the text, please surround it with &lt;b&gt; &lt;/b&gt;."),
    "hints" => array()
);
$messages["italcorrupt"] = array(
    "message_text" => "<h2>" . _("Error in italics markup") . "</h2>" . 
        _("Somehow the italics markup you've done seems to be corrupt. Start the italics with &lt;i&gt; and end it with &lt;/i&gt;."),
    "hints" => array()
);
$messages["boldcorrupt"] = array(
    "message_text" => "<h2>" . _("Error in bold markup") . "</h2>" . 
        _("Somehow the bold markup you've done seems to be corrupt. Start the bold text with &lt;b&gt; and end it with &lt;/b&gt;."),
    "hints" => array()
);
$messages["smcapcorrupt"] = array(
    "message_text" => "<h2>" . _("Error in small caps markup") . "</h2>" .
        _("Somehow the small caps markup you've done seems to be incorrect. Start the small caps with &lt;sc&gt; and end it with &lt;/sc&gt;."),
    "hints" => array()
);
$messages["missingline"] = array(
    "message_text" => "<h2>" . _("Missing blank line") . "</h2>" .
        _("Paragraphs are delimited by blank lines. If a page starts with a new paragraph, place a new line at the start of the page."),
    "hints" => array()
);
$messages["cappedsmcaps"] = array(
    "message_text" => "<h2>" . _("Small Caps not correctly decapitalised") . "</h2>" .
        _("Small caps should have their case changed so that the 'tall' letters are uppercase, and the 'short' letters are lowercase."),
    "hints" => array()
);
$messages["changedspelling"] = array(
    "message_text" => "<h2>" . _("Spelling changed") . "</h2>" .
        _("Do not modernise, or 'correct' the spelling of the author. In any event, 'travelled' is correct British English."),
    "hints" => array()
);


// error checks
$tests[] = array("type" => "markupmissing", "opentext" => "<sc>", "closetext" => "</sc>", "case_sensitive" => TRUE, "error" => "nosmallcaps");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("HOLMES", "BURTON"), "case_sensitive" => TRUE, "error" => "cappedsmcaps");
$tests[] = array("type" => "forbiddentext", "searchtext" => "traveled", "case_sensitive" => TRUE, "error" => "changedspelling");
$tests[] = array("type" => "markupmissing", "opentext" => "<i>", "closetext" => "</i>", "case_sensitive" => FALSE, "error" => "noital");
$tests[] = array("type" => "markupmissing", "opentext" => "<b>", "closetext" => "</b>", "case_sensitive" => FALSE, "error" => "nobold");
$tests[] = array("type" => "markupcorrupt", "opentext" => "<i>", "closetext" => "</i>", "case_sensitive" => FALSE, "error" => "italcorrupt");
$tests[] = array("type" => "markupcorrupt", "opentext" => "<b>", "closetext" => "</b>", "case_sensitive" => FALSE, "error" => "boldcorrupt");
$tests[] = array("type" => "markupcorrupt", "opentext" => "<sc>", "closetext" => "</sc>", "case_sensitive" => FALSE, "error" => "smcapcorrupt");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nAccordingly"), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\nThe "), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("s</sc>"), "case_sensitive" => TRUE, "error" => "smcapcorrupt");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("n</sc>"), "case_sensitive" => TRUE, "error" => "smcapcorrupt");

// vim: sw=4 ts=4 expandtab
