<?PHP
  include_once($relPath.'project_states.inc');

/*
  PR=Pre Processing
  PP=Post Processing
  PI=Proofing (In/Interface/Interval)
*/

  define("PROJ_NEW_TEXT", _("New Project"));
  define("PROJ_NEW_WAITING_APPROVAL_TEXT", _("Awaiting Approval"));
  define("PROJ_NEW_UNAPPROVED_TEXT", _("Unapproved Project"));
  define("PROJ_NEW_APPROVED_TEXT", _("Approved Project"));
  define("PROJ_NEW_FILE_UPLOADED_TEXT", _("Files Uploaded"));
  define("PROJ_NEW_METADATA_FIRST_TEXT", _("Metadata First Round"));
  define("PROJ_NEW_METADATA_BAD_TEXT", _("Bad Metadata"));
  define("PROJ_NEW_METADATA_SECOND_TEXT", _("Metadata Second Round"));
  define("PROJ_NEW_PREPROCESSING_TEXT", _("Preprocessing"));
  define("PROJ_NEW_PENDING_PM_TEXT", _("Pending Project Manager"));

// PI

  // unavailable
  define("PROJ_PROOF_FIRST_UNAVAILABLE_TEXT",_("Unavailable First Round");
  define("PROJ_PROOF_SECOND_UNAVAILABLE_TEXT",_("Unavailable Second Round");

  // waiting for release
  define("PROJ_PROOF_FIRST_WAITING_FOR_RELEASE_TEXT",_("Waiting for First Round Release");
  define("PROJ_PROOF_SECOND_WAITING_FOR_RELEASE_TEXT",_("Waiting for Second Round Release");

  // bad project
  define("PROJ_PROOF_FIRST_BAD_PROJECT_TEXT",_("Bad First Round Project"));
  define("PROJ_PROOF_SECOND_BAD_PROJECT_TEXT",_("Bad Second Round Project"));

  // available for proofing
  define("PROJ_PROOF_FIRST_AVAILABLE_TEXT",_("Available for First Round"));
  define("PROJ_PROOF_SECOND_AVAILABLE_TEXT",_("Available for Second Round"));

  // proofing round complete, but needing verification
  define("PROJ_PROOF_FIRST_VERIFY_TEXT",_("Verify First Round"));
  define("PROJ_PROOF_SECOND_VERIFY_TEXT",_("Verify Second Round"));

  // proofing round complete and verified
  define("PROJ_PROOF_FIRST_COMPLETE_TEXT",_("Completed First Round"));
  define("PROJ_PROOF_SECOND_COMPLETE_TEXT",_("Completed Second Round"));

// PP
  define("PROJ_POST_FIRST_UNAVAILABLE_TEXT", _("Unavailable for Post-Processing"));
  define("PROJ_POST_FIRST_AVAILABLE_TEXT", _("Available for Post-Processing"));
  define("PROJ_POST_FIRST_CHECKED_OUT_TEXT", _("In Post-Processing"));
  define("PROJ_POST_SECOND_AVAILABLE_TEXT", _("Available for Verifying Post-Processing"));
  define("PROJ_POST_SECOND_CHECKED_OUT_TEXT", _("Verifying Post-Processing"));
  define("PROJ_POST_COMPLETE_TEXT", _("Completed Post-Processing"));


// GB
  define("PROJ_SUBMIT_PG_UNAVAILABLE_TEXT", _("Unavailable for Posting"));
  define("PROJ_SUBMIT_PG_AVAILABLE_TEXT", _("Available for Posting"));
  define("PROJ_SUBMIT_PG_POSTING_TEXT", _("Being Posted"));
  define("PROJ_SUBMIT_PG_POSTED_TEXT", _("Posted to Project Gutenberg"));

// CORRECT
  define("PROJ_CORRECT_AVAILABLE_TEXT", _("Verify Corrections"));
  define("PROJ_CORRECT_CHECKED_OUT_TEXT", _("Verifying Corrections"));

// for the 'deletion' of a project
  define("PROJ_DELETE_TEXT", _("Delete Project"));

// for complete project
  define("PROJ_COMPLETE_TEXT", _("Project Complete"));
?>
