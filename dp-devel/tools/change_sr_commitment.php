<?php
$relPath="../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'smoothread.inc');

/***************************************************************************************
*
* transient page to register or revoke a users commitment to smoothread a project.
*
* inputs: projectid, username, action, next_url (per POST-method)
*
* output: none
*
* Remarks:
*
* This is transient page executing the database function for the action "commit" or "withdraw".
* It will automatically call the page adressed by next_url.
*
****************************************************************************************/

$projectid   = validate_projectID('projectid', @$_POST['projectid']);
$action      = get_enumerated_param($_POST, 'action', null, array('commit', 'withdraw'));
$refresh_url = @$_POST['next_url'];

switch ($action) {
case "commit":
    sr_commit($projectid, $pguser);
    $title = _("Commit to SR");
    $body = sprintf(_("Registered SR-commitment of user %s for project %s."),
        $pguser, $projectid);
    break;

case "withdraw":
    sr_withdraw_commitment($projectid, $pguser);
    $title = _("Withdraw SR-commitment");
    $body = sprintf(_("Withdraw SR-commitment of user %s for project %s."),
        $pguser, $projectid);
    break;

default:
    $title = "Error SR-commitment";
    $body = "Error: invalid action $action for SR-commitment change of user $pguser for project $projectid.";
}


metarefresh(2, $refresh_url, $title, $body);

?>
