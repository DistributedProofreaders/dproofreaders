<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once('../quiz_defaults.inc'); // $messages

$theme_args["css_data"] = "h2 {font-size:110%; margin: 0;}
    p.message_id {margin: 0 0 .5em 1em; background-color: #dddddd;}";

output_header(_('Quiz Messages'), SHOW_STATSBAR, $theme_args);

echo "<h1>" . _("Default Quiz Messages") . "</h1>";

echo "<p>" . _("The following list shows the default error messages currently available in the quiz code. Contact a developer if you would like to add others.  Any images used in the messages won't appear on this page but will appear correctly in the quiz.") . "</p>\n";
echo "<p>" . _("(By the way, you should <b>not</b> name any of your custom messages to overlap with these built-in ones.)") . "</p>\n";

foreach ($messages as $key => $message)
{
    echo "<hr><p class='message_id'>" . _("Message ID:") . " $key</p>\n";
    echo $message['message_text'];
}

// vim: sw=4 ts=4 expandtab
