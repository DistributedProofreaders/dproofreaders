<?PHP
// unavailable
define("UNAVAIL_FIRST","unavail_first"); // 0
define("UNAVAIL_SECOND","unavail_second"); // 10

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
    UNAVAIL_FIRST,
    AVAIL_FIRST,
    OUT_FIRST,
    TEMP_FIRST,
    SAVE_FIRST,
    BAD_FIRST,

    UNAVAIL_SECOND,
    AVAIL_SECOND,
    OUT_SECOND,
    TEMP_SECOND,
    SAVE_SECOND,
    BAD_SECOND
);

// -----------------------------------------------------------------------------

class ProofingRoundDescriptor {}
// A container for various constants relating to a particular round of proofing.

$PRD_1 = new ProofingRoundDescriptor;
$PRD_1->unavail_state = UNAVAIL_FIRST;
$PRD_1->avail_state   = AVAIL_FIRST;
$PRD_1->out_state     = OUT_FIRST;
$PRD_1->temp_state    = TEMP_FIRST;
$PRD_1->save_state    = SAVE_FIRST;
$PRD_1->bad_state     = BAD_FIRST;
$PRD_1->time_column_name     = 'round1_time';
$PRD_1->text_column_name     = 'round1_text';
$PRD_1->user_column_name     = 'round1_user';
$PRD_1->prevtext_column_name = 'master_text';

$PRD_2 = new ProofingRoundDescriptor;
$PRD_2->unavail_state = UNAVAIL_SECOND;
$PRD_2->avail_state   = AVAIL_SECOND;
$PRD_2->out_state     = OUT_SECOND;
$PRD_2->temp_state    = TEMP_SECOND;
$PRD_2->save_state    = SAVE_SECOND;
$PRD_2->bad_state     = BAD_SECOND;
$PRD_2->time_column_name     = 'round2_time';
$PRD_2->text_column_name     = 'round2_text';
$PRD_2->user_column_name     = 'round2_user';
$PRD_2->prevtext_column_name = 'round1_text';

// Yes, the usual way to do the above would be to declare the class with a
// bunch of instance variables, then define a constructor function that
// takes a bunch of parameters and assigns them to the instance variables.
// But that's a lot of syntactic overhead when we only need to create these
// two instances.

// Alternatively, I could have simply defined $PRD_1 and $PRD_2 as arrays,
// e.g. $PRD_1 = array( 'unavail_state' => UNAVAIL_FIRST, ... );
// but then the accesses would have been like
//     $prd['unavail_state'],
// which I find somewhat less readable than
//     $prd->unavail_state

// Although $PRD_1 and $PRD_2 are variables, they should be treated as
// constants; thus the all-uppercase names.

// ---------------------------

function get_PRD_for_round( $round_number )
// If $round_number is a valid proofing-round number (currently 1 or 2),
// return the appropriate ProofingRoundDescriptor instance.
// Otherwise, return NULL.
{
    global $PRD_1, $PRD_2;

    if ( $round_number == 1 )
    {
        return $PRD_1;
    }
    else if ( $round_number == 2 )
    {
        return $PRD_2;
    }
    else
    {
        return NULL;
    }
}

// vim: sw=4 ts=4 expandtab
?>
