<?PHP

// bad page
define("BAD_FIRST","bad_first"); // 31
define("BAD_SECOND","bad_second"); // 41

// available
define("AVAIL_FIRST","avail_first"); // 2
define("AVAIL_SECOND","avail_second"); // 12

// checked out
define("OUT_FIRST","out_first"); // 5
define("OUT_SECOND","out_second"); // 15

// temp save
define("TEMP_FIRST","temp_first"); // did not exist
define("TEMP_SECOND","temp_second"); // did not exist

// save
define("SAVE_FIRST","save_first"); // 9
define("SAVE_SECOND","save_second"); // 19

// -----------------------------------------------------------------------------

$PAGE_STATES_IN_ORDER = array(
    AVAIL_FIRST,
    OUT_FIRST,
    TEMP_FIRST,
    SAVE_FIRST,
    BAD_FIRST,

    AVAIL_SECOND,
    OUT_SECOND,
    TEMP_SECOND,
    SAVE_SECOND,
    BAD_SECOND
);

// vim: sw=4 ts=4 expandtab
?>
