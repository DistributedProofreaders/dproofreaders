<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param(), html_safe()
include_once($relPath.'project_states.inc'); // PROJ_NEW, PROJ_P1_UNAVAILABLE

require_login();

$projectid = get_projectID_param($_GET, 'project');

$zip_type = get_enumerated_param($_GET, 'zip_type', null, ['pages', 'illos'], true);

$project = new Project($projectid);

if (!is_null($zip_type)) {
    switch ($zip_type) {
        case "illos":
           $files_list = $project->get_illustrations();
           break;
        case "pages":
           $files_list = $project->get_page_names_from_db();
           break;
        default:
            throw new InvalidArgumentException("Invalid image type specified");
    }
    $files = implode("\n", $files_list);
    $list_name = "{$projectid}_{$zip_type}_flist.txt";
    chdir($project->dir);
    file_put_contents($list_name, $files);

    // Create the zip on-the-fly and stream it back
    $zipfile = "{$projectid}_{$zip_type}.zip";
    header('Content-type: application/zip');
    header("Content-Disposition: attachment; filename=\"$zipfile\"");
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    $cmd = join(" ", [
        "cat",
        escapeshellarg($list_name),
        "|",
        "zip",
        "-@",
        "-",
    ]);
    passthru($cmd);
    unlink($list_name);
    exit();
} else {
    $image_index_str = _('Image Index');

    output_header("$image_index_str: {$project->nameofwork}");

    echo "
        <h1>" . html_safe($project->nameofwork) . "</h1>
        <p>$projectid</p>
        <p><a href='$code_url/project.php?id=$projectid'>", _('Return to Project Page'), "</a></p>
        <h2>$image_index_str</h2>
    ";

    try {
        $page_image_names = $project->get_page_names_from_db();
        $nonpage_image_names = $project->get_illustrations();
    } catch (ProjectException $exception) {
        echo "<p>" . _("An error occurred when loading the project images. The project may have been deleted or archived.") . "</p>";
        echo "<p class='error'>" . $exception->getMessage() . "</p>";
        exit;
    }

    echo "<p>" . _('Below are the individual images for this project.') . "</p>";

    echo "<table border='1'>\n";
    echo "<tr>\n";
    echo "<td class='top-align'>\n";
    list_images($project, $page_image_names, true);
    echo "</td>\n";
    echo "<td class='top-align'>\n";
    list_images($project, $nonpage_image_names, false);
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='center-align'>\n";
    show_dl_link($projectid, $page_image_names, true);
    echo "</td>\n";
    echo "<td class='center-align'>\n";
    show_dl_link($projectid, $nonpage_image_names, false);
    show_delete_all_link($project, $nonpage_image_names);
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function list_images($project, $image_names, $these_are_page_images)
{
    global $code_url;

    $projectid = $project->projectid;
    $existing_image_names = $project->get_page_names_from_dir();

    if ($these_are_page_images) {
        $header = _('Page Images');
    } else {
        $header = _('Non-Page Images (Illustrations)');
    }
    echo "<h4 class='center-align'>$header</h4>";

    $show_replace_links = $project->can_be_managed_by_current_user;
    $show_delete_links = $project->can_be_managed_by_current_user && !$these_are_page_images && ($project->state == PROJ_NEW || $project->state == PROJ_P1_UNAVAILABLE);

    echo "<table>\n";

    {
        echo "<tr>\n";
        echo "<th>", _('Name'), "</th>\n";
        echo "<th>", _('Size'), "</th>\n";
        if ($show_replace_links) {
            echo "<th>", _('Replace'), "</th>\n";
            if ($show_delete_links) {
                echo "<th>", _('Delete'), "</th>\n";
            }
        }
        echo "</tr>\n";
    }

    foreach ($image_names as $image_name) {
        echo "<tr>\n";

        if ($these_are_page_images && !in_array($image_name, $existing_image_names)) {
            echo "<td>$image_name</td>\n";
            echo "<td class='error'>" . _("missing") . "</td>\n";
        } else {
            $encoded_url = "$project->url/" . rawurlencode($image_name);
            echo "<td><a href='$encoded_url'><b>$image_name</b></a></td>\n";

            // scale image sizes to reasonable units
            $size = filesize($image_name);
            $units = "B";
            if ($size > 1024) {
                $size = $size / 1024;
                $units = "KB";
                if ($size > 1024) {
                    $size = $size / 1024;
                    $units = "MB";
                }
            }
            echo "<td class='right-align'>" . sprintf("%0.2f", $size) . " $units</td>\n";
        }

        if ($show_replace_links) {
            if ($these_are_page_images) {
                $replace_url = "$code_url/tools/project_manager/handle_bad_page.php?projectid=$projectid&amp;image=$image_name&amp;modify=image";
            } else {
                $replace_url = "$code_url/tools/project_manager/update_illos.php?projectid=$projectid&amp;image=$image_name&amp;operation=replace";
                $delete_url = "$code_url/tools/project_manager/update_illos.php?projectid=$projectid&amp;image=$image_name&amp;operation=delete";
            }
            echo "<td><a href='$replace_url'>", _('Replace'), "</a></td>\n";

            if ($show_delete_links) {
                echo "<td><a href='$delete_url'>", _('Delete'), "</a></td>\n";
            }
        }

        echo "</tr>\n";
    }

    echo "</table>\n";
}

function show_dl_link($projectid, $image_names, $these_are_page_images)
{
    global $code_url;

    if ($these_are_page_images) {
        $tvalue = _("Download Page Images");
        $ztype = "pages";
    } else {
        $tvalue = _("Download Illustrations");
        $ztype = "illos";
    }

    // Show a download button for non-empty images list
    if (!empty($image_names)) {
        $form_target = "$code_url/tools/proofers/images_index.php?project=$projectid&zip_type=$ztype";
        echo "<form name='idl' id='idl' style='display: inline' action='$form_target' method='POST'>\n";
        echo "<input id='zip_type' type='submit' value='$tvalue'>";
        echo "</form>";
    }
}

function show_delete_all_link($project, $image_names)
{
    global $code_url;

    if ($project->can_be_managed_by_current_user && ($project->state == PROJ_NEW || $project->state == PROJ_P1_UNAVAILABLE) && !empty($image_names)) {
        $form_target = "$code_url/tools/project_manager/update_illos.php";
        $submit_label = _("Delete Illustrations");
        echo "<form action='$form_target' method='POST' style='display: inline'>\n";
        echo "<input type='hidden' name='projectid' value='{$project->projectid}'>";
        echo "<input type='hidden' name='operation' value='delete_all'>";
        echo "<input type='submit' value='$submit_label'>";
        echo "</form>";
    }
}
