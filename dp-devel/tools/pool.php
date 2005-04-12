<?
$relPath="./../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'bookpages.inc');
include_once($relPath.'show_projects_in_state.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'site_news.inc');

$pool_id = @$_GET['pool_id'];

$someone_maintains_the_PP_faq =
    sprintf( _("%s (<a href='%s'>%s</a>) maintains our <a href='%s'>Post Processing FAQ</a>."),
        'Julie Barkley',
        'http://www.pgdp.net/phpBB2/privmsg.php?mode=post&u=1674',
        'juliebarkley',
        "$code_url/faq/post_proof.php" );

if ( $pool_id == 'PP' )
{
    $pool_name = _('Post-Processing');
    $checkedout_proj_state = PROJ_POST_FIRST_CHECKED_OUT;
    $available_proj_state = PROJ_POST_FIRST_AVAILABLE;
    $checkedout_order_setting_name = 'ChPPorder';
    $available_order_setting_name = 'PPorder';
    $available_filtertype_stem = 'PP_av';
    $user_is_allowed_fn = 'user_is_PP';

    $blather = array(
        "<p>",
        _("The books listed below have already gone through two rounds of proofreading on this site and they now need to be massaged into a final e-text."),
        _("Once you have checked out and downloaded a book it will remain checked out to you until you check it back in."),
        _("When you have finished your work on the book, select <i>Upload for Verification</i> from the drop-down list for that project."),
        _("If you have several files to submit for a single project (say a text and HTML version), zip them up together first."),
        "</p>",

        "<p>",
        "<b>" . _("First Time Here?") . "</b>",
        $someone_maintains_the_PP_faq,
        _("Please read the FAQ as it covers all the steps needed to post process an e-text."),
        _("Select an easy work to get started on (usually fiction with a low page count is a good starter book; projects whose manager is BEGIN make excellent first projects for a new post processor)."),
        sprintf( _("Check out the <a href='%s'>Post Processing Forum</a> to post all your questions."), $post_processing_forum_url ),
        _("If nothing interests you right now, check back later and there will be more!"),
        "</p>",
    );
}
elseif ( $pool_id == 'PPV' )
{
    $pool_name = _('Post-Processing Verification');
    $checkedout_proj_state = PROJ_POST_SECOND_CHECKED_OUT;
    $available_proj_state = PROJ_POST_SECOND_AVAILABLE;
    $checkedout_order_setting_name = 'ChPPVorder';
    $available_order_setting_name = 'PPVorder';
    $available_filtertype_stem = 'PPV_av';
    $user_is_allowed_fn = 'user_is_post_proof_verifier';

    $blather = array(
        "<p>",
        _("As an experienced volunteer, you have access to do verification of texts that have been Post Processed already, if you wish."),
        "<font color='red' size=4>",
        sprintf( _("Make sure you read the <b>new</b> <a href='%s'>Post Processing Verification Guidelines</a> and use the <a href='%s'>PPV Report Card</a> for each project you PPV."),
            "$code_url/faq/ppv.php",
            "$code_url/faq/ppv_report.txt" ),
        "</font>",
        "</p>",

        "<p>",
        $someone_maintains_the_PP_faq,
        sprintf( _("As always, the <a href='%s'>Post Processing Forum</a> is available for any of your questions."),
            $post_processing_forum_url ),
        "</p>",
    );
}
else
{
    die("bad 'pool_id' parameter: '$pool_id'");
}

// -----------------------------------------------------------------------------

theme($pool_name, "header");

echo "<h1 align='center'>$pool_id: $pool_name</h1>";

global $pguser;
$userSettings = Settings::get_Settings($pguser);

if (!$user_is_allowed_fn())
{
    echo _("You're not allowed to work in this pool. If you feel this is an error, please contact the site administration.");
    exit();
}



show_site_news_for_page("pool.php?pool_id=$pool_id");
random_news_item_for_page("pool.php?pool_id=$pool_id");


echo "<br>\n";
echo implode( "\n", $blather );

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
    echo "<p><font face='{$theme['font_mainbody']}'>\n";
    echo_special_legend(" 1 = 1");
    echo "</font></p><br>\n";
}

// --------------------------------------------------------------
echo "<hr>\n";

$header = _('Books I Have Checked Out');

echo "<h2 align='center'>$header</h2>";

echo "<a name='checkedout'></a>\n";
show_projects_in_state_plus(
    $checkedout_proj_state,
    " ",
    $checkedout_order_setting_name,
    'order_checkedout'
);
echo "<br>";
echo "<br>";

// --------------------------------------------------------------
echo "<hr>\n";

$header = _('Books Available for Checkout');

echo "<h2 align='center'>$header</h2>";

// -------
$label = $pool_name;
$state_sql = " (state = '$available_proj_state') ";
$filtertype_stem = $available_filtertype_stem;
include($relPath.'filter_project_list.inc');
if (!isset($RFilter)) { $RFilter = ""; }
// -------

echo "<a name='available'></a>\n";
echo "<center><b>$header</b></center>";
show_projects_in_state_plus(
    $available_proj_state,
    $RFilter,
    $available_order_setting_name,
    'order_available'
);
echo "<br>";
echo "<br>";

theme("", "footer");

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_projects_in_state_plus(
    $proj_state,
    $RFilter,
    $order_setting_name,
    $order_param_name
)
// A wrapped version of show_projects_in_state
// that handles getting and saving the table's sort order.
{
    global $pguser;

    // Get saved sort order
    $res = mysql_query("
        SELECT value
        FROM usersettings
        WHERE username = '$pguser' AND setting = '$order_setting_name'
    ");
    if (mysql_num_rows($res) >= 1)
    {
        $saved_order = mysql_result($res, 0);
    }
    else
    {
        $saved_order = 'DaysD';
    }

    // Get new sort order, if any
    $new_order = array_get( $_GET, $order_param_name, $saved_order );

    // If order has changed, save it to database
    if ($new_order != $saved_order)
    {
        mysql_query("
            DELETE FROM usersettings
            WHERE username='$pguser' AND setting='$order_setting_name'
        ") or die(mysql_error());

        mysql_query("
            INSERT INTO usersettings
            SET username='$pguser', setting='$order_setting_name', value='$new_order'
        ") or die(mysql_error());
    }

    show_projects_in_state($proj_state, 1, $RFilter, $new_order);
}

// vim: sw=4 ts=4 expandtab
?>
