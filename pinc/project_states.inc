<?php
include_once($relPath.'site_vars.php');

/*
  States:

  All state names begin with PROJ (project)

  PROJ[_stage][_round]_description

  stages:
  PRE=Pre Processing
  PROOF=Proofreading rounds
  POST=Post Processing
  SUBMIT_PG=submission stage for Project Gutenberg

  rounds:
  NEW
  METADATA COLLECTION (MD)
  FIRST
  SECOND

  (Actually, the naming convention is in transition.)
*/
global $PROJECT_STATES_IN_ORDER, $project_state_medium_label_, $project_state_long_label_,
$project_state_phase_, $project_states_for_star_metal_;

function define_project_states($rounds, $pools)
{
    global $waiting_projects_forum_idx, $pp_projects_forum_idx, $posted_projects_forum_idx,
    $projects_forum_idx, $completed_projects_forum_idx, $deleted_projects_forum_idx,
    $project_states_for_star_metal_, $Round_for_project_state_;

    $PROJECT_STATES_IN_ORDER = [];
    $project_state_medium_label_ = [];
    $project_state_long_label_ = [];
    $project_state_forum_ = [];
    $project_state_phase_ = [];
    $project_states_for_star_metal_ = [
        'BRONZE' => [],
        'SILVER' => [],
        'GOLD' => [],
    ];

    // Note that the order in which these project states are declared
    // is the order in which they will be displayed in various contexts
    // (via $PROJECT_STATES_IN_ORDER).

    // for the initial creation of a project
    declare_project_state(
        "PROJ_NEW",
        "project_new",
        _("New Project"),
        _("New Project"),
        $waiting_projects_forum_idx,
        'NEW',
        ''
    );

    foreach($rounds as $round) {
        $round->project_bad_state = "{$round->id}.proj_bad";
        $round->project_unavailable_state = "{$round->id}.proj_unavail";
        $round->project_waiting_state = "{$round->id}.proj_waiting";
        $round->project_available_state = "{$round->id}.proj_avail";
        $round->project_complete_state = "{$round->id}.proj_done";

        global $Round_for_project_state_;
        $Round_for_project_state_[$round->project_unavailable_state] = $round;
        $Round_for_project_state_[$round->project_waiting_state] = $round;
        $Round_for_project_state_[$round->project_bad_state] = $round;
        $Round_for_project_state_[$round->project_available_state] = $round;
        $Round_for_project_state_[$round->project_complete_state] = $round;

        declare_project_state(
            "PROJ_{$round->id}_BAD_PROJECT",
            $round->project_bad_state,
            "$round->id: " .  _("Bad Project"),
            "$round->name: " . _("Bad Project"),
            $projects_forum_idx,
            'PAGE_EDITING',
            ''
        );

        declare_project_state(
            "PROJ_{$round->id}_UNAVAILABLE",
            $round->project_unavailable_state,
            "$round->id: " . _("Unavailable"),
            "$round->name: " . _("Unavailable"),
            ($round->id == 'P1' ? $waiting_projects_forum_idx : $projects_forum_idx),
            'PAGE_EDITING',
            ''
        );
        declare_project_state(
            "PROJ_{$round->id}_WAITING_FOR_RELEASE",
            $round->project_waiting_state,
            "$round->id: " . _("Waiting"),
            "$round->name: " . _("Waiting for Release"),
            ($round->id == 'P1' ? $waiting_projects_forum_idx : $projects_forum_idx),
            'PAGE_EDITING',
            ''
        );
        declare_project_state(
            "PROJ_{$round->id}_AVAILABLE",
            $round->project_available_state,
            "$round->id: " . _("Available"),
            "$round->name: " . _("Available"),
            $projects_forum_idx,
            'PAGE_EDITING',
            'BRONZE'
        );
        declare_project_state(
            "PROJ_{$round->id}_COMPLETE",
            $round->project_complete_state,
            "$round->id: " . _("Completed"),
            "$round->name: " . _("Completed"),
            $projects_forum_idx,
            'PAGE_EDITING',
            ''
        );
    }

    // POST
    declare_project_state(
        "PROJ_POST_FIRST_UNAVAILABLE",
        "proj_post_first_unavailable",
        _("Unavailable for PP"),
        _("Unavailable for Post-Processing"),
        $pp_projects_forum_idx,
        'PP',
        'SILVER'
    );

    foreach($pools as $pool) {
        switch ($pool->id) {
            case "PP":
                $pool->project_available_state = "proj_post_first_available";
                $pool->project_checkedout_state = "proj_post_first_checked_out";
                break;
            case "PPV":
                $pool->project_available_state = "proj_post_second_available";
                $pool->project_checkedout_state = "proj_post_second_checked_out";
                break;
        }

        global $Pool_for_state_;
        $Pool_for_state_[$pool->project_available_state] = $pool;
        $Pool_for_state_[$pool->project_checkedout_state] = $pool;

        declare_project_state(
            strtoupper($pool->project_available_state),
            $pool->project_available_state,
            "$pool->id: " . _("Available"),
            "$pool->name: " . _("Available"),
            $pp_projects_forum_idx,
            'PP',
            'SILVER'
        );

        declare_project_state(
            strtoupper($pool->project_checkedout_state),
            $pool->project_checkedout_state,
            "$pool->id: " . _("Checked out"),
            "$pool->name: " . _("Checked out"),
            $pp_projects_forum_idx,
            'PP',
            'SILVER'
        );
    }

    declare_project_state(
        "PROJ_POST_COMPLETE",
        "proj_post_complete",
        _("Completed Post"),
        _("Completed Post-Processing"),
        $pp_projects_forum_idx,
        'PP',
        'SILVER'
    );

    // SUBMIT (was GB)
    declare_project_state(
        "PROJ_SUBMIT_PG_POSTED",
        "proj_submit_pgposted",
        _("Posted to PG"),
        _("Completed and Posted to Project Gutenberg"),
        $posted_projects_forum_idx,
        'GB',
        'GOLD'
    );

    // for complete project
    declare_project_state(
        "PROJ_COMPLETE",
        "project_complete",
        _("Project Complete"),
        _("Project Complete"),
        $completed_projects_forum_idx,
        'COMPLETE',
        ''
    );

    // for the 'deletion' of a project
    declare_project_state(
        "PROJ_DELETE",
        "project_delete",
        _("Delete Project"),
        _("Delete Project"),
        $deleted_projects_forum_idx,
        'NONE',
        ''
    );

    // Define constants for use in SQL queries:
    // SQL_CONDITION_BRONZE
    // SQL_CONDITION_SILVER
    // SQL_CONDITION_GOLD

    foreach ($project_states_for_star_metal_ as $star_metal => $project_states) {
        $sql_constant_name = "SQL_CONDITION_$star_metal";
        $sql_condition = '(';
        foreach ($project_states as $project_state) {
            if ($sql_condition != '(') {
                $sql_condition .= ' OR ';
            }
            $sql_condition .= "state='$project_state'";
        }
        $sql_condition .= ')';

        define($sql_constant_name, $sql_condition);
    }
}

function declare_project_state(
    $constant_name,
    $constant_value,
    $medium_label,
    $long_label,
    $forum,
    $phase,
    $star_metal
) {
    global $PROJECT_STATES_IN_ORDER;
    global $project_state_medium_label_;
    global $project_state_long_label_;
    global $project_state_forum_;
    global $project_state_phase_;
    global $project_states_for_star_metal_;

    define($constant_name, $constant_value);
    $PROJECT_STATES_IN_ORDER[] = $constant_value;
    $project_state_medium_label_[$constant_value] = $medium_label;
    $project_state_long_label_[$constant_value] = $long_label;
    $project_state_forum_[$constant_value] = $forum;
    $project_state_phase_[$constant_value] = $phase;
    if ($star_metal) {
        $project_states_for_star_metal_[$star_metal][] = $constant_value;
    }
}

function get_medium_label_for_project_state($state)
{
    global $project_state_medium_label_;
    return array_get($project_state_medium_label_, $state, '');
}

function project_states_text($state)
{
    global $project_state_long_label_;
    return array_get($project_state_long_label_, $state, '');
}

function get_forum_id_for_project_state($state)
{
    global $project_state_forum_;
    return array_get($project_state_forum_, $state, -1);
}

function get_phase_containing_project_state($state)
{
    global $project_state_phase_;
    return array_get($project_state_phase_, $state, 'NONE');
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Return SQL segment that can be used to sort project states
 *
 * In an SQL query, if you "ORDER BY state", it will use alphabetical order,
 * which is not very useful. Instead, ORDER BY the result of this function,
 * and it will use the canonical order-of-declaration for project states.
 */
function sql_collater_for_project_state($state_column)
{
    global $PROJECT_STATES_IN_ORDER;
    return sprintf(
        "FIELD($state_column, %s)",
        surround_and_join($PROJECT_STATES_IN_ORDER, "'", "'", ",")
    );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
$project_status_descriptors = ['posted', 'PPd', 'proofed', 'created'];

/**
 * Return an object with details about a project status
 *
 * Returned object has attributes that are useful for:
 * - finding
 * - counting or
 * - plotting a graph of
 * projects having that status.
 *
 * @param string $which
 *   A word denoting a possible status of a project, one of:
 *   `created`, `proofed`, `PPd`, or `posted`.
 *
 * @return object
 */
function get_project_status_descriptor($which)
{
    $obj = new stdClass();

    switch ($which) {
        case 'created':
            $obj->state_selector = "(
                state NOT LIKE 'project_new%' AND state != 'project_delete'
            )";
            $obj->Xed_title = _('Created');
            $obj->projects_Xed_title = _('Projects Created');
            $obj->graphs_title = _('Created Projects Graphs');
            $obj->per_day_title = _('Projects Created Each Day');
            $obj->cumulative_title = _('Cumulative Projects Created');
            break;

        case 'proofed':
            $obj->state_selector = "(
                state LIKE 'proj_submit%'
                OR state LIKE 'proj_post%'
            )";
            $obj->Xed_title = _('Proofread');
            $obj->projects_Xed_title = _('Projects Proofread');
            $obj->graphs_title = _('Proofread Projects Graphs');
            $obj->per_day_title = _('Projects Proofread Each Day');
            $obj->cumulative_title = _('Cumulative Projects Proofread');
            break;

        case 'PPd':
            $obj->state_selector = "(
                state LIKE 'proj_submit%'
                OR state LIKE 'proj_post_second%'
            )";
            $obj->Xed_title = _('Post-Processed');
            $obj->projects_Xed_title = _('Projects Post-Processed');
            $obj->graphs_title = _('PPd Projects Graphs');
            $obj->per_day_title = _('Projects Post-Processed Each Day');
            $obj->cumulative_title = _('Cumulative Projects PPd');
            break;

        case 'posted':
            $obj->state_selector = "(
                state LIKE 'proj_submit%'
            )";
            $obj->Xed_title = _('Posted to PG');
            $obj->projects_Xed_title = _('Projects Posted');
            $obj->graphs_title = _('Posted Projects Graphs');
            $obj->per_day_title = _('Projects Posted to PG Each Day');
            $obj->cumulative_title = _('Cumulative Projects Posted to PG');
            break;

        default:
            die(html_safe("bad value for 'which': '$which'"));
    }

    return $obj;
}
