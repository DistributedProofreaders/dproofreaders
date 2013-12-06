<?php 
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param
include_once('../small_theme.inc'); // output_small_header

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'type');

include "./data/qd_${quiz_page_id}.inc"; // $messages

$error = get_param_matching_regex($_REQUEST, 'error', NULL, '/^\w+$/');
$number = get_integer_param($_REQUEST, 'number', NULL, 0, NULL);

$quiz = get_Quiz_containing_page($quiz_page_id);

output_small_header($quiz);

// A margin
echo "<div style='margin: .5em;'>";

if (!isset($messages[$error]))
{
    die("supplied message-id ($error) is not valid");
}

$max_hint_number = count($messages[$error]["hints"])-1;
if ($number > $max_hint_number)
{
    die("supplied hint-number ($number) is greater than the maximum $max_hint_number");
}

// Display current hint
echo $messages[$error]["hints"][$number]["hint_text"];
echo "<p>";

// If there are any further hints for this message,
// display a link to the next hint.
if (count($messages[$error]["hints"]) > (1 + $number))
{
    if (isset($messages[$error]["hints"][$number]["linktext"]))
    {
        echo $messages[$error]["hints"][$number]["linktext"];
    }
    else
    {
        echo _("Desperate? Can't find it?");
    }
    $link_contents = "./hints.php?type=$quiz_page_id&error=$error&number=" . ($number + 1);
    echo " " . sprintf(_("Get more hints <a href='%s'>here</a>."), $link_contents) . "</p>";
}

echo " </div>\n</body>\n</html>\n";

// vim: sw=4 ts=4 expandtab
