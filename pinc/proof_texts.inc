<?php
include_once($relPath.'faq.inc'); // get_faq_url()

function get_proof_texts()
{
    $texts = json_encode([
        "invalidChars" => _("The text contains invalid characters that will be <span class='bad-char'>highlighted</span> if possible"),
        "quitText" => _("Quit"),
        "removeChars" => _("Remove bad characters and quit"),
        "mruTitle" => _("Most recently used"),
        // TRANSLATORS: This is an abbreviation for "Most recently used"
        "mruAbbrev" => _("MRU"),
        "helpUrl" => get_faq_url("prooffacehelp.php"),
        "help" => _("Help"),
        "guidelines" => _('Guidelines'),
    ]);

    return "var proofText = $texts;";
}
