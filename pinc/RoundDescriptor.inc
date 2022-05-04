<?php

// $Id$

include_once($relPath.'misc.inc');
include_once($relPath.'Stage.inc');

// -----------------------------------------------------------------------------

global $n_rounds, $Round_for_round_id_, $Round_for_round_number_,
    $Round_for_project_state_, $Round_for_page_state_, $PAGE_STATES_IN_ORDER;

$n_rounds = 0;
$Round_for_round_id_ = [];
$Round_for_round_number_ = [];
$Round_for_project_state_ = [];
$Round_for_page_state_ = [];
$PAGE_STATES_IN_ORDER = [];

class Round extends Stage
// A container for various constants relating to a particular round of proofreading.
{
    public function __construct(
        $round_id,
        $round_name,
        $access_minima,
        $after_satisfying_minima,
        $evaluation_criteria,
        $access_change_callback,
        $description,
        $document,

        $pi_tools,
            // A list of which tools should be available in the proofreading
            // interface's toolbox when proofreading a page in this round.
            // The format is:
            // [
            //     'popup_links' => [ 'link1', 'link2', ... ],
            //     'tool_buttons' => [ 'button1', 'button2', ... ],
            //     'tool_links' => [ 'link1', 'link2', ... ],
            // ]
            // Alternatively, if you want all tools to be visible, use 'ALL'
            // for the array instead:
            // [
            //     'popup_links' => 'ALL',
            //     'tool_buttons' => 'ALL',
            //     'tool_links' => 'ALL',
            // ]
            // See pinc/ProofreadingToolbox.inc for which tools are avaialble
            // and their names.

        $daily_page_limit,
            // This is either NULL (indicating no daily page limit for this round)
            // or a non-negative (though likely positive) integer.

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
    ) {
        parent::__construct(
            $round_id,
            $round_name,
            $access_minima,
            $after_satisfying_minima,
            $evaluation_criteria,
            $access_change_callback,
            $description,
            $document,
            "tools/proofers/round.php?round_id=$round_id"
        );

        global $n_rounds;
        $n_rounds++;
        $this->round_number = $n_rounds;

        $this->pi_tools = $pi_tools;
        $this->daily_page_limit = $daily_page_limit;
        $this->other_rounds_with_visible_usernames = $other_rounds_with_visible_usernames;
        $this->honorifics = $honorifics;
        krsort($this->honorifics);

        $this->project_unavailable_state = constant("PROJ_{$round_id}_UNAVAILABLE");
        $this->project_waiting_state = constant("PROJ_{$round_id}_WAITING_FOR_RELEASE");
        $this->project_bad_state = constant("PROJ_{$round_id}_BAD_PROJECT");
        $this->project_available_state = constant("PROJ_{$round_id}_AVAILABLE");
        $this->project_complete_state = constant("PROJ_{$round_id}_COMPLETE");

        $this->page_avail_state = "{$round_id}.page_avail";
        $this->page_out_state = "{$round_id}.page_out";
        $this->page_temp_state = "{$round_id}.page_temp";
        $this->page_save_state = "{$round_id}.page_saved";
        $this->page_bad_state = "{$round_id}.page_bad";

        $this->time_column_name = "round{$this->round_number}_time";
        $this->text_column_name = "round{$this->round_number}_text";
        $this->user_column_name = "round{$this->round_number}_user";
        $this->textdiff_column_name = "round{$this->round_number}_diff"; // a computed column

        // prevtext_column_name
        //
        // This used to be just a simple column name
        // (the name of the 'text' column of the immediately previous round).
        //
        // But now that a project can skip rounds,
        // the previous version of the text may be further back than that,
        // so prevtext_column_name is now an SQL expression
        // identifying the appropriate text column.
        //
        // Here's the pattern in pseudocode:
        //   r1: master_text
        //   r2: if r1_user != '' then r1_text else master_text
        //   r3: if r2_user != '' then r2_text else if r1_user != '' then r1_text else master_text
        //   etc.
        //
        if ($this->round_number == 1) {
            $this->prevtext_column_name = "master_text";
        } else {
            $sql = "CASE";
            for ($rn = $this->round_number - 1; $rn > 0; $rn--) {
                $sql .= " WHEN LENGTH(round{$rn}_user) THEN round{$rn}_text";
            }
            $sql .= " ELSE master_text";
            $sql .= " END";
            $this->prevtext_column_name = $sql;
        }

        // These can be changed by calling declare_mentoring_pair():
        $this->mentor_round = null;
        $this->mentee_round = null;

        global $Round_for_round_id_;
        $Round_for_round_id_[$this->id] = & $this;

        global $Round_for_round_number_;
        $Round_for_round_number_[$this->round_number] = & $this;

        global $Round_for_project_state_;
        $Round_for_project_state_[$this->project_unavailable_state] = & $this;
        $Round_for_project_state_[$this->project_waiting_state] = & $this;
        $Round_for_project_state_[$this->project_bad_state] = & $this;
        $Round_for_project_state_[$this->project_available_state] = & $this;
        $Round_for_project_state_[$this->project_complete_state] = & $this;

        global $Round_for_page_state_;
        $Round_for_page_state_[$this->page_avail_state] = & $this;
        $Round_for_page_state_[$this->page_out_state] = & $this;
        $Round_for_page_state_[$this->page_temp_state] = & $this;
        $Round_for_page_state_[$this->page_save_state] = & $this;
        $Round_for_page_state_[$this->page_bad_state] = & $this;

        global $PAGE_STATES_IN_ORDER;
        $PAGE_STATES_IN_ORDER[] = $this->page_avail_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_out_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_temp_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_save_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_bad_state;
    }

    public function is_a_mentee_round()
    {
        return !is_null($this->mentor_round);
    }
    public function is_a_mentor_round()
    {
        return !is_null($this->mentee_round);
    }

    // -----------

    public function has_a_daily_page_limit()
    {
        return !is_null($this->daily_page_limit);
    }

    // -----------

    public function get_honorific_for_page_tally($page_tally)
    {
        // Note that krsort($this->honorifics) put it in descending order.
        foreach ($this->honorifics as $threshold => $honorific) {
            if ($page_tally >= $threshold) {
                return $honorific;
            }
        }
        // $page_tally is less than the least threshold.
        // Unusual, since the least threshold is usually zero,
        // but it can happen.
        // Give them the lowest honorific.
        return $honorific;
    }

    public function user_can_access()
    {
        global $code_url, $pguser;

        $uao = $this->user_access($pguser);
        if (!$uao->can_access) {
            $local_message = sprintf(
                // TRANSLATORS: %1$s is the round name, %2$s is the round ID (eg: P1)
                _('You have not yet been cleared to work on projects in %1$s (%2$s). Please visit <a href="%3$s">the %2$s home</a> to find out what happens in this round and how you can qualify to work in it.'),
                $this->name,
                $this->id,
                "$code_url/tools/proofers/round.php?round_id=$this->id",
            );
            throw new UserAccessException($local_message, DP::USER_NOT_QUALIFIED_FOR_ROUND);
        }
    }
}

// ---------------------------

function get_Round_for_round_id($round_id)
{
    global $Round_for_round_id_;
    return array_get($Round_for_round_id_, $round_id, null);
}

// ---------------------------

function get_Round_for_round_number($round_number)
// If $round_number is a valid proofreading-round number,
// return the appropriate Round instance.
// Otherwise, return NULL.
{
    global $Round_for_round_number_;
    return array_get($Round_for_round_number_, $round_number, null);
}

// ---------------------------

function get_Round_for_project_state($project_state)
{
    global $Round_for_project_state_;
    return array_get($Round_for_project_state_, $project_state, null);
}

// ---------------------------

function get_Round_for_page_state($page_state)
{
    global $Round_for_page_state_;
    return array_get($Round_for_page_state_, $page_state, null);
}

// ---------------------------

function get_Round_for_text_column_name($text_column_name)
{
    global $Round_for_round_id_;
    foreach ($Round_for_round_id_ as $round_id => $round) {
        if ($round->text_column_name == $text_column_name) {
            return $round;
        }
    }
    return null;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function sql_collater_for_round_id($round_id_column)
// In an SQL query, if you "ORDER BY round_id", it will use alphabetical order,
// which is not very useful. Instead, ORDER BY the result of this function,
// and it will use the canonical order-of-declaration for rounds.
{
    global $Round_for_round_id_;
    return sprintf("FIELD($round_id_column, %s)",
        surround_and_join(array_keys($Round_for_round_id_), "'", "'", ","));
}

function sql_collater_for_page_state($state_column)
// In an SQL query, if you "ORDER BY state", it will use alphabetical order,
// which is not very useful. Instead, ORDER BY the result of this function,
// and it will use the canonical order-of-declaration for page states.
{
    global $PAGE_STATES_IN_ORDER;
    return sprintf("FIELD($state_column, %s)",
        surround_and_join($PAGE_STATES_IN_ORDER, "'", "'", ","));
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function declare_mentoring_pair($mentee_round_id, $mentor_round_id)
// Asserts that, for difficulty='beginner' projects, pages done in one round
// (the "mentored" or "mentee" round) will garner feedback by qualified users
// ("mentors") in a subsequent round (the "mentoring" or "mentor" round).
{
    $mentee_round = & get_Stage_for_id($mentee_round_id);
    $mentor_round = & get_Stage_for_id($mentor_round_id);
    assert(is_a($mentee_round, 'Round'));
    assert(is_a($mentor_round, 'Round'));

    // Currently, we don't allow:
    // -- a round to be mentored by more than one round, or
    // -- a round to mentor more than one round.
    assert(is_null($mentee_round->mentor_round));
    assert(is_null($mentor_round->mentee_round));

    $mentee_round->mentor_round = $mentor_round;
    $mentor_round->mentee_round = $mentee_round;
}
