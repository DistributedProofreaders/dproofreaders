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
  define("PROJ_POST_UNAVAILABLE", "proj_post_unavailable");
  define("PROJ_POST_AVAILABLE", "proj_post_available");
//  define("AVAIL_PP", "62");  this is not the same as db
  define("PROJ_POST_CHECKED_OUT", "proj_post_checkedout");

  define("PROJ_POST_VERIFY", "proj_post_verify"); // this is not in db 

  define("PROJ_POST_VERIFYING", "proj_post_verifying");
  define("PROJ_POST_COMPLETE", "proj_post_complete");

// SUBMIT (was GB)
  define("PROJ_SUBMIT_PG_UNAVAILABLE", "proj_submit_pgunavailable");
  define("PROJ_SUBMIT_PG_AVAILABLE", "proj_submit_pgavailable");
  define("PROJ_SUBMIT_PG_POSTING", "proj_submit_pgposting");
  define("PROJ_SUBMIT_PG_POSTED", "proj_submit_pgposted");

// for the 'deletion' of a project
  define("PROJ_DELETE", "project_delete");

// for complete project
  define("PROJ_COMPLETE", "project_complete");


// Constants for use in SQL queries
  define("SQL_CONDITION_BRONZE",
           "(state='".PROJ_PROOF_FIRST_AVAILABLE."'"
      . " OR state='".PROJ_PROOF_FIRST_VERIFY."'"
      . " OR state='".PROJ_PROOF_SECOND_AVAILABLE."'"
      . " OR state='".PROJ_PROOF_SECOND_VERIFY."'"
      . ")");
  define("SQL_CONDITION_SILVER",
           "(state='".PROJ_POST_UNAVAILABLE."'"
      . " OR state='".PROJ_POST_AVAILABLE."'"
      . " OR state='".PROJ_POST_CHECKED_OUT."'"
      . " OR state='".PROJ_POST_VERIFY."'"
      . " OR state='".PROJ_POST_VERIFYING."'"
      . " OR state='".PROJ_POST_COMPLETE."'"
      . " OR state='".PROJ_SUBMIT_PG_UNAVAILABLE."'"
      . " OR state='".PROJ_SUBMIT_PG_AVAILABLE."'"
      . " OR state='".PROJ_SUBMIT_PG_POSTING."'"
      . ")");
  define("SQL_CONDITION_GOLD",
           "(state='".PROJ_SUBMIT_PG_POSTED."')");
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
      . " OR state='".PROJ_POST_UNAVAILABLE."'"
      . " OR state='".PROJ_POST_AVAILABLE."'"
      . " OR state='".PROJ_POST_CHECKED_OUT."'"
      . " OR state='".PROJ_POST_VERIFY."'"
      . " OR state='".PROJ_POST_VERIFYING."'"
      . " OR state='".PROJ_POST_COMPLETE."'"
      . " OR state='".PROJ_SUBMIT_PG_UNAVAILABLE."'"
      . " OR state='".PROJ_SUBMIT_PG_AVAILABLE."'"
      . " OR state='".PROJ_SUBMIT_PG_POSTING."'"
      . " OR state='".PROJ_SUBMIT_PG_POSTED."'"
      . " OR state='".PROJ_COMPLETE."'"
      . " OR state='".PROJ_NEW."'"
      . ")")
?>
