<?php
include_once($relPath.'stages.inc'); // $Round_for_round_id_
include_once($relPath.'metarefresh.inc');

function get_page_data_js($projectid, $page, $round_id)
{
    global $code_url;

    $heading = "";
    $text = "";
    $error_message = "";
    $page_names = array();

    if($projectid)
    {
        try
        {
            $projectid = validate_projectID('projectid', $projectid); // throws InvalidProjectIDException
            $project = new Project($projectid); // throws NonexistentProjectException

            $page_names = $project->get_page_names_from_db();
            if(!$page_names)
            {
                throw new UnexpectedValueException(sprintf(_("There are no pages in %s"), $projectid));
            }

            // page names are sorted so will find first match
            $page_match = false;
            if($page !== "")
            {
                foreach($page_names as $page_name)
                {
                    if(1 === preg_match("/.*$page.*/", $page_name))
                    {
                        $page = $page_name;
                        $page_match = true;
                        break;
                    }
                }
            }
            if(!$page_match)
            {
                // no data given or couldn't find a match
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
            $esc_page = DPDatabase::escape($page);
            $result = DPDatabase::query("SELECT $text_column_name FROM $projectid WHERE image = '$esc_page'");
            $row = mysqli_fetch_assoc($result);
            $text = $row[$text_column_name];
        }
        catch(Exception $exception)
        {
            $error_message = html_safe($exception->getMessage());
            $projectid = "";
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
        "imageUrl" => "$project->url/$page"
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
