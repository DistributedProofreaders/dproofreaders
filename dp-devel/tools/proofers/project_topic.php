<?
// DP includes
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'topic.inc'); // topic_create

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

        // find out PM's preference about being signed up for notifications of replies to this thread;
        // can't use settings object, which would be for the user following the link to create the thread, 
        // which may not be the PM, so... go directly to the database table

        $signup_res = mysql_query("SELECT value FROM usersettings WHERE username = '".$proj_mgr."' AND setting = 'auto_proj_thread'" );
        if ($signup_res) {
             $signup_row = mysql_fetch_assoc($signup_res);
             $signup_pref = $signup_row['value'];
             $sign_PM_up = ($signup_pref == 'yes');
        } else {
             $sign_PM_up = false;
        }

	// determine appropriate forum to create thread in
	$forum_id = get_forum_id_for_project_state($state);

        $post_subject = "\"".$nameofwork."\"    by ".$authorsname;

        $message =  "
This thread is for discussion specific to \"$nameofwork\" by $authorsname.

Please review the [url=$code_url/project.php?id=$project_id&detail_level=1]project comments[/url] before posting, as well as any posts below, as your question may already be answered there.

(This post is automatically generated.)
";

        $topic_id = topic_create(
                $forum_id,
                $post_subject,
                $message,
                $proj_mgr,
                $sign_PM_up );

        //Update project_db with topic_id so it can be moved later
        $update_project = mysql_query("UPDATE projects SET topic_id=$topic_id WHERE projectid='$project_id'");

}

// By here, either we had a topic or we've just created one, so redirect to it

$redirect_url = "$forums_url/viewtopic.php?t=$topic_id";
header("Location: $redirect_url");
?>
