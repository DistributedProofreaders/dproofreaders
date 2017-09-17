<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'ProjectSearchForm.inc');
include_once($relPath.'ProjectSearchResults.inc');

require_login();

output_header(_("Project Search"), NO_STATSBAR);

$search_form = new ProjectSearchForm();

if (!isset($_GET['show']) || $_GET['show'] == 'search_form') {

    // New proofreaders are having a hard time finding stuff because they
    // end up on the Project Search page instead of the starting round page.
    // See if we can't help them out by pointing them to the starting
    // round page.
    $pagesproofed = get_pages_proofed_maybe_simulated();
    if($pagesproofed < 100)
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Looking for projects to proofread?");
        echo "</div>";

        echo "<p>" . sprintf(_("If you're looking for projects to proofread, consider using the list on the <a href='%1\$s'>%2\$s</a> round page instead of this search form."), "$code_url/{$ELR_round->relative_url}#{$ELR_round->id}", $ELR_round->id) . "</p>";
        echo "</p>";

        echo "<p><small>";
        echo _("After a period of time, this message will no longer appear.");
        echo "</small></p>";
        echo "</div>";
    }

    echo "
        <h1>", _("Search for Projects"), "</h1>";
        echo "<p>" . _("Search for projects matching the following criteria:") . "</p>";

    $search_form->render('search.php');
} else {
    // Construct and submit the search query.

    $condition = $search_form->get_widget_contribution();

    // Determine whether to use special colors or not
    // (this does not affect the alternating between two
    // background colors) in the project listing.
    $userSettings =& Settings::get_Settings($pguser);
    $show_special_colors = !$userSettings->get_boolean('hide_special_colors');

    echo "<h1>", _("Search Results"), "</h1>\n";

    $search_results = new ProjectSearchResults($search_form->get_page_size());
    $results_offset = intval(@$_GET['results_offset']);
    $search_results->render($condition, $results_offset, $show_special_colors);

    // special colours legend
    // Don't display if the user has selected the
    // setting "Show Special Colors: No".
    // The legend has been put at the bottom of the page
    // because the use of colors is presumably mostly
    // useful as a check that no typo was made. The
    // exact color probably doesn't matter and,
    // furthermore, the PM 'knows' the project and
    // what's so special about it.
    if (!$userSettings->get_boolean('hide_special_colors')) {
        echo_special_legend(" 1 = 1");
    }
}
echo "<br>";

// vim: sw=4 ts=4 expandtab
