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
// specified user
{
    global $code_url;
    global $forums_url;

    // get proofer's phpBB userID
    $userid=_get_uid_from_username( $proofer_username );

    if(!empty($target)) $target = " target='$target'";

    $a = "<a href='$forums_url/privmsg.php?mode=post&amp;u=$userid'$target>$proofer_username</a>";

    if($target == "_blank") {
        $title = _('Link opens in a new window');
        $img = "<img src='$code_url/graphics/New-Window.gif' title='$title'>";

        return "$a $img";
    }

    return $a;
}

function _get_uid_from_username( $username )
// returns the phpBB userID for a given dp username
{
    // use a local in-memory cache so we don't pummel the database
    // for pages that end up calling this for the same small set 
    // of users over and over. cache is local to this function
    static $uidCache;
    // if it's in the cache, return it
    if(isset($uidCache[$username])) return $uidCache[$username];

    $query="SELECT user_id FROM phpbb_users WHERE username='$username'";
    $result = mysql_query($query);
    list($userid) = mysql_fetch_array( $result );
    mysql_free_result($result);

    // update the cache
    $uidCache[$username] = $userid;

    return $userid;
}


// vim: sw=4 ts=4 expandtab
?>
