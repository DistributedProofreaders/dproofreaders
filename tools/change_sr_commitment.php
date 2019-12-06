<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'smoothread.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()

require_login();

/***************************************************************************************
*
* transient page to record or remove a users intent to smoothread a project.
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
    $title = _("Volunteer to SR");
    $body = sprintf(_("%1\$s, you have volunteered to smoothread project %2\$s."),
        $pguser, $projectid);
    break;

case "withdraw":
    sr_withdraw_commitment($projectid, $pguser);
    $title = _("Withdraw from SR");
    $body = sprintf(_("%1\$s, you have withdrawn from Smooth Reading project %2\$s."),
        $pguser, $projectid);
    break;
}


metarefresh(2, "$refresh_url#smooth_start", $title, $body);

// vim: sw=4 ts=4 expandtab
