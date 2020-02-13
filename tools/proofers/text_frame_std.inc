<?php
include_once($relPath.'button_defs.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc');
include_once('preview.inc');
include_once('codepoint_validator.inc');

function echo_text_frame_std( $ppage )
{
    global $code_url;
    
    $json_code_points = get_json_codepoints($ppage->projectid());
    $switch_confirm = javascript_safe(_('Are you sure you want to save the current page and change layout?'));
    $revert_confirm = javascript_safe(_("Are you sure you want to save the current page and revert to the original text for this round?"));

    $header_args = array(
        "css_files" => array("$code_url/styles/preview.css"),
        "js_files" => array(
            "$code_url/tools/proofers/preview.js",
            "$code_url/tools/proofers/previewControl.js",
            "$code_url/tools/proofers/preview_strings.php",
            "$code_url/tools/proofers/character_test.js",
            "$code_url/tools/proofers/codepoint_validator.js",
        ),
        "js_data" => "
        function ldAll() {
            top.initializeStuff(0);
            previewControl = initPrev();
        }
        var codePoints = $json_code_points;
        var standardInterface = true;
        var switchConfirm = '$switch_confirm';
        var revertConfirm = '$revert_confirm';
        ",
        "body_attributes" => "onload='ldAll()'  onresize='previewControl.adjustHeight()' class='no-margin'",
        );
    slim_header(_("Text Frame"), $header_args);
    ?>
    <form name="editform" id="editform" method="post" action="processtext.php" target="proofframe">
    <div style = 'height: 100vh'>
    <?php
    output_preview_div();
    render_validator(); ?>
    <div id = 'proofdiv' class='center-align'>
        <?php

        $ppage->echo_proofing_textarea();

        echo "<br>\n";

        $ppage->echo_hidden_fields();

        echo_button(SAVE_AS_IN_PROGRESS,'s');
        echo_button(SAVE_AS_DONE_AND_PROOF_NEXT,'s');
        echo_button(SAVE_AS_DONE_AND_QUIT,'s');
        echo_button(QUIT,'s');

        echo "<br>\n";

        echo_button(CHANGE_LAYOUT,'s');
        echo_button(SHOW_ALL_TEXT,'s');
        echo_button(RETURN_PAGE,'s');
        echo_button(REPORT_BAD_PAGE,'s');

        echo "<br>\n";

        echo_button(SPELL_CHECK,'s');
        echo_button(PREVIEW, 's');

        echo "<br>\n";

        $ppage->echo_info();

        echo "&nbsp;";

        $comments_url = $ppage->url_for_project_comments(TRUE);
        echo _("View").": <a href=\"$comments_url\" title=\"". attr_safe(_("View Project Comments in New Window"))."\" target=\"viewcomments\">"._("Project Comments")."</a> ";

        $image_url = $ppage->url_for_display_image(TRUE);
        echo "| <a href=\"$image_url\" title=\"". attr_safe(_("View Image in New Window"))."\" target=\"lg_image\">"._("Image")."</a> ";

        echo "<br>\n";

        echo _("Image Resize") . ":";
        ?>
        <input title="<?php echo attr_safe(_("Zoom Out 25%")); ?>" id="zoomout_button" type="button" value="-25%" onclick="top.reSizeRelative(0.75)?true:this.disabled=true;">
        <input title="<?php echo attr_safe(_("Zoom In 25%")); ?>" type="button" value="+25%" onclick="top.reSizeRelative(1.25); document.getElementById('zoomout_button').disabled=false;">
        <input title="<?php echo attr_safe(_("Zoom to Original Size")); ?>" type="button" value="<?php echo attr_safe(_("Original")); ?>" onclick="top.reSizeRelative(-1); document.getElementById('zoomout_button').disabled=false;">

      </div> <!-- proofdiv -->
    </div>
    </form>
    <?php
}
// vim: sw=4 ts=4 expandtab
