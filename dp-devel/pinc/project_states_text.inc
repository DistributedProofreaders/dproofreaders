<?PHP
  include_once('project_states.inc');

/*
  PR=Pre Processing
  PP=Post Processing
  PI=Proofing (In/Interface/Interval)
*/

  define("NEW_PROJECT_TEXT", "New Project");

// PI

  // unavailable
  define("UNAVAIL_PI_FIRST_TEXT","Unavailable First Round");
  define("UNAVAIL_PI_SECOND_TEXT","Unavailable Second Round");

  // waiting for release
  define("WAITING_PI_FIRST_TEXT","Waiting for First Round Release");
  define("WAITING_PI_SECOND_TEXT","Waiting for Second Round Release");

  // bad project
  define("BAD_PI_FIRST_TEXT","Bad First Round Project");
  define("BAD_PI_SECOND_TEXT","Bad Second Round Project");

  // available for proofing
  define("AVAIL_PI_FIRST_TEXT","Available for First Round");
  define("AVAIL_PI_SECOND_TEXT","Available for Second Round");

  // proofing round complete, but needing verification
  define("VERIFY_PI_FIRST_TEXT","Verify First Round");
  define("VERIFY_PI_SECOND_TEXT","Verify Second Round");

  // proofing round complete and verified
  define("COMPLETE_PI_FIRST_TEXT","Completed First Round");
  define("COMPLETE_PI_SECOND_TEXT","Completed Second Round");

// PP
  define("UNAVAIL_PP_TEXT", "Available for Post-Processing");
  define("AVAIL_PP_TEXT", "Available for Post-Processing");
  define("IN_PP_TEXT", "In Post-Processing");
  define("VERIFY_PP_TEXT", "Verify Post-Processing");
  define("VERIFYING_PP_TEXT", "Verifying Post-Processing");
  define("COMPLETE_PP_TEXT", "Completed Post-Processing");


// GB
  define("UNAVAIL_GB_TEXT", "Unavailable for Posting");
  define("AVAIL_GB_TEXT", "Available for Posting");
  define("POSTING_GB_TEXT", "Being Posted");
  define("POSTED_GB_TEXT", "Posted to Project Gutenberg");

// for the 'deletion' of a project
  define("DELETE_PROJECT_TEXT", "Delete Project");

// for complete project
  define("COMPLETED_PROJECT_TEXT", "Project Complete");
?>