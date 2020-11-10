<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once('../quiz_defaults.inc'); // $messages

require_login();

output_header(_('Quiz Messages'));

echo "<h1>" . _("Default Quiz Messages") . "</h1>";

echo "<p>" . _("The following list shows the default error messages currently available in the quiz code. Contact a developer if you would like to add others.  Any images used in the messages won't appear on this page but will appear correctly in the quiz.") . "</p>\n";
echo "<p>" . _("(By the way, you should <b>not</b> name any of your custom messages to overlap with these built-in ones.)") . "</p>\n";

foreach ($messages as $key => $message)
{
    echo "<hr><p>" . _("Message ID:") . " $key</p>\n";
    echo "<h2>" . $message['message_title'] . "</h2>\n";
    echo "<p>" . $message["message_body"] . "</p>\n";
    if (isset($message['wiki_ref']))
    {
        echo "<p>" . $message['wiki_ref'] . "</p>\n";
    }
}

// vim: sw=4 ts=4 expandtab
