<?PHP

// new states (old states are at the end of this list)

/*
  States:

  All state names begin with PROJ (project)

  PROJ[_stage][_round]_description

  stages:
  PRE=Pre Processing
  PROOF=Proofing rounds
  POST=Post Processing
  SUBMIT_PG=submission stage for Project Gutenberg

  rounds:
  FIRST
  SECOND
*/


// PR


// for the initial creation of a project
  define("PROJ_NEW", "project_new");


// PROOF
    
  // unavailable
  define("PROJ_PROOF_FIRST_UNAVAILABLE","unavail_1");
  define("PROJ_PROOF_SECOND_UNAVAILABLE","unavail_2");

  // waiting for release
  define("PROJ_PROOF_FIRST_WAITING_FOR_RELEASE","waiting_1");
  define("PROJ_PROOF_SECOND_WAITING_FOR_RELEASE","waiting_2");

  // bad project
  define("PROJ_PROOF_FIRST_BAD_PROJECT","bad_1");
  define("PROJ_PROOF_SECOND_BAD_PROJECT","bad_2");

  // available for proofing
  define("PROJ_PROOF_FIRST_AVAILABLE","avail_1");
  define("PROJ_PROOF_SECOND_AVAILABLE","avail_2");

  // proofing round complete, but needing verification
  define("PROJ_PROOF_FIRST_VERIFY","verify_1");
  define("PROJ_PROOF_SECOND_VERIFY","verify_2");

  // proofing round complete and verified
  define("PROJ_PROOF_FIRST_COMPLETE","done_1");
  define("PROJ_PROOF_SECOND_COMPLETE","done_2");


// POST
  define("PROJ_POST_UNAVAILABLE", "60");
  define("PROJ_POST_AVAILABLE", "61");
//  define("AVAIL_PP", "62");  this is not the same as db
  define("PROJ_POST_CHECKED_OUT", "65");

  define("PROJ_POST_VERIFY", "67"); // this is not in db 

  define("PROJ_POST_VERIFYING", "68");
  define("PROJ_POST_COMPLETE", "69");

// SUBMIT (was GB)
  define("PROJ_SUBMIT_PG_UNAVAILABLE", "70");
  define("PROJ_SUBMIT_PG_AVAILABLE", "71");
  define("PROJ_SUBMIT_PG_POSTING", "75");
  define("PROJ_SUBMIT_PG_POSTED", "79");

// for the 'deletion' of a project
  define("PROJ_DELETE", "99");

// for complete project
  define("PROJ_COMPLETE", "100");


// old states

/*
  PR=Pre Processing
  PP=Post Processing
  PI=Proofing (In/Interface/Interval)
*/


// for the initial creation of a project
  define("NEW_PROJECT", "new_pr");

// PI

  // unavailable
  define("UNAVAIL_PI_FIRST","unavail_1");
  define("UNAVAIL_PI_SECOND","unavail_2");

  // waiting for release
  define("WAITING_PI_FIRST","waiting_1");
  define("WAITING_PI_SECOND","waiting_2");

  // bad project
  define("BAD_PI_FIRST","bad_1");
  define("BAD_PI_SECOND","bad_2");

  // available for proofing
  define("AVAIL_PI_FIRST","avail_1");
  define("AVAIL_PI_SECOND","avail_2");

  // proofing round complete, but needing verification
  define("VERIFY_PI_FIRST","verify_1");
  define("VERIFY_PI_SECOND","verify_2");

  // proofing round complete and verified
  define("COMPLETE_PI_FIRST","done_1");
  define("COMPLETE_PI_SECOND","done_2");

// PP
  define("UNAVAIL_PP", "60");
  define("AVAIL_PP", "61");
//  define("AVAIL_PP", "62");  this is not the same as db
  define("IN_PP", "65");

  define("VERIFY_PP", "67"); // this is not in db 

  define("VERIFYING_PP", "68");
  define("COMPLETE_PP", "69");

// GB
  define("UNAVAIL_GB", "70");
  define("AVAIL_GB", "71");
  define("POSTING_GB", "75");
  define("POSTED_GB", "79");

// for the 'deletion' of a project
  define("DELETE_PROJECT", "99");

// for complete project
  define("COMPLETED_PROJECT", "100");
?>