<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'showavailablebooks.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()

require_login();

$pool_id = get_enumerated_param($_GET, 'pool_id', null, array_keys($Pool_for_id_));

$pool = get_Pool_for_id($pool_id);

output_header("$pool->id: $pool->name");

global $pguser;
$userSettings =& Settings::get_Settings($pguser);

$uao = $pool->user_access($pguser);

$pool->page_top( $uao );

// Show user how to access this round
if ( !$uao->can_access )
{
    echo "<hr class='divider'>\n";
    show_user_access_object( $uao );
}



show_news_for_page($pool->id);


echo "<hr class='divider'>\n";

echo "<br>\n";
echo implode( "\n", $pool->blather );


echo "<br><p>" . _("If there's a project you're interested in, you can get to a page about that project by clicking on the title of the work. (We strongly recommend you right-click and open this project-specific page in a new window or tab.) The page will let you see the project comments and check the project in or out as well as download the associated text and image files.") . "</p>";



// --------------------------------------------------------------

show_projects_for_pool( $pool, 'checkedout' );

// --------------------------------------------------------------

show_projects_for_pool( $pool, 'available' );

// special colours legend
// Don't display if the user has selected the
// setting "Show Special Colors: No".
if (!$userSettings->get_boolean('hide_special_colors'))
{
    echo_special_legend(" 1 = 1");
}

// vim: sw=4 ts=4 expandtab
