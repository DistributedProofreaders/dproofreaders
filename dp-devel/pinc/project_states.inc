<?PHP
/*
  PR=Pre Processing
  PP=Post Processing
  PI=Proofing (In/Interface/Interval)
*/

// for the initial creation of a project
  define("NEW_PROJECT", "new_pr");

// PR





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