<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include($relPath.'project_states.inc');
include($relPath.'project_trans.inc');
include_once($relPath.'theme.inc');

// This page is run by a post-processor that has completed doing the first round
// post-processing & is ready to upload his/her file and move the project to
// PPV.
//
// Future features for this page:
//
//   Attachment to the PG Submission page
//   If file fails due to no file or bad file type/name, allow the user to try again.
//


// This is the first run of the page, when the form has not been filled out yet

if ($mode != "upload") {

    // parameter to the file, projectid for the one they are working on
    $project = $_GET['projectid'];

    // Get all the information about the book they are working on
    $sql = mysql_query("SELECT nameofwork, authorsname, language, scannercredit, clearance, username FROM projects WHERE projectid = '$projectid'");
    $NameofWork = mysql_result($sql, 0, "nameofwork");
    $AuthorsName = mysql_result($sql, 0, "authorsname");
    $Language = mysql_result($sql, 0, "language");
    $scannercredit = mysql_result($sql, 0, "scannercredit");
    $clearance = mysql_result($sql, 0, "clearance");
    $username = mysql_result($sql, 0, "username");

    // Get the real name of the project manager
    $result = mysql_query("SELECT email, real_name FROM users WHERE userrname = '$username'");
    $email = mysql_result($result, 0, "email");
    $real_name = mysql_result($result, 0, "real_name");

    // Get the real name of the user uploading a file
    $result = mysql_query("SELECT real_name FROM users WHERE username = '$pguser'");
    $post_proofer = mysql_result($result, 0, "real_name");

    // mark the project as completed post-processing
    $error_msg = project_transition( $project, PROJ_POST_SECOND_CHECKED_OUT );

    theme("Completed Post-Processing", "header");

?>
<P>This project has been checked in as completed and <? echo $real_name; ?> has been e-mailed. Make sure the file you uploaded is a zip file.
<P>Credit line: <? echo $real_name, $scannercredit;?> and The Online Distributed Proofreading Team.
<P>Completed Zip File: <FORM ENCTYPE="multipart/form-data" ACTION=postcompleted.php METHOD="POST"><input name=zipfile TYPE="file" SIZE=25>
<input type="hidden" name=mode value="upload">
<?
    print "<input type=\"hidden\" name=projectid value=\"$project\"><INPUT TYPE=\"submit\" VALUE=\"Upload\"></FORM>";

} else { // Form has been filled out with file to be uploaded

    // parameter to the file, projectid for the one they are working on
    $project = $_GET['projectid'];

    if ($file != "none") {
        if ((substr($file, -4) == ".zip") && (ereg("[^A-Za-z0-9.]", $file))) {    		
            copy ($file, "$projects_dir/$project/post.zip");
            echo "Project has been uploaded. Back to <a href=\"post_proofers.php\">Post-Processing</a>.";
        } else {
            echo "Invalid file type was uploaded. Please e-mail the project manager with your file. Back to <a href=\"post_proofers.php\">Post-Processing</a>.";
        } 
    } else {
        echo "No file was submitted. Please e-mail the project manager with your file. Back to <a href=\"post_proofers.php\">Post-Processing</a>.";
    }
}

theme("", "footer");
?>
