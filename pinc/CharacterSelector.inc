<?php
include_once($relPath."Project.inc");
include_once($relPath."unicode.inc"); // convert_codepoint_ranges_to_characters()

class CharacterSelector
{
    function __construct ($projectid)
    {
        $project = get_real_or_quiz_project($projectid);
        $this->picker_sets = $project->get_pickersets();
    }

    function draw()
    {
        // The code in tools/proofers/character_selector.js uses these ids and
        // classes and adds the MRU buttons if this code is changed, that code
        // also needs to change.

        $selector_string = "<div id='selector_row'>";
        $row_string = "";

        foreach ($this->picker_sets as $picker_set)
        {
            if(count($this->picker_sets) == 1)
            {
                $prefix = "";
            }
            else
            {
                $prefix = "$picker_set->name: ";
            }

            foreach ($picker_set->get_subsets() as $code => $picker)
            {
                $safe_code = bin2hex($code);
                $title = $prefix . $picker_set->get_title($code);
                $selector_string .= "<button type='button' id='_$safe_code' class='selector_button' title='$title'>" . html_safe($code) . "</button>";
                $row_string .= "<div class='_$safe_code key-block'>\n";
                foreach($picker as $row)
                {
                    $row_string .= $this->draw_row(convert_codepoint_ranges_to_characters($row));
                }
                $row_string .= "</div>\n";
            }
        }

        $selector_string .= "</div>\n";

        echo "<div class='char_box'><input type='text' readonly class='big_text' id='large_char'></div>\n";
        echo "<div id='char-selector' class='nowrap'>$selector_string$row_string</div>\n";
    }

    function draw_row($chars)
    {

        $row = "<div class='table-row'>";

        foreach($chars as $character)
        {
            $row .= "<div class='table-cell'>";
            if (NULL == $character)
            {
                $row .= "<button type='button' class='picker invisible'></button>";
            }
            else
            {
                $title_string = attr_safe(IntlChar::charName($character));
                $row .= "<button type='button' class='picker' title='$title_string'>" . html_safe(IntlChar::chr($character)) . "</button>";
            }
            $row .= "</div>";
        }
        return $row . "</div>\n";
    }
}

// vim: sw=4 ts=4 expandtab
