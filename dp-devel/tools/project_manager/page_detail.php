<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once('page_table.inc');

$projectid = @$_GET['project'];

$myresult = mysql_query("
                SELECT nameofwork, state FROM projects WHERE projectid = '$projectid'
        ");

$row = mysql_fetch_assoc($myresult);
$state = $row['state'];
$title = $row['nameofwork'];

$label = _("Return to Project Page for");

echo "<br>\n";
echo "<a href='$code_url/project.php?id=$projectid&amp;expected_state=$state'>$label $title</a>";
echo "<br>\n";

include_once('detail_legend.inc');

echo "N.B. It is <b>strongly</b> recommended that you view page differentials by right-clicking on a diff link and opening the link in a new window or tab."."<br>\n";

echo_page_table( $projectid, $show_image_size);

?>

