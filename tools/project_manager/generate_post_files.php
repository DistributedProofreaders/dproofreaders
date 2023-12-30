<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once('./post_files.inc');

require_login();

$valid_round_ids = Rounds::get_ids();
array_unshift($valid_round_ids, '[OCR]');

$projectid = get_projectID_param($_REQUEST, 'projectid');
$round_id = get_enumerated_param($_REQUEST, 'round_id', null, $valid_round_ids);
$which_text = get_enumerated_param($_REQUEST, 'which_text', null, ['EQ', 'LE']);
$include_proofers = get_integer_param($_REQUEST, 'include_proofers', 0, 0, 1);
$save_files = get_integer_param($_REQUEST, 'save_files', 0, 0, 1);

$errors = [];

// only sitemanagers are allowed to save files
if ($save_files && !user_is_a_sitemanager()) {
    $errors[] = _('You are not authorized to invoke this script.');
}

// only people who can see names on the page details page
// can see names here.
$project = new Project($projectid);

if ($include_proofers && ! $project->names_can_be_seen_by_current_user) {
    $errors[] = _('You are not authorized to invoke this script.');
}

// if we are not saving files, then we are just downloading the zip.
// don't send anything out other than the headers and zip file contents.
if ($save_files) {
    output_page_header($project);
}

set_time_limit(0); // no time limit

if (!$errors) {
    try {
        if ($save_files) {
            flush();
            generate_post_files($project, $round_id, $which_text, $include_proofers, '');
            echo "<p>" . _("Done.") . "</p>";
        } else {
            generate_interim_file($project, $round_id, $which_text, $include_proofers);
        }
    } catch (Exception $exception) {
        $errors[] = $exception->getMessage();
    }
}

if ($errors) {
    output_page_header($project);

    foreach ($errors as $error) {
        echo "<p class='error'>" . html_safe($error) . "</p>";
    }
    exit();
}

function output_page_header($project)
{
    global $round_id, $which_text, $include_proofers, $save_files;

    // Allow this function to be called multiple times but only output
    // the contents once.
    static $was_output = false;
    if ($was_output) {
        return;
    }
    $was_output = true;

    $title = _("Generating Post Files");
    slim_header($title);
    echo "<h1>" . html_safe($title) . "</h1>";
    echo "<p>" . sprintf(_("Generating files for '%s'."), html_safe($project->nameofwork)) . "</p>";
    echo "<pre>";
    echo "projectid        = $project->projectid\n";
    echo "round_id         = $round_id\n";
    echo "which_text       = $which_text\n";
    echo "include_proofers = $include_proofers\n";
    echo "save_files       = $save_files\n";
    echo "</pre>";
}
