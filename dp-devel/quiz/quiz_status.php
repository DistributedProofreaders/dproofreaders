<?
$relPath = "../../pinc/";
include_once($relPath."quizzes.inc");
include_once($relPath."theme.inc");
include_once($relPath."dp_main.inc");
# theme('','');
$theme_args = array("css_data" => "
th {background: $theme[color_headerbar_bg];
    color: $theme[color_headerbar_font];}
    
.no {background: #FF5555;}
.yes {background: #55FF55;}

");
theme('My Quiz Status','header',$theme_args);

echo "<br />";
foreach ($defined_quizzes as $quiz)
{
  $title = ${$quiz}->name;
  echo "<p><table border='1'><tr><th colspan='2'>$title</th></tr>";
  $pages = ${$quiz}->pages;
  foreach ($pages as $page)
  {
    echo "<tr><td>$page <a href='generic/main.php?type=$page'>(attempt this page)</a></td>";
    $passed = user_has_passed_quiz_page($pguser, $page);
    $text = $passed ? _("Passed") : _("Not passed");
    $cellbits = $passed ? " class='yes'" : " class='no'";
    echo "<td{$cellbits}>$text</td></tr>";

  }
  $total_pass = ${$quiz}->user_has_passed($pguser);
  $text = $total_pass ? _("Quiz passed") : _("Quiz not passed");
  $cellbits = $total_pass ? " class='yes'" : " class='no'";
  echo "<tr><td colspan='2' $cellbits style='font-weight: bold;'>$text</td></tr></table></p>";
}

theme('','footer')
?>
