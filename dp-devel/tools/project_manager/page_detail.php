<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'echo_project_info.inc');

theme("Project Details", "header");

echo_page_table( $project, $show_image);

theme("","footer");
?>