<?php
include_once($relPath."Project.inc");
include_once($relPath."unicode.inc"); // convert_codepoint_ranges_to_characters()

class CharacterSelector
{
    private array $picker_sets;

    public function __construct($projectid)
    {
        $project = get_project_or_quiz($projectid);
        $this->picker_sets = $project->get_pickersets();
    }

    public function draw()
    {
        // The code in tools/proofers/character_selector.js uses these ids and
        // classes and adds the MRU buttons if this code is changed, that code
        // also needs to change.

        $selector_string = "<div id='selector_row'>";
        $row_string = "";

        $pickerid = 1;
        foreach ($this->picker_sets as $picker_set) {
            if (!$picker_set) {
                continue;
            }

            if (count($this->picker_sets) == 1) {
                $prefix = "";
            } else {
                $prefix = "$picker_set->name: ";
            }

            foreach ($picker_set->get_subsets() as $code => $picker) {
                $safe_code = "picker_$pickerid";
                $pickerid++;
                $title = $prefix . $picker_set->get_title($code);
                $selector_string .= "<button type='button' id='$safe_code' class='selector_button' title='$title'>" . html_safe($code) . "</button>";
                $row_string .= "<div class='$safe_code key-block'>\n";
                foreach ($picker as $row) {
                    $row_string .= $this->draw_row(convert_codepoint_ranges_to_characters($row));
                }
                $row_string .= "</div>\n";
            }
        }

        $selector_string .= "</div>\n";

        echo "<div class='char_box'><input type='text' readonly class='big_text' id='large_char'></div>\n";
        echo "<div id='char-selector' class='nowrap'>$selector_string$row_string</div>\n";
    }

    public function draw_row($chars)
    {
        $row = "<div class='table-row'>";

        foreach ($chars as $character) {
            $row .= "<div class='table-cell'>";
            if (null == $character) {
                $row .= "<button type='button' class='picker invisible'></button>";
            } else {
                $title_string = attr_safe(utf8_character_name($character));
                // to ensure the accents for Greek capital letters are visible, add a text indent.
                // see also similar code in tools/proofers/character_selector.js
                if (str_starts_with($title_string, "GREEK CAPITAL") && (str_contains($title_string, "OXIA") || str_contains($title_string, "VARIA"))) {
                    $align_style = "style='text-indent:0.35em'";
                } else {
                    $align_style = "";
                }
                $row .= "<button type='button' class='picker' title='$title_string' $align_style>" . html_safe($character) . "</button>";
            }
            $row .= "</div>";
        }
        return $row . "</div>\n";
    }
}
