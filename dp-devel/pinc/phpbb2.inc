<?PHP

// Wrappers around some phpBB2 functionality.

// Generally, for functions defined here,
// parameters are in the order Where, What, Who:
//     Where: $forum_id and/or $topic_id
//     What:  $post_subject, $post_text
//     Who:   $poster_name, $poster_is_real

// WARNING:
// This file includes phpBB2 files, which set and use global variables,
// some of which have the same names as global variables that the DP code
// sets and uses. (e.g., $theme, $lang, $starttime).  Because of the
// potential for collisions, you should avoid mixing this file with DP code.

$this_is_top_level_cli_invocation = (
    php_sapi_name() == 'cli' &&
    count(get_included_files()) == 1 );
// i.e., this file is being executed because it was the
// script argument to a command-line invocation of php,
// probably invoked by DP code via exec() or system().

if ( $this_is_top_level_cli_invocation )
{
    // We assume it was invoked from the pinc/ directory.
    $relPath = './';
    include_once($relPath.'v_site.inc'); // $forums_dir
}

// PHPBB includes (from the standard installation)
define('IN_PHPBB', true);
$phpbb_root_path = $forums_dir.'/';
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);

// include the custom PHPBB file
include_once($relPath . 'functions_insert_post.'.$phpEx);

// -----------------------------------------------------------------------------

function phpbb2_create_topic(
    $forum_id,
    $post_subject,
    $post_text,
    $poster_name,
    $poster_is_real,
    $make_poster_watch_topic
)
{
    $post_result = _insert_post(
        $forum_id,
        NULL, // causes a new topic to be created
        $post_subject,
        $post_text,
        $poster_name,
        $poster_is_real
    );

    $topic_id = $post_result['topic_id'];

    // if the poster wanted to be signed up for notifications, do so
    if ($make_poster_watch_topic)
    {
        assert($poster_is_real);
        $poster_id = $post_result['poster_id'];
         mysql_query("
             INSERT INTO phpbb_topics_watch
             SET
                 user_id = $poster_id,
                 topic_id = $topic_id,
                 notify_status = 0
         ") or die_nz(mysql_error());
    }

    return $topic_id;
}

// -----------------------------------------------------------------------------

function phpbb2_add_post(
    $topic_id,
    $post_subject,
    $post_text,
    $poster_name,
    $poster_is_real
)
{
    // Which forum is the topic in?
    $res = mysql_query("
        SELECT forum_id
        FROM phpbb_topics
        WHERE topic_id = $topic_id
    ") or die_nz(mysql_error());
    if ( mysql_num_rows($res) == 0 )
    {
        die_nz("no such topic: '$topic_id'\n");
    }
    list($forum_id) = mysql_fetch_row($res);

    _insert_post(
        $forum_id,
        $topic_id,
        $post_subject,
        $post_text,
        $poster_name,
        $poster_is_real
    );
}

// -----------------------------------------------------------------------------

function _insert_post(
    $forum_id,
    $topic_id,
    $post_subject,
    $post_text,
    $poster_name,
    $poster_is_real
)
// Same as insert_post() in functions_insert_post.php, except:
// -- if $poster_is_real, deduce $poster_id from $poster_name; otherwise use -1.
// -- attachsig is deduced from poster's preferences;
// -- we override $user_ip;
// -- we omit some of insert_post()'s parameters, and rearrange the rest
{
    if ( $poster_is_real )
    {
        // determine user_id and signature preference of poster
        $id_result = mysql_query("
            SELECT user_id, user_attachsig
            FROM phpbb_users
            WHERE username = '$poster_name'
        ") or die_nz(mysql_error());
        if ( mysql_num_rows($id_result) == 0 )
        {
            die_nz("no user named '$poster_name'");
        }
        list($poster_id,$poster_attachsig) = mysql_fetch_row($id_result);

        // The 'user_attachsig' column is defined as "tinyint(1) default NULL",
        // so its possible values are 0, 1, and NULL,
        // and the possible values of $poster_attachsig are '0', '1', and NULL.
        // (Note that mysql_fetch_* always returns values as string or null.)
        // It turns out that insert_post()'s $user_attach_sig parameter
        // can't handle a NULL, so we have to deal with that here.
        //
        if ( is_null($poster_attachsig) ) {$poster_attachsig = 1;}
        // (It seems to me that 0 or '0' would make more sense than 1.)
    }
    else
    {
        // poster is fake
        // (i.e., $poster_name is not the username of an actual user)
        $poster_id = -1;
        $poster_attachsig = '0';
    }

    // Don't attach the current user's IP address (if any) to this post.
    // functions_insert_post.php gets the IP address from global $user_ip
    global $user_ip;
    $user_ip = '7f000001'; //127.0.0.1

    $post_result = insert_post(
        $post_text,
        $post_subject,
        $forum_id,
        $poster_id,
        $poster_name,
        $poster_attachsig,
        $topic_id
    );

    $post_result['poster_id'] = $poster_id;

    return $post_result;
}

// -----------------------------------------------------------------------------

function die_nz($string)
// Like die($string), but with a non-zero exit status.
{
    echo $string, "\n";
    exit(1);
}

// -----------------------------------------------------------------------------

if ( $this_is_top_level_cli_invocation )
{
    // $argv[0] is the location of this file.
    // $argv[1] indicates the function to call.
    // $argv[2+] are the arguments to pass to it.

    $func_name = 'phpbb2_' . $argv[1];
    $args = array_slice( $argv, 2 );

    // echo "calling $func_name with "; var_dump($args);
    $result = call_user_func_array( $func_name, $args );
    echo $result;
    // So that $result will show up as the last line of input,
    // which is captured & returned by exec() and system(),
    // assuming that's how this is being invoked.
}

// vim: sw=4 ts=4 expandtab
?>
