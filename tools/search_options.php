<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'ProjectSearchResults.inc');
include_once($relPath.'ProjectSearchResultsConfig.inc');

require_login();

if (isset($_GET['show']) && ($_GET['show'] == 'set_columns'))
{
    $config_saver = new ConfigSaver();
    $config_saver->store_data();

    if(isset($_GET['origin']))
    {
        metarefresh(0, $_GET['origin']);
        exit();
    }
}

output_header(_("Configure Search Results"), NO_STATSBAR);

$config_form = new ConfigForm();
$config_form->render();

// vim: sw=4 ts=4 expandtab
