<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'misc.inc'); // get_integer_param()

require_login();

$project = get_projectID_param($_GET, 'project');
$image = get_page_image_param($_GET, 'image');
$round_num = get_integer_param($_GET, 'round_num', null, 0, MAX_NUM_PAGE_EDITING_ROUNDS);

if ($round_num == 0) {
    $text_column_name = 'master_text';
} else {
    $round = get_Round_for_round_number($round_num);
    $text_column_name = $round->text_column_name;
}

validate_projectID($project);
if (!does_project_page_table_exist($project)) {
    die(_("Project table not found, it may have been deleted or archived."));
}

$sql = sprintf("
    SELECT $text_column_name FROM $project WHERE image = '%s'",
    DPDatabase::escape($image));
$result = DPDatabase::query($sql);
$row = mysqli_fetch_assoc($result);
if (!$row) {
    // The page-table exists, but the WHERE clause did not match any row.
    // This could happen if a user saved a URL involving this script,
    // and the page was later deleted or renamed.
    // TRANSLATORS: %1$s is the page image; %2$s is the project ID
    die(sprintf(_("Could not find text for %1\$s in %2\$s"), $image, $project));
}

$data = $row[$text_column_name];

header("Content-type: text/plain; charset=$charset");
// SENDING PAGE-TEXT TO USER
// It's a text/plain document, so no encoding is necessary.
echo $data;
