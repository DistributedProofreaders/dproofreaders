<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'quizzes.inc');

// From theme.inc
html_header('');

$current_quiz = $_REQUEST['quiz_id'];
$quiz_name = ${$current_quiz}->name;

echo "<table cellspacing=0 cellpadding=0 width='100%'>";
echo "<tr width='100%'>";
echo "<td width='100%' bgcolor='".$theme['color_logobar_bg']."'>\n";
echo "<a href='$code_url' target='_top'><img src='$code_url/pinc/templates/".$theme_name."".$theme['image_logo']."' width='360' height='68' alt='Distributed Proofreaders' border='0'></a>\n";
echo "</td>";
echo "</tr>";
echo "</table>";
// Close table, and to prevent weird spacing, start a new table.

$font_face  = $theme['font_headerbar'];
$font_color = $theme['color_headerbar_font'];
$bgcolor    = $theme['color_headerbar_bg'];

echo "<table cellspacing=0 cellpadding=0 width='100%'>";
echo "<tr>";
echo "<td width='20%' align='left' bgcolor='$bgcolor'>\n";
echo "<a href='$code_url/default.php' target='_top'>\n";
echo "<font face='$font_face' size='1' color='$font_color'>DP</font></a>\n";
echo "<font face='$font_face' size='1' color='$font_color'> &#187; $quiz_name</font>\n";
echo "</td>\n";
echo "<td /* width='50%' */ align='right' bgcolor='$bgcolor'>\n";
echo "<a href='$code_url/activity_hub.php' target='_top'><font face='$font_face' size='1' color='$font_color'>" . _("Activity Hub") . "</font></a>";
echo "<font face='$font_face' size='1' color='$font_color'>&nbsp;<b>&#183;</b>&nbsp;</font>";
if ( $current_quiz == "FQ" ) {
  echo "<a href='$code_url/faq/document.php' target='_top'><font face='$font_face' size='1' color='$font_color'>" . _("Formatting Guidelines") . "</font></a>";  
}
elseif ( $current_quiz == "PQ" ) {
  echo "<a href='$code_url/faq/proofreading_guidelines.php' target='_top'><font face='$font_face' size='1' color='$font_color'>" . _("Proofreading Guidelines") . "</font></a>";
}
else {
  echo "<a href='$code_url/faq/faq_central.php' target='_top'><font face='$font_face' size='1' color='$font_color'>" . _("Help") . "</font></a>";
}
echo "</td>";
echo "</tr>";
echo "</table>";
?>
