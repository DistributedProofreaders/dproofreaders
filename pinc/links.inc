<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'misc.inc'); // attr_safe()

function new_window_link( $href, $linktext )
// Returns a string containing a snippet of HTML
// for a link that opens in a new window.
{
    global $code_url;

    $a = "<a href='$href' target='_blank'>$linktext</a>";

    $title = _('Link opens in a new window');
    $img = "<img src='$code_url/graphics/New-Window.gif' title='$title' alt='$title'>";

    return "$a $img";
}

// -----------------------------------------------------------------------------

function recycle_window_link( $href, $linktext, $target )
// Returns a string containing a snippet of HTML
// for a link that opens in a specific target.
{
    global $code_url;

    $a = "<a href='$href' target='$target'>$linktext</a>";

    $title = _('Link opens in a specific, possibly pre-existing, window');
    $img = "<img src='$code_url/graphics/Recycle-Window.gif' title='$title'>";

    return "$a $img";
}

// -----------------------------------------------------------------------------

function new_help_window_link( $help_topic_id, $linktext )
// Returns a string containing a snippet of HTML
// for a link that opens a help topic in a new window.
{
    global $code_url;

    $a = "<a href=\"JavaScript:newHelpWin('$help_topic_id');\">$linktext</a>";

    $title = _('Link opens in a new window');
    $img = "<img src='$code_url/graphics/New-Window.gif' title='$title'>";

    return "$a $img";
}

// -----------------------------------------------------------------------------

function private_message_link( $proofer_username, $target="_blank" )
// returns a string containing a snippet of HTML
// for a link that opens a window to send a PM to the
// specified user.
// Specifying a NULL $target parameter will return a link to send
// a PM in the current window.
{
    global $code_url;

    $targetString="";
    if(!empty($target))
        $targetString = " target='$target'";

    $message_url = attr_safe(get_url_to_compose_message_to_user($proofer_username));

    $a = "<a href='$message_url'$targetString>$proofer_username</a>";

    if($target == "_blank") {
        $title = _('Link opens in a new window');
        $img = "<img src='$code_url/graphics/New-Window.gif' title='$title'>";

        return "$a $img";
    }

    return $a;
}

?>
