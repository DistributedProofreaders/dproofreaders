<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'links.inc');
include_once($relPath.'Project.inc');

require_login();

if (!user_is_a_sitemanager()) {
    die(_("You are not authorized to invoke this script."));
}

$action = get_enumerated_param($_POST, 'action', 'showform', ['showform', 'check', 'dojump']);
$projectid = get_projectID_param($_POST, 'projectid', true);
$valid_new_states = [];
foreach (Rounds::get_all() as $round) {
    $valid_new_states[] = $round->project_unavailable_state;
}
$new_state = get_enumerated_param($_POST, 'new_state', null, $valid_new_states, true);


$title = _("Project Jump");
output_header($title);
echo "<h1>$title</h1>";

echo "<p>" . _("Jump a project to a specific state.") . "</p>\n";

switch ($action) {
    case 'showform':
        display_project_jump_form("showform", $projectid, $new_state);
        break;

    case 'check':
        jump_project($projectid, $new_state, true);
        display_project_jump_form("check", $projectid, $new_state);
        break;

    case 'dojump':
        jump_project($projectid, $new_state, false);
        echo "\n\n" . return_to_project_page_link($projectid) . "\n";
        break;
}


function display_project_jump_form(string $action, ?string $projectid, ?string $new_state): void
{
    echo "<form method='post'>\n";
    echo "<table>\n";
    if ($action == "showform") {
        echo "<tr>\n";
        echo "<th>" . _("Project") . ":</th>\n";
        echo "<td><input type='text' name='projectid' size='28' required></td>\n";
        echo "</tr>";

        echo "<tr>";
        echo "<th>" . _("State") . ":</th>\n";
        echo "<td><select name='new_state'>";
        foreach (Rounds::get_all() as $round) {
            echo "<option value='{$round->project_unavailable_state}'>{$round->project_unavailable_state}</option>";
        }
        echo "</select></td>\n";
        echo "</tr>";

        echo "<tr>";
        echo "<td></td>";
        echo "<td>";
        echo "<input type='submit' name='submit_button' value='" . attr_safe(_("Check")) . "'>\n";
        echo "<input type='hidden' name='action' value='check'>\n";
        echo "</td>\n";
        echo "</tr>";
    } elseif ($action == "check") {
        echo "<tr>";
        echo "<td>";
        echo "<input type='hidden' name='projectid' value='" . attr_safe($projectid) . "'>";
        echo "<input type='hidden' name='new_state' value='" . attr_safe($new_state) . "'>";
        echo "<input type='hidden' name='action' value='dojump'>\n";
        echo "<input type='submit' name='submit_button' value='" . attr_safe(_("Do it")) . "'>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>\n";
    echo "</form>";
}

function jump_project(string $projectid, string $new_state, bool $just_checking): void
{
    global $pguser;
    $project = new Project($projectid);
    echo "<pre>";
    echo "    projectid : $projectid\n";
    echo "    title     : $project->nameofwork\n";
    echo "    state     : $project->state\n";

    // ----------------------

    if ($project->state == $new_state) {
        throw new RuntimeException("Project is already in {$new_state}");
    }

    if (!$project->pages_table_exists) {
        throw new RuntimeException("Project does not have a pages table and cannot be jumped to a new state");
    }

    if ($just_checking) {
        echo "</pre>\n";
        echo "<p class='warning''>\n";
        echo "The project will be jumped to state <b>{$new_state}</b>.<br>\n";
        echo "No page details will be changed.\n";
        echo "</p>\n";
        return;
    }

    // ----------------------------------------------------

    $sql = sprintf(
        "
        UPDATE projects
        SET state = '%s', modifieddate = UNIX_TIMESTAMP()
        WHERE projectid = '%s'
        ",
        DPDatabase::escape($new_state),
        DPDatabase::escape($projectid)
    );
    DPDatabase::query($sql);
    echo "    jumped to : $new_state\n";
    $project->log_project_event(
        $pguser,
        'transition',
        $project->state,
        $new_state,
        'Project jumped to correct state'
    );
    echo "</pre>";
}
