<?PHP
// Give information about a single round,
// including (most importantly) the list of projects available for work.

$relPath='../../pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'showavailablebooks.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'site_news.inc');

$userSettings = Settings::get_Settings($pguser);

$round_id = array_get( $_GET, 'round_id', NULL );
if (is_null($round_id))
{
    echo "round.php invoked without round_id parameter.";
    exit;
}

$prd = get_PRD_for_round_id($round_id);
if (is_null($round_id))
{
    echo "round.php invoked with invalid round_id='$round_id'.";
    exit;
}

theme( $prd->round_name, 'header');

echo "<h1>{$prd->round_name}</h1>\n";

// ---------------------------------------

$pagesproofed = get_pages_proofed_maybe_simulated();


welcome_see_beginner_forum( $pagesproofed );


show_site_news_for_page("round.php?round_id=".$round_id);
random_news_item_for_page("round.php?round_id=".$round_id);


if ($pagesproofed <= 100)
{
    echo "<hr width='75%'>\n";

    if ($pagesproofed > 80)
    {
        echo "<i><font size=-1>";
        echo _("After you proof a few more pages, the following introductory Simple Proofreading Rules will be removed from this page. However, they are permanently available ");
        echo "<a href =" . $code_url . "/faq/simple_proof_rules.php>";
        echo _("here");
        echo "</a> ";
        echo _("if you wish to refer to them later. (You can bookmark that link if you like.)");
        echo "</font></i><br><br>";
    }

    include($relPath.'simple_proof_text.inc');
}

if ($pagesproofed >= 10)
{
    echo "<hr width='75%'>\n";

    if ($pagesproofed < 40)
    {
        echo "<font size=-1 face=" . $theme['font_mainbody'] . "><i>";
        echo _("Now that you have proofread 10 pages you can see the Random Rule. Every time this page is refreshed, a random rule is selected from the");
        echo " <a href=" . $code_url . "/faq/document.php>";
        echo _("Proofreading Guidelines");
        echo "</a> ";
        echo _("is displayed in this section");
        echo "<br>";
        echo _("(This explanatory line will eventually vanish.)");
        echo "</i></font><br><br>";
    }


    echo "<font face=".$theme['font_mainbody']."><b>";
    echo _("Random Rule");
    echo "</b></font><br>";


    $result = dpsql_query("SELECT subject,rule,doc FROM rules ORDER BY RAND(NOW()) LIMIT 1");
    $rule = mysql_fetch_assoc($result);
    echo "<i>".$rule['subject']."</i><br>";
    echo "".$rule['rule']."<br>";
    echo _("See the ");
    echo "<a href='$code_url/faq/document.php#".$rule['doc']."'>".$rule['subject']."</a>";
    echo _(" section of the ");
    echo "<a href='$code_url/faq/document.php'>";
    echo _("Proofreading Guidelines");
    echo "</a><br><br>";
}

thoughts_re_mentor_feedback( $pagesproofed );

if ($pagesproofed <= 20)
{
    echo "<hr width='75%'>\n";

    echo "<font face=" . $theme['font_mainbody'] ."><b>";
    echo _("Click on the name of a book in the list below to start proofreading.");
    echo "</b></font><br><br>\n";
}
else
{
    // filter block
    echo "<hr width='75%'>\n";

	$state_sql = " (state = '{$prd->project_available_state}') ";
	$label = $prd->round_name;
    $filtertype_stem = $prd->round_id;
    include_once($relPath.'filter_project_list.inc');
}
if (!isset($RFilter)) { $RFilter = ""; }


// special colours legend
// Don't display if the user has selected the
// setting "Show Special Colors: No".
// Regardless of the preference, don't display
// the legend to newbies.
if ($pagesproofed >= 10 && !$userSettings->get_boolean('hide_special_colors'))
{
    echo "<hr width='75%'>\n";
    echo "<p><font face='{$theme['font_mainbody']}'>\n";
    if (!isset($state_sql)) {
        $state_sql = " (state = '{$prd->project_available_state}') ";
    }
    echo_special_legend($state_sql);
    echo "</font></p><br>\n";
}

show_block_for_round($prd->round_number, $RFilter);

theme('', 'footer');

// vim: sw=4 ts=4 expandtab
?>
