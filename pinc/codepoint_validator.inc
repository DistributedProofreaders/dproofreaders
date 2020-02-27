<?php

function render_validator()
{
    $bad_char_message = _("The text contains invalid characters that will be <span class='bad-char'>highlighted</span> if possible");
    $quit_text = _("Quit");
    $remove_text = _("Remove bad characters and quit");
    echo <<<END
<div class='flex_container' >
<div class='stretchbox ws-pre' id='check-text'>
</div>
<div class='fixedbox'>
$bad_char_message
&nbsp;<button type='button' id='cc-quit'>$quit_text</button>
&nbsp;<button type='button' id='cc-remove'>$remove_text</button>
</div></div>
END;
}
