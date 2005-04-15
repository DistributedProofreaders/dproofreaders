<?
$relPath="../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'metarefresh.inc');

// Get Passed parameters to code
$projectid  = $_GET['project'];
$curr_state = $_GET['curr_state'];
$request    = $_GET['request'];

/*
Compare this file to tools/project_manager/changestate.php.
Both handle requests to change the state of a project.
The one in project_manager handles requests that can only be
done by the project's project manager, or by a site admin.
This one handles requests that can be done by people other than the PM.
They should maybe be merged.
*/

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$reqd_state = $request;

$refresh_url = '';

// -----------------------------------------------------------------------------

// X_AVAILABLE -> X_CHECKED_OUT
// Check out the project for some purpose.

if ($curr_state == PROJ_POST_FIRST_AVAILABLE &&
    $reqd_state == PROJ_POST_FIRST_CHECKED_OUT)
{
	$do_what = "do post-processing";
	$refresh_url = "$code_url/project.php?id=$projectid&amp;expected_state=$reqd_state";
}
else if ($curr_state == PROJ_POST_SECOND_AVAILABLE &&
         $reqd_state == PROJ_POST_SECOND_CHECKED_OUT)
{
	$do_what = "verify post-processing";
	$refresh_url = "$code_url/project.php?id=$projectid&amp;expected_state=$reqd_state";
}
else if ($curr_state == PROJ_CORRECT_AVAILABLE &&
         $reqd_state == PROJ_CORRECT_CHECKED_OUT)
{
	$do_what = "verify corrections";
	$refresh_url = "pool.php?pool_id=CR";
}

if ($refresh_url != '')
{
	$error_msg = project_transition( $projectid, $reqd_state, array( 'checkedoutby' => $pguser ) );

	if ($error_msg == '')
	{
		metarefresh(2, $refresh_url, "Project Checkout Successful",
			"This project has been checked out to you to $do_what.");
	}
	else
	{
		echo "$error_msg<br><br>\n";
		metarefresh(2, $refresh_url, "Project Checkout Unsuccessful",
			"Something went wrong, and this project has probably not been checked out to you.");
	}
	return;
}

// -----------------------------------------------------------------------------

// X_CHECKED_OUT -> X_AVAILABLE
// Abandon (return) a checked-out project. Make it available for others to check out.

if ($curr_state == PROJ_POST_FIRST_CHECKED_OUT &&
    $reqd_state == PROJ_POST_FIRST_AVAILABLE)
{
	$do_what = "do the post-processing";
	$refresh_url = "pool.php?pool_id=PP";
}
else if ($curr_state == PROJ_POST_SECOND_CHECKED_OUT &&
         $reqd_state == PROJ_POST_SECOND_AVAILABLE)
{
	$do_what = "verify the post-processing";
	$refresh_url = "pool.php?pool_id=PPV";
}
else if ($curr_state == PROJ_CORRECT_CHECKED_OUT &&
         $reqd_state == PROJ_CORRECT_AVAILABLE)
{
	$do_what = "verify the corrections";
	$refresh_url = "pool.php?pool_id=CR";
}

if ( $refresh_url != '' )
{
	$error_msg = project_transition( $projectid, $reqd_state );

	if ($error_msg == '')
	{
		metarefresh(2, $refresh_url, "Project Return Successful",
			"This project has been returned so others can $do_what.");
	}
	else
	{
		echo "$error_msg<br><br>\n";
		metarefresh(2, $refresh_url, "Project Return Unsuccessful",
			"Something went wrong, and this project has probably not been returned.");
	}
	return;
}

// -----------------------------------------------------------------------------

// X_CHECKED_OUT -> something other than X_AVAILABLE
// Check in a checked-out project, or return to PPer

if ($curr_state == PROJ_POST_FIRST_CHECKED_OUT &&
    $reqd_state == PROJ_POST_SECOND_AVAILABLE)
{
	$refresh_url="upload_text.php?project=$projectid&stage=post_1";
}
else if ($curr_state == PROJ_CORRECT_CHECKED_OUT &&
         $reqd_state == PROJ_SUBMIT_PG_POSTED)
{
	$refresh_url="correct/completecorr.php?project=$projectid";
}
// Special case for returning PPV project to PP'ers queue
else if ($curr_state == PROJ_POST_SECOND_CHECKED_OUT &&
         $reqd_state == PROJ_POST_FIRST_CHECKED_OUT)
{
	$refresh_url = "pool.php?pool_id=PPV";
	$error_msg = project_transition( $projectid, $reqd_state );
	if ($error_msg == '')
	{
		// No need to remove original upload, re-uploads now permitted
		metarefresh(0,$refresh_url,"Project Return Sucessful",
			"This project has been returned to the Post-Processor's checked-out Queue.");
		return;
	}
	else
	{
		echo "$error_msg<br><br>\n";
		metarefresh(2, $refresh_url, "Project Return Unsuccessful",
			"Something went wrong, and this project has probably not been returned.");
		return;
	}
}

if ( $refresh_url != '' )
{
	metarefresh(0,$refresh_url,"Transferring...","");
	return;
}

// -----------------------------------------------------------------------------

echo "You requested:<br>\n";
echo "curr_state=$curr_state<br>\n";
echo "request=$request<br>\n";
echo "This is not supported.<br>\n";

?>
