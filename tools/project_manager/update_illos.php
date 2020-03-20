<?php
// Replace an illustration file.

$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'misc.inc'); // get_upload_err_msg, get_enumerated_param, attr_safe
include_once($relPath.'project_states.inc'); // PROJ_NEW
include_once($relPath.'abort.inc'); // provide_escape_links

require_login();

// (This script's functionality overlaps that of handle_bad_page.php.
// They should perhaps be refactored.)

$projectid = validate_projectID('projectid', @$_REQUEST['projectid']);
$operation = get_enumerated_param($_REQUEST, 'operation', 'replace', array('replace', 'delete', 'delete_all'));

if ($operation !== 'delete_all')
{
    $image     = @$_REQUEST['image'];
    // Note that using validate_page_image_filename() here would be inappropriate,
    // because it's specific to *page* images (which this isn't),
    // and so enforces a maximum filename length.
    if (!preg_match('/^\w[\w.-]*\.(png|jpg)$/', $image)) // see _check_file() in add_files.php
    {
        // This should only happen if someone is URL-tweaking.
        die(_("Parameter 'image' is not a valid illustration filename."));
    }
}

$project = new Project($projectid);

$is_delete_all_operation = false;
if ($operation == 'replace')
{
    $operation_image_str = _('Replace Illustration');
}
elseif ($operation == 'delete')
{
    $operation_image_str = _('Delete Illustration');
}
else
{
    $operation_image_str = _('Delete All Illustrations');
    $is_delete_all_operation = true;
}

output_header(_("Update Illustration"));

echo "<h1>" . _("Update Illustration") . "</h1>\n";

echo "<p>";
echo "<b>" . _("Project") . ":</b> " . html_safe($project->nameofwork) . "<br>";
echo "<b>" . _("Project ID") . ":</b> {$project->projectid}<br>";
echo "<b>" . _("Project state") . ":</b> {$project->state}<br>";
if (!$is_delete_all_operation)
{
    echo "<b>" . _("Illustration") . ":</b> $image<br>";
    $image_link = "<a href='$projects_url/$projectid/" . rawurlencode($image) . "'>" . _("Illustration") . "</a>";
    echo "<b>" . _("View") . ":</b> $image_link";
}
echo "</p>";

echo "<h2>$operation_image_str</h2>\n";

if (!$project->can_be_managed_by_current_user)
{
    echo "<p>", _('You are not authorized to manage this project.'), "</p>\n";
    provide_escape_links();
    exit;
}

if ($operation == 'delete' && $project->state != PROJ_NEW)
{
    echo "<p>", _('You can only delete illustrations for a project in the new state.'), "</p>\n";
    provide_escape_links();
    exit;
}

$nonpage_image_names = $project->get_illustrations();
if (!$is_delete_all_operation && (!in_array($image, $nonpage_image_names) || !is_file("$projects_dir/$projectid/$image")))
{
    // This too should only happen if someone is URL-tweaking.
    // (Or conceivably, the file was recently deleted, and the user
    // has an image_index that was generated before the deletion.)
    echo "<p>", _('There is no such illustration in the project.'), "</p>\n";
    provide_escape_links();
    exit;
}

if ( isset($_FILES['replacement_image']) )
{
    // The user has uploaded a file.
    $err_msg = handle_upload($projectid, $image, $_FILES['replacement_image'] );
    $success_msg = _('Illustration successfully replaced.');
} elseif (@$_REQUEST['confirmed'] == 'yes')
{
    if ($is_delete_all_operation)
    {
        $err_msg = handle_delete_all($projectid, $nonpage_image_names);
        $success_msg = _('All project illustrations successfully deleted.');
    }
    else
    {
        $err_msg = handle_delete($projectid, $image);
        $success_msg = _('Illustration successfully deleted.');
    }
}

if (isset($success_msg)) {
    if ( $err_msg == '' )
    {
        echo "<p>", $success_msg, "</p>\n";
        echo "<p>",
            "<a href='$code_url/tools/proofers/images_index.php?project=$projectid'>",
            _('Return to Images Index'),
            "</a>",
            "</p>\n";
        exit;
    }
    else
    {
        echo "<p class='error'>";
        echo _('An error occurred.'), "\n";
        echo $err_msg, "\n";
        echo _('Please try again.'), "\n";
        echo "</p>\n";
        // Fall through to try again.
    }
}

if ($operation == 'replace')
{
    echo "<p>", _('Select a replacement illustration to upload:'), "</p>\n";
    echo "
        <form enctype='multipart/form-data' method='post'>
        <input type='hidden' name='projectid' value='$projectid'>
        <input type='hidden' name='image' value='$image'>
        <input type='hidden' name='operation' value='$operation'>
        <input type='file' name='replacement_image' size='50'>
        <br>
        <input type='submit' value='", attr_safe(_("Upload Illustration")), "'>
        </form>
    ";
}
elseif ($operation == 'delete')
{
    echo "<p>", _('Are you sure you want to delete this illustration?'), "</p>\n";
    echo "
        <form method='post'>
        <input type='hidden' name='projectid' value='$projectid'>
        <input type='hidden' name='image' value='$image'>
        <input type='hidden' name='operation' value='$operation'>
        <input type='hidden' name='confirmed' value='yes'>
        <br>
        <input type='submit' value='", attr_safe(_("Do It")), "'>
        </form>
    ";
}
else
{
    echo "<p>", _('Are you sure you want to delete all project illustrations?'), "</p>\n";
    echo "
        <form method='post'>
        <input type='hidden' name='projectid' value='$projectid'>
        <input type='hidden' name='operation' value='$operation'>
        <input type='hidden' name='confirmed' value='yes'>
        <br>
        <input type='submit' value='", attr_safe(_("Do It")), "'>
        </form>
    ";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function handle_upload( $projectid, $image, $replacement_image_info )
// If there's a problem, return a string containing an error message.
// If no problem, return the empty string.
{
    global $projects_dir;

    // Check the error code.
    $error_code = $replacement_image_info['error'];
    if ($error_code != UPLOAD_ERR_OK)
    {
        return
            sprintf( _('Error code = %d.'), $error_code )
            . "\n"
            . "(" . get_upload_err_msg($error_code) . ")";
    }

    // Check that the extensions match.
    $curr_ext = pathinfo($image, PATHINFO_EXTENSION);
    $repl_ext = pathinfo($replacement_image_info['name'], PATHINFO_EXTENSION);
    if ( $curr_ext != $repl_ext )
    {
        return sprintf(
            _('Replacement file\'s extension (%1$s) does not match current file\'s extension (%2$s).'),
            $repl_ext,
            $curr_ext
        );
    }

    // $replacement_image_info['type'] might be relevant, but is it trustworthy?

    // Check $replacement_image_info['size']?

    $image_path = "$projects_dir/$projectid/$image";
    $r = move_uploaded_file($replacement_image_info['tmp_name'], $image_path);
    if ( $r )
    {
        return '';
    }
    else
    {
        return _('The uploaded file cannot be moved into the project directory for some reason.');
    }
}

function handle_delete( $projectid, $image)
// If there's a problem, return a string containing an error message.
// If no problem, return the empty string.
{
    global $projects_dir;

    // Check the error code.
    $image_path = "$projects_dir/$projectid/$image";
    $r = unlink($image_path);
    if ( $r )
    {
        return '';
    }
    else
    {
        return _('The uploaded file cannot be deleted from the project directory for some reason.');
    }
}

function handle_delete_all( $projectid, $nonpage_image_names)
// If there's a problem, return a string containing an error message.
// If no problem, return the empty string.
{
    global $projects_dir;

    // Check the error code.
    foreach ($nonpage_image_names as $nonpage_image_name)
    {
        $image_path = "$projects_dir/$projectid/$nonpage_image_name";
        $r = unlink($image_path);
        if ( !$r )
        {
            $has_error = true;
        }
    }

    if (!$has_error)
    {
        return "";
    }
    else
    {
        return _('One or more illustrations could not be deleted.');
    }
}

// vim: sw=4 ts=4 expandtab
