<?PHP
/*
  PR=Pre Processing
  PP=Post Processing
  PI=Proofing (In/Interface/Interval)
*/

// for the initial creation of a project
  define("NEW_PROJECT", "new_pr");

// PR
  // these can actually be used for more than just project states as part of the pool

  // needs

  // TP&V cleared
  define("NEEDS_PR_CLEAR", "need_clear");
  define("HAS_PR_CLEAR", "has_clear");
  // images to be added
  define("NEEDS_PR_IMAGES", "need_img");
  define("HAS_PR_IMAGES", "has_img");
  // text files to be added
  define("NEEDS_PR_TEXT", "need_txt");
  define("HAS_PR_TEXT", "has_txt");
  // or, someone to ocr first
  define("NEEDS_PR_OCR", "need_ocr");
  define("HAS_PR_OCR", "has_ocr");
  // someone to pm
  define("NEEDS_PR_PM", "need_pm");
  define("HAS_PR_PM", "has_pm");

  // verify
  // can be released to check processes
  define("READY_PR_VERIFY_TEXT", "verify_txt");
  define("READY_PR_VERIFY_IMAGES", "verify_img");

  // check text and images for integrity
  define("CHECK_PR_TEXT", "check_txt");
  define("CHECKED_PR_TEXT", "checked_txt");
  define("CHECK_PR_IMAGES", "check_img");
  define("CHECKED_PR_IMAGES", "checked_img");

  // can be released to server processes
  define("READY_PR_PROCESS_TEXT", "verify_process_pr_txt");
  define("READY_PR_PROCESS_IMAGES", "verify_process_pr_img");

  // preformat text
  define("PROCESS_PR_TEXT", "process_pr_txt");
  define("PROCESSED_PR_TEXT", "processed_pr_txt");
  // manipulate/convert images
  define("PROCESS_PR_IMAGES", "process_pr_img");
  define("PROCESSED_PR_IMAGES", "processed_pr_img");

  // waiting for pm to release project
  define("READY_PR_RELEASE", "verify_pr_release");
  // project released by pm
  define("RELEASE_PR_PROJECT", "release_pr");

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

  // old codes
  define("AVAIL_PP", "62");
  define("IN_PP", "65");
  define("VERIFY_PP", "67");
  define("VERIFYING_PP", "68");
  define("COMPLETE_PP", "69");
  // end old codes

  // needs advanced markup (is optionally set by PM during PR and generates a 'round 3')
  define("NEEDS_PP_AXML", "need_axml");
  // there are a few steps missing here as it goes through an xml 'round'
  define("COMPLETE_PP_AXML", "done_axml");

  // the manual melding to take care of paragraph breaks across pages, etc.
  define("READY_PP_MELD_TEXT", "verify_meld_txt");
  define("MELDING_PP_TEXT", "meld_txt");
  define("MELDED_PP_TEXT", "melded_txt");

  // the spelling and 'common error check' may need to be two steps
  // and recursive until someone is satisfied that neither
  // process 'destroyed' the other
  define("READY_PP_CHECK_TEXT", "verify_check_txt");
  define("CHECKING_PP_TEXT", "check_txt");
  define("CHECKED_PP_TEXT", "checked_txt");

  // more to come.... ?

  // TEI conversion
  define("READY_PP_TEI", "verify_create_tei");
  define("CREATING_PP_TEI", "creating_tei");
  define("CREATED_PP_TEI", "created_tei");

  // submission stuff....

?>