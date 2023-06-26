<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'misc.inc'); // attr_safe()

function new_window_link($href, $linktext)
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

function recycle_window_link($href, $linktext, $target)
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

function new_help_window_link($help_topic_id, $linktext)
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

function private_message_link($proofer_username, $target = "_blank")
// returns a string containing a snippet of HTML
// for a link that opens a window to send a PM to the
// specified user.
// Specifying a NULL $target parameter will return a link to send
// a PM in the current window.
{
    global $code_url;

    $targetString = "";
    if (!empty($target)) {
        $targetString = " target='$target'";
    }

    $message_url = attr_safe(get_url_to_compose_message_to_user($proofer_username));

    $a = "<a href='$message_url'$targetString>$proofer_username</a>";

    if ($target == "_blank") {
        $title = _('Link opens in a new window');
        $img = "<img src='$code_url/graphics/New-Window.gif' title='$title'>";

        return "$a $img";
    }

    return $a;
}

// -----------------------------------------------------------------------------

function project_page_link_url($project_id, $query_param_array = null, $anchor_link = "")
// Returns HTML-safe URL for the given project page.
// Optional second argument is array of query parameters (without ?/&), e.g. ["detail_level=4"]
// Optional third argument is page anchor (without #), e.g "holds"
// NOTE: query_params and anchor_link should be urlencoded if necessary
{
    global $code_url;

    $url = "$code_url/project.php?id=$project_id";
    if (!empty($query_param_array)) {
        $url .= "&" . implode("&", $query_param_array);
    }
    if (!empty($anchor_link)) {
        $url .= "#" . $anchor_link;
    }
    return html_safe($url);
}

// -----------------------------------------------------------------------------

function return_to_project_page_link($project_id, $query_param_array = null, $anchor_link = "")
// Returns a string containing an HTML link to return to a project page.
// Optional second argument is array of query parameters (without ?/&), e.g. ["detail_level=4"]
// Optional third argument is page anchor (without #), e.g "holds"
// NOTE: query_params and anchor_link should be urlencoded if necessary
{
    $url = project_page_link_url($project_id, $query_param_array, $anchor_link);
    return sprintf(
             _("Return to the <a %s>project page</a>"),
             "href='$url' target='_top'"
           );
}

// -----------------------------------------------------------------------------

function return_to_round_page_link($round_id)
// Returns a string containing a snippet of HTML to return to a round page.
{
    global $code_url;

    return sprintf(
             _("Return to <a %s>round %s</a>"),
             "href='$code_url/tools/proofers/round.php?round_id=$round_id' target='_top'",
             $round_id
           );
}

// -----------------------------------------------------------------------------

function return_to_activity_hub_link()
// Returns a string containing a snippet of HTML to return to the Activity Hub.
{
    global $code_url;

    return sprintf(
             _("Return to the <a %s>Activity Hub</a>"),
             "href='$code_url/activity_hub.php' target='_top'"
           );
}
