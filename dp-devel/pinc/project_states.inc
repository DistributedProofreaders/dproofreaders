<?PHP
/*
  PR=Pre Processing
  PP=Post Processing
  PI=Proofing (In/Interface/Interval)
*/

// PI

  // unavailable
  define("UNAVAIL_PI_FIRST","30");
  define("UNAVAIL_PI_SECOND","40");

  // bad project
  define("BAD_PI_FIRST","31");
  define("BAD_PI_SECOND","41");

  // available for proofing
  define("AVAIL_PI_FIRST","32");
  define("AVAIL_PI_SECOND","42");

  // proofing round complete, but needing verification
  define("VERIFY_PI_FIRST","38");
  define("VERIFY_PI_SECOND","48");

  // proofing round complete and verified
  define("SAVE_PI_FIRST","39");
  define("SAVE_PI_SECOND","49");

  define("AVAIL_PP", "62");
  define("IN_PP", "65");
  define("VERIFY_PP", "67");
  define("VERIFYING_PP", "68");
  define("COMPLETE_PP", "69");
?>