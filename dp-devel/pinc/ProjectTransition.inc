<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');

$PROJECT_TRANSITIONS = array();

class ProjectTransition
{
    function ProjectTransition(
        $curr_state,
        $next_state,
        $restriction,
        $action_name,
        $confirmation_question,
        $action_type,
        $checkedoutby_to_transit,
        $destination
    )
    // A user who satisfies the $restriction can cause
    // a project to transit from $curr_state to $next_state.
    {
        $this->curr_state  = $curr_state;
        $this->next_state  = $next_state;
        $this->restriction = $restriction;
        $this->action_name = $action_name;
        $this->confirmation_question = $confirmation_question;
        $this->action_type = $action_type;
        $this->checkedoutby_to_transit = $checkedoutby_to_transit;

        if ( $destination == '[project page]' )
        {
            global $code_url;
            $destination = "$code_url/project.php?id=<PROJECTID>&amp;expected_state=$next_state";
        }
        $this->destination = $destination;

        global $PROJECT_TRANSITIONS;
        $PROJECT_TRANSITIONS[] =& $this;
    }

    function is_valid_for( $project, $username )
    {
        if ( $this->curr_state != $project->state ) return FALSE;

        if ( $this->restriction == 'holder' )
        {
            // The user (if any) who currently has the project checked out
            // can perform the action.
            return ( $username == $project->checkedoutby );
        }

        $restriction_words = explode(' ', $this->restriction);
        if ( $restriction_words[0] == 'user_can_work_in_stage' )
        {
            assert( count($restriction_words) == 2 );
            $stage_id = $restriction_words[1];
            // Anyone who can work in this particular stage
            // can perform the action.
            return user_can_work_in_stage($username, $stage_id);
        }

        die("transition has bad 'restriction' value: '$this->restriction'");
    }
}

// -----------------------------------------------------------------------------

function get_valid_transitions( $project, $username )
// Return an array of transitions that the given user
// can perform on the project (in its current state).
{
    global $PROJECT_TRANSITIONS;

    $valids = array();
    foreach ( $PROJECT_TRANSITIONS as $transition )
    {
        if ( $transition->is_valid_for($project, $username) )
        {
            $valids[] = $transition;
        }
    }

    return $valids;
}

// -----------------------------------------------------------------------------

function get_transition( $curr_state, $next_state )
{
    global $PROJECT_TRANSITIONS;
    foreach ( $PROJECT_TRANSITIONS as $transition )
    {
        if ( $transition->curr_state == $curr_state &&
             $transition->next_state == $next_state )
        {
            return $transition;
        }
    }
    return NULL;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// X_AVAILABLE -> X_CHECKED_OUT
// Anyone who can work in stage X can check out the project.

new ProjectTransition(
    PROJ_POST_FIRST_AVAILABLE,
    PROJ_POST_FIRST_CHECKED_OUT,
    'user_can_work_in_stage PP',
    _("Check Out Book"),
    _("Are you sure you want to check this book out for post processing?"),
    'transit_and_redirect',
    TRUE,
    '[project page]'
);

new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_POST_SECOND_CHECKED_OUT,
    'user_can_work_in_stage PPV',
    _("Check Out Book"),
    _("Are you sure you want to check this book out for verifying post processing?"),
    'transit_and_redirect',
    TRUE,
    '[project page]'
);

new ProjectTransition(
    PROJ_CORRECT_AVAILABLE,
    PROJ_CORRECT_CHECKED_OUT,
    'user_can_work_in_stage CR',
    _("Check Out Book"),
    _("Are you sure you want to check this book out to review corrections?"),
    'transit_and_redirect',
    TRUE,
    '[project page]'
);

// -----------------------------------------------------------------------------

// X_CHECKED_OUT -> X_AVAILABLE
// The user who has the project checked out for X can abandon/return it,
// making it available for others to check out.

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_FIRST_AVAILABLE,
    'holder',
    _("Return to Available"),
    _("Are you sure you want to make this book available to others for post processing?"),
    'transit_and_redirect',
    FALSE,
    "pool.php?pool_id=PP"
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_SECOND_AVAILABLE,
    'holder',
    _("Return to Available"),
    _("Are you sure you want to make this book available to others to verify and lose your work?"),
    'transit_and_redirect',
    FALSE,
    "pool.php?pool_id=PPV"
);

new ProjectTransition(
    PROJ_CORRECT_CHECKED_OUT,
    PROJ_CORRECT_AVAILABLE,
    'holder',
    _("Return to Available"),
    _("Are you sure you want to make this book available to others for reviewing corrections?"),
    'transit_and_redirect',
    FALSE,
    "pool.php?pool_id=CR"
);

// -----------------------------------------------------------------------------

// X_CHECKED_OUT -> something other than X_AVAILABLE
// The user who has the project checked out for X
// can check it in (or return it to a previous holder).

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_SECOND_AVAILABLE,
    'holder',
    _("Upload for Verification"),
    NULL,
    'redirect',
    FALSE,
    "upload_text.php?project=<PROJECTID>&stage=post_1"
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_FIRST_CHECKED_OUT,
    'holder',
    _("Return to Post-Processor"),
    _("Are you sure you want to return this book to the post-processor for further work?"),
    'transit_and_redirect',
    FALSE,
    "pool.php?pool_id=PPV"
);

new ProjectTransition(
    PROJ_CORRECT_CHECKED_OUT,
    PROJ_SUBMIT_PG_POSTED,
    'holder',
    _("Posted to Project Gutenberg"),
    NULL,
    'redirect',
    FALSE,
    "correct/completecorr.php?project=<PROJECTID>"
);

// vim: sw=4 ts=4 expandtab
?>
