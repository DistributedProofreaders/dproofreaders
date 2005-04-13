<?PHP

// $Id$

include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'TallyBoard.inc');
include_once($relPath.'Stage.inc');

define('MAX_NUM_PAGE_EDITING_ROUNDS', 4);

// -----------------------------------------------------------------------------

$n_rounds = 0;
$Round_for_round_id_      = array();
$Round_for_round_number_  = array();
$Round_for_project_state_ = array();
$Round_for_page_state_    = array();
$PAGE_STATES_IN_ORDER   = array();

class Round extends Stage
// A container for various constants relating to a particular round of proofing.
{
    function Round(
        $round_id,
        $round_name,
        $access_minima,
        $description,
            // These parameters have the same semantics as the
            // corresponding parameters of the Stage constructor.
            // See Stage.inc for documentation.

        $listing_bgcolors,
            // An array of (two) HTML colors that will be used as the bgcolor
            // in alternating rows in the listing of projects in this round.
        $other_rounds_with_visible_usernames
            // An array of round_ids.
            // If user X worked on a page in this round, they can see the
            // username of another user Y who worked on the page *if* user Y
            // worked on the page in a round that apppears in this parameter.
    )
    {
        $this->Stage(
            $round_id,
            $round_name,
            $access_minima,
            $description,
            "tools/proofers/round.php?round_id=$round_id"
        );

        global $n_rounds;
        $n_rounds++;
        $this->round_number       = $n_rounds;

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

        global $Round_for_round_id_;
        $Round_for_round_id_[$this->id] =& $this;

        global $Round_for_round_number_;
        $Round_for_round_number_[$this->round_number] =& $this;

        global $Round_for_project_state_;
        $Round_for_project_state_[$this->project_unavailable_state] =& $this;
        $Round_for_project_state_[$this->project_waiting_state    ] =& $this;
        $Round_for_project_state_[$this->project_bad_state        ] =& $this;
        $Round_for_project_state_[$this->project_available_state  ] =& $this;
        $Round_for_project_state_[$this->project_complete_state   ] =& $this;

        global $Round_for_page_state_;
        $Round_for_page_state_[$this->page_avail_state] =& $this;
        $Round_for_page_state_[$this->page_out_state  ] =& $this;
        $Round_for_page_state_[$this->page_temp_state ] =& $this;
        $Round_for_page_state_[$this->page_save_state ] =& $this;
        $Round_for_page_state_[$this->page_bad_state  ] =& $this;

        global $PAGE_STATES_IN_ORDER;
        $PAGE_STATES_IN_ORDER[] = $this->page_avail_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_out_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_temp_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_save_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_bad_state;
    }

}

$P1 = new Round(
    'P1',
    _('Proofreading Round 1'),
    array(),
    _("The page-texts are the output from OCR software and have not been looked at."),
    array(
        0 => '#FFE4B5', // mocassin
        1 => '#FFF8DC', // cornsilk
    ),
    array()
);

$P2 = new Round(
    'P2',
    _('Proofreading Round 2'),
    array( 'P1' => 1000, 'F1' => 300, 'days since reg' => 91, 'quiz' => 1 ),
    _("The page-texts have already been proofread once, but now need to be examined <b>closely</b> for small errors that may have been missed."),
    array(
        0 => '#DDA0DD', // plum
        1 => '#D8BFD8', // thistle
    ),
    array( 'P1' )
);

$F1 = new Round(
    'F1',
    _('Formatting Round 1'),
    array( 'P1' => 300, 'days since reg' => 21, 'quiz' => 1 ),
    _("The page-texts have already been proofread twice, but now need to be formatted with markup which may be specific to the project."),
    array(
        0 => '#FFE4B5', // mocassin
        1 => '#FFF8DC', // cornsilk
    ),
    array()
);

$F2 = new Round(
    'F2',
    _('Formatting Round 2'),
    NULL,
    _("The page-texts in this round need to be carefully checked to remove any remaining formatting or proofreading errors."),
    array(
        0 => '#DDA0DD', // plum
        1 => '#D8BFD8', // thistle
    ),
    array( 'F1' )
);

// Although $P1, $P2, etc are variables, they should be treated as constants.

// ---------------------------

// After creating all descriptors:
assert( $n_rounds == MAX_NUM_PAGE_EDITING_ROUNDS );

// ---------------------------

function get_Round_for_round_id( $round_id )
{
    global $Round_for_round_id_;
    return array_get( $Round_for_round_id_, $round_id, NULL );
}

// ---------------------------

function get_Round_for_round_number( $round_number )
// If $round_number is a valid proofing-round number (currently 1 or 2),
// return the appropriate Round instance.
// Otherwise, return NULL.
{
    global $Round_for_round_number_;
    return array_get( $Round_for_round_number_, $round_number, NULL );
}

// ---------------------------

function get_Round_for_project_state( $project_state )
{
    global $Round_for_project_state_;
    return array_get( $Round_for_project_state_, $project_state, NULL );
}

// ---------------------------

function get_Round_for_page_state( $page_state )
{
    global $Round_for_page_state_;
    return array_get( $Round_for_page_state_, $page_state, NULL );
}

// vim: sw=4 ts=4 expandtab
?>
