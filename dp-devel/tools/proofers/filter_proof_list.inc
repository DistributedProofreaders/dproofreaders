<?
$relPath="./../../pinc/";

// initialise variables to values for "proofing"

$action_page = 'proof_per.php';

if ($userP['u_plist'] == 1) 
{
	$state_sql = " (state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR
		 state = '".PROJ_PROOF_FIRST_VERIFY."') ";
	$label = _("First Round");
}
if ($userP['u_plist'] == 2)
{
	$state_sql = " (state = '".PROJ_PROOF_SECOND_AVAILABLE."' OR
		 state = '".PROJ_PROOF_SECOND_VERIFY."') ";
	$label = _("Second Round");
}
if ($userP['u_plist'] == 3)
{
	$state_sql = " (state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR
		 state = '".PROJ_PROOF_FIRST_VERIFY."' OR
		 state = '".PROJ_PROOF_SECOND_AVAILABLE."' OR
		 state = '".PROJ_PROOF_SECOND_VERIFY."') ";
	$label = "";
}


$filtertype_stem = "proof";

// add the filter code

include_once($relPath.'filter_project_list.inc');

?>

