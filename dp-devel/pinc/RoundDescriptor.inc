<?PHP
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gettext_setup.inc');

define('MAX_NUM_PAGE_EDITING_ROUNDS', 2);

// -----------------------------------------------------------------------------

$n_rounds = 0;
$PRD_for_round_id_      = array();
$PRD_for_round_number_  = array();
$PRD_for_project_state_ = array();
$PRD_for_page_state_    = array();

class ProofingRoundDescriptor
// A container for various constants relating to a particular round of proofing.
{
    function ProofingRoundDescriptor(
        $round_id,
            // A very short mnemonic identifier for the round.
            // (Should probably conform to the rules for a PHP variable name.)
        $minimum_user_pages,
            // The minimum number of pages that a user must complete
            // in order to be allowed to participate in this round.
        $title,
            // A title that appears above the list of projects in this round.
        $description,
            // A sentence or two explaining what happens in this round.
        $listing_bgcolors,
            // An array of (two) HTML colors that will be used as the bgcolor
            // in alternating rows in the listing of projects in this round.
        $other_rounds_with_visible_usernames
            // An array of ProofingRoundDescriptor objects.
            // If user X worked on a page in this round, they can see the
            // username of another user Y who worked on the page *if* user Y
            // worked on the page in a round that apppears in this parameter.
    )
    {
        global $n_rounds;
        $n_rounds++;
        $this->round_number       = $n_rounds;

        $this->round_id           = $round_id;
        $this->minimum_user_pages = $minimum_user_pages;
        $this->title              = $title;
        $this->description        = $description;
        $this->listing_bgcolors   = $listing_bgcolors;
        $this->other_rounds_with_visible_usernames = $other_rounds_with_visible_usernames;

        $this->project_unavailable_state = constant("PROJ_{$round_id}_UNAVAILABLE");
        $this->project_waiting_state     = constant("PROJ_{$round_id}_WAITING_FOR_RELEASE");
        $this->project_bad_state         = constant("PROJ_{$round_id}_BAD_PROJECT");
        $this->project_available_state   = constant("PROJ_{$round_id}_AVAILABLE");
        $this->project_complete_state    = constant("PROJ_{$round_id}_COMPLETE");

        $this->page_avail_state          = "{$round_id}.page_avail";
        $this->page_out_state            = "{$round_id}.page_out";
        $this->page_temp_state           = "{$round_id}.page_temp";
        $this->page_save_state           = "{$round_id}.page_saved";
        $this->page_bad_state            = "{$round_id}.page_bad";

        $this->time_column_name          = "round{$this->round_number}_time";
        $this->text_column_name          = "round{$this->round_number}_text";
        $this->user_column_name          = "round{$this->round_number}_user";
        $this->textdiff_column_name      = "round{$this->round_number}_diff"; // a computed column
        if ( $this->round_number == 1 )
        {
            $this->prevtext_column_name = "master_text";
        }
        else
        {
            $prev_round_number = $this->round_number - 1;
            $this->prevtext_column_name = "round{$prev_round_number}_text";
        }

        global $PRD_for_round_id_;
        $PRD_for_round_id_[$this->round_id] =& $this;

        global $PRD_for_round_number_;
        $PRD_for_round_number_[$this->round_number] =& $this;

        global $PRD_for_project_state_;
        $PRD_for_project_state_[$this->project_unavailable_state] =& $this;
        $PRD_for_project_state_[$this->project_waiting_state    ] =& $this;
        $PRD_for_project_state_[$this->project_bad_state        ] =& $this;
        $PRD_for_project_state_[$this->project_available_state  ] =& $this;
        $PRD_for_project_state_[$this->project_complete_state   ] =& $this;

        global $PRD_for_page_state_;
        $PRD_for_page_state_[$this->page_avail_state] =& $this;
        $PRD_for_page_state_[$this->page_out_state  ] =& $this;
        $PRD_for_page_state_[$this->page_temp_state ] =& $this;
        $PRD_for_page_state_[$this->page_save_state ] =& $this;
        $PRD_for_page_state_[$this->page_bad_state  ] =& $this;
    }
}

$PRD_1 = new ProofingRoundDescriptor(
    'P1',
    -20,
    _("Current First - Round Projects"),
    _("The page-texts are the output from OCR software and have not been looked at."),
    array(
        0 => '#FFE4B5', // mocassin
        1 => '#FFF8DC', // cornsilk
    ),
    array()
);

$PRD_2 = new ProofingRoundDescriptor(
    'P2',
    100,
    _("Current Second - Round Projects"),
    _("The page-texts have already been proofread once, but now need to be examined <b>closely</b> for small errors that may have been missed."),
    array(
        0 => '#DDA0DD', // plum
        1 => '#D8BFD8', // thistle
    ),
    array( $PRD_1 )
);

// Although $PRD_1 and $PRD_2 are variables, they should be treated as
// constants; thus the all-uppercase names.

// ---------------------------

// After creating all descriptors:
assert( $n_rounds == MAX_NUM_PAGE_EDITING_ROUNDS );

// ---------------------------

function get_PRD_for_round_id( $round_id )
{
    global $PRD_for_round_id_;
    return array_get( $PRD_for_round_id_, $round_id, NULL );
}

// ---------------------------

function get_PRD_for_round( $round_number )
// If $round_number is a valid proofing-round number (currently 1 or 2),
// return the appropriate ProofingRoundDescriptor instance.
// Otherwise, return NULL.
{
    global $PRD_for_round_number_;
    return array_get( $PRD_for_round_number_, $round_number, NULL );
}

// ---------------------------

function get_PRD_for_project_state( $project_state )
{
    global $PRD_for_project_state_;
    return array_get( $PRD_for_project_state_, $project_state, NULL );
}

// ---------------------------

function get_PRD_for_page_state( $page_state )
{
    global $PRD_for_page_state_;
    return array_get( $PRD_for_page_state_, $page_state, NULL );
}

// -------------------------------------

$PAGE_STATES_IN_ORDER = array();
for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
{
    $prd = get_PRD_for_round($rn);
    $PAGE_STATES_IN_ORDER[] = $prd->page_avail_state;
    $PAGE_STATES_IN_ORDER[] = $prd->page_out_state;
    $PAGE_STATES_IN_ORDER[] = $prd->page_temp_state;
    $PAGE_STATES_IN_ORDER[] = $prd->page_save_state;
    $PAGE_STATES_IN_ORDER[] = $prd->page_bad_state;
}

// vim: sw=4 ts=4 expandtab
?>
