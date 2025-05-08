<?php
function echo_bad_page_instructions()
{
    echo "<p>" . _("If you are unable to proofread the page you were presented, you can mark it bad with this form to let the Project Manager know it requires attention. Before doing so, let's review what constitutes a bad page and some possible fixes you can try first.") . "</p>";

    echo "<h2>" . _("Commonly Misidentified Bad Pages") . "</h2>";
    echo "<p>" . sprintf(_("The following scenarios are commonly reported as bad pages, but they are not. If either of the below are true, hit '%s' below and continue proofreading the page."), _("Cancel")) . "</p>";
    echo "<ul>";
    echo "<li>" . _("<b>Blank image and text</b> - Books often have blank pages in them. If the image loads and is blank and there is no page text, this is a blank page, not a bad page. Please proofread it as a blank page per the guidelines.") . "</li>";
    echo "<li>" . _("<b>Garbled text</b> - Sometimes the optical character recognition (OCR) does a very poor job on an image and the text is more garbled than useful. Please treat these pages as type-ins and make the text match the image. These are frustrating, but not bad, pages.") . "</li>";
    echo "</ul>";

    echo "<h2>" . _("Issues and Possible Fixes") . "</h2>";
    echo "<ul>";
    echo "<li>" . sprintf(_("<b>%s</b> - If the page text loaded but no image is visible, it might be a missing image. Sometimes, the image may not show up due to technical problems with your browser. Saving the page as 'In Progress' and opening it back up can often resolve this issue. If this doesn't fix the problem, please report it as a bad page."), _("Missing Image")) . "</li>\n";
    echo "<li>" . sprintf(_("<b>%s</b> - If the image loads and has text on it, but no page text is visible it's a bad page."), _("Missing Text")) . "</li>\n";
    echo "<li>" . sprintf(_("<b>%s</b> - If the image loads and has text on it and in no way matches the text that was loaded, it's a bad page."), _("Image/Text Mismatch")) . "</li>\n";
    echo "<li>" . sprintf(_("<b>%s</b> - If the image loads but it looks corrupted in some way, as if the image was not saved correctly, it's a bad page."), _("Corrupted Image")) . "</li>\n";
    echo "</ul>";

    echo "<p>" . sprintf(_("Rarely there are other issues that could be considered a bad page, but please review the first section for misidentified pages before reporting them as <b>%s</b>."), _("Other")) . "</p>\n";

    echo "<h2>" . _("Submit a Bad Page Report") . "</h2>";
    echo "<p>" . sprintf(_("If you still think it is a bad page, please let us know by filling out the information below. If not, hit %s."), _("Cancel")) . "</p>\n";
}
