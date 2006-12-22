<?
$relPath="./../../pinc/";
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include($relPath.'user_project_info.inc');

$project = $_GET['project'];
$proofstate = $_GET['proofstate'];

$subscribed = user_is_subscribed_to_project_event( $pguser, $project, 'posted' );

if (!$subscribed) {
    subscribe_user_to_project_event( $pguser, $project, 'posted' );
} else {
    unsubscribe_user_from_project_event( $pguser, $project, 'posted' );
}

metarefresh(0, "$code_url/project.php?id=$project&amp;expected_state=$proofstate", _("Posted Notice"), "");
?>
