<?PHP
  include_once($relPath.'project_states.inc');

/*
  PR=Pre Processing
  PP=Post Processing
  PI=Proofing (In/Interface/Interval)
*/

  define("PROJ_NEW_TEXT", "New Project");
  define("PROJ_NEW_WAITING_APPROVAL_TEXT", "Awaiting Approval");
  define("PROJ_NEW_UNAPPROVED_TEXT", "Unapproved Project");
  define("PROJ_NEW_APPROVED_TEXT", "Approved Project");
  define("PROJ_NEW_FILE_UPLOAD_TEXT", "Awaiting File Upload");
  define("PROJ_NEW_METADATA_FIRST_TEXT", "Metadata First Round");
  define("PROJ_NEW_METADATA_BAD_TEXT", "Bad Metadata");
  define("PROJ_NEW_METADATA_SECOND_TEXT", "Metadata Second Round");
  define("PROJ_NEW_PREPROCESSING_TEXT", "Preprocessing");
  define("PROJ_NEW_PENDING_PM_TEXT", "Pending Project Manager");

// PI

  // unavailable
  define("PROJ_PROOF_FIRST_UNAVAILABLE_TEXT","Unavailable First Round");
  define("PROJ_PROOF_SECOND_UNAVAILABLE_TEXT","Unavailable Second Round");

  // waiting for release
  define("PROJ_PROOF_FIRST_WAITING_FOR_RELEASE_TEXT","Waiting for First Round Release");
  define("PROJ_PROOF_SECOND_WAITING_FOR_RELEASE_TEXT","Waiting for Second Round Release");

  // bad project
  define("PROJ_PROOF_FIRST_BAD_PROJECT_TEXT","Bad First Round Project");
  define("PROJ_PROOF_SECOND_BAD_PROJECT_TEXT","Bad Second Round Project");

  // available for proofing
  define("PROJ_PROOF_FIRST_AVAILABLE_TEXT","Available for First Round");
  define("PROJ_PROOF_SECOND_AVAILABLE_TEXT","Available for Second Round");

  // proofing round complete, but needing verification
  define("PROJ_PROOF_FIRST_VERIFY_TEXT","Verify First Round");
  define("PROJ_PROOF_SECOND_VERIFY_TEXT","Verify Second Round");

  // proofing round complete and verified
  define("PROJ_PROOF_FIRST_COMPLETE_TEXT","Completed First Round");
  define("PROJ_PROOF_SECOND_COMPLETE_TEXT","Completed Second Round");

// PP
  define("PROJ_POST_FIRST_UNAVAILABLE_TEXT", "Unavailable for Post-Processing");
  define("PROJ_POST_FIRST_AVAILABLE_TEXT", "Available for Post-Processing");
  define("PROJ_POST_FIRST_CHECKED_OUT_TEXT", "In Post-Processing");
  define("PROJ_POST_SECOND_AVAILABLE_TEXT", "Available for Verifying Post-Processing");
  define("PROJ_POST_SECOND_CHECKED_OUT_TEXT", "Verifying Post-Processing");
  define("PROJ_POST_COMPLETE_TEXT", "Completed Post-Processing");


// GB
  define("PROJ_SUBMIT_PG_UNAVAILABLE_TEXT", "Unavailable for Posting");
  define("PROJ_SUBMIT_PG_AVAILABLE_TEXT", "Available for Posting");
  define("PROJ_SUBMIT_PG_POSTING_TEXT", "Being Posted");
  define("PROJ_SUBMIT_PG_POSTED_TEXT", "Posted to Project Gutenberg");

// CORRECT
  define("PROJ_CORRECT_AVAILABLE_TEXT", "Verify Corrections");
  define("PROJ_CORRECT_CHECKED_OUT_TEXT", "Verifying Corrections");

// for the 'deletion' of a project
  define("PROJ_DELETE_TEXT", "Delete Project");

// for complete project
  define("PROJ_COMPLETE_TEXT", "Project Complete");
?>
