<?
$relPath='../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'quizzes.inc');
$db_Connection=new dbConnect();
$theme_args["css_data"] = "th.q {background:$theme[color_headerbar_bg];
color:$theme[color_headerbar_font];}";
theme(_('Interactive Quizzes'),'header',$theme_args);
?>
<body bgcolor='#ffffff'>

<h1>Interactive Quizzes</h1>

<p>Welcome to DP's interactive quizzes! The following quizzes are available:</p>

<?
foreach ($defined_quizzes as $quiz) 
{
  ${$quiz}->show_results_table($pguser);
}
?>




<!-- As the title indicates you can
use this in two different ways. If you are not yet familiar with the Proofreading
Guidelines you should use it as a tutorial. You can do so by using the 'next
step in tutorial' links.</p>
<p>
If you already know the guidelines you can use it as a quiz only, just for fun
or to confirm your knowledge. Do this by using the 'next step in quiz' links.</p>
<p>
At the moment we have 5 parts, all directed to beginners. Each part might take
about 10 minutes, less if you don't read (and need!) the tutorial parts.</p>
<p>
This is still a preliminary version, feedback is highly welcome. Please post it
 <a href="http://www.pgdp.net/phpBB2/viewtopic.php?t=8539">here</a>.</p>
<p>
<a href="./tuts/tut1.php">Start with step 1 in tutorial</a><br>
<a href="./generic/main.php?type=step1">Start with step 1 in quiz</a></p>
<p>If you have already done some parts and want to reenter, choose your entry point here:<br>
<a href="./tuts/tut2.php">part 2</a> (paragraphs, hyphens, dashes)<br>
<a href="./tuts/tut3.php">part 3</a> (chapter headers, punctuation)<br>
<a href="./tuts/tut4.php">part 4</a> (illustrations, footnotes)<br>
<a href="./tuts/tut5.php">part 5</a> (poetry, block quotations)<br></p>
<br><br>

-->
<?
theme("", "footer");
?>
