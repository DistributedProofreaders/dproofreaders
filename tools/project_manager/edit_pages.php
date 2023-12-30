<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'page_table.inc');
include_once('page_operations.inc');

require_login();

$projectid = get_projectID_param($_REQUEST, 'projectid');
$operation = get_enumerated_param($_REQUEST, 'operation', null, ['clear', 'delete']);
$selected_pages = @$_REQUEST['selected_pages'];
if (!is_array($selected_pages)) {
    die(_('selected_pages is not a list of pages.'));
}
foreach ($selected_pages as $image => $setting) {
    validate_page_image($image);
}

output_header(_("Edit Pages Confirmation"), NO_STATSBAR);
echo "<br>\n";

$project = new Project($projectid);

abort_if_cant_edit_project($projectid);

// -----------------------------------------------------------------------------
// Check the set of selected pages.

if (count($selected_pages) == 0) {
    echo _("You did not select any pages.") . "<br>\n";
    echo "<br>\n";
    exit;
}

// -----------------------------------------------------------------------------

$page_func_map = [
    'clear' => 'page_clear',
    'delete' => 'page_del',
];

$page_request_map = [
    // 'bad' => _('You requested that each page be marked bad.'),
    'clear' => _('You requested that each page have the effects of its current round be cleared.'),
    'delete' => _('You requested that each page be deleted.'),
];

$page_func = $page_func_map[$operation];
$your_request = $page_request_map[$operation];

if (@$_REQUEST['confirmed'] == 'yes') {
    // Perform the operation.

    foreach ($selected_pages as $image => $setting) {
        // Ignore $setting, it's always 'on'.
        echo _("Image"), "=$image:<br>\n";
        $err = $page_func($projectid, $image);
        echo($err ? $err : _("success"));
        echo "<br>\n";
        echo "<br>\n";
    }
    echo return_to_project_page_link($projectid, ["detail_level=4"]) . "<br>\n";
} else {
    // Obtain confirmation

    echo _("You selected the following page(s):") . "<br>\n";
    echo "<br>\n";
    echo_page_table($project, 0, true, $selected_pages);
    echo "<br>\n";
    echo "$your_request<br>\n";
    echo "<br>\n";

    echo "<form method='post' action='edit_pages.php'>\n";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";
    echo "<input type='hidden' name='operation' value='$operation'>\n";
    foreach ($selected_pages as $image => $setting) {
        echo "<input type='hidden' name='selected_pages[$image]' value='$setting'>\n";
    }
    echo "<input type='hidden' name='confirmed' value='yes'>\n";
    echo "<input type='submit' value='" . attr_safe(_("Do it")) . "'>\n";
    echo "<br>\n";
}

echo "<br>\n";
