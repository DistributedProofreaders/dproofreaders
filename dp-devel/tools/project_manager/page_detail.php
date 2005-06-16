<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once('page_table.inc');

$projectid = @$_GET['project'];

$project = new Project( $projectid );

$state = $project->state;
$title = $project->nameofwork;
$page_details_str = _('Page Details');

$no_stats = 1;
theme( "$page_details_str: $title", 'header' );

echo "<h1>$title</h1>\n";
echo "<h2>$page_details_str</h2>\n";

$url = "$code_url/project.php?id=$projectid&amp;expected_state=$state";
$label = _("Return to Project Page");

echo "<a href='$url'>$label</a>";
echo "<br>\n";

include_once('detail_legend.inc');

echo "N.B. It is <b>strongly</b> recommended that you view page differentials by right-clicking on a diff link and opening the link in a new window or tab."."<br>\n";

echo_page_table( $project, $show_image_size);

echo "<br>";
theme( '', 'footer' );

?>

