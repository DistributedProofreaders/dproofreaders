<?
$relPath="./../../pinc/";

// initialise variables to values for "post-processing"

$action_page = 'post_proofer.php';
$label = _("Post-Processing");
$state_sql = " (state = '".PROJ_PROOF_FIRST_AVAILABLE."') ";
$filtertype_stem = "avail_PP";

// add the filter code

include_once($relPath.'filter_project_list.inc');
?>
