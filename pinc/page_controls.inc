<?php
include_once($relPath.'stages.inc'); // $Round_for_round_id_
include_once($relPath.'metarefresh.inc');

class PageData
{
    function __construct()
    {
        global $code_url;

        $this->error_message = "";
        $this->projectid = array_get($_GET, "project", "");
        $this->page = array_get($_GET, "imagefile", "");
        $this->round_id = get_enumerated_param($_GET, 'round_id', 'OCR', expanded_rounds());
        $this->project = null;
        $this->project_selector_url = "$code_url/tools/project_manager/project_selector.php?origin={$_SERVER['PHP_SELF']}";
        $this->images = array();

        if($this->projectid)
        {
            try
            {
                $this->projectid = validate_projectID('projectid', $this->projectid); // throws InvalidProjectIDException
                $this->project = new Project($this->projectid); // throws NonexistentProjectException

                $res = mysqli_query(DPDatabase::get_connection(), "SELECT image FROM {$this->projectid} ORDER BY image ASC") or die(mysqli_error(DPDatabase::get_connection()));
                if(0 == $res->num_rows)
                {
                    throw new UnexpectedValueException(sprintf(_("There are no pages in %s"), $this->projectid));
                }
                while($row = mysqli_fetch_assoc($res))
                {
                    $this->images[] = $row["image"];
                }
                mysqli_free_result($res);

                if($this->page !== "")
                {
                    // find a match for the data
                    $esc_page = mysqli_real_escape_string(DPDatabase::get_connection(), $this->page);
                    $res = mysqli_query(DPDatabase::get_connection(), "SELECT image FROM $this->projectid WHERE image LIKE '%$esc_page%'") or die(mysqli_error(DPDatabase::get_connection()));
                    if (mysqli_num_rows($res) !== 0)
                    {
                        $this->page = mysqli_fetch_assoc($res)["image"];
                    }
                    else
                    {
                        $this->page = "";
                    }
                    mysqli_free_result($res);
                }
                if($this->page === "")
                {
                    // no data given or couldn't find a match
                    $this->page = $this->images[0];
                }
            }
            catch(Exception $exception)
            {
                $this->error_message = html_safe($exception->getMessage());
            }
        }
        if(!$this->project || $this->error_message)
        {
            $error_message = urlencode($this->error_message);
            metarefresh(0, "{$this->project_selector_url}&error=$error_message");
        }
    }

    function get_page_data()
    {
        global $code_url;
        $heading = "<p>" . html_safe($this->project->nameofwork).
            "\n&nbsp;<a href='$code_url/project.php?id=$this->projectid'>" . _("Go to Project Page") . "</a>
            &nbsp; <a href='{$this->project_selector_url}'>" . _("Select a Different Project") . "</a></p>";

        if ($this->round_id == "OCR")
        {
            $text_column_name = 'master_text';
        }
        else
        {
            $text_column_name = get_Round_for_round_id($this->round_id)->text_column_name;
        }
        $result = mysqli_query(DPDatabase::get_connection(), sprintf("SELECT $text_column_name FROM $this->projectid WHERE image = '%s'", mysqli_real_escape_string(DPDatabase::get_connection(), $this->page)));
        $row = mysqli_fetch_assoc($result);
        $text = $row[$text_column_name];

        $page_browser_data = [
            "projectid" => $this->projectid,
            "pages" => $this->images,
            "currentPage" => $this->page,
            "currentRound" => $this->round_id,
            "text" => $text,
            "heading" => $heading,
        ];
        $page_browser_data = json_encode($page_browser_data);

        return "var pageBrowserData = $page_browser_data;";
    }
}

function expanded_rounds()
{
    global $Round_for_round_id_;

    $expanded_rounds = array_keys($Round_for_round_id_);
    array_unshift($expanded_rounds, 'OCR');
    return $expanded_rounds;
}

function get_dp_data()
{
    $proofreading_font_faces = get_available_proofreading_font_faces();
    $font_family_fallback = get_proofreading_font_family_fallback();
    $proofreading_font_sizes = get_available_proofreading_font_sizes();

    $font_faces = [];
    foreach($proofreading_font_faces as $index => $font)
    {
        if($index == 0)
        {
            $font = $font_family_fallback;
        }
        else
        {
            if($index == 1)
            {
                $font = get_user_proofreading_font_other();
                if(!$font)
                    continue;
            }
        }
        $font_faces[] = $font;
    }

    $font_sizes = [];
    foreach($proofreading_font_sizes as $size)
    {
        if($size == '')
        {
            $size_text = BROWSER_DEFAULT_STR;
        }
        else
        {
            $size_text = $size;
        }
        $font_sizes[$size] = $size_text;
    }

    $font_data = [
        "faces" => $font_faces,
        "sizes" => $font_sizes,
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
    ];

    global $code_url;
    $button_images = array(
        "imgVSplit" => "$code_url/tools/proofers/gfx/bt4.png",
        "imgHSplit" => "$code_url/tools/proofers/gfx/bt5.png",
    );

    global $projects_url;
    $dp_data = json_encode([
        "font" => $font_data,
        "expandedRounds" => expanded_rounds(),
        "strings" => $strings,
        "buttonImages" => $button_images,
        "projectsUrl" => $projects_url,
    ]);

    return "var dpData = $dp_data;";
}
