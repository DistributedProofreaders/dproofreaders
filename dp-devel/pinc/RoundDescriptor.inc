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
        $document,
        $listing_bgcolors,
            // These parameters have the same semantics as the
            // corresponding parameters of the Stage constructor.
            // See Stage.inc for documentation.

        $other_rounds_with_visible_usernames,
            // An array of round_ids.
            // If user X worked on a page in this round, they can see the
            // username of another user Y who worked on the page *if* user Y
            // worked on the page in a round that apppears in this parameter.

        $honorifics
            // An array of integer => string items that determine a user's
            // "title" on the basis of their page tally in this round.
            // In each item:
            // -- The number is a page-tally threshold.
            // -- The string is the honorific for someone who has achieved that
            //    threshold, but not the next higher.
            // (Needn't be in a particular order.)
    )
    {
        $this->Stage(
            $round_id,
            $round_name,
            $access_minima,
            $after_satisfying_minima,
            $description,
            $document,
            $listing_bgcolors,
            "tools/proofers/round.php?round_id=$round_id"
        );

        global $n_rounds;
        $n_rounds++;
        $this->round_number       = $n_rounds;

        $this->other_rounds_with_visible_usernames = $other_rounds_with_visible_usernames;
        $this->honorifics = $honorifics;
        krsort($this->honorifics);

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

        // These can be changed by calling declare_mentoring_pair():
        $this->mentor_round = null;
        $this->mentee_round = null;

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

    function is_a_mentee_round() { return !is_null($this->mentor_round); }
    function is_a_mentor_round() { return !is_null($this->mentee_round); }

    function get_honorific_for_page_tally( $page_tally )
    {
        // Note that krsort($this->honorifics) put it in descending order.
        foreach ( $this->honorifics as $threshold => $honorific )
        {
            if ( $page_tally >= $threshold )
            {
                return $honorific;
            }
        }
        // $page_tally is less than the least threshold.
        // Unusual, since the least threshold is usually zero,
        // but it can happen.
        // Give them the lowest honorific.
        return $honorific;
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

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function sql_collater_for_round_id( $round_id_column )
// In an SQL query, if you "ORDER BY round_id", it will use alphabetical order,
// which is not very useful. Instead, ORDER BY the result of this function,
// and it will use the canonical order-of-declaration for rounds.
{
    global $Round_for_round_id_;
    $s = "FIELD($round_id_column";
    foreach ( $Round_for_round_id_ as $round_id => $round )
    {
        $s .= ",'$round_id'";
    }
    $s .= ")";
    return $s;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function declare_mentoring_pair( $mentee_round_id, $mentor_round_id )
// Asserts that, for difficulty='beginner' projects, pages done in one round
// (the "mentored" or "mentee" round) will garner feedback by qualified users
// ("mentors") in a subsequent round (the "mentoring" or "mentor" round).
{
    $mentee_round =& get_Stage_for_id( $mentee_round_id );
    $mentor_round =& get_Stage_for_id( $mentor_round_id );
    assert( is_a( $mentee_round, 'Round' ) );
    assert( is_a( $mentor_round, 'Round' ) );

    // Currently, we don't allow:
    // -- a round to be mentored by more than one round, or
    // -- a round to mentor more than one round.
    assert( is_null($mentee_round->mentor_round) );
    assert( is_null($mentor_round->mentee_round) );

    $mentee_round->mentor_round = $mentor_round;
    $mentor_round->mentee_round = $mentee_round;
}

// vim: sw=4 ts=4 expandtab
?>
