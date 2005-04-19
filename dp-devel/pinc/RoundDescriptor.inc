<?PHP

// $Id$

include_once($relPath.'misc.inc');
include_once($relPath.'Stage.inc');

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
        $after_satisfying_minima,
        $description,
        $listing_bgcolors,
            // These parameters have the same semantics as the
            // corresponding parameters of the Stage constructor.
            // See Stage.inc for documentation.

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
            $after_satisfying_minima,
            $description,
            $listing_bgcolors,
            "tools/proofers/round.php?round_id=$round_id"
        );

        global $n_rounds;
        $n_rounds++;
        $this->round_number       = $n_rounds;

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

// ---------------------------

function get_Round_for_round_id( $round_id )
{
    global $Round_for_round_id_;
    return array_get( $Round_for_round_id_, $round_id, NULL );
}

// ---------------------------

function get_Round_for_round_number( $round_number )
// If $round_number is a valid proofing-round number,
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
