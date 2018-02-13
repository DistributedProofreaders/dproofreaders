<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'SettingsClass.inc');

// check that caller is localhost or bail
if(!requester_is_localhost())
    die("You are not authorized to perform this request.");

$current_ppers = Settings::get_users_with_setting('postprocessor', 'yes');

$result = mysqli_query(DPDatabase::get_connection(), "SELECT DISTINCT(postproofer) FROM projects WHERE postproofer is not null");
while (list($postproofer) = mysqli_fetch_row($result))
{
    if(trim($postproofer) && !in_array($postproofer, $current_ppers))
    {
        $userSettings =& Settings::get_settings($postproofer);
        $userSettings->set_boolean("postprocessor", true);
        echo "Added: $postproofer<br>\n";
    }
}

// vim: sw=4 ts=4 expandtab
