<?
$relPath="./../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'filter_project_list.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'showavailablebooks.inc');

$pool_id = @$_GET['pool_id'];

$pool = get_Pool_for_id($pool_id);
if ( is_null($pool) )
{
    die("bad 'pool_id' parameter: '$pool_id'");
}

$available_filtertype_stem = "{$pool->id}_av";

// -----------------------------------------------------------------------------

theme("$pool->id: $pool->name", "header");

global $pguser;
$userSettings = Settings::get_Settings($pguser);

$uao = $pool->user_access($pguser);

$pool->page_top( $uao );

// Show user how to access this round
if ( !$uao->can_access )
{
    echo "<hr width='75%'>\n";
    show_user_access_object( $uao );
}



show_news_for_page($pool->id);


echo "<hr width='75%'>\n";

echo "<br>\n";
echo implode( "\n", $pool->blather );

echo "
<br>
<p>
If there's a project you're interested in,
  you can get to a page about that project
  by clicking on the title of the work.
(We strongly recommend you right-click
  and open this project-specific page in a new window or tab.)
The page will let you see the project comments
  and check the project in or out
  as well as download the associated text and image files.
</p>
";

// special colours legend
// Don't display if the user has selected the
// setting "Show Special Colors: No".
if (!$userSettings->get_boolean('hide_special_colors'))
{
    echo "<hr width='75%'>\n";
    echo_special_legend(" 1 = 1");
}

// --------------------------------------------------------------
echo "<hr>\n";

$header = _('Books I Have Checked Out');

echo "<h2 align='center'>$header</h2>";

echo "<a name='checkedout'></a>\n";
show_projects_available_for_postprocessing( $pool, 'checkedout', "" );
echo "<br>";
echo "<br>";

// --------------------------------------------------------------
echo "<hr>\n";

$header = _('Books Available for Checkout');

echo "<h2 align='center'>$header</h2>";

// -------
$state_sql = "state = '{$pool->project_available_state}'";
process_and_display_project_filter_form($pguser, $available_filtertype_stem, $pool->name, $_REQUEST, $state_sql);
// -------

echo "<a name='available'></a>\n";
echo "<center><b>$header</b></center>";
show_projects_available_for_postprocessing( $pool, 'available', get_project_filter_sql($pguser, $available_filtertype_stem) );
echo "<br>";
echo "<br>";

theme("", "footer");

// vim: sw=4 ts=4 expandtab
?>
