<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'misc.inc'); // attr_safe(), extract_zip_to(), return_bytes()
include_once($relPath.'smoothread.inc'); // handle_smooth_reading_change()
include_once($relPath.'upload_file.inc'); // show_upload_form()

detect_too_large();
require_login();

$projectid = validate_projectID('project', @$_REQUEST['project']);
$valid_stages = array('post_1', 'return_1', 'return_2', 'correct', 'smooth_avail', 'smooth_done');
$stage = get_enumerated_param($_REQUEST, 'stage', NULL, $valid_stages, TRUE);
// $stage==smooth_avail controls sr, 2 cases:
// days given: upload a file & make sr available first time or after finished for days from now.
// days not given (defaults to 0): replace file only
$days = get_integer_param($_REQUEST, 'days', 0, 0, 56);
$action  = @$_REQUEST['action'];
$postcomments = @$_POST['postcomments'];

$project = new Project($projectid);

// Deny post_1 and return_1 if the project is currently in SR
if (($stage == 'post_1' || $stage == 'return_1') &&
    $project->is_available_for_smoothreading())
{
    $title = _("Disabled during Smooth Reading");
    $body = '<p>' . _("This function is disabled while the project is in the Smooth Reading Pool.")  . '</p>' .
            '<p>' . _("If you believe this is an error, please contact db-req for assistance.")      . '</p>';

    metarefresh(10, "$code_url/project.php?id=$projectid", $title, $body);
}

$error_messages = array();
if ($stage == 'post_1')
{
    $title = _("Upload post-processed file for verification");
    $intro_blurb = _("This page allows you to upload a post-processed file for verification.");
    $submit_label = _("Upload file");
    $is_file_optional = FALSE;
    $indicator = "_second";
    $project_is_in_valid_state = PROJ_POST_FIRST_CHECKED_OUT == $project->state;
    $user_is_able_to_perform_action = $project->PPer_is_current_user || user_is_a_sitemanager();
    $new_state = PROJ_POST_SECOND_AVAILABLE;
    $extras = array();
    $back_url = "$code_url/tools/pool.php?pool_id=PP";
    $back_blurb = _("Post-Processing Page");
}
else if ($stage == 'return_1')
{
    $title = _("Return project to the post-processing pool");
    $intro_blurb = _("This page allows you to return the project to the post-processing pool. You can optionally upload a partially post-processed file for another post-processor to pick up and use.");
    $submit_label = _("Return project");
    $is_file_optional = TRUE;
    $indicator = "_first_in_prog_".$pguser;
    $project_is_in_valid_state = PROJ_POST_FIRST_CHECKED_OUT == $project->state;
    $user_is_able_to_perform_action = $project->PPer_is_current_user || user_is_a_sitemanager();
    $new_state = PROJ_POST_FIRST_AVAILABLE;
    $extras = array();
    $back_url = "$code_url/tools/pool.php?pool_id=PP";
    $back_blurb = _("Post-Processing Page");
}
else if ($stage == 'return_2')
{
    $title = _("Return project to the post-processing verification pool");
    $intro_blurb = _("This page allows you to return the project to the post-processing verification pool. You can optionally upload a partially verified file for another verifier to pick up and use.");
    $submit_label = _("Return project");
    $is_file_optional = TRUE;
    $indicator = "_second_in_prog_".$pguser;
    $project_is_in_valid_state = PROJ_POST_SECOND_CHECKED_OUT == $project->state;
    $user_is_able_to_perform_action = $project->PPVer_is_current_user || user_is_a_sitemanager();
    $new_state = PROJ_POST_SECOND_AVAILABLE;
    $extras = array();
    $back_url = "$code_url/tools/pool.php?pool_id=PPV";
    $back_blurb = _("Post-Processing Verification Page");
}
else if ($site_supports_corrections_after_posting && $stage == 'correct' )
{
    $title = _("Upload corrected edition");
    $intro_blurb = _("This page allows you to upload a corrected version of the completed e-text if you've found an error.");
    $submit_label = _("Upload file");
    $is_file_optional = FALSE;
    $indicator = "_corrections";
    $project_is_in_valid_state = PROJ_SUBMIT_PG_POSTED == $project->state;
    $user_is_able_to_perform_action = TRUE;
    $new_state = PROJ_CORRECT_AVAILABLE;
    $extras = array();
    $back_url = "$code_url/list_etexts.php?x=g";
    $back_blurb = _("Gold List");
    $pre_step_instructions = sprintf(
        _("When making corrections, please read over the entire book and compare your corrections to the <a href='%s'>page images</a> available. Frequently Asked Questions will be developed as this feature is used more. Put any questions in the forums."),
        "$code_url/project.php?id=$projectid&detail_level=3"
    );
}
else if ($stage == 'smooth_avail')
{
    $title = _("Upload file for Smooth Reading");
    $intro_blurb = _("This page allows you to upload a fully post-processed file for Smooth Reading. Uploading another version will overwrite the previously uploaded file.");
    $submit_label = _("Upload file");
    $is_file_optional = FALSE;
    $indicator = "_smooth_avail";
    $project_is_in_valid_state = PROJ_POST_FIRST_CHECKED_OUT == $project->state;
    $user_is_able_to_perform_action = $project->PPer_is_current_user || user_is_a_sitemanager();
    $new_state = PROJ_POST_FIRST_CHECKED_OUT;
    $extras = array();
    $back_url = "$code_url/project.php?id=$projectid&amp;expected_state=$new_state#smooth_start";
    $back_blurb = _("Project Page");
}
else if ($stage == 'smooth_done')
{
    $title = _("Upload smooth read version");
    $intro_blurb = _("This page allows you to upload a smooth read version of the project. One version per user per project is allowed: additional uploads by the same user will overwrite their previous upload.");
    $submit_label = _("Upload file");
    $is_file_optional = FALSE;
    $indicator = "_smooth_done_".$pguser;
    // This requirement is in project.php as well
    $project_is_in_valid_state = $project->is_available_for_smoothreading();
    if(time() >= $project->smoothread_deadline)
    {
        // This string matches one in project.php
        $error_messages[] = _('The Smooth Reading deadline for this project has passed.');
    }
    $user_is_able_to_perform_action = TRUE;
    $new_state = PROJ_POST_FIRST_CHECKED_OUT;
    $extras = array();
    $back_url = "$code_url/project.php?id=$projectid&amp;expected_state=$new_state";
    $back_blurb = _("Project Page");
}
else if(!$stage)
{
    // this may be due to a timeout when uploading big files.

    slim_header(_("Upload failed"));

    echo "<p>", _("The upload failed."), "</p>\n";
    echo "<p>", get_big_upload_blurb(), "</p>";

    echo "<p>" . sprintf(_("Please go <a href='%s'>back</a> and try uploading
        the original again or uploading a smaller placeholder instead."),
        "javascript:history.back()") . "</p>";

    exit;
}

$return_anchor = "<a href='$back_url'>$back_blurb</a>";
$return_message = "<p>". sprintf(_("Return to the %s"), $return_anchor). "</p>";

if (!isset($action))
{
    // Present the upload page.
    output_header($title, NO_STATSBAR, get_upload_args());

    echo "<h1>$title</h1>";
    echo "<h2>" . sprintf("Project: %s", $project->nameofwork) . "</h2>";

    echo $return_message;

    try
    {
        // validate the project is in the correct state
        if(!$project_is_in_valid_state)
        {
            throw new FileUploadException(_("The project is not in the correct state for this action."));
        }

        // validate the user has the ability to do this action
        if(!$user_is_able_to_perform_action)
        {
            throw new FileUploadException(_("You do not have permission to perform this action."));
        }

        echo "<p>$intro_blurb</p>";
        if(isset($pre_step_instructions))
        {
            echo "<p>$pre_step_instructions</p>";
        }

        $form_content = "<input type='hidden' name='project' value='$projectid'>
            <input type='hidden' name='stage' value='$stage'>
            <input type='hidden' name='days' value='$days'>
            <input type='hidden' name='action' value='1'>";
        if ($stage != 'smooth_done')
        {
            if ($stage != 'smooth_avail')
            {
                $form_content .= _("(optional) Leave comments for the next person who checks out this project:");
            }
            else
            {
                $form_content .= _("Leave instructions for smooth readers:");
            }
            $form_content .= "<br><textarea style='margin-bottom: 1em;' name='postcomments' cols='75' rows='10'></textarea>\n";
        }

        show_upload_form($form_content, $submit_label);
    }
    catch(FileUploadException $e)
    {
        echo "<p class='error'>", $e->getMessage(), "</p>\n";
        foreach($error_messages as $message)
        {
            echo "<p class='error'>$message</p>";
        }
    }
}
else
{
    // Handle a submission from the upload page.

    // make reasonably sure script does not timeout on large file uploads
    set_time_limit(14400);

    slim_header($title);
    echo "<h1>$title</h1>";
    echo "<h2>", sprintf("Project: %s", $project->nameofwork), "</h2>";

    // if files have been uploaded, process them and mangle the postcomments
    $temporary_path = "";
    $returning_to_pool = ('return_1' == $stage || 'return_2' == $stage);
    try
    {
        try
        {
            // inner try succeeds only if there is a file
            $file_info = validate_uploaded_file();
            $have_file = true;
            $temporary_path = $file_info["tmp_name"];
            $original_name = $file_info['name'];

            zip_check($original_name, $temporary_path);
            // replace filename
            $zipext = ".zip";
            $name = $projectid.$indicator.$zipext;
            $location = "$project->dir/$name";
            ensure_path_is_unused( $location );
            rename($temporary_path, $location);
            if ($stage == 'smooth_avail')
            {
                $project->delete_smoothreading_dir();
                $smooth_dir = "$project->dir/smooth";
                if(!mkdir($smooth_dir))
                {
                    throw new FileUploadException("Could not create smooth directory");
                }
                $zip_ok = extract_zip_to($location, $smooth_dir);
                if($zip_ok)
                {
                    // extract any zips in smooth_dir
                    $zips = glob("$smooth_dir/*.zip");
                    foreach($zips as $zip)
                    {
                        $zip_ok = extract_zip_to($zip, $smooth_dir);
                        if(!$zip_ok)
                        {
                            break;
                        }
                    }
                }
                if(!$zip_ok)
                {
                    throw new FileUploadException("failed to extract files");
                }
            }
        }
        catch(NoFileUploadedException $e)
        {
            $have_file = false;
            if(!$returning_to_pool)
            {
                throw new FileUploadException($e->getMessage());
            }
        }
        // we've put the file in the right place.
        // now let's deal with the postcomments.
        // we construct the bit that's going to be added on to the existing postcomments.
        // if we're returning to available, and the user hasn't loaded a file, and not
        // entered any comments, we don't bother.
        // Otherwise, we add a divider, time stamp, user name, and the name of the file
        // "uploaded by" & "returned by" not translated since they go into postcomments rather than being viewed by the present user
        $divider = "\n----------\n".date("Y-m-d H:i");
        if ($have_file) {
            $divider .= " $name uploaded by ";
        }
        else if ($returning_to_pool) {
            $divider .= " returned by ";
        }
        else {
            $divider .= " "; // this shouldn't actually happen
        }
        $divider .= $pguser."\n";
        if (strlen($postcomments)>0 || $have_file) {
            $postcomments = $divider . $postcomments;
        }
        // note that $postcomments is used as a global variable inside do_state_change() inside project_transition()

        $error_msg = project_transition( $projectid, $new_state, $pguser, $extras );
        if ($error_msg)
        {
            throw new FileUploadException($error_msg);
        }

        // special handling for smooth reading, which does not involve a state change
        // so project_transition() will do nothing
        // but still needs some changes recorded in project table
        // the comments get recorded even if it's just a replacement
        if ($stage == 'smooth_avail')
        {
            handle_smooth_reading_change($project, $postcomments, $days, false);
        }

        if ($stage == 'smooth_done')
        {
            notify_project_event_subscribers( $project, 'sr_reported' );
        }

        // let them know file uploaded and send back to the right place
        $msg1 = _("File uploaded. Thank you!");
        $msg2 = _("Project returned to pool");
        if ($have_file && $returning_to_pool) {
            $msg = $msg1."\n".$msg2;
        }
        else if ($have_file) {
            $msg = $msg1;
        }
        else if  ($returning_to_pool) {
            $msg = $msg2;
        }
        else {
            $msg = _("This shouldn't happen. No file upload and not returning to pool.");
        }
        echo "<p>$msg</p>";
    }
    catch(FileUploadException $e)
    {
        if(is_file($temporary_path))
        {
            unlink($temporary_path);
        }
        echo "<p class='error'>", $e->getMessage(), "</p>\n";
    }
    echo $return_message;
}

#----------------------------------------------------------------------------

// Ensure that nothing exists at $path.
// (If something's there, rename it.)
// EXCEPT: let people overwrite their finished SR files as often as they want
function ensure_path_is_unused( $path )
{
    global $stage, $db_requests_email_addr;

    if ( file_exists($path) )
    {

        if (($stage != 'smooth_done') AND ($stage != 'smooth_avail')){

            $bak = "$path.bak";
            ensure_path_is_unused( $bak );
            $success = rename( $path, $bak );
            if (!$success)
            {
                // It will already have printed a warning.
                die( sprintf(
                    _("A problem occurred with your upload. Please email %s for assistance, and include the text of this page."),
                    $db_requests_email_addr) );
            }
        } else {

            unlink($path);
        }
    }
}

// vim: sw=4 ts=4 expandtab
