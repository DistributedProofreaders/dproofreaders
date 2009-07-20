<?php
$relPath='../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'quizzes.inc');
$db_Connection=new dbConnect();
$theme_args["css_data"] = "th.q {background:$theme[color_headerbar_bg];
color:$theme[color_headerbar_font];}";

if (!empty($_GET['show_only']) && (array_search($_GET['show_only'],$valid_quiz_ids) !== false))
{
    $quiz_id = $_GET['show_only'];
    theme(${$quiz_id}->name,'header',$theme_args);
    echo "<h1>".${$quiz_id}->name."</h1>";
    ${$quiz_id}->show_results_table($pguser);
}
else
{
  theme(_('Interactive Quizzes'),'header',$theme_args);
  echo "<h1>Interactive Quizzes</h1>
        <p>Welcome to $site_abbreviation's interactive quizzes! The following quizzes are available:</p>";
  foreach ($valid_quiz_ids as $quiz_id)
  {
    ${$quiz_id}->show_results_table($pguser);
  }
}

theme("", "footer");
?>
