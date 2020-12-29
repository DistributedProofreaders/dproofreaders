<?php
include_once($relPath.'misc.inc'); // array_get, html_safe()
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
  CORRECT=Correcting Edition

  rounds:
  NEW
  METADATA COLLECTION (MD)
  FIRST
  SECOND

  (Actually, the naming convention is in transition.)
*/

$PROJECT_STATES_IN_ORDER = array();
$project_state_medium_label_ = array();
$project_state_long_label_ = array();
$project_state_forum_ = array();
$project_state_phase_ = array();
$project_states_for_star_metal_ = array(
    'BRONZE' => array(),
    'SILVER' => array(),
    'GOLD'   => array(),
);

function declare_project_state(
    $constant_name,
    $constant_value,
    $medium_label,
    $long_label,
    $forum,
    $phase,
    $star_metal )
{
    global $PROJECT_STATES_IN_ORDER;
    global $project_state_medium_label_;
    global $project_state_long_label_;
    global $project_state_forum_;
    global $project_state_phase_;
    global $project_states_for_star_metal_;

    define( $constant_name, $constant_value );
    $PROJECT_STATES_IN_ORDER[] = $constant_value;
    $project_state_medium_label_[$constant_value] = $medium_label;
    $project_state_long_label_[$constant_value] = $long_label;
    $project_state_forum_[$constant_value] = $forum;
    $project_state_phase_[$constant_value] = $phase;
    if ($star_metal) $project_states_for_star_metal_[$star_metal][] = $constant_value;
}

function get_medium_label_for_project_state($state)
{
    global $project_state_medium_label_;
    return array_get( $project_state_medium_label_, $state, '' );
}

function project_states_text($state)
{
    global $project_state_long_label_;
    return array_get( $project_state_long_label_, $state, '' );
}

function get_forum_id_for_project_state($state)
{
    global $project_state_forum_;
    return array_get( $project_state_forum_, $state, -1 );
}

function get_phase_containing_project_state($state)
{
    global $project_state_phase_;
    return array_get( $project_state_phase_, $state, 'NONE' );
}

// -----------------------------------------------

function declare_project_states_for_round( $round_id, $round_name )
{
    global $projects_forum_idx;
    global $waiting_projects_forum_idx;

    declare_project_state(
        "PROJ_{$round_id}_BAD_PROJECT",
        "$round_id.proj_bad",
        "$round_id: " .  _("Bad Project"),
        "$round_name: " . _("Bad Project"),
        $projects_forum_idx,
        'PAGE_EDITING',
        ''
    );
    declare_project_state(
        "PROJ_{$round_id}_UNAVAILABLE",
        "$round_id.proj_unavail",
        "$round_id: " . _("Unavailable"),
        "$round_name: " . _("Unavailable"),
        ($round_id == 'P1' ? $waiting_projects_forum_idx : $projects_forum_idx ),
        'PAGE_EDITING',
        ''
    );
    declare_project_state(
        "PROJ_{$round_id}_WAITING_FOR_RELEASE",
        "$round_id.proj_waiting",
        "$round_id: " . _("Waiting"),
        "$round_name: " . _("Waiting for Release"),
        ($round_id == 'P1' ? $waiting_projects_forum_idx : $projects_forum_idx ),
        'PAGE_EDITING',
        ''
    );
    declare_project_state(
        "PROJ_{$round_id}_AVAILABLE",
        "$round_id.proj_avail",
        "$round_id: " . _("Available"),
        "$round_name: " . _("Available"),
        $projects_forum_idx,
        'PAGE_EDITING',
        'BRONZE'
    );
    declare_project_state(
        "PROJ_{$round_id}_COMPLETE",
        "$round_id.proj_done",
        "$round_id: " . _("Completed"),
        "$round_name: " . _("Completed"),
        $projects_forum_idx,
        'PAGE_EDITING',
        ''
    );
}

// -----------------------------------------------

// Note that the order in which these project states are declared
// is the order in which they will be displayed in various contexts
// (via $PROJECT_STATES_IN_ORDER).


// PR


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

// METADATA
if ($site_supports_metadata)
{
    declare_project_state(
        "PROJ_NEW_WAITING_APPROVAL",
        "project_new_waiting_app",
         _("Awaiting Approval"),
         _("Awaiting Approval"),
        $waiting_projects_forum_idx,
        'NONE',
        ''
    );
    declare_project_state(
        "PROJ_NEW_UNAPPROVED",
        "project_new_unapp",
        _("Unapproved Project"),
        _("Unapproved Project"),
        $waiting_projects_forum_idx,
        'NONE',
        ''
    );
    declare_project_state(
        "PROJ_NEW_APPROVED",
        "project_new_app",
        _("Approved Project"),
        _("Approved Project"),
        $waiting_projects_forum_idx,
        'NONE',
        ''
    );
    declare_project_state(
        "PROJ_NEW_FILE_UPLOADED",
        "project_new_uploaded",
        _("Files Uploaded"),
        _("Files Uploaded"),
        $waiting_projects_forum_idx,
        'NONE',
        ''
    );
    declare_project_state(
        "PROJ_NEW_METADATA_FIRST",
        "project_md_first",
        _("Metadata First Round"),
        _("Metadata First Round"),
        $waiting_projects_forum_idx,
        'NONE',
        ''
    );
    declare_project_state(
        "PROJ_NEW_METADATA_BAD",
        "project_md_bad",
        _("Bad Metadata"),
        _("Bad Metadata"),
        $waiting_projects_forum_idx,
        'NONE',
        ''
    );
    declare_project_state(
        "PROJ_NEW_METADATA_SECOND",
        "project_md_second",
        _("Metadata Second Round"),
        _("Metadata Second Round"),
        $waiting_projects_forum_idx,
        'NONE',
        ''
    );
    declare_project_state(
        "PROJ_NEW_PREPROCESSING",
        "project_new_preprocess",
        _("Preprocessing"),
        _("Preprocessing"),
        $waiting_projects_forum_idx,
        'NONE',
        ''
    );
    declare_project_state(
        "PROJ_NEW_PENDING_PM",
        "project_new_pend_pm",
        _("Pending Project Manager"),
        _("Pending Project Manager"),
        $waiting_projects_forum_idx,
        'NONE',
        ''
    );
}


// PROOF
declare_project_states_for_round( 'P1', _('Proofreading Round 1') );
declare_project_states_for_round( 'P2', _('Proofreading Round 2') );
declare_project_states_for_round( 'P3', _('Proofreading Round 3') );

// FORMAT
declare_project_states_for_round( 'F1', _('Formatting Round 1') );
declare_project_states_for_round( 'F2', _('Formatting Round 2') );


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
declare_project_state(
    "PROJ_POST_FIRST_AVAILABLE",
    "proj_post_first_available",
    _("Available for PP"),
    _("Available for Post-Processing"),
    $pp_projects_forum_idx,
    'PP',
    'SILVER'
);

declare_project_state(
    "PROJ_POST_FIRST_CHECKED_OUT",
    "proj_post_first_checked_out",
    _("In PP"),
    _("In Post-Processing"),
    $pp_projects_forum_idx,
    'PP',
    'SILVER'
);

declare_project_state(
    "PROJ_POST_SECOND_AVAILABLE",
    "proj_post_second_available",
    _("Available for PPV"),
    _("Available for Verifying Post-Processing"),
    $pp_projects_forum_idx,
    'PP',
    'SILVER'
);

declare_project_state(
    "PROJ_POST_SECOND_CHECKED_OUT",
    "proj_post_second_checked_out",
    _("In PPV"),
    _("Verifying Post-Processing"),
    $pp_projects_forum_idx,
    'PP',
    'SILVER'
);
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

// CORRECT
if ($site_supports_corrections_after_posting)
{
    declare_project_state(
        "PROJ_CORRECT_AVAILABLE",
        "proj_correct_available",
        _("Verify Corrections"),
        _("Verify Corrections"),
        $posted_projects_forum_idx,
        'NONE',
        'GOLD'
    );
    declare_project_state(
        "PROJ_CORRECT_CHECKED_OUT",
        "proj_correct_checked_out",
        _("Verifying Corrections"),
        _("Verifying Corrections"),
        $posted_projects_forum_idx,
        'NONE',
        'GOLD'
    );
}

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

// -----------------------------------------------------------------------------

// Define constants for use in SQL queries:
// SQL_CONDITION_BRONZE
// SQL_CONDITION_SILVER
// SQL_CONDITION_GOLD

foreach ( $project_states_for_star_metal_ as $star_metal => $project_states )
{
    $sql_constant_name = "SQL_CONDITION_$star_metal";
    $sql_condition = '(';
    foreach ( $project_states as $project_state )
    {
    if ($sql_condition != '(')
    {
        $sql_condition .= ' OR ';
    }
    $sql_condition .= "state='$project_state'";
    }
    $sql_condition .= ')';

    define( $sql_constant_name, $sql_condition );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function sql_collater_for_project_state( $state_column )
// In an SQL query, if you "ORDER BY state", it will use alphabetical order,
// which is not very useful. Instead, ORDER BY the result of this function,
// and it will use the canonical order-of-declaration for project states.
{
    global $PROJECT_STATES_IN_ORDER;
    $s = "FIELD($state_column";
    foreach ( $PROJECT_STATES_IN_ORDER as $project_state )
    {
        $s .= ",'$project_state'";
    }
    $s .= ")";
    return $s;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
$project_status_descriptors = array('created', 'proofed', 'PPd', 'posted');

function get_project_status_descriptor( $which )
// $which is a word denoting a possible status of a project
// (created, proofed, PPd, or posted).
// Return an object whose attributes hold various constants that
// are useful for:
// -- finding,
// -- counting, or
// -- plotting a graph of
// projects having that status.
// (Somewhat ad hoc, but useful.)
{
    $obj = new stdClass;

    switch ( $which )
    {
        case 'created':
            $obj->state_selector = "(
                state NOT LIKE 'project_new%' AND state != 'project_delete'
            )";
            $obj->color = 'green';
            $obj->Xed_title = _('Created');
            $obj->projects_Xed_title = _('Projects Created');
            $obj->graphs_title = _('Created Projects Graphs');
            $obj->per_day_title = _('Projects Created Each Day');
            $obj->cumulative_title = _('Cumulative Projects Created');
            break;

        case 'proofed':
            $obj->state_selector = "(
                state LIKE 'proj_submit%'
                OR state LIKE 'proj_correct%'
                OR state LIKE 'proj_post%'
            )";
            $obj->color = 'blue';
            $obj->Xed_title = _('Proofread');
            $obj->projects_Xed_title = _('Projects Proofread');
            $obj->graphs_title = _('Proofread Projects Graphs');
            $obj->per_day_title = _('Projects Proofread Each Day');
            $obj->cumulative_title = _('Cumulative Projects Proofread');
            break;

        case 'PPd':
            $obj->state_selector = "(
                state LIKE 'proj_submit%'
                OR state LIKE 'proj_correct%'
                OR state LIKE 'proj_post_second%'
            )";
            $obj->color = 'silver';
            $obj->Xed_title = _('Post-Processed');
            $obj->projects_Xed_title = _('Projects Post-Processed');
            $obj->graphs_title = _('PPd Projects Graphs');
            $obj->per_day_title = _('Projects Post-Processed Each Day');
            $obj->cumulative_title = _('Cumulative Projects PPd');
            break;

        case 'posted':
            $obj->state_selector = "(
                state LIKE 'proj_submit%'
                OR state LIKE 'proj_correct%'
            )";
            $obj->color = 'gold';
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

?>
