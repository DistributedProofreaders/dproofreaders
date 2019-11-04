<?php

function render_validator()
{
    $bad_char_message = _("The text contains characters which are not enabled for this project");
    $quit_text = _("Quit");
    echo <<<END
<div class='flex_container nodisp' id='checker'>
<div class='stretchbox ws-pre' id='check-text'>
</div>
<div class='fixedbox'>
$bad_char_message
&nbsp;<button type='button' id='cc-quit'>$quit_text</button>
</div>
</div>
END;
}

function get_json_codepoints()
{
    // this will come from get_valid_codepoints()
    $latin1_codepoints = [
        'U+000a',
        'U+000d',
        'U+0020-U+007E',
        'U+00A1-U+00AC',
        'U+00AE-U+00FF',
    ];
    return json_encode($latin1_codepoints);
}
