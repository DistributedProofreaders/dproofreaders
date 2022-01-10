<?php
function get_proof_texts()
{
    $texts = json_encode([
        "invalidChars" => _("The text contains invalid characters that will be <span class='bad-char'>highlighted</span> if possible"),
        "quitText" => _("Quit"),
        "removeChars" => _("Remove bad characters and quit"),
    ]);

    return "var proofText = $texts;";
}
