<?PHP
include_once($relPath.'site_vars.php');

function new_window_link( $href, $linktext )
// Returns a string containing a snippet of HTML
// for a link that opens in a new window.
{
    global $code_url;

    $a = "<a href='$href' target='_blank'>$linktext</a>";

    $title = _('Link opens in a new window');
    $img = "<img src='$code_url/graphics/New-Window.gif' title='$title'>";

    return "$a $img";
}

// vim: sw=4 ts=4 expandtab
?>
