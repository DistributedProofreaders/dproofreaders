<?
// DP includes
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');

// PHPBB includes (from the standard installation)
define('IN_PHPBB', true);
$phpbb_root_path = $forums_dir.'/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

// include the custom PHPBB file
include($relPath . 'functions_insert_post.'.$phpEx);

// Which project?
$project_id = $_GET['project'];

// Get info about project
$proj_result = mysql_query("SELECT nameofwork, authorsname, topic_id, username, state FROM projects WHERE projectid='$project_id'");

$row = mysql_fetch_array($proj_result);

$topic_id = $row['topic_id'];

//Determine if there is an existing topic or not; if not, create one
if(($topic_id == "") || ($topic_id == 0))
{
        $nameofwork = $row['nameofwork'];
        $authorsname = $row['authorsname'];
        $proj_mgr = $row['username'];
	$state = $row['state'];

	// determine appropriate forum to create thread in

	switch ($state) {
        	case PROJ_PROOF_FIRST_WAITING_FOR_RELEASE :
	    	$forum_id = $waiting_projects_forum_idx;
            break ;

	        case PROJ_PROOF_FIRST_AVAILABLE :
	        case PROJ_PROOF_SECOND_AVAILABLE :
	    	$forum_id = $projects_forum_idx;
            break ;

        	case PROJ_POST_FIRST_AVAILABLE :
	        case PROJ_POST_FIRST_CHECKED_OUT :
	        case PROJ_POST_SECOND_AVAILABLE :
        	case PROJ_POST_SECOND_CHECKED_OUT :
	        case PROJ_POST_COMPLETE :
	    	$forum_id = $pp_projects_forum_idx;
            break ;

	        case PROJ_SUBMIT_PG_POSTED :
	    	$forum_id = $posted_projects_forum_idx;
            break ;

	        default :
	    	$forum_id = $projects_forum_idx;
            break ;	
	}

        $post_subject = "\"".$nameofwork."\"    by ".$authorsname;

        $message =  "
This thread is for discussion specific to \"$nameofwork\" by $authorsname.

Please review the [url=$code_url/tools/proofers/projects.php?project=$project_id&proofing=1]project comments[/url] before posting, as well as any posts below, as your question may already be answered there.

(This post is automatically generated.)
";

        // determine forums ID and signature preference of PM

        $id_result = mysql_query("SELECT user_id, user_attachsig FROM phpbb_users WHERE username = '".$proj_mgr."'");
        $id_row = mysql_fetch_array($id_result);

        $owner = $id_row['user_id'];
        $sig = $id_row['user_attachsig'];
        if ($sig == '') {$sig = 1;}

        // create the post
        $post_result =  insert_post(
                $message,
                $post_subject,
                $forum_id,  
                $owner,
                $proj_mgr,
                $sig);

        $topic_id = $post_result['topic_id'];

        //Update project_db with topic_id so it can be moved later
        $update_project = mysql_query("UPDATE projects SET topic_id=$topic_id WHERE projectid='$project_id'");

}

// By here, either we had a topic or we've just created one, so redirect to it

$redirect_url = "$forums_url/viewtopic.php?t=$topic_id";
header("Location: $redirect_url");
?>
