<?php
include_once($relPath.'http_headers.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'slim_header.inc');
include_once($relPath.'codepoint_validator.inc');
include_once('PPage.inc');
include_once('image_block_enh.inc');
include_once('button_menu.inc');
include_once('preview.inc');

function echo_proof_frame_enh( $ppage )
{
    global $code_url, $userP;

    $project = new Project($ppage->projectid());
    $codepoint_regex_class = javascript_safe(build_codepoint_regex_filter($project->get_valid_codepoints()));
    $switch_confirm = javascript_safe(_('Are you sure you want to save the current page and change layout?'));
    $revert_confirm = javascript_safe(_("Are you sure you want to save the current page and revert to the original text for this round?"));

    $header_args = array(
        "css_files" => array("$code_url/styles/preview.css"),
        "js_files" => array(
            "$code_url/tools/proofers/preview.js",
            "$code_url/tools/proofers/previewControl.js",
            "$code_url/tools/proofers/preview_strings.php",
            "$code_url/scripts/character_test.js",
            "$code_url/scripts/text_validator.js",
            "$code_url/tools/proofers/proof_validate.js",
            "$code_url/tools/proofers/process_diacritcal_markup.js",
        ),
        "js_data" => ibe_get_js() . "
        function ldAll() {
            top.initializeStuff(1);
            previewControl = initPrev();
        }
        var codePointRegexCharClass = \"$codepoint_regex_class\";
        var standardInterface = false;
        var switchConfirm = '$switch_confirm';
        var revertConfirm = '$revert_confirm';
        ",
        "css_data" => ibe_get_styles(),
        "body_attributes" => 'id="enhanced_interface" onload="ldAll()" onresize="previewControl.adjustHeight()"',
    );

    slim_header(_("Proofreading Page"), $header_args);

    if ($userP['i_layout']==1)
        $iWidth=$userP['v_zoom'];
    else
        $iWidth=$userP['h_zoom'];
    $iWidth=round((1000*$iWidth)/100);

    ibe_echo_block( $ppage->url_for_image(TRUE), $iWidth );

    ?>
    <div id="controlframe">
        <?php
        output_preview_div();
        echo "<div class='nodisp' id='validator'>";
        render_validator();
        echo "</div>";
        ?>
        <div id='proofdiv'>
            <form name="editform" id="editform" method="POST" action="processtext.php">
                <?php $ppage->echo_hidden_fields(); ?>
                <table id="tbtext">
                    <tr>
                        <td id="tdtop">
                            <?php echo_button_menu( $ppage ); ?>
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

// vim: sw=4 ts=4 expandtab
