<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param(), html_safe()
include_once($relPath.'project_states.inc'); // PROJ_NEW, PROJ_P1_UNAVAILABLE

require_login();

$projectid = validate_projectID('project', @$_GET['project']);

$zip_type = get_enumerated_param( $_GET, 'zip_type', NULL, array('pages', 'illos'), TRUE );

$project = new Project($projectid);

$image_index_str = _('Image Index');

// Suppress display if we're streaming back a zipfile
// so that we can control the http headers
if (is_null($zip_type))
{
output_header("$image_index_str: {$project->nameofwork}");

echo "
    <h1>" . html_safe($project->nameofwork) . "</h1>
    <p>$projectid</p>
    <p><a href='$code_url/project.php?id=$projectid'>", _('Return to Project Page'), "</a></p>
    <h2>$image_index_str</h2>
    <p>" . _('Below are the individual images for this project.') . "</p>
";
}

$page_image_names = array();
$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT image
    FROM $projectid
    ORDER BY image
") or die(mysqli_error(DPDatabase::get_connection()));
while ( list($image) = mysqli_fetch_row($res) )
{
    $page_image_names[] = $image;
}

chdir("$projects_dir/$projectid");
$existing_image_names = glob("*.{png,jpg}", GLOB_BRACE);
// That returns a sorted list of the .png files
// followed by a sorted list of the .jpg files,
// but we want the two lists interleaved...
sort($existing_image_names);

$nonpage_image_names = array_diff($existing_image_names, $page_image_names);

if (!is_null($zip_type))
{
    chdir("$projects_dir/$projectid");
    $list_name = $projectid."_".$zip_type."_flist.txt";
    switch($zip_type)
    {
    case "illos":
	$files_list=$nonpage_image_names;
	break;
    default:
    case "pages":
	$files_list=$page_image_names;
    }
    $files=implode(chr(10),$files_list);
    file_put_contents( $list_name, $files );

    // Create the zip on-the-fly and stream it back
    $zipfile=$projectid . "_" . $zip_type . ".zip";
    header('Content-type: application/zip');
    header('Content-Disposition: attachment; filename="'.$zipfile.'"');
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    passthru("cat $list_name |zip -@ -");
    unlink($list_name);
    exit();
}
else
{
    echo "<table border='1'>\n";
    echo "<tr>\n";
    echo "<td class='top-align'>\n";
    list_images( $project, $page_image_names, $existing_image_names, TRUE);
    echo "</td>\n";
    echo "<td class='top-align'>\n";
    list_images( $project, $nonpage_image_names, $existing_image_names, FALSE);
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='center-align'>\n";
    show_dl_link( $projectid, $page_image_names, TRUE );
    echo "</td>\n";
    echo "<td class='center-align'>\n";
    show_dl_link( $projectid, $nonpage_image_names, FALSE );
    show_delete_all_link( $project, $nonpage_image_names );
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function list_images( $project, $image_names, $existing_image_names, $these_are_page_images )
{
    global $code_url, $projects_url;

    $projectid=$project->projectid;

    if ( $these_are_page_images )
    {
        $header = _('Page Images');
    }
    else
    {
        $header = _('Non-Page Images (Illustrations)');
    }
    echo "<h4 class='center-align'>$header</h4>";

    $show_replace_links = $project->can_be_managed_by_current_user;
    $show_delete_links = !$these_are_page_images && ($project->state == PROJ_NEW || $project->state == PROJ_P1_UNAVAILABLE);

    echo "<table>\n";

    {
        echo "<tr>\n";
        echo "<th>", _('Name'), "</th>\n";
        echo "<th>", _('Size'), "</th>\n";
        if ( $show_replace_links )
        {
            echo "<th>", _('Replace'), "</th>\n";
            if ($show_delete_links)
            {
                echo "<th>", _('Delete'), "</th>\n";
            }
        }
        echo "</tr>\n";
    }

    foreach ( $image_names as $image_name )
    {
        echo "<tr>\n";

        if ( $these_are_page_images && !in_array( $image_name, $existing_image_names ) )
        {
            echo "<td>$image_name</td>\n";
            echo "<td class='error'>" . _("missing") . "</td>\n";
        }
        else
        {
            $encoded_url = "$projects_url/$projectid/" . rawurlencode($image_name);
            echo "<td><a href='$encoded_url'><b>$image_name</b></a></td>\n";

            // scale image sizes to reasonable units
            $size = filesize($image_name);
            $units = "B";
            if($size > 1024)
            {
                $size = $size / 1024;
                $units = "KB";
                if($size > 1024)
                {
                    $size = $size / 1024;
                    $units = "MB";
                }
            }
            echo "<td class='right-align'>" . sprintf("%0.2f", $size) . " $units</td>\n";
        }

        if ( $show_replace_links )
        {
            if ( $these_are_page_images)
            {
                $replace_url = "$code_url/tools/project_manager/handle_bad_page.php?projectid=$projectid&amp;image=$image_name&amp;modify=image";
            }
            else
            {
                $replace_url = "$code_url/tools/project_manager/update_illos.php?projectid=$projectid&amp;image=$image_name&amp;operation=replace";
                $delete_url = "$code_url/tools/project_manager/update_illos.php?projectid=$projectid&amp;image=$image_name&amp;operation=delete";
            }
            echo "<td><a href='$replace_url'>", _('Replace'), "</a></td>\n";

            if ( $show_delete_links)
            {
                echo "<td><a href='$delete_url'>", _('Delete'), "</a></td>\n";
            }
        }

        echo "</tr>\n";
    }

    echo "</table>\n";

}

function show_dl_link( $projectid, $image_names, $these_are_page_images )
{
    global $code_url;

    if ( $these_are_page_images )
    {
	$tvalue=_("Download Page Images");
	$ztype="pages";
    }
    else
    {
	$tvalue=_("Download Illustrations");
	$ztype="illos";
    }

    // Show a download button for non-empty images list
    if(!empty($image_names))
    {
	$form_target="$code_url/tools/proofers/images_index.php?project=$projectid&zip_type=$ztype";
	echo "<form name='idl' id='idl' style='display: inline' action='$form_target' method='POST'>\n";
        echo "<input id='zip_type' type='submit' value='$tvalue'>";
        echo "</form>";
    }
}

function show_delete_all_link( $project, $image_names )
{
    global $code_url;

    if(($project->state == PROJ_NEW || $project->state == PROJ_P1_UNAVAILABLE) && !empty($image_names))
    {
        $form_target="$code_url/tools/project_manager/update_illos.php";
        $submit_label=_("Delete Illustrations");
        echo "<form action='$form_target' method='POST' style='display: inline'>\n";
        echo "<input type='hidden' name='projectid' value='{$project->projectid}'>";
        echo "<input type='hidden' name='operation' value='delete_all'>";
        echo "<input type='submit' value='$submit_label'>";
        echo "</form>";
    }
}

// vim: sw=4 ts=4 expandtab
