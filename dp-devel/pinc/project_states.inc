<?PHP

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

$project_state_label_ = array();

function declare_project_state(
    $constant_name,
    $constant_value,
    $label )
{
    global $project_state_label_;
    define( $constant_name, $constant_value );
    $project_state_label_[$constant_value] = $label;
}


// PR


// for the initial creation of a project
declare_project_state(
    "PROJ_NEW",
    "project_new",
     _("New Project")
);

  // You might think that this group of states should be embedded within
  // if ($site_supports_metadata) { ... }
  // Properly, they should, along with every piece of code that uses them.
  // But there are some uses out there (e.g., case arms) that would be
  // difficult to make conditional on $site_supports_metadata. So instead:
  // (1) We leave those uses unconditional, and rely on the assumption
  //     that they won't trigger as long as there isn't a project in
  //     one of these states.
  // (2) We leave these defines here, so those uses do not raise errors.
  //
declare_project_state(
    "PROJ_NEW_WAITING_APPROVAL",
    "project_new_waiting_app",
     _("Awaiting Approval")
);
declare_project_state(
    "PROJ_NEW_UNAPPROVED",
    "project_new_unapp",
    _("Unapproved Project")
);
declare_project_state(
    "PROJ_NEW_APPROVED",
    "project_new_app",
    _("Approved Project")
);
declare_project_state(
    "PROJ_NEW_FILE_UPLOADED",
    "project_new_uploaded",
    _("Files Uploaded")
);
declare_project_state(
    "PROJ_NEW_METADATA_FIRST",
    "project_md_first",
    _("Metadata First Round")
);
declare_project_state(
    "PROJ_NEW_METADATA_BAD",
    "project_md_bad",
    _("Bad Metadata")
);
declare_project_state(
    "PROJ_NEW_METADATA_SECOND",
    "project_md_second",
    _("Metadata Second Round")
);
declare_project_state(
    "PROJ_NEW_PREPROCESSING",
    "project_new_preprocess",
    _("Preprocessing")
);
declare_project_state(
    "PROJ_NEW_PENDING_PM",
    "project_new_pend_pm",
    _("Pending Project Manager")
);


// PROOF

  // unavailable
declare_project_state(
    "PROJ_PROOF_FIRST_UNAVAILABLE",
    "unavail_1",
    _("Unavailable First Round")
);
declare_project_state(
    "PROJ_PROOF_SECOND_UNAVAILABLE",
    "unavail_2",
    _("Unavailable Second Round")
);

  // waiting for release
declare_project_state(
    "PROJ_PROOF_FIRST_WAITING_FOR_RELEASE",
    "waiting_1",
    _("Waiting for First Round Release")
);
declare_project_state(
    "PROJ_PROOF_SECOND_WAITING_FOR_RELEASE",
    "waiting_2",
    _("Waiting for Second Round Release")
);

  // bad project
declare_project_state(
    "PROJ_PROOF_FIRST_BAD_PROJECT",
    "bad_1",
    _("Bad First Round Project")
);
declare_project_state(
    "PROJ_PROOF_SECOND_BAD_PROJECT",
    "bad_2",
    _("Bad Second Round Project")
);

  // available for proofreading
declare_project_state(
    "PROJ_PROOF_FIRST_AVAILABLE",
    "avail_1",
    _("Available for First Round")
);
declare_project_state(
    "PROJ_PROOF_SECOND_AVAILABLE",
    "avail_2",
    _("Available for Second Round")
);

  // proofreading round complete, but needing verification
declare_project_state(
    "PROJ_PROOF_FIRST_VERIFY",
    "verify_1",
    _("Verify First Round")
);
declare_project_state(
    "PROJ_PROOF_SECOND_VERIFY",
    "verify_2",
    _("Verify Second Round")
);

  // proofreading round complete and verified
declare_project_state(
    "PROJ_PROOF_FIRST_COMPLETE",
    "done_1",
    _("Completed First Round")
);
declare_project_state(
    "PROJ_PROOF_SECOND_COMPLETE",
    "done_2",
    _("Completed Second Round")
);


// POST
declare_project_state(
    "PROJ_POST_FIRST_UNAVAILABLE",
    "proj_post_first_unavailable",
    _("Unavailable for Post-Processing")
);
declare_project_state(
    "PROJ_POST_FIRST_AVAILABLE",
    "proj_post_first_available",
    _("Available for Post-Processing")
);

declare_project_state(
    "PROJ_POST_FIRST_CHECKED_OUT",
    "proj_post_first_checked_out",
    _("In Post-Processing")
);

declare_project_state(
    "PROJ_POST_SECOND_AVAILABLE",
    "proj_post_second_available",
    _("Available for Verifying Post-Processing")
);

declare_project_state(
    "PROJ_POST_SECOND_CHECKED_OUT",
    "proj_post_second_checked_out",
    _("Verifying Post-Processing")
);
declare_project_state(
    "PROJ_POST_COMPLETE",
    "proj_post_complete",
    _("Completed Post-Processing")
);



// SUBMIT (was GB)
declare_project_state(
    "PROJ_SUBMIT_PG_UNAVAILABLE",
    "proj_submit_pgunavailable",
    _("Unavailable for Posting")
);
declare_project_state(
    "PROJ_SUBMIT_PG_AVAILABLE",
    "proj_submit_pgavailable",
    _("Available for Posting")
);
declare_project_state(
    "PROJ_SUBMIT_PG_POSTING",
    "proj_submit_pgposting",
    _("Being Posted")
);
declare_project_state(
    "PROJ_SUBMIT_PG_POSTED",
    "proj_submit_pgposted",
    _("Posted to Project Gutenberg")
);

// CORRECT
declare_project_state(
    "PROJ_CORRECT_AVAILABLE",
    "proj_correct_available",
    _("Verify Corrections")
);
declare_project_state(
    "PROJ_CORRECT_CHECKED_OUT",
    "proj_correct_checked_out",
    _("Verifying Corrections")
);

// for the 'deletion' of a project
declare_project_state(
    "PROJ_DELETE",
    "project_delete",
    _("Delete Project")
);

// for complete project
declare_project_state(
    "PROJ_COMPLETE",
    "project_complete",
    _("Project Complete")
);

// -----------------------------------------------------------------------------

$PROJECT_STATES_IN_ORDER = array_merge(
    array(
        PROJ_NEW
    ),

    (
        $site_supports_metadata
        ?
        array(
            PROJ_NEW_WAITING_APPROVAL,
            PROJ_NEW_UNAPPROVED,
            PROJ_NEW_APPROVED,
            PROJ_NEW_FILE_UPLOADED,
            PROJ_NEW_METADATA_FIRST,
            PROJ_NEW_METADATA_BAD,
            PROJ_NEW_METADATA_SECOND,
            PROJ_NEW_PREPROCESSING,
            PROJ_NEW_PENDING_PM
        )
        :
        array()
    ),

    array(
        PROJ_PROOF_FIRST_BAD_PROJECT,
        PROJ_PROOF_FIRST_UNAVAILABLE,
        PROJ_PROOF_FIRST_WAITING_FOR_RELEASE,
        PROJ_PROOF_FIRST_AVAILABLE,
        PROJ_PROOF_FIRST_VERIFY,
        PROJ_PROOF_FIRST_COMPLETE,

        PROJ_PROOF_SECOND_BAD_PROJECT,
        PROJ_PROOF_SECOND_UNAVAILABLE,
        PROJ_PROOF_SECOND_WAITING_FOR_RELEASE,
        PROJ_PROOF_SECOND_AVAILABLE,
        PROJ_PROOF_SECOND_VERIFY,
        PROJ_PROOF_SECOND_COMPLETE,

        PROJ_POST_FIRST_UNAVAILABLE,
        PROJ_POST_FIRST_AVAILABLE,
        PROJ_POST_FIRST_CHECKED_OUT,
        PROJ_POST_SECOND_AVAILABLE,
        PROJ_POST_SECOND_CHECKED_OUT,
        PROJ_POST_COMPLETE,

        PROJ_SUBMIT_PG_UNAVAILABLE,
        PROJ_SUBMIT_PG_AVAILABLE,
        PROJ_SUBMIT_PG_POSTING,
        PROJ_SUBMIT_PG_POSTED,

        PROJ_CORRECT_AVAILABLE,
        PROJ_CORRECT_CHECKED_OUT,

        PROJ_COMPLETE,
        PROJ_DELETE
    )
);

// -----------------------------------------------------------------------------

// Constants for use in SQL queries
  define("SQL_CONDITION_BRONZE",
           "(state='".PROJ_PROOF_FIRST_AVAILABLE."'"
      . " OR state='".PROJ_PROOF_FIRST_VERIFY."'"
      . " OR state='".PROJ_PROOF_SECOND_AVAILABLE."'"
      . " OR state='".PROJ_PROOF_SECOND_VERIFY."'"
      . ")");
  define("SQL_CONDITION_SILVER",
           "(state='".PROJ_POST_FIRST_UNAVAILABLE."'"
      . " OR state='".PROJ_POST_FIRST_AVAILABLE."'"
      . " OR state='".PROJ_POST_FIRST_CHECKED_OUT."'"
      . " OR state='".PROJ_POST_SECOND_AVAILABLE."'"
      . " OR state='".PROJ_POST_SECOND_CHECKED_OUT."'"
      . " OR state='".PROJ_POST_COMPLETE."'"
      . " OR state='".PROJ_SUBMIT_PG_UNAVAILABLE."'"
      . " OR state='".PROJ_SUBMIT_PG_AVAILABLE."'"
      . " OR state='".PROJ_SUBMIT_PG_POSTING."'"
      . ")");
  define("SQL_CONDITION_GOLD",
           "(state='".PROJ_SUBMIT_PG_POSTED."'"
      . " OR state='".PROJ_CORRECT_AVAILABLE."'"
      . " OR state='".PROJ_CORRECT_CHECKED_OUT."'"
	. ")");

?>
