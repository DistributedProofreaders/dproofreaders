<?
$relPath="../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'theme.inc');

// use:
// $code_url/tools/upload_text.php?project=projectid&curr_state=...

$project = $_REQUEST['project'];
$stage   = $_REQUEST['stage'];
$weeks   = @$_REQUEST['weeks'];
$action  = @$_REQUEST['action'];

$standard_blurb = _("<B>Note:</B>Please make sure the file you upload is Zipped (not Gzip, TAR, etc.). The file should have the .zip extension, NOT .Zip, .ZIP, etc. After you click Upload, the browser will appear to be slow getting to the next page. This is because it is uploading the file.");

if ($stage == 'post_1')
{
    $what = _("Post-Processed File");
    $for_what = _("for Verification");
    $indicator = "_second";
    $new_state = PROJ_POST_SECOND_AVAILABLE;
    $extras = array();
    $back_url = "$code_url/tools/pool.php?pool_id=PP";
    $back_blurb = _("Back to Post Proofers Page");
    $bottom_blurb = $standard_blurb;
}
else if ($stage == 'return_1')
{
    $what = _("In progress Post-Processed File");
    $for_what = _("for others to work on");
    $indicator = "_first_in_prog_".$pguser;
    $new_state = PROJ_POST_FIRST_AVAILABLE;
    $extras = array();
    $back_url = "$code_url/tools/pool.php?pool_id=PP";
    $back_blurb = _("Back to Post Proofers Page");
    $bottom_blurb = $standard_blurb . " " . _("To return the project to the pool without uploading a file, leave the file name empty and click on Upload.");
}
else if ($stage == 'return_2')
{
    $what = _("In progress Verified File");
    $for_what = _("for others to work on");
    $indicator = "_second_in_prog_".$pguser;
    $new_state = PROJ_POST_SECOND_AVAILABLE;
    $extras = array();
    $back_url = "$code_url/tools/pool.php?pool_id=PPV";
    $back_blurb = _("Back to Post Proofer Verification Page");
    $bottom_blurb = $standard_blurb . " " . _("To return the project to the pool without uploading a file, leave the file name empty and click on Upload.");
}
else if ($stage == 'correct')
{
    $what = _("Corrected Edition");
    $for_what = _("for Verification");
    $indicator = "_corrections";
    $new_state = PROJ_CORRECT_AVAILABLE;
    $extras = array();
    $back_url = "$code_url/list_etexts.php?x=g";
    $back_blurb = _("Back to Gold List");
    $bottom_blurb = $standard_blurb . " " . _("When making corrections, please read over the entire book and compare your corrections to the <a href='http://www.pgdp.net/projects/$project'>page images</a> available. Frequently Asked Questions will be developed as this feature is used more. Put any questions in the forums.");
}
else if ($stage == 'smooth_avail')
{
    $what = _("File Ready for Smooth Reading");
    $for_what = "";
    $indicator = "_smooth_avail";
    $new_state = PROJ_POST_FIRST_CHECKED_OUT;
    $extras = array();
    $back_url = "$code_url/project.php?id=$project&amp;expected_state=$new_state";
    $back_blurb = _("Back to Project Page");
    $bottom_blurb = $standard_blurb;
    $deadline = time() + ($weeks * 60 * 60 * 24 * 7);
}
else if ($stage == 'smooth_done')
{
    $what = _("Smooth Read Version");
    $for_what = "";
    $indicator = "_smooth_done_".$pguser;
    $new_state = PROJ_POST_FIRST_CHECKED_OUT;
    $extras = array();
    $back_url = "$code_url/project.php?id=$project&amp;expected_state=$new_state";
    $back_blurb = _("Back to Project Page");
    $bottom_blurb = $standard_blurb;
    $deadline = time() + ($weeks * 60 * 60 * 24 * 7);

}

else
{
    echo "Don't know how to handle stage='$stage'<br>\n";
    return;
}

if (!isset($action))
{
    // Present the upload page.

    $header = "$what "._("Upload");
    theme($header, "header");

    echo "<FORM ACTION='upload_text.php' METHOD='POST' ENCTYPE='multipart/form-data'>";
    echo "<br><table bgcolor='#ffffff' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse'>";
    echo "<tr><td bgcolor='$theme[color_headerbar_bg]' colspan='2' align='center'>";
    echo "<B><font color='#ffffff'>"._("Upload $what $for_what")."</font></B>";
    echo "<td bgcolor='#ffffff' align='center'>";
    echo "<INPUT TYPE='hidden' NAME='project' VALUE=$project>";
    echo "<INPUT TYPE='hidden' NAME='stage' VALUE='$stage'>";
    echo "<INPUT TYPE='hidden' NAME='weeks' VALUE='$weeks'>";
    echo "<INPUT TYPE='hidden' NAME='action' VALUE='1'>";
    echo "<INPUT TYPE='hidden' NAME='MAX_FILE_SIZE' VALUE='25165824'>";
    echo "<tr><td bgcolor='#e0e8dd' align='center'>";
    echo "<STRONG>"._("Zipped File:")."</STRONG>";
    echo "<td bgcolor='#ffffff' align='center'>";
    echo "<INPUT TYPE='file' NAME='files[]' SIZE='25' MAXSIZE='50'>";
    echo "<tr><td bgcolor='$theme[color_logobar_bg]' colspan='2' align='center'>";
    if ($stage != 'smooth_done') {
        if ($stage != 'smooth_avail') {
            echo "<STRONG>"._("Leave Comments:")."</STRONG>";
        } else {
            echo "<STRONG>"._("Leave Instructions for Smooth Readers:")."</STRONG>";
        }
        echo "<tr><td bgcolor='#e0e8dd' colspan='2' align='center'>";
        echo "<textarea NAME='postcomments' COLS='50' ROWS='16'></textarea>";
    }
    echo "<tr><td bgcolor='$theme[color_logobar_bg]' colspan='2' align='center'>";
    echo "<INPUT TYPE='submit' VALUE='Upload'>";
    echo "<tr><td bgcolor='#ffffff' colspan='2' align='center'>";
    echo $bottom_blurb;
    echo "<tr><td bgcolor='$theme[color_headerbar_bg]' colspan='2' align='center'>";
    echo "<A HREF='$back_url'><B>$back_blurb</B></A>";
    echo "</TD></TR></TABLE></FORM></DIV></CENTER>";
    theme("", "footer");

}
else
{
    // Handle a submission from the upload page.

    // if files have been uploaded, process them
    // mangle the postcomments

    // make reasonably sure script does not timeout on large file uploads
    set_time_limit(14400);
    $path_to_file = "$projects_dir/$project";

    $files = $HTTP_POST_FILES['files'];

    // do some checks. File must exist (except if we are returning to PP 
    // or PPV available.
    // if we have a file, we need its name to end in .zip, and we need
    // it to have non zero size.
    // and there must be only one file.

    $returning_to_pool = ('return_1' == $stage || 'return_2' == $stage);
    $need_file = !$returning_to_pool;    // in future, may be other conditions for this
    $file_count = count($files['name']);
    if ($file_count > 1) {
        echo _("You may only upload one file");
        exit();
    }
    // file_count never seems to be 0, but we keep the check in, just in case
    $have_file = (1 == $file_count && strlen($files['name'][0]) > 0);

    if ($need_file && ! $have_file) {
        echo _("You must upload a file");
        exit();
    }

    if ($have_file) {       // we have a file now. do some more checks.
        if (substr($files['name'][0], -4) != ".zip") {
            echo _("Invalid Filename");
            exit();
        }
        if (0 == $files['size'][0]) {
            echo _("File is empty");
            exit();
        }  
    }
    
    // this is ridiculous, why not just include the "/" to start with?
    if (!ereg("/$", $path_to_file))
    {
        $path_to_file = $path_to_file."/";
    }

    function ensure_path_is_unused( $path )
        // Ensure that nothing exists at $path.
        // (If something's there, rename it.)
        // EXCEPT: let people overwrite their finished SR files as often as they want
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
                        echo sprintf(
                            _("A problem occurred with your upload. Please email %s for assistance, and include the text of this page."),
                            $db_requests_email_addr );
                        exit;
                    }
                } else {

                    unlink($path);
                }
            }
        }

    if ($have_file) {
        // replace filename
        $zipext = ".zip";
        $name = $project.$indicator.$zipext;
        $location = $path_to_file.$name;
        ensure_path_is_unused( $location );
        copy($files['tmp_name'][0],$location);
        unlink($files['tmp_name'][0]);
    }
    // we've put the file in the right place.
    // now let's deal with the postcomments.
    // we construct the bit that's going to be added on to the existing postcomments.
    // if we're returning to available, and the user hasn't loaded a file, and not
    // entered any comments, we don't bother.
    // Otherwise, we add a divider, time stamp, user name, and the name of the file
    $postcomments = @$_POST['postcomments'];
    $divider = "\n----------\n".date("Y-m-d H:i");
    if ($have_file) {
        $divider .= "  ".$name." "._("uploaded by")." ";
    }
    else if ($returning_to_pool) {
        $divider .= " "._("returned by")." ";
    }
    else {
        $divider .= " "; // this shouldn't actually happen
    }
    $divider .= $pguser."\n";
    if (strlen($postcomments)>0 || $have_file) {
        $postcomments = $divider . $postcomments;
    }

    $error_msg = project_transition( $project, $new_state, $pguser, $extras );
    if ($error_msg)
    {
        echo "$error_msg<br>\n";
    }

    // special handling for smooth reading, which does not involve a state change
    // but still needs some changes recorded in project table
    // the comments get recorded even if it's just a replacement
    if ($stage == 'smooth_avail') {
        $qstring = "
                          UPDATE projects SET ";
        if ($weeks != "replace") {
            $qstring .= "smoothread_deadline = $deadline, ";
        }
        $qstring .= "postcomments = CONCAT(postcomments,'$postcomments')
                          WHERE projectid = '$project'
                      ";
        $qry =  mysql_query($qstring);
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
    metarefresh(1, $back_url, $msg, $msg);
}

// vim: sw=4 ts=4 expandtab
?>
