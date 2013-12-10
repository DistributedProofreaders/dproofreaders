<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param
include_once($relPath.'prefs_options.inc'); // $f_f $f_s

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'type');

include "./quiz_page.inc"; // qp_initial_page_text qp_sample_solution

// Figure out what font to use
if ($user_is_logged_in)
{
    // Use the font prefs for the user's default interface layout, 
    // since they're more likely to have set those prefs
    global $userP;

    if ( $userP['i_layout']==1 )
    {
        $font_face_i = $userP['v_fntf'];
        $font_size_i = $userP['v_fnts'];    
    }
    else
    {
        $font_face_i = $userP['h_fntf'];
        $font_size_i = $userP['h_fnts'];
    }
    $font_face = $f_f[$font_face_i];
    $font_size = $f_s[$font_size_i];

    $font_settings = '';
    if ( $font_face != '' && $font_face != BROWSER_DEFAULT_STR )
    {
        $font_settings .= "font-family: $font_face;";
        $font_settings .= " ";
    }
    if ( $font_size != '' && $font_size != BROWSER_DEFAULT_STR )
    {
        $font_settings .= "font-size: $font_size;";
    }
}
else
{
    $font_settings = '';
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="HTML Tidy, see www.w3.org">
<title></title>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo "$charset";?>">
</head>
<body bgcolor='#ffffff' onload='top.initializeStuff(1)'>
<form action="./returnfeed.php?type=<?php echo $quiz_page_id; ?>" target="right" method="post" name="editform" id="editform">
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
        if("UTF-8" != strtoupper($charset))
        {
            $solution = iconv("UTF-8", $charset, $solution);
        }

        echo "<textarea name='cheat_text' style='display: none;'>\n";
        echo htmlspecialchars($solution, ENT_NOQUOTES);
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

</body>
</html>
