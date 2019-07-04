<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param
include_once($relPath.'prefs_options.inc'); // get_user_proofreading_font()

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'quiz_page_id');

include "./quiz_page.inc"; // qp_initial_page_text qp_sample_solution

// Figure out what font to use
if ($user_is_logged_in)
{
    list($font_face, $font_size) = get_user_proofreading_font();

    $font_settings = 'font-family: ';
    if ( $font_face != '' )
    {
        $font_settings .= "$font_face, ";
    }
    $font_settings .= get_proofreading_font_family_fallback() . "; ";
    if ( $font_size != '' )
    {
        $font_settings .= "font-size: $font_size;";
    }
}
else
{
    $font_settings = '';
}

$header_args = array(
    'body_attributes' => "onload='top.initializeStuff(1)'",
);

slim_header("", $header_args);
?>
<form action="./returnfeed.php?quiz_page_id=<?php echo $quiz_page_id; ?>" target="right" method="post" name="editform" id="editform">
<textarea rows="12" cols="60" name="text_data" id="text_data" wrap="off"
    style='width:100%; <?php echo $font_settings; ?>'>
<?php echo qp_initial_page_text(); ?>
</textarea> <p>
<input type="submit" value="<?php echo _("Check"); ?>">
<input type="reset" value="<?php echo _("Restart"); ?>">
<?php
    if ($testing)
    {
        $solution = qp_sample_solution();

        echo "<textarea name='cheat_text' style='display: none;' disabled>\n";
        echo html_safe($solution);
        echo "</textarea>\n";

        $onclick = 'document.forms[0].text_data.value = document.forms[0].cheat_text.value;';
        echo "<input type='button' value='". _("Cheat!") ."' onclick='$onclick'>\n";
        echo "<span style='color: red;'>";
        echo _("(This button is present only during testing.)");
        echo "</span>\n";
    }
?>
</p>
</form>
