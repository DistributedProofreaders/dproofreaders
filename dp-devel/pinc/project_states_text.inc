<?PHP
  include_once($relPath.'project_states.inc');

/*
  PR=Pre Processing
  PP=Post Processing
  PI=Proofing (In/Interface/Interval)
*/

  define("PROJ_NEW_TEXT", "New Project");

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
  define("PROJ_POST_UNAVAILABLE_TEXT", "Unavailable for Post-Processing");
  define("PROJ_POST_AVAILABLE_TEXT", "Available for Post-Processing");
  define("PROJ_POST_CHECKED_OUT_TEXT", "In Post-Processing");
  define("PROJ_POST_VERIFY_TEXT", "Verify Post-Processing");
  define("PROJ_POST_VERIFYING_TEXT", "Verifying Post-Processing");
  define("PROJ_POST_COMPLETE_TEXT", "Completed Post-Processing");
  define("PROJ_POST_AVAIL_SECOND_TEXT", "Available for 2nd round of PP");
  define("PROJ_POST_CHECKEDOUT_SECOND_TEXT", "Checkedout for 2nd round PP");


// GB
  define("PROJ_SUBMIT_PG_UNAVAILABLE_TEXT", "Unavailable for Posting");
  define("PROJ_SUBMIT_PG_AVAILABLE_TEXT", "Available for Posting");
  define("PROJ_SUBMIT_PG_POSTING_TEXT", "Being Posted");
  define("PROJ_SUBMIT_PG_POSTED_TEXT", "Posted to Project Gutenberg");

// for the 'deletion' of a project
  define("PROJ_DELETE_TEXT", "Delete Project");

// for complete project
  define("PROJ_COMPLETE_TEXT", "Project Complete");
?>