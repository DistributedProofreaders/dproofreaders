<?
$relPath="./../../pinc/";

// initialise variables to values for "post-processing"

$action_page = 'post_proofers.php';
$label = _("Post-Processing");
$state_sql = " (state = '".PROJ_POST_FIRST_AVAILABLE."') ";
$filtertype_stem = "avail_PP";

// add the filter code

include($relPath.'filter_project_list.inc');
?>
