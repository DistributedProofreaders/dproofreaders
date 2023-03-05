<?php
include_once($relPath.'button_defs.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'codepoint_validator.inc');
include_once('preview.inc');

function echo_text_frame_std($ppage)
{
    global $code_url;

    $project = new Project($ppage->projectid());
    $valid_character_pattern = build_character_regex_filter($project->get_valid_codepoints(), "js");
    $switch_confirm = json_encode(_('Are you sure you want to save the current page and change layout?'));
    $revert_confirm = json_encode(_("Are you sure you want to save the current page and revert to the original text for this round?"));

    $header_args = [
        "css_files" => ["$code_url/styles/preview.css"],
        "js_files" => [
            "$code_url/tools/proofers/preview.js",
            "$code_url/tools/proofers/previewControl.js",
            "$code_url/scripts/character_test.js",
            "$code_url/scripts/text_validator.js",
            "$code_url/tools/proofers/proof_validate.js",
            "$code_url/tools/proofers/process_diacritcal_markup.js",
            "$code_url/tools/proofers/dp_proof.js",
        ],
        "js_data" => get_preview_js_vars() . get_preview_font_data_js() . "
        function ldAll() {
            top.initializeStuff(0);
        }
        var validCharacterPattern = '$valid_character_pattern';
        var standardInterface = true;
        var switchConfirm = '$switch_confirm';
        var revertConfirm = '$revert_confirm';
        ",
        "body_attributes" => "id='standard_interface_text' onload='ldAll()'",
    ];
    slim_header(_("Text Frame"), $header_args); ?>
    <form name="editform" id="editform" method="post" action="processtext.php" target="proofframe" 'onsubmit'="return submitForm()">
    <div style = 'height: 100vh'>
    <?php
    output_preview_div();
    echo "<div class='nodisp full-height' id='validator'>";
    render_validator();
    echo "</div>"; ?>
    <div id = 'proofdiv' class='center-align'>
        <?php

        $ppage->echo_proofing_textarea();

    echo "<br>\n";

    $ppage->echo_hidden_fields();

    echo_button(SAVE_AS_IN_PROGRESS, 's');
    echo_button(SAVE_AS_DONE_AND_PROOF_NEXT, 's');
    echo_button(SAVE_AS_DONE_AND_QUIT, 's');
    echo_button(QUIT, 's');

    echo "<br>\n";

    echo_button(CHANGE_LAYOUT, 's');
    echo_button(SHOW_ALL_TEXT, 's');
    echo_button(RETURN_PAGE, 's');
    echo_button(REPORT_BAD_PAGE, 's');

    echo "<br>\n";

    echo_button(SPELL_CHECK, 's');
    echo_button(PREVIEW, 's');

    echo "<br>\n";

    $ppage->echo_info();

    echo "&nbsp;";

    $comments_url = $ppage->url_for_project_comments(true);
    echo _("View").": <a href=\"$comments_url\" title=\"". attr_safe(_("View Project Comments in New Window"))."\" target=\"viewcomments\">"._("Project Comments")."</a> ";

    $image_url = $ppage->url_for_display_image(true);
    echo "| <a href=\"$image_url\" title=\"". attr_safe(_("View Image in New Window"))."\" target=\"lg_image\">"._("Image")."</a> "; ?>

      </div> <!-- proofdiv -->
    </div>
    </form>
    <?php
}
