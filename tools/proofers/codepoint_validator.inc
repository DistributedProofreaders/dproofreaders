<?php
include_once($relPath."Project.inc");

function render_validator()
{
    $bad_char_message = _("The text contains characters which are not enabled for this project");
    $quit_text = _("Quit");
    $remove_text = _("Remove bad characters and quit");
    echo <<<END
<div class='flex_container nodisp' id='checker'>
<div class='stretchbox ws-pre' id='check-text'>
</div>
<div class='fixedbox'>
$bad_char_message
&nbsp;<button type='button' id='cc-quit'>$quit_text</button>
&nbsp;<button type='button' id='cc-remove'>$remove_text</button>
</div>
</div>
END;
}

function get_json_codepoints($projectid)
{
    $project = new Project($projectid);
    return json_encode($project->get_valid_codepoints());
}
