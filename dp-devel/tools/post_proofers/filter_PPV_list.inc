<?
$relPath="./../../pinc/";

// initialise variables to values for "post-processing"

$action_page = 'post_proofers.php';
$label = _("Post-Processing Verification");
$state_sql = " (state = '".PROJ_POST_SECOND_AVAILABLE."') ";
$filtertype_stem = "avail_PPV";

// add the filter code

include($relPath.'filter_project_list.inc');
?>
