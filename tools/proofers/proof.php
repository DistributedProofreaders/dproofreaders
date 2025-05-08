<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'widget_text.inc'); // $widget_text
include_once($relPath.'bad_page_instructions.inc');
include_once($relPath.'draw_toolbox.inc');

require_login();

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

$title = _("Proof test");

$js_files = [
    "$code_url/pinc/3rdparty/xregexp/xregexp-all.js",
    "$code_url/scripts/splitControl.js",
    "$code_url/scripts/image_widget.js",
    "$code_url/scripts/view_splitter_2b.js",
    "$code_url/scripts/misc.js",
    "$code_url/scripts/validator.js",
    "$code_url/scripts/analyse_format.js",
    "$code_url/scripts/show_format.js",
    "$code_url/scripts/word_check.js",
    "$code_url/scripts/text_widget.js",
    "$code_url/scripts/toolbox.js",
    "$code_url/tools/proofers/proof.js",
    "$code_url/pinc/3rdparty/quill/quill.js",
    "$code_url/pinc/3rdparty/katex/katex.min.js",
    // "https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js",
    // "https://cdn.jsdelivr.net/npm/katex@0.16.21/dist/katex.min.js",
];

$header_args = [
    "css_files" => [
        "$code_url/styles/struct.css",
        "$code_url/pinc/3rdparty/quill/quill.core.css",
        "$code_url/pinc/3rdparty/katex/katex.min.css",
        // "https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.core.css",
        // "https://cdn.jsdelivr.net/npm/katex@0.16.21/dist/katex.min.css",
    ],
    "js_files" => $js_files,
    "js_data" => "var langCode = '" . substr(get_desired_language(), 0, 2) . "';",
    "body_attributes" => 'class="no-margin overflow-hidden fix-full"',
];

slim_header($title, $header_args);

$proof_text =
[
    "adjustLineSpace" => _("Adjust the line spacing"),
    "noStartTag" => _("No start tag for this end tag"),
    "noEndTag" => _("No end tag for this start tag"),
    "noEndTagInPara" => _("No corresponding end tag in paragraph"),
    "misMatchTag" => _("End tag does not match start tag"),
    "nestedTag" => _("Tag nested within same tag"),
    "unRecTag" => _("Unrecognized tag"),
    "charBefore" => _("No characters should precede this"),
    "blankBefore" => _("A blank line should precede this"),
    "blankAfter" => _("A blank line should follow %s"),
    "NWinNW" => _("No-wrap inside no-wrap"),
    "BQinNW" => _("Block quote inside no-wrap"),
    "charAfter" => _("No characters should follow %s"),
    "OolPrev" => _("Out-of-line start tag should not be preceded by normal text"),
    "OolNext" => _("Out-of-line end tag should not be followed by normal text"),
    "blankLines124" => _("Only 1, 2 or 4 blank lines should be used"),
    "puncAfterStart" => _("Punctuation after start tag"),
    "spaceAfterStart" => _("Space after start tag"),
    "nlAfterStart" => _("Newline after start tag"),
    "nlBeforeEnd" => _("Newline before end tag"),
    "spaceBeforeEnd" => _("Space before end tag"),
    "noBold" => _("Heading should not be entirely bold"),
    "scNoCap" => _("Small caps must contain at least one upper case character"),
    "charBeforeStart" => _("Character or punctuation before inline start tag"),
    "charAfterEnd" => _("Character after inline end tag"),
    "puncBEnd" => _(", ; or : before end tag"),
    "noCloseBrack" => _("No matching closing bracket"),
    "footnoteId" => _("Footnote identifier should be a letter or number"),
    "starAnchor" => _("Footnote anchor should be an upper-case letter"),
    "noFootnote" => _("No corresponding footnote on this page"),
    "noAnchor" => _("No anchor for this footnote"),
    "noColon" => _("Footnote must have a colon"),
    "colonNext" => _("The colon should immediately follow *[Footnote"),
    "spaceNext" => _("Footnote should be followed by one space and identifier"),
    "dupNote" => _("Duplicate footnote identifier"),
    "continueFirst" => _("Continuation footnote should precede others"),
    "sideNoteBlank" => _("A blank line should precede and follow Sidenote"),
    "emptyTag" => _("Empty tag"),
    "multipleAnchors" => _("Multiple anchors for same footnote"),
    "boldPara" => _("Entirely bold paragraph or section heading"),
    "validate" => _("Validate"),
    "colorMarkup" => _("Color markup"),
    "hideTags" => _("Hide tags"),
    "quitFP" => _("Quit Format Preview"),
    "quitWC" => _("Quit Word Check"),
    "languages" => _("Languages"),
    "ok" => _("OK"),
    "textOnly" => _("Text Only"),
    "formatPreview" => _("Format Preview"),
    "wordCheck" => _("Word Check"),
    "scrollWithText" => _("Scroll with Text"),
    "selectAReason" => _("Please select a reason"),
    "noUser" => pgettext("no user", "none"),
    "forumURL" => get_url_for_forum(),
    "options" => _("Options"),
    "previewMath" => _("Preview Math"),
    "allowUnderline" => _("Allow <u> for underline"),
    "qStopProof" => _("Are you sure you want to stop proofreading?"),
    "qRevert" => _("Are you sure you want to revert to your last save?"),
    "qReturn" => _("This will discard all changes you have made on this page. Are you sure you want to return this page to the current round?"),
    "accept" => _("Accept"),
];

$proof_text = json_encode($proof_text);

function echo_bad_page_report()
{
    global $PAGE_BADNESS_REASONS;

    echo "<div id='bad_page_report' style='display:none;'>";
    echo_bad_page_instructions();
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
}

echo "
<div id='proofreading_interface' data-proof_text='$proof_text' data-widget_text ='$widget_text' class='column-flex'>
    <div class='fixed-box border_1' id='page_control'>
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
                <a target='viewcomments' href = '$code_url/faq/prooffacehelp.php'>" . _('Interface Help') . "</a>
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
echo_bad_page_report();
echo "
</div>
<div class='stretch-box' id='image_text'>
<div id='image_container' class='column-flex'></div>
<div id='text_div' class='column-flex'></div>
</div>";
draw_toolbox();
echo "</div>";
