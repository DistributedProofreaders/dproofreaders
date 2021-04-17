<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'gradual.inc'); // maybe_output_new_proofer_message()
include_once($relPath.'ProjectSearchForm.inc');
include_once($relPath.'ProjectSearchResults.inc');

require_login();

try {
    $show_view = get_enumerated_param($_GET, 'show', 'search_form',
        ['search_form', 'search', 'set_columns', 'config']);
} catch (Exception $e) {
    $show_view = 'search_form';
}

// exits if handled
handle_set_cols($show_view, "PS");

$header_args = ["js_files" => ["$code_url/tools/dropdown.js"]];
output_header(_("Project Search"), NO_STATSBAR, $header_args);
$search_form = new ProjectSearchForm();

handle_config($show_view, "PS", _("Configure Search Results"));

if ($show_view == 'search_form') {
    echo "<h1>", _("Search for Projects"), "</h1>";

    maybe_output_new_proofer_message();

    $search_form->render();
    exit();
}

// show must be search
$condition = $search_form->get_condition();

echo "<h1>", _("Search Results"), "</h1>\n";

$search_results = new ProjectSearchResults("PS");
echo "<p><a href='?show=search_form'>" . _("Start New Search") . "</a> | " . get_refine_search_link() . " | " . get_search_configure_link() . "</p>";
$search_results->render($condition);
