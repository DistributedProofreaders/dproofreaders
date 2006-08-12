<?php
/***************************************************************************
 *                         functions_insert_post.php
 *                      -------------------------------
 *   Author  		: 	netclectic - Adrian Cockburn - adrian@netclectic.com
 *   Created 		: 	Monday, Sept 23, 2002
 *   Modified 		: 	Friday, July 11, 2003
 *
 *	 File Version   :   1.0.7   -   fix issue with single quotes 
 *                                  fix missing globals in add_attach
 *	                    1.0.6   -   fux bugs with special chars (this time :D)
 *                      1.0.5   -   add error_die_function parameter
 *                                  fux bugs with special chars
 *                                  remove check for version 
 *                      1.0.4   -   add current_time paramter to insert_post
 *                                  fixed bug with add_attach not including the functions_attach file
 *                      1.0.3   -   fixed small bug in insert_post - thanks to Pda0
 *	                 	1.0.2   -   added user_notification support
 *                      1.0.1   -   some bug fixes
 *                      1.0.0   -   original release
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_search.'.$phpEx);



/***************************************************************************
 *                             insert_post
 *                         -------------------
 *   Author         :   netclectic - Adrian Cockburn - adrian@netclectic.com
 *                          thanks to wineknow for the suggestion of adding the current_time parameter
 *   Version        :   1.0.7
 *   Created 		: 	Monday, Sept 23, 2002
 *   Last Updated   :   Friday, July 11, 2003
 *
 *   Description    :   This functions is used to insert a post into your phpbb forums. 
 *                      It handles all the related bits like updating post counts, 
 *                      indexing search words, etc.
 *                      The post is inserted for a specific user, so you will have to 
 *                      already have a user setup which you want to use with it.
 *
 *                      If you're using the POST method to input data then you should call stripslashes on
 *                      your subject and message before calling insert_post - see test_insert_post for example.
 *
 *   Parameters     :   $message            - the message that will form the body of the post
 *                      $subject            - the subject of the post
 *                      $forum_id           - the forum the post is to be added to
 *                      $user_id            - the id of the user for the post
 *                      $user_name          - the username of the user for the post
 *                      $user_attach_sig    - should the user's signature be attached to the post
 *
 *   Options Params :   $topic_id           - if topic_id is passed then the post will be 
 *                                              added as a reply to this topic
 *                      $topic_type         - defaults to POST_NORMAL, can also be
 *                                              POST_STICKY, POST_ANNOUNCE or POST_GLOBAL_ANNOUNCE
 *                      $do_notification    - should users be notified of new posts (only valid for replies)
 *                      $notify_user        - should the 'posting' user be signed up for notifications of this topic
 *                      $current_time       - should the current time be used, if not then you should supply a posting time
 *                      $error_die_function - can be used to supply a custom error function.
 *                      $html_on = false    - should html be allowed (parsed) in the post text.
 *                      $bbcode_on = true   - should bbcode be allowed (parsed) in the post text.
 *                      $smilies_on = true  - should smilies be allowed (parsed) in the post text.
 *
 *   Returns        :   If the function succeeds without an error it will return an array containing
 *                      the post id and the topic id of the new post. Any error along the way will result in either
 *                      the normal phpbb message_die function being called or a custom die function determined
 *                      by the $error_die_function parameter.
 ***************************************************************************/
function insert_post( 
    $message, 
    $subject, 
    $forum_id, 
    $user_id, 
    $user_name, 
    $user_attach_sig, 
    $topic_id = NULL, 
    $topic_type = POST_NORMAL, 
    $do_notification = false, 
    $notify_user = false, 
    $current_time = 0, 
    $error_die_function = '', 
    $html_on = 0, 
    $bbcode_on = 1, 
    $smilies_on = 1 )
{
    global $db, $board_config, $user_ip;

    // initialise some variables
    $topic_vote = 0; 
    $poll_title = '';
    $poll_options = '';
    $poll_length = '';
    $mode = 'reply'; 

    $bbcode_uid = ($bbcode_on) ? make_bbcode_uid() : ''; 
    $error_die_function = ($error_die_function == '') ? "message_die" : $error_die_function;
    $current_time = ($current_time == 0) ? time() : $current_time;
    
    // parse the message and the subject (belt & braces :)
    $message = addslashes(unprepare_message($message));
    $message = prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on, $bbcode_uid);
    $subject = addslashes(str_replace('"','&quot;',trim($subject)));
    $username = addslashes(unprepare_message(trim($user_name)));
    
    // fix for \" in username - wineknow.com
    $username = str_replace("\\\"","\"", $username);    
    
    // if this is a new topic then insert the topic details
    if ( is_null($topic_id) )
    {
        $mode = 'newtopic'; 
        $sql = "INSERT INTO " . TOPICS_TABLE . " (topic_title, topic_poster, topic_time, forum_id, topic_status, topic_type, topic_vote) VALUES ('$subject', " . $user_id . ", $current_time, $forum_id, " . TOPIC_UNLOCKED . ", $topic_type, $topic_vote)";
        if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
        {
            $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
        }
        $topic_id = $db->sql_nextid();
    }

    // insert the post details using the topic id
    $sql = "INSERT INTO " . POSTS_TABLE . " (topic_id, forum_id, poster_id, post_username, post_time, poster_ip, enable_bbcode, enable_html, enable_smilies, enable_sig) VALUES ($topic_id, $forum_id, " . $user_id . ", '$username', $current_time, '$user_ip', $bbcode_on, $html_on, $smilies_on, $user_attach_sig)";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    $post_id = $db->sql_nextid();
    
    // insert the actual post text for our new post
    $sql = "INSERT INTO " . POSTS_TEXT_TABLE . " (post_id, post_subject, bbcode_uid, post_text) VALUES ($post_id, '$subject', '$bbcode_uid', '$message')";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // update the post counts etc.
    $newpostsql = ($mode == 'newtopic') ? ',forum_topics = forum_topics + 1' : '';
    $sql = "UPDATE " . FORUMS_TABLE . " SET 
                forum_posts = forum_posts + 1,
                forum_last_post_id = $post_id
                $newpostsql 	
            WHERE forum_id = $forum_id";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // update the first / last post ids for the topic
    $first_post_sql = ( $mode == 'newtopic' ) ? ", topic_first_post_id = $post_id  " : ' , topic_replies=topic_replies+1'; 
    $sql = "UPDATE " . TOPICS_TABLE . " SET 
                topic_last_post_id = $post_id 
                $first_post_sql
            WHERE topic_id = $topic_id";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // update the user's post count and commit the transaction
    $sql = "UPDATE " . USERS_TABLE . " SET 
                user_posts = user_posts + 1
            WHERE user_id = $user_id";
    if ( !$db->sql_query($sql, END_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // add the search words for our new post
    switch ($board_config['version'])
    {
        case '.0.0' : 
        case '.0.1' : 
        case '.0.2' : 
        case '.0.3' : 
            add_search_words($post_id, stripslashes($message), stripslashes($subject));
            break;
        
        default :
            add_search_words('', $post_id, stripslashes($message), stripslashes($subject));
            break;
    }
    
    // do we need to do user notification
    if ( ($mode == 'reply') && $do_notification )
    {
        // DP bugfix (critical): $userdata['user_id'] must be set; otherwise,
        // user_notification() will generate a bad SQL query and die.
        global $userdata;
        $userdata['user_id'] = $user_id;

	// DP bugfix (minor): We should pass the topic title, not the post subject,
	// as the third param to user_notification.
        $sql = "SELECT topic_title FROM " . TOPICS_TABLE . " WHERE topic_id = $topic_id";
        if ( !($result = $db->sql_query($sql)) )
        {
            $error_die_function(GENERAL_ERROR, 'Error getting topic_title', '', __LINE__, __FILE__, $sql);
        }
        list($topic_title) = $db->sql_fetchrow($result); 

        $post_data = array();
        user_notification($mode, $post_data, $topic_title, $forum_id, $topic_id, $post_id, $notify_user);
    }
    
    // if all is well then return the id of our new post
    return array('post_id'=>$post_id, 'topic_id'=>$topic_id);
}




/***************************************************************************
 *                         insert_post_with_attach
 *                        -------------------------
 *   Author         :   Pda0 - Patricio Anguita 
 *                             modified by netclectic to work with new insert_post function
 *
 *   Version        :   1.0.3
 *   Created 		: 	Monday, Nov 27, 2002
 *   Last Updated   :   Thursday, March 27, 2003
 *
 *   Description    :   This function will post a new message on a forum, and append attachments to it.
 *
 *   Parameters     :   $message          - the message that will form the body of the post
 *                      $subject          - the subject of the post
 *                      $forum_id         - the forum the post is to be added to
 *                      $user_id          - the id of the user for the post
 *                      $user_name        - the username of the user for the post
 *                      $user_attach_sig  - should the user's signature be attached to the post
 *
 *                      $filenames        - array ('filename1' , 'filename2') etc... 
 *
 *                      $topic_id         - OPTIONAL, if topic_id is passed then the post will be 
 *                                          added as a reply to this topic
 *                      $topic_type       - OPTIONAL, defaults to POST_NORMAL, can also be
 *                                          POST_STICKY, POST_ANNOUNCE or POST_GLOBAL_ANNOUNCE
 *                      $do_notification  - should users be notified of new posts (only valid for replies)
 *                      $notify_user      - should the 'posting' user be signed up for notifications of this topic
 *                      $current_time     - should the current time be used, if not the you should supply a posting time
 *                      $error_die_function - 
 *                      $html_on = false    -
 *                      $bbcode_on = true   -
 *                      $smilies_on = true  -
 *
 *   Returns        :   If the function succeeds without an error it will return an array containing
 *                      the post id and the topic id of the new post and a sub-array containing the attachment id's
 *                      of the attached file(s). 
 ***************************************************************************/
function insert_post_with_attach ( 
    $message, 
    $subject, 
    $forum_id, 
    $user_id, 
    $user_name, 
    $user_attach_sig, 
    $filenames, 
    $topic_id = NULL, 
    $topic_type = POST_NORMAL, 
    $do_notification = false, 
    $notify_user = false, 
    $current_time = 0,
    $error_die_function = '',
    $html_on = 0, 
    $bbcode_on = 1, 
    $smilies_on = 1 )
{
    // First, post the message 
    $error_die_function = ($error_die_function == '') ? "message_die" : $error_die_function;

    $post_details = insert_post( $message, 
                                 $subject, 
                                 $forum_id, 
                                 $user_id, 
                                 $user_name, 
                                 $user_attach_sig, 
                                 $topic_id, 
                                 $topic_type, 
                                 $do_notification, 
                                 $notify_user, 
                                 $current_time, 
                                 $error_die_function,
                                 $html_on, 
                                 $bbcode_on, 
                                 $smilies_on );
    
    // Now, post a attachment for each of the files listed in $filenames 
    $attachments = array();
    foreach ($filenames as $filename) 
    { 
        $attachments[] = add_attach($user_id, $post_details['post_id'], $filename, $filename, $error_die_function); 
    } 
    $post_details['attachments'] = $attachments;
    
    return $post_details;
} 



/***************************************************************************
 *                             add_attach
 *                         ------------------
 *   Author         :   Acyd Burn
 *                           modified by Pda0 to make it work as a function 
 *                           modified by netclectic to fix up a few things
 *
 *   Version        :   1.0.4
 *   Created 		: 	Monday, Nov 27, 2002
 *   Last Updated   :   Friday, July 11, 2003
 *
 *   Description    :   This function will add attachments to a given post
 *
 *   Parameters     :   $user_id          - the id of the user for the post
 *                      $post_id          - the id of the post to add the attachment to
 *                      $filename         - the filename of the file to be attached
 *                                          relative to the storage path defined in the attach mod settings
 *
 *   Returns        :   $attach_id        - this id of the added attachment
 ***************************************************************************/
function add_attach ( $user_id, $post_id, $filename, $file_comment = '', $error_die_function = '' ) 
{ 
    global $phpbb_root_path, $phpEx;
    
    if ( !file_exists($phpbb_root_path . 'attach_mod/includes/functions_attach.'.$phpEx) )
    {
        $error_die_function(GENERAL_ERROR, 'Unable to locate attachment functions.', '', __LINE__, __FILE__); 
    }
    
    include_once($phpbb_root_path . 'attach_mod/includes/functions_attach.'.$phpEx);
    
    global $db, $upload_dir; 
    
    $type = 'application/octet-stream'; 
    $filename = trim($filename);
    
    // Opera add the name to the mime type 
    $type = ( strstr($type, '; name') ) ? str_replace(strstr($type, '; name'), '', $type) : $type; 
    $extension = get_extension($filename); 
    $_filesize = @filesize($upload_dir . '/' . $filename); 
    $_filesize = intval($_filesize); 
    
    // 
    // Prepare Values 
    // 
    $filetime = time(); 
    
    $attach_filename = $filename; 
    
    // 
    // insert attachment into db, here the user submited it directly 
    // 
    $sql = "INSERT INTO " . ATTACHMENTS_DESC_TABLE . " (physical_filename, real_filename, comment, extension, mimetype, filesize, filetime, thumbnail) 
        VALUES ( '" . $attach_filename . "', '" . str_replace("'", "''", $filename) . "', '" . trim($file_comment) . "', '" . $extension . "', '" . $type . "', " . $_filesize . ", " . $filetime . ", 0)"; 
    
    // 
    // Inform the user that his post has been created, but nothing is attached 
    // 
    if ( !(attach_sql_query($sql)) ) 
    { 
         $error_die_function(GENERAL_ERROR, 'Couldn\'t store Attachment.', '', __LINE__, __FILE__, $sql); 
    } 
    
    $attach_id = $db->sql_nextid(); 
    
    $sql = 'INSERT INTO ' . ATTACHMENTS_TABLE . ' (attach_id, post_id, privmsgs_id, user_id_1, user_id_2) 
        VALUES (' . $attach_id . ', ' . $post_id . ', 0, ' . $user_id . ', 0)'; 
    
    if ( !(attach_sql_query($sql)) ) 
    { 
         $error_die_function(GENERAL_ERROR, 'Couldn\'t store Attachment.', '', __LINE__, __FILE__, $sql); 
    } 
    
    $sql = "UPDATE " . POSTS_TABLE . " 
        SET post_attachment = 1 
        WHERE post_id = " . $post_id; 
    
    if ( !(attach_sql_query($sql)) ) 
    { 
         $error_die_function(GENERAL_ERROR, 'Unable to update Posts Table.', '', __LINE__, __FILE__, $sql); 
    } 
    
    $sql = "SELECT topic_id FROM " . POSTS_TABLE . " 
        WHERE post_id = " . $post_id; 
    
    if ( !($result = attach_sql_query($sql)) ) 
    { 
         $error_die_function(GENERAL_ERROR, 'Unable to select Posts Table.', '', __LINE__, __FILE__, $sql); 
    } 
    
    $row = $db->sql_fetchrow($result); 
    
    $sql = "UPDATE " . TOPICS_TABLE . " 
        SET topic_attachment = 1 
        WHERE topic_id = " . $row['topic_id']; 
    
    if ( !(attach_sql_query($sql)) ) 
    { 
         $error_die_function(GENERAL_ERROR, 'Unable to update Topics Table.', '', __LINE__, __FILE__, $sql); 
    } 
    
    return $attach_id;
} 

?>
