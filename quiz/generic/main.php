<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'pi_structure.inc');

require_login();

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'quiz_page_id');

include "./quiz_page.inc"; // qp_round_id_for_pi_toolbox

$header_args = [
    'js_modules' => [
        "$code_url/scripts/quiz.js",
    ],
    'js_files' => [
        "$code_url/node_modules/quill/dist/quill.js",
    ],
    "css_files" => [
        "$code_url/styles/struct.css",
        "$code_url/node_modules/quill/dist/quill.core.css",
    ],
    "body_attributes" => 'class="no-margin overflow-hidden fix-full"',
];

// $browser_title is from qd file which is included via quiz_page.inc
slim_header($browser_title, $header_args);

$quiz_char_suites = new QuizCharSuites();
$quiz_pickersets = json_encode($quiz_char_suites->get_verbose_pickersets(), JSON_HEX_APOS);
$text = attr_safe(qp_initial_page_text());
$round_type = attr_safe(qp_round_id_for_pi_toolbox());
echo "
<div class='column-flex'>
    <div class='stretch-box row_flex'>
        <div id='quiz_left' class='column-flex'>
            <div id='quiz_image'>
                <div id='image_container'>
                    <img src='" . qp_page_image_path() . "' alt=''>
                </div>
            </div>
            <div class='column-flex' id='quiz_text_outer'>
                <div id='quiz_text' class='stretch-box' data-initial_text='$text' data-pickersets='$quiz_pickersets' data-round_type='$round_type'></div>
                <div id='quiz_controls' class='fixed_box'>

<form action='./returnfeed.php?quiz_page_id=$quiz_page_id' target='right' method='post' name='editform' id='editform'>
<input type='hidden' name='text_data' id='text_data'>
<input type='submit' class='margin_a' value='" . _("Check") . "'>
<input type='button' class='margin_a' id='restart' value='" . _("Restart") . "'>";

if (SiteConfig::get()->testing) {
    $solution = attr_safe(qp_sample_solution());
    echo "<input type='button' class='margin_a' id='cheat_button' data-cheat_text='$solution' value='". _("Cheat!") ."'>\n";
    echo "<span style='color: red;'>";
    echo _("(This button is present only during testing.)");
    echo "</span>\n";
}

echo"</form>
                </div>
            </div>
        </div>
        <div id='quiz_right'>
            <iframe name='right' width='100%' height='100%' src='right.php?quiz_page_id=$quiz_page_id'>
            </iframe>
        </div>
    </div>";
draw_toolbox();
echo "</div>";
