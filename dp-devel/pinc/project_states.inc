<?PHP
include_once($relPath.'misc.inc'); // array_get
include_once($relPath.'v_site.inc');

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
*/

$PROJECT_STATES_IN_ORDER = array();
$project_state_label_ = array();
$project_state_phase_ = array();
$project_states_for_star_metal_ = array(
    'BRONZE' => array(),
    'SILVER' => array(),
    'GOLD'   => array(),
);

function declare_project_state(
    $constant_name,
    $constant_value,
    $label,
    $phase,
    $star_metal )
{
    global $PROJECT_STATES_IN_ORDER;
    global $project_state_label_;
    global $project_state_phase_;
    global $project_states_for_star_metal_;

    define( $constant_name, $constant_value );
    $PROJECT_STATES_IN_ORDER[] = $constant_value;
    $project_state_label_[$constant_value] = $label;
    $project_state_phase_[$constant_value] = $phase;
    if ($star_metal) $project_states_for_star_metal_[$star_metal][] = $constant_value;
}

function project_states_text($state)
{
    global $project_state_label_;
    return array_get( $project_state_label_, $state, '' );
}

function get_phase_containing_project_state($state)
{
    global $project_state_phase_;
    return array_get( $project_state_phase_, $state, 'NONE' );
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
    'NEW',
    ''
);

if ($site_supports_metadata)
{
	declare_project_state(
	    "PROJ_NEW_WAITING_APPROVAL",
	    "project_new_waiting_app",
	     _("Awaiting Approval"),
	    'NONE',
	    ''
	);
	declare_project_state(
	    "PROJ_NEW_UNAPPROVED",
	    "project_new_unapp",
	    _("Unapproved Project"),
	    'NONE',
	    ''
	);
	declare_project_state(
	    "PROJ_NEW_APPROVED",
	    "project_new_app",
	    _("Approved Project"),
	    'NONE',
	    ''
	);
	declare_project_state(
	    "PROJ_NEW_FILE_UPLOADED",
	    "project_new_uploaded",
	    _("Files Uploaded"),
	    'NONE',
	    ''
	);
	declare_project_state(
	    "PROJ_NEW_METADATA_FIRST",
	    "project_md_first",
	    _("Metadata First Round"),
	    'NONE',
	    ''
	);
	declare_project_state(
	    "PROJ_NEW_METADATA_BAD",
	    "project_md_bad",
	    _("Bad Metadata"),
	    'NONE',
	    ''
	);
	declare_project_state(
	    "PROJ_NEW_METADATA_SECOND",
	    "project_md_second",
	    _("Metadata Second Round"),
	    'NONE',
	    ''
	);
	declare_project_state(
	    "PROJ_NEW_PREPROCESSING",
	    "project_new_preprocess",
	    _("Preprocessing"),
	    'NONE',
	    ''
	);
	declare_project_state(
	    "PROJ_NEW_PENDING_PM",
	    "project_new_pend_pm",
	    _("Pending Project Manager"),
	    'NONE',
	    ''
	);
}


// PROOF

// first round
declare_project_state(
    "PROJ_PROOF_FIRST_BAD_PROJECT",
    "bad_1",
    _("Bad First Round Project"),
    'PAGE_EDITING',
    ''
);
declare_project_state(
    "PROJ_PROOF_FIRST_UNAVAILABLE",
    "unavail_1",
    _("Unavailable First Round"),
    'PAGE_EDITING',
    ''
);
declare_project_state(
    "PROJ_PROOF_FIRST_WAITING_FOR_RELEASE",
    "waiting_1",
    _("Waiting for First Round Release"),
    'PAGE_EDITING',
    ''
);
declare_project_state(
    "PROJ_PROOF_FIRST_AVAILABLE",
    "avail_1",
    _("Available for First Round"),
    'PAGE_EDITING',
    'BRONZE'
);
declare_project_state(
    "PROJ_PROOF_FIRST_COMPLETE",
    "done_1",
    _("Completed First Round"),
    'PAGE_EDITING',
    ''
);

// second round
declare_project_state(
    "PROJ_PROOF_SECOND_BAD_PROJECT",
    "bad_2",
    _("Bad Second Round Project"),
    'PAGE_EDITING',
    ''
);
declare_project_state(
    "PROJ_PROOF_SECOND_UNAVAILABLE",
    "unavail_2",
    _("Unavailable Second Round"),
    'PAGE_EDITING',
    ''
);
declare_project_state(
    "PROJ_PROOF_SECOND_WAITING_FOR_RELEASE",
    "waiting_2",
    _("Waiting for Second Round Release"),
    'PAGE_EDITING',
    ''
);
declare_project_state(
    "PROJ_PROOF_SECOND_AVAILABLE",
    "avail_2",
    _("Available for Second Round"),
    'PAGE_EDITING',
    'BRONZE'
);
declare_project_state(
    "PROJ_PROOF_SECOND_COMPLETE",
    "done_2",
    _("Completed Second Round"),
    'PAGE_EDITING',
    ''
);


// POST
declare_project_state(
    "PROJ_POST_FIRST_UNAVAILABLE",
    "proj_post_first_unavailable",
    _("Unavailable for Post-Processing"),
    'PP',
    'SILVER'
);
declare_project_state(
    "PROJ_POST_FIRST_AVAILABLE",
    "proj_post_first_available",
    _("Available for Post-Processing"),
    'PP',
    'SILVER'
);

declare_project_state(
    "PROJ_POST_FIRST_CHECKED_OUT",
    "proj_post_first_checked_out",
    _("In Post-Processing"),
    'PP',
    'SILVER'
);

declare_project_state(
    "PROJ_POST_SECOND_AVAILABLE",
    "proj_post_second_available",
    _("Available for Verifying Post-Processing"),
    'PP',
    'SILVER'
);

declare_project_state(
    "PROJ_POST_SECOND_CHECKED_OUT",
    "proj_post_second_checked_out",
    _("Verifying Post-Processing"),
    'PP',
    'SILVER'
);
declare_project_state(
    "PROJ_POST_COMPLETE",
    "proj_post_complete",
    _("Completed Post-Processing"),
    'PP',
    'SILVER'
);



// SUBMIT (was GB)
declare_project_state(
    "PROJ_SUBMIT_PG_POSTED",
    "proj_submit_pgposted",
    _("Posted to Project Gutenberg"),
    'GB',
    'GOLD'
);

// CORRECT
declare_project_state(
    "PROJ_CORRECT_AVAILABLE",
    "proj_correct_available",
    _("Verify Corrections"),
    'NONE',
    'GOLD'
);
declare_project_state(
    "PROJ_CORRECT_CHECKED_OUT",
    "proj_correct_checked_out",
    _("Verifying Corrections"),
    'NONE',
    'GOLD'
);

// for complete project
declare_project_state(
    "PROJ_COMPLETE",
    "project_complete",
    _("Project Complete"),
    'COMPLETE',
    ''
);

// for the 'deletion' of a project
declare_project_state(
    "PROJ_DELETE",
    "project_delete",
    _("Delete Project"),
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

?>
