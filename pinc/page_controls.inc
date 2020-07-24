<?php
include_once($relPath.'stages.inc'); // $Round_for_round_id_
include_once($relPath.'metarefresh.inc');

function get_page_data_js($projectid, $page, $round_id, $error_message)
{
    global $code_url;

    $heading = "";
    $text = "";
    $page_names = array();
    $image_url = "";

    if($projectid)
    {
        try
        {
            // $projectid has been validated before we get here
            $project = new Project($projectid); // throws NonexistentProjectException

            $page_names = $project->get_page_names_from_db();
            if(!$page_names)
            {
                throw new UnexpectedValueException(sprintf(_("There are no pages in %s"), $projectid));
            }

            // if page is not null check if it exists else select first page
            if($page && !in_array($page, $page_names))
            {
                $page = $page_names[0];
            }

            $heading = html_safe($project->nameofwork) . "<a href='$code_url/project.php?id=$projectid'>" . _("Go to Project Page") . "</a>";

            if ($round_id == "OCR")
            {
                $text_column_name = 'master_text';
            }
            else
            {
                $text_column_name = get_Round_for_round_id($round_id)->text_column_name;
            }
            if($page)
            {
                $esc_page = DPDatabase::escape($page);
                $result = DPDatabase::query("SELECT $text_column_name FROM $projectid WHERE image = '$esc_page'");
                $row = mysqli_fetch_assoc($result);
                $text = $row[$text_column_name];
                $image_url = "$project->url/$page";
            }
        }
        catch(Exception $exception)
        {
            $error_message = html_safe($exception->getMessage());
        }
    }

    $page_browser_data = [
        "projectid" => $projectid,
        "pages" => $page_names,
        "currentPage" => $page,
        "currentRound" => $round_id,
        "text" => $text,
        "heading" => $heading,
        "errorMessage" => $error_message,
        "imageUrl" => "$image_url"
    ];
    $page_browser_data = json_encode($page_browser_data);

    return "var pageBrowserData = $page_browser_data;";
}

function expanded_rounds()
{
    global $Round_for_round_id_;

    $expanded_rounds = array_keys($Round_for_round_id_);
    array_unshift($expanded_rounds, 'OCR');
    return $expanded_rounds;
}

function get_proofreading_interface_data_js()
{
    $font_data = [
        "faces" => get_font_styles(),
        "faceFamilies" => get_full_font_families(),
        "sizes" => get_font_sizes(),
        "sizeFamilies" => get_font_size_families(),
    ];

    $strings = [
        "image" => _("Image"),
        "resize" => _("Resize"),
        "page" => _("Page"),
        "previous" => _("Previous"),
        "next" => _("Next"),
        "round" => _("Round"),
        "switchVert" => _("Switch to Vertical Layout"),
        "switchHoriz" => _("Switch to Horizontal Layout"),
        "changeFontFace" => _("Change Font Face"),
        "changeFontSize" => _("Change Font Size"),
        "wrap" => _("Wrap"),
        "showImage" => _("Show Image only"),
        "showText" => _("Show Text only"),
        "showImageText" => _("Show Image & Text"),
        "selectAProject" => _("Select a Project"),
        "selectProject" => _("Select Project"),
        "optional" => _("(optional)"),
        "reset" => _("Select a different project"),
        "projectid" => _("Project ID"),
        "selectAPage" => _("Please select a page"),
    ];

    global $code_url;
    $button_images = array(
        "imgVSplit" => "$code_url/tools/proofers/gfx/bt4.png",
        "imgHSplit" => "$code_url/tools/proofers/gfx/bt5.png",
    );

    $proof_int_data = json_encode([
        "font" => $font_data,
        "expandedRounds" => expanded_rounds(),
        "strings" => $strings,
        "buttonImages" => $button_images,
    ]);

    return "var proofIntData = $proof_int_data;";
}
