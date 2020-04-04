<?php
include_once($relPath.'stages.inc'); // $Round_for_round_id_
include_once($relPath.'metarefresh.inc');

class PageData
{
    function __construct()
    {
        global $code_url, $Round_for_round_id_;

        $this->expanded_rounds = array_keys($Round_for_round_id_);
        array_unshift($this->expanded_rounds, 'OCR');

        $this->error_message = "";
        $this->projectid = array_get($_GET, "project", "");
        $this->page = array_get($_GET, "imagefile", "");
        $this->round_id = get_enumerated_param($_GET, 'round_id', 'OCR', $this->expanded_rounds);
        $this->project = null;
        $this->project_selector_url = "$code_url/tools/project_manager/project_selector.php?origin={$_SERVER['PHP_SELF']}";
        $this->selected_index = 0;

        if(!$this->projectid)
        {
            return;
        }
        try
        {
            $this->projectid = validate_projectID('projectid', $this->projectid); // throws InvalidProjectIDException
            $this->project = new Project($this->projectid); // throws NonexistentProjectException

            $this->images = array();
            $res = mysqli_query(DPDatabase::get_connection(), "SELECT image FROM {$this->projectid} ORDER BY image ASC") or die(mysqli_error(DPDatabase::get_connection()));
            while($row = mysqli_fetch_assoc($res))
            {
                $this->images[] = $row["image"];
            }
            mysqli_free_result($res);

            if($this->page !== "")
            {
                $esc_page = mysqli_real_escape_string(DPDatabase::get_connection(), $this->page);
                $res = mysqli_query(DPDatabase::get_connection(), "SELECT image FROM $this->projectid WHERE image LIKE '%$esc_page%'") or die(mysqli_error(DPDatabase::get_connection()));
                if (mysqli_num_rows($res) !== 0)
                {
                    $this->page = mysqli_fetch_assoc($res)["image"];
                }
                mysqli_free_result($res);
                foreach($this->images as $index => $image)
                {
                    if($image === $this->page)
                    {
                        $this->selected_index = $index;
                    }
                }
            }
            if(array_get($_GET, "next-button", ""))
            {
                $this->selected_index += 1;
                $this->page = $this->images[$this->selected_index];
            }
            if(array_get($_GET, "prev-button", ""))
            {
                $this->selected_index -= 1;
                $this->page = $this->images[$this->selected_index];
            }
        }
        catch(Exception $exception)
        {
            $this->error_message = html_safe($exception->getMessage());
        }
    }

    function validate()
    {
        if(!$this->project)
        {
            $error_message = urlencode($this->error_message);
            metarefresh(0, "{$this->project_selector_url}&error=$error_message");
        }
    }

    function draw_page_selector()
    {
        echo _("Page") . ":&nbsp;<select name='imagefile' id='page-select'>\n";
        foreach($this->images as $index => $image)
        {
            $selected = selected_text($index === $this->selected_index);
            echo "<option value='$image'$selected>$image</option>\n";
        }
        echo "</select>\n";

        echo "<input type='submit' name='prev-button' value='", attr_safe(_("Previous")), "'", disabled_text($this->selected_index <= 0), ">\n";
        echo "<input type='submit' name='next-button' value='", attr_safe(_("Next")), "'", disabled_text($this->selected_index >= (count($this->images) - 1)), ">\n";
    }

    function draw_round_selector()
    {
        echo _("Round") . ":&nbsp;<select name='round_id' id='round'>\n";
        foreach ($this->expanded_rounds as $round)
        {
            $selected = selected_text($round === $this->round_id);
            echo "<option value='$round'$selected>$round</option>\n";
        }
        echo "</select>\n";
    }

    function draw_heading()
    {
        global $code_url;
        echo "<p>", html_safe($this->project->nameofwork),
            "\n&nbsp;<a href='$code_url/project.php?id=$this->projectid'>", _("Go to Project Page"), "</a>
            &nbsp; <a href='{$this->project_selector_url}'>", _("Select a Different Project"), "</a></p>\n";
    }

    function draw_hidden_projectid()
    {
        echo "<input type='hidden' name='project' value='" . attr_safe($this->projectid) . "'>";
    }

    function draw_image()
    {
        global $projects_url;
        echo "<img id='image' src='$projects_url/{$this->projectid}/$this->page'>\n";
    }

    function get_text()
    {
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
        return $row[$text_column_name];
    }

    function show_text()
    {
        echo html_safe($this->get_text());
    }
}

function disabled_text($disabled)
{
    return $disabled ? " disabled" : "";
}

function selected_text($selected)
{
    return $selected ? " selected" : "";
}

function draw_text_button()
{
    global $code_url;
    echo "<input type='submit' formaction='$code_url/tools/project_manager/display_text.php' value='", attr_safe(_("Show Text only")), "'>\n";
}

function draw_image_button()
{
    global $code_url;
    echo "<input type='submit' formaction='$code_url/tools/project_manager/displayimage.php' value='", attr_safe(_("Show Image only")), "'>\n";
}

function draw_image_text_button()
{
    global $code_url;
    echo "<input type='submit' formaction='$code_url/tools/project_manager/display_image_text.php' value='", attr_safe(_("Show Image & Text")), "'>\n";
}

function draw_size_controls()
{
    echo _("Image"), ": <input type='number' id='percent' min='1' max='999' value='100'>%\n";
    echo "<input type='button' id='resize' value='", _("Resize"), "'>\n";
}

function draw_split_control()
{
    global $code_url;
    echo "<button type='button' class='img-button' id='v-switch' title='", attr_safe(_("Switch to Vertical Layout")), "'><img src='$code_url/tools/proofers/gfx/bt4.png'></button>";
    echo "<button type='button' class='img-button' id='h-switch' title='", attr_safe(_("Switch to Horizontal Layout")), "'><img src='$code_url/tools/proofers/gfx/bt5.png'></button>\n";
}

function draw_font_selector()
{
    $proofreading_font_faces = get_available_proofreading_font_faces();
    $font_family_fallback = get_proofreading_font_family_fallback();

    echo "<select id='fntFace' title='", attr_safe(_("Change Font Face")), "'>\n";
    foreach($proofreading_font_faces as $index => $font)
    {
        if($index == 0)
        {
            $font_text = BROWSER_DEFAULT_STR;
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
            $font_text = $font;
        }

        echo "<option value='" . attr_safe($font) . "'>" . html_safe($font_text) . "</option>\n";
    }
    echo "</select>\n";
}

function draw_font_size_selector()
{
    $proofreading_font_sizes = get_available_proofreading_font_sizes();

    echo "<select id='fntSize' title='", attr_safe(_("Change Font Size")),"'>\n";
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
        echo "<option value='$size'>$size_text</option>\n";
    }
    echo "</select>\n";
}
