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

if ( substr($request,0,4) == 'GET_' )
{
	// This isn't a request for a state-change,
	// it's just a request for some info about the project.

	if ($request == 'GET_IMAGES_ZIP') {
		$refresh_url="$projects_url/$projectid/".$projectid."images.zip";
	} else if ($request == 'GET_IMAGES_HTML') {
		$refresh_url="$projects_url/$projectid/images.html";
	} else if ($request == 'GET_PROJ_DETAILS') {
		$refresh_url="../project_manager/project_detail.php?project=$projectid";
	} else if ($request == 'GET_COMMENTS_PROOF') {
		$refresh_url="proofers/projects.php?project=$projectid&proofing=1";
	} else if ($request == 'GET_COMMENTS_POST') {
		$refresh_url="post_proofers/post_comments.php?project=$projectid";
	} else if ($request == 'GET_TEXT_POST_1_ZIP') {
		$refresh_url="$projects_url/$projectid/$projectid.zip";
	} else if ($request == 'GET_TEXT_POST_2_ZIP') {
		$refresh_url="$projects_url/$projectid/{$projectid}_second.zip";
	} else if ($request == 'GET_TEXT_CORR_ZIP') {
		$refresh_url="$projects_url/$projectid/{$projectid}_corrections.zip";
	} else if ($request == 'GET_XML_POST_1_ZIP') {
		$refresh_url="$projects_url/$projectid/{$projectid}_TEI.zip";

		// For a while (2003 Feb-Aug?), sendtopost generated TEI files,
		// but didn't zip them. We could go back and zip them all, or
		// we can do it here, upon request.

		$TEI_base = "$projects_dir/$projectid/{$projectid}_TEI";
		$TEI_txt  = "$TEI_base.txt";
		$TEI_zip  = "$TEI_base.zip";

		if (!file_exists($TEI_zip) && file_exists($TEI_txt) )
		{
		    // Create the zip
		    echo "creating the zip...";
		    exec("zip -j $TEI_zip $TEI_txt");
		}
	} else {
		echo "You requested:<br>\n";
		echo "curr_state=$curr_state<br>\n";
		echo "request=$request<br>\n";
		echo "This is not supported.<br>\n";
		return;
	}
	metarefresh(0,$refresh_url,"Transferring...","");
	return;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// This *is* a request for a state-change.

$reqd_state = $request;

$refresh_url = '';

// -----------------------------------------------------------------------------

// X_AVAILABLE -> X_CHECKED_OUT
// Check out the project for some purpose.

if ($curr_state == PROJ_POST_FIRST_AVAILABLE &&
    $reqd_state == PROJ_POST_FIRST_CHECKED_OUT)
{
	$do_what = "do post-processing";
	$refresh_url = "post_proofers/post_proofers.php";
}
else if ($curr_state == PROJ_POST_SECOND_AVAILABLE &&
         $reqd_state == PROJ_POST_SECOND_CHECKED_OUT)
{
	$do_what = "verify post-processing";
	$refresh_url = "post_proofers/post_proofers.php";
}
else if ($curr_state == PROJ_CORRECT_AVAILABLE &&
         $reqd_state == PROJ_CORRECT_CHECKED_OUT)
{
	$do_what = "verify corrections";
	$refresh_url = "correct/index.php";
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
	$refresh_url = "post_proofers/post_proofers.php";
}
else if ($curr_state == PROJ_POST_SECOND_CHECKED_OUT &&
         $reqd_state == PROJ_POST_SECOND_AVAILABLE)
{
	$do_what = "verify the post-processing";
	$refresh_url = "post_proofers/post_proofers.php";
}
else if ($curr_state == PROJ_CORRECT_CHECKED_OUT &&
         $reqd_state == PROJ_CORRECT_AVAILABLE)
{
	$do_what = "verify the corrections";
	$refresh_url = "correct/index.php";
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
// Check in a checked-out project.

if ($curr_state == PROJ_POST_FIRST_CHECKED_OUT &&
    $reqd_state == PROJ_POST_SECOND_AVAILABLE)
{
	$refresh_url="upload_text.php?project=$projectid&stage=post_1";
}
else if ($reqd_state == 69) // No code requests this.
{
	$refresh_url="post_proofers/completepost.php?project=$projectid";
}
else if ($curr_state == PROJ_CORRECT_CHECKED_OUT &&
         $reqd_state == PROJ_SUBMIT_PG_POSTED)
{
	$refresh_url="correct/completecorr.php?project=$projectid";
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
