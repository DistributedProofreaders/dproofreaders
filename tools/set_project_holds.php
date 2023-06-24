<?php

// Allow authorized users to set/remove holds on a project.

$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'Project.inc'); // get_projectID_param()
include_once($relPath.'links.inc');

require_login();

slim_header(_("Project Holds"));

$projectid = get_projectID_param($_POST, 'projectid');
$return_uri = urldecode($_POST['return_uri']);

$project = new Project($projectid);

if (!$project->can_be_managed_by_current_user) {
    echo "<p>", _('You are not authorized to manage this project.'), "</p>\n";
    exit;
}

// --------------------------------------------------------------------
// Compute the difference between the requested set of hold-states
// and the current set. (Put each holdable state into one of 4 groups:)

$delta_ = [
    'remove' => [],
    'keep' => [],
    'add' => [],
    'keepout' => [],
];

$old_hold_states = $project->get_hold_states();

foreach ($Round_for_round_id_ as $round) {
    foreach (['project_waiting_state', 'project_available_state'] as $s) {
        $state = $round->$s;

        $old_hold = in_array($state, $old_hold_states);

        // In $_POST keys, dots get converted to underscores.
        $new_hold = (@$_POST[str_replace('.', '_', $state)] == 'on');

        if ($old_hold) {
            if ($new_hold) {
                $w = 'keep';
            } else {
                $w = 'remove';
            }
        } else {
            if ($new_hold) {
                $w = 'add';
            } else {
                $w = 'keepout';
            }
        }
        $delta_[$w][] = $state;
    }
}

// -----------------------------------------------
// Restate the requested changes, and perform them.

$headers = [
    'remove' => _("Removing holds for the following states:"),
    'keep' => _("Keeping holds for the following states:"),
    'add' => _("Adding holds for the following states:"),
];

foreach ($headers as $w => $header) {
    $states = $delta_[$w];
    if (count($states)) {
        echo "<p>$header</p>\n";
        echo "<ul>\n";
        foreach ($states as $state) {
            echo "<li>", get_medium_label_for_project_state($state), "</li>\n";
        }
        echo "</ul>\n";

        // -----------------------------------

        if ($w == 'remove') {
            $project->remove_holds($states);
        } elseif ($w == 'add') {
            $project->add_holds($states);
        }
    }
}

echo "<p>" . return_to_project_page_link($projectid, "#holds") . "</p>";
