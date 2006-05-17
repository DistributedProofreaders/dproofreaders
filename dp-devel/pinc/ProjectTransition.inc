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
        if ( $action_name == '[default]' )
        {
            $action_name = sprintf(
                _('Change State to %s'),
                project_states_text($next_state)
            );
        }

        if ( $confirmation_question == '[default]' )
        {
            $confirmation_question = sprintf(
                _('Are you sure you want to change the state of this project to %s?'),
                project_states_text($next_state)
            );
        }

        $this->curr_state  = $curr_state;
        $this->next_state  = $next_state;
        $this->restriction = $restriction;
        $this->action_name = $action_name;
        $this->confirmation_question = $confirmation_question;
        $this->action_type = $action_type;
        $this->checkedoutby_to_transit = $checkedoutby_to_transit;

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
            // To handle cases where that person goes AWOL,
            // we also allow site admins to perform the action.
            return (
                $username == $project->checkedoutby
                ||
                that_user_is_a_sitemanager($username)
            );
        }
        elseif ( $this->restriction == 'manager' )
        {
            return $project->can_be_managed_by_user($username);
        }
        elseif ( $this->restriction == 'proj_facilitator' )
        {
            return (
                that_user_is_proj_facilitator($username)
                || 
                that_user_is_a_sitemanager($username)
            );
        }
        elseif ( $this->restriction == 'site_manager' )
        {
            return that_user_is_a_sitemanager($username);
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

new ProjectTransition(
    PROJ_NEW,
    PROJ_P1_UNAVAILABLE,
    'manager',
    '[default]',
    NULL,
    'transit_and_redirect',
    FALSE,
    '<RETURN>'
);

if ($site_supports_metadata)
{
    new ProjectTransition(
        PROJ_NEW_FILE_UPLOADED,
        PROJ_NEW_METADATA_FIRST,
        'manager',
        '[default]',
        NULL,
        'transit_and_redirect',
        FALSE,
        '<RETURN>'
    );
}

// -----------------------------------------------------------------------------

for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
{
    $round = get_Round_for_round_number($rn);

    new ProjectTransition(
        $round->project_unavailable_state,
        $round->project_waiting_state,
        'manager',
        '[default]',
        NULL,
        'transit_and_redirect',
        FALSE,
        '<RETURN>'
    );

    new ProjectTransition(
        $round->project_waiting_state,
        $round->project_unavailable_state,
        'manager',
        '[default]',
        NULL,
        'transit_and_redirect',
        FALSE,
        '<RETURN>'
    );

    new ProjectTransition(
        $round->project_waiting_state,
        $round->project_available_state,
        'proj_facilitator',
        '[default]',
        '[default]',
        'transit_and_redirect',
        FALSE,
        '<RETURN>'
    );

    new ProjectTransition(
        $round->project_bad_state,
        $round->project_unavailable_state,
        'manager',
        '[default]',
        NULL,
        'transit_and_redirect',
        FALSE,
        '<RETURN>'
    );

    new ProjectTransition(
        $round->project_bad_state,
        'automodify',
        'manager',
        _('automodify'),
        NULL,
        'redirect',
        FALSE,
        'automodify.php?project=<PROJECTID>'
        // which will leave the project in the appropriate
        // BAD, AVAILABLE, or COMPLETE state.
    );

    new ProjectTransition(
        $round->project_available_state,
        $round->project_unavailable_state,
        'manager',
        '[default]',
        '[default]',
        'transit_and_redirect',
        FALSE,
        '<RETURN>'
    );

    new ProjectTransition(
        $round->project_available_state,
        'automodify',
        'manager',
        _('automodify'),
        NULL,
        'redirect',
        FALSE,
        'automodify.php?project=<PROJECTID>'
    );
}

// -----------------------------------------------------------------------------
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
    '<RETURN>'
);

new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_POST_SECOND_CHECKED_OUT,
    'user_can_work_in_stage PPV',
    _("Check Out Book"),
    _("Are you sure you want to check this book out for verifying post processing?"),
    'transit_and_redirect',
    TRUE,
    '<RETURN>'
);

new ProjectTransition(
    PROJ_CORRECT_AVAILABLE,
    PROJ_CORRECT_CHECKED_OUT,
    'user_can_work_in_stage CR',
    _("Check Out Book"),
    _("Are you sure you want to check this book out to review corrections?"),
    'transit_and_redirect',
    TRUE,
    '<RETURN>'
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
    '<RETURN>'
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_SECOND_AVAILABLE,
    'holder',
    _("Return to Available"),
    _("Are you sure you want to make this book available to others to verify and lose your work?"),
    'transit_and_redirect',
    FALSE,
    '<RETURN>'
);

new ProjectTransition(
    PROJ_CORRECT_CHECKED_OUT,
    PROJ_CORRECT_AVAILABLE,
    'holder',
    _("Return to Available"),
    _("Are you sure you want to make this book available to others for reviewing corrections?"),
    'transit_and_redirect',
    FALSE,
    '<RETURN>'
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
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_SECOND_CHECKED_OUT,
    'site_manager',
    '[default]',
    NULL,
    'transit_and_redirect',
    TRUE,
    '<RETURN>'
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_FIRST_CHECKED_OUT,
    'holder',
    _("Return to Post-Processor"),
    _("Are you sure you want to return this book to the post-processor for further work?"),
    'transit_and_redirect',
    FALSE,
    '<RETURN>'
);

// -----------------------------------------------------------------------------
// Transitions to and from PROJ_POST_FIRST_UNAVAILABLE:

// To PROJ_POST_FIRST_UNAVAILABLE:

new ProjectTransition(
    PROJ_POST_FIRST_AVAILABLE,
    PROJ_POST_FIRST_UNAVAILABLE,
    'manager',
    '[default]',
    '[default]',
    'transit_and_redirect',
    FALSE,
    '<RETURN>'
);

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_FIRST_UNAVAILABLE,
    'manager',
    '[default]',
    _('Someone currently has this project checked out. Are you sure you want to make it unavailable?'),
    'transit_and_redirect',
    FALSE,
    '<RETURN>'
);

// From PROJ_POST_FIRST_UNAVAILABLE:

new ProjectTransition(
    PROJ_POST_FIRST_UNAVAILABLE,
    PROJ_POST_FIRST_AVAILABLE,
    'manager',
    _('Make Project Available for Post-Processing'),
    '[default]',
    'transit_and_redirect',
    FALSE,
    '<RETURN>'
);

new ProjectTransition(
    PROJ_POST_FIRST_UNAVAILABLE,
    PROJ_POST_FIRST_CHECKED_OUT,
    'manager',
    _('Check Out Project for Post-Processing'),
    _("It's unclear whether this transition should even be possible. Are you sure?"),
    'transit_and_redirect',
    TRUE,
    '<RETURN>'
);

// -----------------------------------------------------------------------------
// Transitions to PROJ_POST_COMPLETE:
// (We should probably eliminate these, and the PROJ_POST_COMPLETE state.)

$post_complete_question =
    _("Sending a project to post_complete probably isn't a good idea. Are you sure?");

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_COMPLETE,
    'site_manager',
    '[default]',
    $post_complete_question,
    'transit_and_redirect',
    FALSE,
    '<RETURN>'
);

new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_POST_COMPLETE,
    'site_manager',
    '[default]',
    $post_complete_question,
    'transit_and_redirect',
    FALSE,
    '<RETURN>'
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_COMPLETE,
    'site_manager',
    '[default]',
    $post_complete_question,
    'transit_and_redirect',
    FALSE,
    '<RETURN>'
);

// -----------------------------------------------------------------------------
// Transitions to PROJ_SUBMIT_PG_POSTED:

$pg_posted_question =
    _('Project has been posted by PG?');

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_SUBMIT_PG_POSTED,
    'site_manager',
    '[default]',
    $pg_posted_question,
    'transit_and_redirect',
    FALSE,
    'editproject.php?action=edit&project=<PROJECTID>&posted=1'
);

new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_SUBMIT_PG_POSTED,
    'site_manager',
    '[default]',
    $pg_posted_question,
    'transit_and_redirect',
    FALSE,
    'editproject.php?action=edit&project=<PROJECTID>&posted=1'
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_SUBMIT_PG_POSTED,
    'site_manager',
    '[default]',
    $pg_posted_question,
    'transit_and_redirect',
    FALSE,
    'editproject.php?action=edit&project=<PROJECTID>&posted=1'
);

new ProjectTransition(
    PROJ_POST_COMPLETE,
    PROJ_SUBMIT_PG_POSTED,
    'site_manager',
    '[default]',
    $pg_posted_question,
    'transit_and_redirect',
    FALSE,
    'editproject.php?action=edit&project=<PROJECTID>&posted=1'
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

// -----------------------------------------------------------------------------
// Transitions to PROJ_DELETE.

foreach ( $PROJECT_STATES_IN_ORDER as $curr_state )
{
    // Don't bother creating a transition for Delete -> Delete.
    if ( $curr_state == PROJ_DELETE ) continue;

    // A NEW project can be deleted by its manager.
    // Anything else requires an SA.
    $who_may_delete = (
        $curr_state == PROJ_NEW 
        ? 'manager'
        : 'site_manager'
    );

    new ProjectTransition(
        $curr_state,
        PROJ_DELETE,
        $who_may_delete,
        _('Delete Project'),
        _('<P><B>NOTE:</B> Deleting is only for a project that is beyond repair.<P>Are you sure you want to delete this project?'),
        'transit_and_redirect',
        FALSE,
        '<RETURN>'
    );
}

// vim: sw=4 ts=4 expandtab
?>
