<?PHP
/*
  States:

  All state names begin with PROJ (project)

  PROJ[_stage][_round]_description

  stages:
  PRE=Pre Processing
  PROOF=Proofing rounds
  POST=Post Processing
  CONVERT=conversion (to TEI)
  SUBMIT_PG=submission stage for Project Gutenberg

  rounds:
  FIRST
  SECOND
*/

/*

  Flags:
  All flag names begin with FLAG.
  (Pages do not have flags, only states, so PROJ is not needed)

  FLAG[_stage][_round][_status]_description

  stages:
  PRE=Pre Processing
  PROOF=Proofing rounds
  POST=Post Processing

  rounds:
  FIRST
  SECOND

  status:
  (as true/on | false/off pairs)
  NEEDS | HAS
  {etc.}

*/

// for the initial creation of a project
  define("PROJ_NEW", "project_new");

// for the 'deletion' of a project
  define("PROJ_DELETE", "project_delete");

// for complete project
  define("PROJ_COMPLETE", "project_complete");


// PRE

  // needs

  // TP&V cleared
  define("FLAG_PRE_NEEDS_CLEAR", "flag_pre_needs_clear");
  define("FLAG_PRE_HAS_CLEAR", "flag_pre_has_clear");
  // images to be added
  define("FLAG_PRE_NEEDS_IMAGES", "flag_pre_need_img");
  define("FLAG_PRE_HAS_IMAGES", "flag_pre_has_img");
  // text files to be added
  define("FLAG_PRE_NEEDS_TEXT", "flag_pre_need_txt");
  define("FLAG_PRE_HAS_TEXT", "flag_pre_has_txt");
  // or, someone to ocr first
  define("FLAG_PRE_NEEDS_OCR", "flag_pre_need_ocr");
  define("FLAG_PRE_HAS_OCR", "flag_pre_has_ocr");
  // someone to pm
  define("FLAG_PRE_NEEDS_PM", "flag_pre_need_pm");
  define("FLAG_PRE_HAS_PM", "flag_pre_has_pm");

  // verify
  // can be released to check processes
  define("PROJ_PRE_VERIFY_TEXT", "proj_pre_verify_txt");
  define("PROJ_PRE_VERIFY_IMAGES", "Proj_pre_verify_img");

  // check text and images for integrity
  define("FLAG_PRE_CHECK_PR_TEXT", "flag_pre_check_txt");
  define("FLAG_PRE_CHECKED_PR_TEXT", "flag_pre_checked_txt");
  define("FLAG_PRE_CHECK_PR_IMAGES", "flag_pre_check_img");
  define("FLAG_PRE_CHECKED_PR_IMAGES", "flag_pre_checked_img");

  // can be released to server processes
  define("PROJ_PRE_PROCESS_TEXT", "proj_pre_process_txt");
  define("PROJ_PRE_PROCESS_IMAGES", "proj_pre_process_img");

  // preformat text
  define("FLAG_PRE_PROCESS_TEXT", "flag_pre_process_txt");
  define("FLAG_PRE_PROCESSED_TEXT", "flag_pre_processed_txt");
  // manipulate/convert images
  define("FLAG_PRE_PROCESS_IMAGES", "flag_pre_process_img");
  define("FLAG_PRE_PROCESSED_IMAGES", "flag_pre_processed_img");

  // waiting for pm to release project
  define("PROJ_PRE_RELEASE_PROJECT", "proj_pre_release_project");
  // project released by pm
  define("PROJ_PRE_RELEASED_PROJECT", "proj_pre_released_project");

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

  // being proofread
  define("PROJ_PROOF_FIRST_EDITING","editing_1");
  define("PROJ_PROOF_SECOND_EDITING","editing_2");

  // proofing round complete, but needing verification
  define("PROJ_PROOF_FIRST_VERIFY","verify_1");
  define("PROJ_PROOF_SECOND_VERIFY","verify_2");

  // proofing round complete and verified
  define("PROJ_PROOF_FIRST_COMPLETE","done_1");
  define("PROJ_PROOF_SECOND_COMPLETE","done_2");


// POST

  // needs advanced markup (is optionally set by PM at any point and generates a 'round 3')
  // when this round 3 occurs is not specified in this document.  The flag is a POST flag.
  define("FLAG_POST_NEEDS_AXML", "flag_post_need_axml");
  // there are a few steps missing here as it goes through an xml 'round'
  define("FLAG_POST_HAS_AXML", "flag_post_has_axml");

  /*
     the manual melding to take care of paragraph breaks across pages, etc.
     If POST will be on whole document, must be done before first round of POST.
        Errors can be caught during POST rounds.
     Else can be done at end of POST
  */

  define("PROJ_POST_MELD_AVAILABLE", "proj_post_meld_available");
  define("PROJ_POST_MELD_CHECKED_OUT", "proj_post_meld_checkedout");
  define("PROJ_POST_MELD_COMPLETE", "proj_post_meld_complete");


// First "round" of post-processing is what is the current PP section
// Second "round" of post-processing is where an advanced proofreader verifies a project before posting it

  /*
     check flags may be recursively turned on and off during POST.
  */
  define("FLAG_POST_FIRST_NEEDS_SPELLING", "flag_post_first_needs_spelling");
  define("FLAG_POST_HAS_SPELLING", "flag_post_second_has_spelling");
  define("FLAG_POST_FIRST_NEEDS_ERRORCHECK", "flag_post_first_needs_errorcheck");
  define("FLAG_POST_SECOND_HAS_ERRORCHECK", "flag_post_second_has_errorcheck");

  define("PROJ_POST_FIRST_UNAVAILABLE", "proj_post_first_unavailable");
  define("PROJ_POST_SECOND_UNAVAILABLE", "proj_post_second_unavailable");

  // problem found in PP
  define("PROJ_POST_FIRST_BAD_PROJECT", "proj_post_first_bad");
  define("PROJ_POST_SECOND_BAD_PROJECT", "proj_post_second_bad");

  // available for post-processing
  define("PROJ_POST_FIRST_AVAILABLE", "proj_post_first_available");
  define("PROJ_POST_SECOND_AVAILABLE", "proj_post_second_available");

  // checked out for post-processing
  define("PROJ_POST_FIRST_CHECKED_OUT", "proj_post_first_checkedout");
  define("PROJ_POST_SECOND_CHECKED_OUT", "proj_post_second_checkedout");

  /*
     while checked out, it can go into these states so that the
     POST processor can return to that stage on after exit
     all checing states == checked out
  */
  // spelling check
  define("PROJ_POST_FIRST_SPELLING", "proj_post_first_spelling");
  define("PROJ_POST_SECOND_SPELLING", "proj_post_second_spelling");

  // error check
  define("PROJ_POST_SECOND_ERRORCHECK", "proj_post_first_errorcheck");
  define("PROJ_POST_FIRST_ERRORCHECK", "proj_post_second_errorcheck");

  // raw edit
  define("PROJ_POST_FIRST_RAWEDIT", "proj_post_first_rawedit");
  define("PROJ_POST_SECOND_RAWEDIT", "proj_post_second_rawedit");
  // end checking states

  // post-processing complete, but needing verificiation
  define("PROJ_POST_FIRST_VERIFY", "proj_post_first_verify");
  define("PROJ_POST_SECOND_VERIFY", "proj_post_second_verify");

  // being verified
  define("PROJ_POST_FIRST_VERIFYING", "proj_post_first_verifying");
  define("PROJ_POST_SECOND_VERIFYING", "proj_post_second_verifying");

  // post-processing round complete and verified
  define("PROJ_POST_FIRST_COMPLETE", "proj_post_first_complete");
  define("PROJ_POST_SECOND_COMPLETE", "proj_post_second_complete");

  /* 
     if the POST is done on full document, may need a meld check here, 
          but the error check should handle it.
     else this is where the meld round goes.
  */

// CONVERT
  // TEI conversion
  define("PROJ_CONVERT_TEI_UNAVAILABLE", "proj_convert_tei_unavailable");
  define("PROJ_CONVERT_TEI_BAD_MELD", "proj_convert_tei_bad");
  define("PROJ_CONVERT_TEI_AVAILABLE", "proj_convert_tei_available");
  define("PROJ_CONVERT_TEI_CONVERTING", "proj_convert_tei_converting");
  define("PROJ_CONVERT_TEI_COMPLETE", "proj_convert_tei_complete");


// SUBMIT (was GB/PO)
  define("PROJ_SUBMIT_PG_UNAVAILABLE", "proj_submit_pgunavailable");
  define("PROJ_SUBMIT_PG_AVAILABLE", "proj_submit_pgavailable");
  define("PROJ_SUBMIT_PG_POSTING", "proj_submit_pgposting");
  define("PROJ_SUBMIT_PG_POSTED", "proj_submit_pgposted");

// post conversion/submission processes go here
?>