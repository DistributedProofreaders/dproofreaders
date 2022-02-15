<?php
include_once($relPath.'http_headers.inc');
include_once($relPath.'slim_header.inc');
include_once("proof_image.inc"); // get_image_data()
include_once("std_view.inc");

function echo_proof_frame_std($ppage)
{
    global $code_url;

    $project = new Project($ppage->projectid());
    $valid_character_pattern = build_character_regex_filter($project->get_valid_codepoints(), "js");
    $switch_confirm = json_encode(_('Are you sure you want to save the current page and change layout?'));
    $revert_confirm = json_encode(_("Are you sure you want to save the current page and revert to the original text for this round?"));

    $header_args = [
        "js_files" => [
            "$code_url/scripts/splitControl.js",
            "proof_std.js",
            "$code_url/tools/proofers/preview.js",
            "$code_url/tools/proofers/previewControl.js",
            "$code_url/scripts/character_test.js",
            "$code_url/scripts/text_validator.js",
            "$code_url/tools/proofers/proof_validate.js",
            "$code_url/tools/proofers/process_diacritcal_markup.js",
            "$code_url/scripts/control_bar.js",
            "$code_url/tools/proofers/proof_image.js",
        ],
        "css_files" => ["$code_url/styles/preview.css"],
        "js_data" => get_preview_js_vars() . get_preview_font_data_js()
        . get_control_bar_texts() . get_image_data($ppage) . get_std_view_data()
        . "function ldAll() {
            top.initializeStuff(0);
        }
        var validCharacterPattern = '$valid_character_pattern';
        var switchConfirm = '$switch_confirm';
        var revertConfirm = '$revert_confirm';
        ",
        "body_attributes" => "style='margin: 0; overflow: hidden;' onload='ldAll()' class='fix-full'",
    ];

    slim_header(_("Proofing"), $header_args);

    echo_std_view();
    echo_text_area($ppage);
    echo "</div>\n";
    echo "</div>\n";
}

function echo_text_area($ppage)
{
    echo "<form name='editform' id='editform' method='post' action='processtext.php'>";
    echo "<div>";
    output_preview_div();
    echo "<div class='nodisp full-height' id='validator'>";
    render_validator();
    echo "</div>";
    echo "<div id = 'proofdiv' class='center-align'>";
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
    echo "| <a href=\"$image_url\" title=\"". attr_safe(_("View Image in New Window"))."\" target=\"lg_image\">"._("Image")."</a> ";

    echo "</div>"; // proofdiv
    echo "</div>";
    echo "</form>";
}
