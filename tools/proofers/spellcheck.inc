<?php
$relPath = "./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'slim_header.inc');
include_once($relPath.'links.inc'); // new_window_link
include_once($relPath.'misc.inc'); // javascript_safe, attr_safe
include_once($relPath.'faq.inc');
include_once('spellcheck_text.inc');
include_once('PPage.inc');
include_once('image_block_enh.inc');
include_once("proof_image.inc");
include_once("std_view.inc");

// text_data should be set before we get here -- should take place in processtext.php

$revert_text = $_POST['revert_text'] ?? $text_data;

$is_header_visible = array_get($_SESSION, "is_header_visible", 1);

$project = new Project($ppage->projectid());
$valid_character_pattern = build_character_regex_filter($project->get_valid_codepoints(), "js");

$keep_corrections = _("Keep corrections and return to proofreading this page");
$rerun = _("Check page against an additional language");

$word_check_messages = json_encode([
    "disableAwLabel" => _("Word has been edited; unable to Suggest"),
    "enableAwLabel" => _("Unflag All & Suggest Word"),
    "confirmExitString" => _("Changes have been made. OK to quit without saving?"),
    "pageChangedError" => _("Cannot save page from WordCheck. Words have been modified."),
    "badCharsError" => _("There are invalid characters in the page"),
    "keepCorrectons" => $keep_corrections,
    "rerun" => $rerun,
]);

$user = User::load_current();

$js_files = [
    "$code_url/tools/proofers/wordcheck.js",
    "$code_url/scripts/character_test.js",
    "$code_url/scripts/control_bar.js",
    "$code_url/tools/proofers/proof_image.js",
];

if ($user->profile->i_type == 0) {
    $js_files[] = "$code_url/scripts/splitControl.js";
    $js_files[] = "proof_std.js";
}

$header_args = [
    "js_files" => $js_files,
    "js_data" => get_page_js() .
        get_control_bar_texts() . get_image_data($ppage) . get_std_view_data()
        . "
        var validCharacterPattern = '$valid_character_pattern';
        var wordCheckMessages = $word_check_messages;
    ",

    "body_attributes" => 'style="margin: 0;" id="wordcheck_interface"  class="fix-full" onload="ldAll()"',
];

if ($user->profile->i_type == 1) {
    $header_args["css_data"] = ibe_get_styles();
}

slim_header(_("WordCheck"), $header_args);

// print basic image html
if ($user->profile->i_type == 1) {
    ibe_echo_block();
    echo '<div id="controlframe">';
} else {
    echo_std_view();
}
?>
<form name="spcorrects" action="processtext.php" method="POST">
<?php
    // change all EOL characters to [lf]
    $revert_text = str_replace(["\r", "\n\n", "\n"], ["\n", "[lf]", "[lf]"], $revert_text);

    echo "<input type='hidden' name='revert_text' value='" . attr_safe($revert_text) . "'>\n";
    echo "<input type='hidden' id='is_changed' name='is_changed' value='$is_changed'>\n";
    echo "<input type='hidden' id='accepted_words' name=\"accepted_words\" value='"
        . attr_safe(implode(' ', $accepted_words)) . "'>\n";
    echo "<input type='hidden' id='is_header_visible' name=\"is_header_visible\" value='$is_header_visible'>\n";

    // run the text through the spellcheck - returns the form contents and a list of languages the page was checked against
    [$page_contents, $languages, $messages] =
        spellcheck_text($text_data, $ppage->projectid(), $ppage->imagefile(), $aux_language, $accepted_words);

    // start the div containing the link to show/hide the WordCheck header
    echo "<div>";
    echo "<a id='wc_header_link' href='#' onClick='return toggleWCHeader();'>";
    if ($is_header_visible) {
        echo _("Hide WordCheck Header");
        $header_style = "";
    } else {
        echo _("Show WordCheck Header");
        $header_style = "style='display: none;'";
    }
    echo "</a>";
    echo "</div>";

    // start the div that consists of the WordCheck header
    // that can be toggled shown/hidden
    echo "<div id='wc_header' $header_style>";
    if (count($messages) != 0) {
        // warnings or errors were raised, print them out
        echo "<p class='warning'>" . _("The following warnings/errors were raised:") . "<br>\n";
        foreach ($messages as $message) {
            echo "$message<br>\n";
        }
        echo "</p>";
    }

    // start the first line of the WordCheck header
    // printing any dictionaries used
    echo "<p>";
    if (count($languages) > 0) {
        echo sprintf(_("Dictionaries used: <b>%s</b>."), implode(', ', $languages));
    } else {
        echo "<b>" . _("No check against a dictionary has been made.") . "</b>";
    }

    // for the vertical layout, stick in a line break
    // for the horizontal one, stick in a space
    if ($user->profile->i_layout == 1) {
        echo "<br>";
    } else {
        echo " ";
    }

    // output the code allowing the user to select another language
    echo _("Use additional: ");
    echo "<select name='aux_language'>";
    echo "<option value=''>" . _("Language") . "</option>\n";

    // get a list of languages with dictionaries installed on the system
    $dict_list = get_languages_with_dictionaries();
    asort($dict_list);
    foreach ($dict_list as $langcode => $language) {
        // skip the languages that we just used.
        if (in_array($language, $languages)) {
            continue;
        }
        echo "<option value='" .  attr_safe($language) . "'";
        if (strcmp($language, $aux_language) == 0) {
            echo " selected";
        }
        echo ">$language</option>\n";
    }
    echo "</select>";
?>
<input
    type="submit"
    name="rerunauxlanguage"
    id="rerunauxlanguage"
    value="<?php echo attr_safe(_("Check")); ?>"
    title="<?php echo attr_safe($rerun); ?>"
>
<br>
<?php
    // show help blurb on the UA&S icon
    echo sprintf(
        _("The %s icon accepts the word for this page and suggests it for the dictionary."),
        "<img src=\"$code_url/graphics/Book-Plus-Small.gif\" border=\"0\">"
    );
    echo "</p>";
    echo "</div>";
    // here ends the div for the WordCheck header

?>
<table id="tbtext">
<tr><td id="tdtop">
<?php
    // print the link to the WordCheck FAQ
    echo new_window_link(get_faq_url("wordcheck-faq.php"), _("WordCheck FAQ"));
?>
</td></tr>
<tr>
<td id="tdtext">
<?php
    $ppage->echo_hidden_fields();
    echo $page_contents;
?>
</td>
</tr>
<tr>
    <td id="tdtop">
        <input
            type="submit"
            name="spcorrect"
            id="spcorrect"
            value="<?php echo attr_safe(_("Submit Corrections")); ?>"
            title="<?php echo attr_safe($keep_corrections); ?>"
        >
        &nbsp;&nbsp;&nbsp;
        <input
            type="submit"
            name="spexit"
            value="<?php echo attr_safe(_("Quit WordCheck")); ?>"
            title="<?php echo attr_safe(_("Abandon corrections and return to proofreading this page")); ?>"
            onClick="return confirmExit();"
        >
        <input
            type="submit"
            name="spsaveandnext"
            id="spsaveandnext"
            value="<?php echo attr_safe(_("Save as 'Done' & Proofread Next Page")); ?>"
            title="<?php echo attr_safe(_("Save page as done and proofread the next available page")); ?>"
<?php
    if ($is_changed) {
        echo "            disabled\n";
    }
?>
        >
    </td>
</tr>
</table>
</form>
</div>
<?php
if ($user->profile->i_type != 1) {
    echo "</div>";
}
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_page_js()
{
    global $charset;

    $show_wc_header_label = javascript_safe(_("Show WordCheck Header"), $charset);
    $hide_wc_header_label = javascript_safe(_("Hide WordCheck Header"), $charset);

    return <<<PAGE_JS
                function ldAll() {
                    top.initializeStuff(2);
                }

                function toggleWCHeader() {
                    var header=document.getElementById('wc_header');
                    var headerLink=document.getElementById('wc_header_link');
                    var headerStateSave=document.getElementById('is_header_visible');

                    if(header) {
                        if(header.style.display=='') {
                            header.style.display='none';
                            headerLink.innerHTML='$show_wc_header_label';
                            headerStateSave.value=0;
                        } else{
                            header.style.display='';
                            headerLink.innerHTML='$hide_wc_header_label';
                            headerStateSave.value=1;
                        }
                    }
                    return false;
                }
        PAGE_JS;
}
