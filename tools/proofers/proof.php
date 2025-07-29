<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'pi_structure.inc');
include_once($relPath.'DPage.inc');  // PAGE_BADNESS_REASONS

require_login();

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

$title = _("Proofreading Interface");

$header_args = [
    "js_modules" => [
        "$code_url/tools/proofers/proof.js",
    ],
    "js_files" => [
        "$code_url/scripts/misc.js",
        "$code_url/node_modules/katex/dist/katex.min.js",
        "$code_url/node_modules/quill/dist/quill.js",
    ],
    "css_files" => [
        "$code_url/styles/struct.css",
        "$code_url/node_modules/katex/dist/katex.min.css",
        "$code_url/node_modules/quill/dist/quill.core.css",
    ],
    "body_attributes" => 'class="no-margin overflow-hidden fix-full"',
];

slim_header($title, $header_args);

$forum_url_encoded = json_encode(get_url_for_forum());

echo "
<div id='proofreading_interface' data-forum_url='$forum_url_encoded' class='column-flex'>
    <div class='fixed-box default-border' id='page_control'>
        <div class='row_flex'>
            <span class='stretch-box margin_a' id='project_title'>
            </span>
            <span class='fixed-box'>
                <span id='page_number'></span>
                <a id=view_other_pages target='lg_image'>", _("View other pages"), "</a>
            </span>
        </div>
        <div class='row_flex'>
            <span class='stretch-box margin_a'>
                <a id='project_page' target='project-comments' title='", _("View Project Comments in New Window"), "'>" . _("Project Page") . "</a>
            </span>
            <span class='fixed-box'>
                <a id=editing_guidelines target='roundDoc'>", _('Guidelines'), "</a>
                <a target='viewcomments' href = '../../faq/prooffacehelp.php'>" . _('Interface Help') . "</a>
            </span>
        </div>
        <div id='action_buttons'>",
action_button('save_button', _("Save")),
action_button('exit_button', _("Exit")),
action_button('done_and_exit_button', _("Done & Exit")),
action_button('done_and_next_button', _("Done & Next")),
action_button('revert_to_original_button', _("Revert to Original")),
action_button('revert_to_saved_button', _("Revert to Saved")),
action_button('abandon_button', _("Abandon")),
action_button('report_bad_button', _("Report Bad Page")),
"</div>";

// report bad page -- begin
echo "<div id='bad_page_report' style='display:none;'>";

echo "<h1>" . _("Report Bad Page") . "</h1>";
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

echo "<p><b>" . _("Reason") . ":</b>
<select id='reason_selector' class='margin_a'>";
echo "<option disabled selected value=''>", _("Please select a reason"), "</option>";
foreach ($PAGE_BADNESS_REASONS as $reason) {
    echo "<option value='" . $reason["name"] . "'>" . $reason["string"] . "</option>";
}
echo "</select>",
action_button('submit_bad_report', _("Submit")),
action_button('cancel_bad_report', _("Cancel")),
"</div>";
// report bad page -- end

echo "
</div>
<div class='stretch-box' id='image_text'>
<div id='image_container' class='column-flex'></div>
<div id='text_div' class='column-flex'></div>
</div>";
draw_toolbox();
echo "</div>";
