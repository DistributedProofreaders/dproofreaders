<?PHP
include_once($relPath.'page_states.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gettext_setup.inc');

define('MAX_NUM_PAGE_EDITING_ROUNDS', 2);

class ProofingRoundDescriptor {}
// A container for various constants relating to a particular round of proofing.

$PRD_1 = new ProofingRoundDescriptor;
$PRD_1->round_number = 1;
$PRD_1->project_waiting_state   = PROJ_PROOF_FIRST_WAITING_FOR_RELEASE;
$PRD_1->project_bad_state       = PROJ_PROOF_FIRST_BAD_PROJECT;
$PRD_1->project_available_state = PROJ_PROOF_FIRST_AVAILABLE;
$PRD_1->project_complete_state  = PROJ_PROOF_FIRST_COMPLETE;
$PRD_1->page_avail_state   = AVAIL_FIRST;
$PRD_1->page_out_state     = OUT_FIRST;
$PRD_1->page_temp_state    = TEMP_FIRST;
$PRD_1->page_save_state    = SAVE_FIRST;
$PRD_1->page_bad_state     = BAD_FIRST;
$PRD_1->time_column_name     = 'round1_time';
$PRD_1->text_column_name     = 'round1_text';
$PRD_1->user_column_name     = 'round1_user';
$PRD_1->prevtext_column_name = 'master_text';
$PRD_1->textdiff_column_name = 'round1_diff'; // a computed column
$PRD_1->description = _("The page-texts are the output from OCR software and have not been looked at.");
$PRD_1->other_rounds_with_visible_usernames = array();

$PRD_2 = new ProofingRoundDescriptor;
$PRD_2->round_number = 2;
$PRD_2->project_waiting_state   = PROJ_PROOF_SECOND_WAITING_FOR_RELEASE;
$PRD_2->project_bad_state       = PROJ_PROOF_SECOND_BAD_PROJECT;
$PRD_2->project_available_state = PROJ_PROOF_SECOND_AVAILABLE;
$PRD_2->project_complete_state  = PROJ_PROOF_SECOND_COMPLETE;
$PRD_2->page_avail_state   = AVAIL_SECOND;
$PRD_2->page_out_state     = OUT_SECOND;
$PRD_2->page_temp_state    = TEMP_SECOND;
$PRD_2->page_save_state    = SAVE_SECOND;
$PRD_2->page_bad_state     = BAD_SECOND;
$PRD_2->time_column_name     = 'round2_time';
$PRD_2->text_column_name     = 'round2_text';
$PRD_2->user_column_name     = 'round2_user';
$PRD_2->prevtext_column_name = 'round1_text';
$PRD_2->textdiff_column_name = 'round2_diff'; // a computed column
$PRD_2->description = _("The page-texts have already been proofread once, but now need to be examined <b>closely</b> for small errors that may have been missed.");
$PRD_2->other_rounds_with_visible_usernames = array( $PRD_1 );

// Yes, the usual way to do the above would be to declare the class with a
// bunch of instance variables, then define a constructor function that
// takes a bunch of parameters and assigns them to the instance variables.
// But that's a lot of syntactic overhead when we only need to create these
// two instances.

// Alternatively, I could have simply defined $PRD_1 and $PRD_2 as arrays,
// e.g. $PRD_1 = array( 'page_avail_state' => AVAIL_FIRST, ... );
// but then the accesses would have been like
//     $prd['page_avail_state'],
// which I find somewhat less readable than
//     $prd->page_avail_state

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

// ---------------------------

function get_PRD_for_project_state( $project_state )
{
    global $PRD_1, $PRD_2;

    switch ($project_state)
    {
        case PROJ_PROOF_FIRST_UNAVAILABLE:
        case PROJ_PROOF_FIRST_WAITING_FOR_RELEASE:
        case PROJ_PROOF_FIRST_AVAILABLE:
        case PROJ_PROOF_FIRST_BAD_PROJECT:
        case PROJ_PROOF_FIRST_COMPLETE:
            return $PRD_1;

        case PROJ_PROOF_SECOND_UNAVAILABLE:
        case PROJ_PROOF_SECOND_WAITING_FOR_RELEASE:
        case PROJ_PROOF_SECOND_AVAILABLE:
        case PROJ_PROOF_SECOND_BAD_PROJECT:
        case PROJ_PROOF_SECOND_COMPLETE:
            return $PRD_2;

        default:
            return NULL;
    }
}

// ---------------------------

function get_PRD_for_page_state( $page_state )
{
    global $PRD_1, $PRD_2;

    switch ($page_state)
    {
        case AVAIL_FIRST:
        case OUT_FIRST:
        case TEMP_FIRST:
        case SAVE_FIRST:
        case BAD_FIRST:
            return $PRD_1;

        case AVAIL_SECOND:
        case OUT_SECOND:
        case TEMP_SECOND:
        case SAVE_SECOND:
        case BAD_SECOND:
            return $PRD_2;

        default:
            return NULL;
    }
}

// vim: sw=4 ts=4 expandtab
?>
