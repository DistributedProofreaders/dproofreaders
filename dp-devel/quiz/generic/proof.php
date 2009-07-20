<?php
$relPath='../../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

$page_id = get_enumerated_param($_REQUEST, 'type', NULL, $valid_page_ids);
$quiz_id = get_enumerated_param($_REQUEST, 'quiz_id', NULL, $valid_quiz_ids);

include "./data/qd_${page_id}.inc";

// Figure out what font to use
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="HTML Tidy, see www.w3.org">
<script type='text/javascript'>
s = "<?php
if ($testing)
echo str_replace("\n",'\n',addslashes($solutions[0]));?>";
</script>
<title></title>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body bgcolor='#ffffff'>
<form action="./returnfeed.php?type=<?php echo $page_id; ?>&quiz_id=<?php echo $quiz_id; ?>" target="right" method="post">
<textarea rows="12" cols="60" name="output" id='output' wrap="off"
<?php 
    echo "style='";
    if ( $font_face != '' && $font_face != BROWSER_DEFAULT_STR )
    {
        echo "font-family: $font_face;";
        echo " ";
    }
    if ( $font_size != '' && $font_size != BROWSER_DEFAULT_STR )
    {
        echo "font-size: $font_size;";
    }
    echo "'";
?>
>
<?php echo $ocr_text; ?>
</textarea> <p>
<input type="submit" value="Check">
<input type="reset" value="Restart"></form>

<a href='#' onclick="document.forms[0].elements['output'].value=s" accesskey='`'></a>

</body>
</html>
