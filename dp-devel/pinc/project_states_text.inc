<?PHP
/*
  PR=Pre Processing
  PP=Post Processing
  PI=Proofing (In/Interface/Interval)
*/
  include('project_states.inc');
// PI

  // unavailable
  define("UNAVAIL_PI_FIRST_TEXT","Unavailable First Round");
  define("UNAVAIL_PI_SECOND_TEXT","Unavailable Second Round");

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
  define("SAVE_PI_FIRST_TEXT","Completed First Round");
  define("SAVE_PI_SECOND_TEXT","Completed Second Round");

  define("AVAIL_PP_TEXT", "Available for Post-Processing");
  define("IN_PP_TEXT", "In Post-Processing");
  define("VERIFY_PP_TEXT", "Verify Post-Processing");
  define("VERIFYING_PP_TEXT", "Verifying Post-Processing");
  define("COMPLETE_PP_TEXT", "Completed Post-Processing");

  function project_states_text($state) {
    switch($state) {
      case AVAIL_PP:
         return AVAIL_PP_TEXT;
      case IN_PP:
         return IN_PP_TEXT;
      case VERIFY_PP:
         return VERIFY_PP_TEXT;
      case VERIFYING_PP:
         return VERIFYING_PP_TEXT;
      case COMPLETE_PP:
         return COMPLETE_PP_TEXT;
    }
    return "";
  }
?>