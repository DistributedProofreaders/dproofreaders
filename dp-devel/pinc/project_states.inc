<?PHP

/*
  States:

  All state names begin with PROJ (project)

  PROJ[_stage][_round]_description

  stages:
  PRE=Pre Processing
  PROOF=Proofing rounds
  POST=Post Processing
  SUBMIT_PG=submission stage for Project Gutenberg
  CORRECT=Correcting Edition

  rounds:
  FIRST
  SECOND
*/


// PR


// for the initial creation of a project
  define("PROJ_NEW", "project_new");  //did not exist, but sort of 0


// PROOF

  // unavailable
  define("PROJ_PROOF_FIRST_UNAVAILABLE","unavail_1"); // 0
  define("PROJ_PROOF_SECOND_UNAVAILABLE","unavail_2"); // 10

  // waiting for release
  define("PROJ_PROOF_FIRST_WAITING_FOR_RELEASE","waiting_1"); // 1
  define("PROJ_PROOF_SECOND_WAITING_FOR_RELEASE","waiting_2"); // 11

  // bad project
  define("PROJ_PROOF_FIRST_BAD_PROJECT","bad_1"); // 31
  define("PROJ_PROOF_SECOND_BAD_PROJECT","bad_2"); // 41

  // available for proofing
  define("PROJ_PROOF_FIRST_AVAILABLE","avail_1"); // 2
  define("PROJ_PROOF_SECOND_AVAILABLE","avail_2"); // 12

  // proofing round complete, but needing verification
  define("PROJ_PROOF_FIRST_VERIFY","verify_1"); // 8
  define("PROJ_PROOF_SECOND_VERIFY","verify_2"); // 18

  // proofing round complete and verified
  define("PROJ_PROOF_FIRST_COMPLETE","done_1"); // 9
  define("PROJ_PROOF_SECOND_COMPLETE","done_2"); // 19


// POST
  define("PROJ_POST_FIRST_UNAVAILABLE", "proj_post_unavailable"); // 60
  define("PROJ_POST_FIRST_AVAILABLE", "proj_post_available"); // 61
//  define("AVAIL_PP", "62");  this is not the same as db
  define("PROJ_POST_FIRST_CHECKED_OUT", "proj_post_checkedout"); // 65

  define("PROJ_POST_SECOND_AVAILABLE", "proj_post_verify"); // this is not in db

  define("PROJ_POST_SECOND_CHECKED_OUT", "proj_post_verifying"); // 67
  define("PROJ_POST_COMPLETE", "proj_post_complete"); // 69
  define("PROJ_POST_AVAIL_SECOND", "proj_post_avail_second");
  define("PROJ_POST_CHECKEDOUT_SECOND", "proj_post_checkedout_second");



// SUBMIT (was GB)
  define("PROJ_SUBMIT_PG_UNAVAILABLE", "proj_submit_pgunavailable"); // 70
  define("PROJ_SUBMIT_PG_AVAILABLE", "proj_submit_pgavailable"); // 71
  define("PROJ_SUBMIT_PG_POSTING", "proj_submit_pgposting"); // 75
  define("PROJ_SUBMIT_PG_POSTED", "proj_submit_pgposted"); // 79

// CORRECT
  define("PROJ_CORRECT_VERIFY", "proj_correct_verify");
  define("PROJ_CORRECT_VERIFYING", "proj_correct_verifying");

// for the 'deletion' of a project
  define("PROJ_DELETE", "project_delete"); // 99

// for complete project
  define("PROJ_COMPLETE", "project_complete"); // 100??

// -----------------------------------------------------------------------------

$PROJECT_STATES_IN_ORDER = array(
    PROJ_NEW,

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
    PROJ_POST_AVAIL_SECOND,
    PROJ_POST_CHECKEDOUT_SECOND,

    PROJ_SUBMIT_PG_UNAVAILABLE,
    PROJ_SUBMIT_PG_AVAILABLE,
    PROJ_SUBMIT_PG_POSTING,
    PROJ_SUBMIT_PG_POSTED,

    PROJ_CORRECT_VERIFY,
    PROJ_CORRECT_VERIFYING,

    PROJ_COMPLETE,
    PROJ_DELETE
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
      . " OR state='".PROJ_POST_AVAIL_SECOND."'"
      . " OR state='".PROJ_POST_CHECKEDOUT_SECOND."'"
      . ")");
  define("SQL_CONDITION_GOLD",
           "(state='".PROJ_SUBMIT_PG_POSTED."'"
      . " OR state='".PROJ_CORRECT_VERIFY."'"
      . " OR state='".PROJ_CORRECT_VERIFYING."'"
	. ")");
  define("SQL_CONDITION_ALL",
	   "(state='".PROJ_PROOF_FIRST_UNAVAILABLE."'"
      . " OR state='".PROJ_PROOF_SECOND_UNAVAILABLE."'"
      . " OR state='".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."'"
      . " OR state='".PROJ_PROOF_SECOND_WAITING_FOR_RELEASE."'"
      . " OR state='".PROJ_PROOF_FIRST_BAD_PROJECT."'"
      . " OR state='".PROJ_PROOF_SECOND_BAD_PROJECT."'"
      . " OR state='".PROJ_PROOF_FIRST_AVAILABLE."'"
      . " OR state='".PROJ_PROOF_SECOND_AVAILABLE."'"
      . " OR state='".PROJ_PROOF_FIRST_VERIFY."'"
      . " OR state='".PROJ_PROOF_SECOND_VERIFY."'"
      . " OR state='".PROJ_PROOF_FIRST_COMPLETE."'"
      . " OR state='".PROJ_PROOF_SECOND_COMPLETE."'"
      . " OR state='".PROJ_POST_FIRST_UNAVAILABLE."'"
      . " OR state='".PROJ_POST_FIRST_AVAILABLE."'"
      . " OR state='".PROJ_POST_FIRST_CHECKED_OUT."'"
      . " OR state='".PROJ_POST_SECOND_AVAILABLE."'"
      . " OR state='".PROJ_POST_SECOND_CHECKED_OUT."'"
      . " OR state='".PROJ_POST_COMPLETE."'"
      . " OR state='".PROJ_SUBMIT_PG_UNAVAILABLE."'"
      . " OR state='".PROJ_SUBMIT_PG_AVAILABLE."'"
      . " OR state='".PROJ_SUBMIT_PG_POSTING."'"
      . " OR state='".PROJ_SUBMIT_PG_POSTED."'"
      . " OR state='".PROJ_CORRECT_VERIFY."'"
      . " OR state='".PROJ_CORRECT_VERIFYING."'"
      . " OR state='".PROJ_COMPLETE."'"
      . " OR state='".PROJ_NEW."'"
      . ")")

?>
