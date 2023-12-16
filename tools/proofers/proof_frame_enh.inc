<?php
include_once($relPath.'http_headers.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'slim_header.inc');
include_once($relPath.'codepoint_validator.inc');
include_once($relPath.'button_defs.inc');
include_once($relPath.'control_bar.inc'); // get_control_bar_texts()
include_once('PPage.inc');
include_once('image_block_enh.inc');
include_once('preview.inc');

function echo_proof_frame_enh($ppage)
{
    global $code_url;

    $project = new Project($ppage->projectid());
    $valid_character_pattern = build_character_regex_filter($project->get_valid_codepoints(), "js");
    $switch_confirm = json_encode(_('Are you sure you want to save the current page and change layout?'));
    $revert_confirm = json_encode(_("Are you sure you want to save the current page and revert to the original text for this round?"));

    $user = User::load_current();
    $storage_key = "proof-enh" . (($user->profile->i_layout == 1) ? "-v" : "-h");

    $image_data = json_encode([
        "imageUrl" => $ppage->url_for_image(),
        "storageKey" => $storage_key,
        "align" => "L",
    ]);

    $header_args = [
        "css_files" => ["$code_url/styles/preview.css"],
        "js_files" => [
            "$code_url/pinc/3rdparty/xregexp/xregexp-all.js",
            "$code_url/tools/proofers/preview.js",
            "$code_url/tools/proofers/previewStyles.js",
            "$code_url/tools/proofers/previewControl.js",
            "$code_url/scripts/character_test.js",
            "$code_url/scripts/text_validator.js",
            "$code_url/tools/proofers/proof_validate.js",
            "$code_url/tools/proofers/process_diacritcal_markup.js",
            "$code_url/scripts/control_bar.js",
            "$code_url/tools/proofers/proof_image.js",
        ],
        "js_data" => get_preview_js_vars() . get_preview_font_data_js() .
        get_control_bar_texts() . "
        var imageData = $image_data;
        function ldAll() {
            top.initializeStuff(1);
        }
        var validCharacterPattern = '$valid_character_pattern';
        var standardInterface = false;
        var switchConfirm = '$switch_confirm';
        var revertConfirm = '$revert_confirm';
        ",
        "css_data" => ibe_get_styles(),
        "body_attributes" => 'id="enhanced_interface" onload="ldAll()"',
    ];

    slim_header(_("Proofreading Page"), $header_args);

    ibe_echo_block(); ?>
    <div id="controlframe">
        <?php
        output_preview_div();
    echo "<div class='nodisp full-height' id='validator'>";
    render_validator();
    echo "</div>"; ?>
        <div id='proofdiv'>
            <form name="editform" id="editform" method="POST" action="processtext.php" onsubmit="return top.submitForm(this)">
                <?php $ppage->echo_hidden_fields(); ?>
                <table id="tbtext">
                    <tr>
                        <td id="tdtop">
                            <?php echo_button_menu($ppage); ?>
                        </td>
                    </tr>
                    <tr>
                        <td id="tdtext">
                            <?php $ppage->echo_proofing_textarea(); ?>
                        </td>
                    </tr>
                    <tr>
                        <td id="tdbottom">
                            <?php $ppage->echo_info(); ?>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <?php
}

function echo_button_menu($ppage)
{
    $user = User::load_current();
    $proofreading_font_faces = get_available_proofreading_font_faces();
    $proofreading_font_sizes = get_available_proofreading_font_sizes();
    $font_family_fallback = get_proofreading_font_family_fallback();

    $title = attr_safe(_("Change Font Face"));
    echo "<select name='fntFace' ID='fntFace' class='dropsmall' title='$title'
        onChange='top.changeFontFamily(this.options[this.selectedIndex].value,
            this.options[this.selectedIndex].text, \"$font_family_fallback\")'>";
    [$current_font, $current_size] = get_user_proofreading_font();
    foreach ($proofreading_font_faces as $index => $font) {
        if ($index == 0) {
            $font = BROWSER_DEFAULT_STR;
        }
        if ($index == 1) {
            $font = get_user_proofreading_font_other();
            if (!$font) {
                continue;
            }
        }

        $optP = "<option \r\n value=\"$index\"";
        if ($font == $current_font) {
            $optP .= " selected";
        }
        $optP .= ">" . html_safe($font) . "</option>";
        echo $optP;
    }
    echo "</select>";

    $title = attr_safe(_("Change Font Size"));
    echo "<select name='fntSize' ID='fntSize' class='dropsmall' title='$title'
        onChange='top.changeFontSize(this.options[this.selectedIndex].value,
            this.options[this.selectedIndex].text)'>";
    foreach ($proofreading_font_sizes as $index => $size) {
        if ($size == '') {
            $size = BROWSER_DEFAULT_STR;
        }

        $optP = "<option \r\n value=\"$index\"";
        if ($size == $current_size) {
            $optP .= " selected";
        }
        $optP .= ">$size</option>";
        echo $optP;
    }
    echo "</select>";
    echo "\n";

    echo_button(REVERT_TO_ORIGINAL, 'a');
    echo "\n";

    if ($ppage->can_be_reverted_to_last_save()) {
        echo_button(REVERT_TO_LAST_SAVE, 'a');
    } else {
        echo_img(REVERT_TO_LAST_SAVE_DISABLED, 'a');
    }

    echo "<a href='#'' onclick='top.showNW()'>";
    echo_img(SHOW_ALL_TEXT, 'a');
    echo "</a>";

    echo "<a href='#' onclick='previewControl.show()'>";
    echo_img(PREVIEW, 'a');
    echo  "</a>";

    $url = $ppage->url_for_project_comments(true);
    echo "<a href='$url' TARGET='viewcomments'>";
    echo_img(PROJECT_COMMENTS, 'a');
    echo "</a>";

    echo_button(SPELL_CHECK, 'a');
    echo "\n";
    echo_button(CHANGE_LAYOUT, 'a');
    echo "\n";
    echo_button(RETURN_PAGE, 'a');
    echo "\n";

    echo_button(REPORT_BAD_PAGE, 'a');
    echo "\n";

    echo_button(SAVE_AS_IN_PROGRESS, 'a');
    echo "\n";

    echo_button(SAVE_AS_DONE_AND_PROOF_NEXT, 'a');
    echo "\n";

    echo_button(SAVE_AS_DONE_AND_QUIT, 'a');
    echo "\n";
    echo_button(QUIT, 'a');
}
