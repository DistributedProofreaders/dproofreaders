<?PHP

// $Id$

include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'TallyBoard.inc');

define('MAX_NUM_PAGE_EDITING_ROUNDS', 4);

// -----------------------------------------------------------------------------

$n_rounds = 0;
$PRD_for_round_id_      = array();
$PRD_for_round_number_  = array();
$PRD_for_project_state_ = array();
$PRD_for_page_state_    = array();
$PAGE_STATES_IN_ORDER   = array();

class ProofingRoundDescriptor
// A container for various constants relating to a particular round of proofing.
{
    function ProofingRoundDescriptor(
        $round_id,
            // A very short mnemonic identifier for the round.
            // (Should probably conform to the rules for a PHP variable name.)
        $round_name,
            // A gettext-translated name for the round.
        $access_minima,
            // An array of minimum requirements that a user must satisfy
            // in order to be allowed to participate in this round
            // (barring special permission).
            // If empty array, no minima: everyone can participate.
            // If NULL, users cannot qualify for this round by satisfying minima.
        $users_can_be_granted_access,
            // If TRUE, a user can be given access to this round (regardless
            // of how they stand w.r.t access minima) via a notation in the
            // database (specific to that user and this round).
            // (Theoretically, this param is unnecessary. We could act as if it
            // were always TRUE, i.e. always check the db for such a notation;
            // in the cases where this arg *would* be FALSE, we would presumably
            // find nothing in the db, and so arrive at the same answer.
            // However, this param is useful as an optimization and as
            // documentation of intent.)
        $description,
            // A sentence or two explaining what happens in this round.
        $tally_name,
            // The name of the tally for pages saved-as-done in this round.
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
        global $n_rounds;
        $n_rounds++;
        $this->round_number       = $n_rounds;

        $this->round_id           = $round_id;
        $this->round_name         = $round_name;
        $this->access_minima      = $access_minima;
        $this->users_can_be_granted_access = $users_can_be_granted_access;
        $this->description        = $description;
        $this->tally_name         = $tally_name;
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

        global $PAGE_STATES_IN_ORDER;
        $PAGE_STATES_IN_ORDER[] = $this->page_avail_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_out_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_temp_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_save_state;
        $PAGE_STATES_IN_ORDER[] = $this->page_bad_state;
    }

    function user_access( $username, $n_pages_completed=null )
    // Return an array containing:
    // -- a boolean: TRUE iff the user can access this round.
    // -- NULL or an array of arrays (4-tuples):
    //         ( $criterion_str, $minimum, $user_value, $satisfied )
    // -- a non-empty array of strings: sentences (addressed to "you")
    //         saying why you or don't have access.
    // 
    // UNIMPLEMENTED:
    // If $n_pages_completed is non-null, use it as the number of pages
    // that the user has completed. Otherwise, consult the database.
    {
        $res = mysql_query("
            SELECT *
            FROM users
            WHERE username='$username'
        ") or die(mysql_error());

        if ( mysql_num_rows($res) == 0 )
        {
            die( "User '$username' does not exist." );
        }

        $user_obj = mysql_fetch_object($res);


        $minima_table = null;
        $sentences = array();

        // Considering the minima...

        if ( is_null($this->access_minima) )
        {
            $sentences[] = _("Users cannot qualify for this round by satisfying minima.");
            $can = FALSE;
        }
        else if ( empty($this->access_minima) )
        {
            $minima_table = null;
            $sentences[] = _("All users can work in this round.");
            $can = TRUE;
        }
        else
        {
            $minima_table = array();
            $all_minima_satisfied = TRUE;
            foreach ( $this->access_minima as $criterion_code => $minimum )
            {
                if ( $criterion_code == 'days since reg' )
                {
                    $criterion_str = _('days since registration');
                    $user_value = sprintf( '%.1f', ( time() - $user_obj->date_created ) / 86400 );
                }
                else if ( $criterion_code == 'quiz' )
                {
                    assert( $minimum == 1 );
                    $criterion_str = _('proofreading quiz pass');
                    // UNIMPLEMENTED
                    // For now, say they have passed.
                    $user_value = 1;
                }
                else
                {
                    $tally_name = $criterion_code;
                    $criterion_str = sprintf( _("'%s' pages completed"), $tally_name );
                    $tallyboard = new TallyBoard( $tally_name, 'U' );
                    $user_value = $tallyboard->get_current_tally( $user_obj->u_id );
                }
                $satisfied = ( $user_value >= $minimum );
                $minima_table[] = array( $criterion_str, $minimum, $user_value, $satisfied );
                if ( !$satisfied ) $all_minima_satisfied = FALSE;
            }

            if ( $all_minima_satisfied )
            {
                $sentences[] = _('You satisfy the minima, so you are allowed in the round.');
                $can = TRUE;
            }
            else
            {
                $sentences[] = _('You do not satisfy the minima.');
                $can = FALSE;
            }
        }

        // Considering special grant of access:
        if (!$can)
        {
            if ( $this->users_can_be_granted_access )
            {
                if ( $this->user_has_been_granted_access($username) )
                {
                    $can = TRUE;
                    $sentences[] = _("However, you have been granted access to this round.");
                }
                else
                {
                    $can = FALSE;
                    $sentences[] = _("However, you can be granted access to this round. See XXX for details.");
                }
            }
            else
            {
                // There is no granting of access for this round.
                $can = FALSE;
            }
        }

        // Site managers can go anywhere.
        if ( !$can )
        {
            if ($user_obj->sitemanager == 'yes')
            {
                $can = TRUE;
                $sentences[] = _("But ultimately, you're a site manager, so you have access to everywhere.");
            }
        }

        return array( $can, $minima_table, $sentences );
    }

    function user_has_been_granted_access( $username )
    {
        $res = mysql_query("
            SELECT value
            FROM usersettings
            WHERE username='$username' AND setting='{$this->round_id}.access'
        ") or die(mysql_error());
        return (mysql_num_rows($res) >= 1 and mysql_result($res,0) == 'yes');
    }

    function roundCode()
    {
        return $this->round_id;
    }
}

$P1 = new ProofingRoundDescriptor(
    'P1',
    _('Proofreading Round 1'),
    array(),
    FALSE,
    _("The page-texts are the output from OCR software and have not been looked at."),
    'P',
    array(
        0 => '#FFE4B5', // mocassin
        1 => '#FFF8DC', // cornsilk
    ),
    array()
);

$P2 = new ProofingRoundDescriptor(
    'P2',
    _('Proofreading Round 2'),
    array( 'P' => 1000, 'days since reg' => 91, 'quiz' => 1 ),
    FALSE,
    _("The page-texts have already been proofread once, but now need to be examined <b>closely</b> for small errors that may have been missed."),
    'P',
    array(
        0 => '#DDA0DD', // plum
        1 => '#D8BFD8', // thistle
    ),
    array( 'P1' )
);

$F1 = new ProofingRoundDescriptor(
    'F1',
    _('Formatting Round 1'),
    array( 'P' => 300, 'days since reg' => 21, 'quiz' => 1 ),
    FALSE,
    _("The page-texts have already been proofread twice, but now need to be formatted with markup which may be specific to the project."),
    'F',
    array(
        0 => '#FFE4B5', // mocassin
        1 => '#FFF8DC', // cornsilk
    ),
    array()
);

$F2 = new ProofingRoundDescriptor(
    'F2',
    _('Formatting Round 2'),
    NULL,
    TRUE,
    _("The page-texts in this round need to be carefully checked to remove any remaining formatting or proofreading errors."),
    'F',
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

// vim: sw=4 ts=4 expandtab
?>
