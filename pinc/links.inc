<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'misc.inc'); // attr_safe()

/**
 * Returns a string containing a snippet of HTML
 * for a link that opens in a new window.
 */
function new_window_link($href, $linktext)
{
    global $code_url;

    $a = "<a href='$href' target='_blank'>$linktext</a>";

    $title = _('Link opens in a new window');
    $img = "<img src='$code_url/graphics/New-Window.gif' title='$title' alt='$title'>";

    return "$a $img";
}

// -----------------------------------------------------------------------------

/**
 * Returns a string containing a snippet of HTML
 * for a link that opens in a specific target.
 */
function recycle_window_link($href, $linktext, $target)
{
    global $code_url;

    $a = "<a href='$href' target='$target'>$linktext</a>";

    $title = _('Link opens in a specific, possibly pre-existing, window');
    $img = "<img src='$code_url/graphics/Recycle-Window.gif' title='$title'>";

    return "$a $img";
}

// -----------------------------------------------------------------------------

/**
 * Returns a string containing a snippet of HTML
 * for a link that opens a help topic in a new window.
 */
function new_help_window_link($help_topic_id, $linktext)
{
    global $code_url;

    $a = "<a href=\"JavaScript:newHelpWin('$help_topic_id');\">$linktext</a>";

    $title = _('Link opens in a new window');
    $img = "<img src='$code_url/graphics/New-Window.gif' title='$title'>";

    return "$a $img";
}

// -----------------------------------------------------------------------------

/**
 * Returns a string containing a snippet of HTML
 * for a link that opens a window to send a PM to the
 * specified user.
 *
 * Specifying a NULL $target parameter will return a link to send
 * a PM in the current window.
 */
function private_message_link($proofer_username, $target = "_blank")
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

/**
 * Returns HTML-safe URL for the given project page.
 *
 * @param array $query_param_array
 *   Query parameters (without ?/&), e.g. ["detail_level=4"].
 *   Should be `urlencode()` d if necessary
 *
 * @param string $anchor_link
 *   Page anchor (without #), e.g "holds"
 *   Should be `urlencode()` d if necessary
 */
function project_page_link_url($projectid, $query_param_array = null, $anchor_link = "")
{
    global $code_url;

    $url = "$code_url/project.php?id=$projectid";
    if (!empty($query_param_array)) {
        $url .= "&" . implode("&", $query_param_array);
    }
    if (!empty($anchor_link)) {
        $url .= "#" . $anchor_link;
    }
    return html_safe($url);
}

// -----------------------------------------------------------------------------

/**
 * Returns a string containing an HTML link to return to a project page.
 *
 * @param array $query_param_array
 *   Query parameters (without ?/&), e.g. ["detail_level=4"].
 *   Should be `urlencode()` d if necessary
 *
 * @param string $anchor_link
 *   Page anchor (without #), e.g "holds"
 *   Should be `urlencode()` d if necessary
 */
function return_to_project_page_link($projectid, $query_param_array = null, $anchor_link = "")
{
    $url = project_page_link_url($projectid, $query_param_array, $anchor_link);
    return sprintf(
        _("Return to the <a %s>project page</a>"),
        "href='$url' target='_top'"
    );
}

// -----------------------------------------------------------------------------

/**
 * Returns a string containing a snippet of HTML to return to a round page.
 */
function return_to_round_page_link($round_id)
{
    global $code_url;

    return sprintf(
        _("Return to <a %s>round %s</a>"),
        "href='$code_url/tools/proofers/round.php?round_id=$round_id' target='_top'",
        $round_id
    );
}

// -----------------------------------------------------------------------------

/**
 * Returns a string containing a snippet of HTML to return to the Activity Hub.
 */
function return_to_activity_hub_link()
{
    global $code_url;

    return sprintf(
        _("Return to the <a %s>Activity Hub</a>"),
        "href='$code_url/activity_hub.php' target='_top'"
    );
}
