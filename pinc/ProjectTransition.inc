<?php
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'DPage.inc'); // project_drop_pages Pages_prepForRound
include_once($relPath.'forum_interface.inc'); // topic_change_forum
include_once($relPath.'maybe_mail.inc'); // maybe_mail_project_manager
include_once($relPath.'../tools/project_manager/post_files.inc');
include_once($relPath.'project_events.inc');
include_once($relPath.'site_vars.php'); // $auto_post_to_project_topic
include_once($relPath.'user_project_info.inc');
include_once($relPath.'User.inc');

define('PT_AUTO', '[AUTO]');

$PROJECT_TRANSITIONS = array();

class ProjectTransition
{
    function __construct(
        $from_state,
        $to_state,
        $who_restriction,
        $options
            // project_restriction
            // action_name
            // confirmation_question
            // detour
            // settings_template
            // collateral_actions
            // disabled_during_SR
    )
    // A user who satisfies the $who_restriction can cause
    // a project that satisfies $project_restriction
    // to transit from $from_state to $to_state.
    {
        $this->from_state  = $from_state;
        $this->to_state    = $to_state;
        $this->who_restriction = $who_restriction;

        foreach ( $options as $option_name => $option_value )
        {
            $this->$option_name = $option_value;
        }

        if ( $this->action_name == '[default]' )
        {
            $this->action_name = sprintf(
                _('Change State to %s'),
                project_states_text($this->to_state)
            );
        }

        if ( $this->confirmation_question == '[default]' )
        {
            $this->confirmation_question = sprintf(
                _('Are you sure you want to change the state of this project to %s?'),
                project_states_text($this->to_state)
            );
        }

        global $PROJECT_TRANSITIONS;
        $PROJECT_TRANSITIONS[] =& $this;
    }

    function is_valid_for( $project, $who )
    {
        if ( $this->from_state != $project->state ) return FALSE;

        if ( $this->project_restriction )
        {
            $function_name = $this->project_restriction;
            if ( !$function_name( $project, $this ) )
            {
                return FALSE;
            }
        }

        foreach ( explode('|', $this->who_restriction) as $simple_who_restriction )
        {
            if ( $this->is_valid_for_who( $project, $simple_who_restriction, $who ) )
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    function is_valid_for_who( $project, $simple_who_restriction, $who )
    {
        if ( $simple_who_restriction == 'holder' )
        {
            // The user (if any) who currently has the project checked out
            // can perform the action.
            // To handle cases where that person goes AWOL,
            // we also allow site admins to perform the action.
            return (
                $who == $project->checkedoutby
                ||
                that_user_is_a_sitemanager($who)
            );
        }
        elseif ( $simple_who_restriction == 'unrestricted_manager' )
        {
            return $project->can_be_managed_by_user($who)
                && (! that_user_has_project_loads_disabled($who) );
        }
        elseif ( $simple_who_restriction == 'manager' )
        {
            return $project->can_be_managed_by_user($who);
        }
        elseif ( $simple_who_restriction == 'proj_facilitator' )
        {
            return (
                that_user_is_proj_facilitator($who)
                || 
                that_user_is_a_sitemanager($who)
            );
        }
        elseif ( $simple_who_restriction == 'site_manager' )
        {
            return that_user_is_a_sitemanager($who);
        }
        elseif ( $simple_who_restriction == 'PMholder' )
        {
            return (
                $project->can_be_managed_by_user($who) 
                &&
                $project->checkedoutby == $project->username
            );
        }
        elseif ( $simple_who_restriction == 'auto' )
        {
            return ($who == PT_AUTO);
        }

        $restriction_words = explode(' ', $simple_who_restriction);
        if ( $restriction_words[0] == 'user_can_work_in_stage' )
        {
            assert( count($restriction_words) == 2 );
            $stage_id = $restriction_words[1];
            // Anyone who can work in this particular stage
            // can perform the action.
            return user_can_work_in_stage($who, $stage_id);
        }
        else if ( $restriction_words[0] == 'user_can_check_out_in_stage' )
        {
            assert( count($restriction_words) == 2 );
            $stage_id = $restriction_words[1];
            if ($stage_id == 'PP')
            {
                // have to be able to work in the stage, and
                // not be over the limit
                return user_can_work_in_stage($who, $stage_id) 
                    && ! that_user_is_over_PP_checked_out_limit($who);
            }
            else
            {
                // have to be able to work in the stage
                return user_can_work_in_stage($who, $stage_id);
            }
        }

        die("transition has bad 'restriction' value: '$simple_who_restriction'");
    }

    // Returns whether the transition is currently disabled
    function is_disabled($project)
    {
        return ($this->why_disabled($project) != '');
    }

    // Returns the reason why the ProjectTransition is currently disabled
    //
    //   SR = Disabled during Smooth Reading
    function why_disabled($project)
    {
        if ($project->is_available_for_smoothreading() &&
            isset($this->disabled_during_SR) &&
            $this->disabled_during_SR)
        {
            return 'SR';
        }

        // If we reached this, there is no reason for it to be disabled
        return '';
    }

    // ---------------------------------------------------------------

    function do_state_change( $project, $who, $extras )
    // Apply the transition to the given project,
    // and perform any attendant processing.
    // If there are any problems, return a string containing an error message.
    // Otherwise, return an empty string.
    //
    // This function produces no output except for debugging messages.
    //
    // This function should be the only code that modifies the 'state'
    // column of the 'projects' table.
    // It should perhaps also be the only place that modifies these columns:
    //     modifieddate checkedoutby postproofer postcomments
    {
        global $testing;

        // make sure this transition is valid
        assert( $this->is_valid_for( $project, $who ) );

        $projectid = $project->projectid;

        $from_round = get_Round_for_project_state($this->from_state);
        $to_round   = get_Round_for_project_state($this->to_state);
        if ( !is_null($to_round) )
        {
            // do some checks on the project if it's going into a round
            if ( $this->to_state == $to_round->project_waiting_state
              || $this->to_state == $to_round->project_available_state )
            {
                $errors = project_pre_release_check( get_object_vars($project), $to_round );
                if ($errors)
                {
                    $error = _("Transition disallowed because of project problems:")."\n<pre>\n$errors</pre>";
                    // At this point, the corresponding code in changestate.php
                    // would transit the project to the round's bad state.
                    // It's probably sufficient to just leave it in its current state.
                    return $error;
                }
            }
        }

        if ( $this->to_state == PROJ_DELETE )
        {
            global $projects_dir;
            exec("rm -rf $projects_dir/$projectid");

            project_drop_pages( $projectid );

            delete_project_wordcheck_events( $projectid );

            // Formerly, if the project was in the 'New' state, we would here
            // delete it from the projects table and log a 'deletion' event.
            // However, it's somewhat problematic to delete the entry from the
            // 'projects' table. Instead, we now treat such cases like all
            // other project deletions: we keep the entry in the projects table,
            // and have the code below change its state to 'deleted'."
        }

        // ----------------------------------------------------------------------
        // deal with updating the projects table
        $settings = "state='{$this->to_state}'";
        if ( !empty($this->settings_template) )
        {
            // echo "transition->settings_template = $this->settings_template\n";

            // expand template
            $patterns = array(); $replacements = array();

            $braced_pattern = '/<[^>]*>/';
            $r = preg_match_all( $braced_pattern, $this->settings_template, $matches );
            if ( $r === FALSE )
            {
                return "preg_match_all returned FALSE";
            }
            foreach( $matches[0] as $braced_thing )
            {
                switch ( $braced_thing )
                {
                    case '<TIMESTAMP>':
                        $replacement = time();
                        break;

                    case '<WHO>':
                        if ( $who == PT_AUTO )
                        {
                            return "\$who == PT_AUTO shouldn't happen for <WHO>";
                        }
                        $replacement = mysqli_real_escape_string(DPDatabase::get_connection(), $who);
                        break;

                    case '<G:postcomments>':
                        global $postcomments; // defined in upload_text.php
                        $replacement = mysqli_real_escape_string(DPDatabase::get_connection(), $postcomments);
                        break;

                    default:
                        return "in settings_template, bad braced_thing $braced_thing";
                        break;
                }
                $patterns[] = "/$braced_thing/";
                $replacements[] = $replacement;
            }

            $settings_addition = preg_replace(
                $patterns, $replacements, $this->settings_template );

            $settings .= ", $settings_addition";
        }
        // finished expanding the template, so we can go ahead and update the table

        if ($testing)
        {
            echo "    settings: $settings<br>\n";
        }

        $res = mysqli_query(DPDatabase::get_connection(), "
            UPDATE projects SET $settings WHERE projectid = '$projectid'
        ");
        if (!$res)
        {
            return "mysql error: " . DPDatabase::log_error();
        }

        // ---------------------------------------------------------------------
        // Okay, so the update to the 'projects' table succeeded.
        // Now make any collateral changes to other tables.
        // (This needs to be expanded.)

        //     In case of the Post Proofer returning the project to Post Proofing pool,
        // we want to unsubscribe them from the 'sr_reported' event because they are
        // no longer the Post Proofer for that project and the event is meant for
        // Post Proofers.
        if ($this->from_state == PROJ_POST_FIRST_CHECKED_OUT &&
            $this->to_state == PROJ_POST_FIRST_AVAILABLE)
        {
            unsubscribe_user_from_project_event( $project->checkedoutby, $projectid, 'sr_reported' );
        }

        $checkedoutby = '';
        $pool = get_Pool_for_state($this->to_state);
        if ( !is_null($pool) && $this->to_state == $pool->project_checkedout_state )
        {
            // we want the project event to record who it's been
            // checked out to
            $project = new Project($projectid);
            $checkedoutby = $project->checkedoutby;
        }
        // log the project event
        $err = log_project_event(
            $projectid,
            $who,
            'transition',
            $this->from_state,
            $this->to_state,
            ($checkedoutby != '' ? "out_to: $checkedoutby" : @$extras['details'])
        );
        if ( $err )
        {
            return "log_project_event error: $err";
        }

        if ( $from_round != $to_round && !is_null($to_round) )
        {
            // Transiting into a round from either a different round or a non-round.
            // Update the corresponding projectID* table.
            Pages_prepForRound( $projectid, $to_round->round_number );
        }

        if ( $project->topic_id != "" )
        {
            // The project has a discussion topic.
            // Ensure it's in the appropriate forum for the project's new state.
            topic_change_forum(
                $project->topic_id,
                get_forum_id_for_project_state($this->to_state) );
        }

        if ( $this->collateral_actions )
        {
            // Because of the "UPDATE projects" command, $project is now out of date.
            // *** but maybe not, see above "$project = new Project($projectid);"
            // So get an up-to-date version of the project info (in 
            // $new_project) and pass both versions to the collateral_actions
            // function
            $new_project = new Project($projectid);

            $function_name = $this->collateral_actions;
            $function_name( $project, $new_project, $this, $who );
        }

        return '';
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_pre_release_check( $project, $round )
// Check whether the project seems ready to be released into the round.
// If it is, return an empty string.
// If it has problems, return a detailed error-message.
{
    global $projects_dir;

    $errors = '';

    $projectid  = $project['projectid'];
    $nameofwork = $project['nameofwork'];
    $author     = $project['authorsname'];
    $language   = $project['language'];
    $clearance  = $project['clearance'];

    if ($nameofwork == '') $errors .= "  "._("The 'Title' field is blank.")."\n";
    if ($author == '')     $errors .= "  "._("The 'Author' field is blank.")."\n";
    if ($language == '')   $errors .= "  "._("The 'Language' field is blank.")."\n";
    if ($clearance == '')  $errors .= "  "._("The 'Clearance' field is blank.")."\n";

    if ($errors) $errors .= "\n";

    $res = mysqli_query(DPDatabase::get_connection(), "
        SELECT image, LENGTH($round->prevtext_column_name) AS input_text_length
        FROM $projectid
        ORDER BY image ASC
    ");

    if (!$res)
    {
        $errors .= "  "._("There is no page-table.")."\n";
        return $errors;
    }


    if (mysqli_num_rows($res) == 0) $errors .= "  "._("There are no pages.")."\n";

    while ( $page = mysqli_fetch_assoc($res) )
    {
        $filename = $page['image'];
        $filepath = "$projects_dir/$projectid/$filename";

        if (!file_exists($filepath)) {
            $errors .= "  $filename: "._("image file is missing")."\n";
        } else if (filesize($filepath) < 100) {
            $errors .= "  $filename: "._("image file is small and probably bad")."\n";
        }

        /*
        An empty page-text is no longer an indication that something went wrong.
        if ($page['input_text_length'] == 0) {
            $errors .= "  $filename: "._("text is empty")."\n";
        }
        */
    }

    return $errors;
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

function get_transition( $from_state, $to_state )
{
    global $PROJECT_TRANSITIONS;
    foreach ( $PROJECT_TRANSITIONS as $transition )
    {
        if ( $transition->from_state == $from_state &&
             $transition->to_state   == $to_state )
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
    array(
        'project_restriction'   => null,
        'action_name'           => '[default]',
        'confirmation_question' => null,
        'detour'                => null,
        'settings_template'     => '',
        'collateral_actions'    => null,
    )
);

if ($site_supports_metadata)
{
    new ProjectTransition(
        PROJ_NEW_FILE_UPLOADED,
        PROJ_NEW_METADATA_FIRST,
        'manager',
        array(
            'project_restriction'   => null,
            'action_name'           => '[default]',
            'confirmation_question' => null,
            'detour'                => null,
            'settings_template'     => '',
            'collateral_actions'    => null,
        )
    );
}

// -----------------------------------------------------------------------------

for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
{
    $round = get_Round_for_round_number($rn);

    // setting modifieddate:
    //
    // The time when projects enter the Waiting state
    // is the overall ordering criterion for the "release queue".
    // Thus, we set modifieddate when transiting to Waiting
    // (from Unavailable or the previous round's Complete).
    //
    // The time when projects enter the Available state
    // is the ordering criterion for "Sort by Days"
    // in the list of available projects.
    // (But if the project is just coming back from
    // Bad, that shouldn't affect the sort order.)
    // Thus, we set modifieddate when transiting to Available
    // from anything other than Bad (i.e., from Waiting).


    new ProjectTransition(
        $round->project_unavailable_state,
        $round->project_waiting_state,
        // PMs with project loads disabled can't move a project 
        // out of unavailable, either
        'unrestricted_manager',
        array(
            'project_restriction'   => null,
            'action_name'           => '[default]',
            'confirmation_question' => null,
            'detour'                => null,
            'settings_template'     => 'modifieddate="<TIMESTAMP>"',
            'collateral_actions'    => null,
        )
    );

    new ProjectTransition(
        $round->project_waiting_state,
        $round->project_unavailable_state,
        'manager',
        array(
            'project_restriction'   => null,
            'action_name'           => '[default]',
            'confirmation_question' => null,
            'detour'                => null,
            'settings_template'     => '',
            'collateral_actions'    => null,
        )
    );

    new ProjectTransition(
        $round->project_waiting_state,
        $round->project_available_state,
        'proj_facilitator|auto',
        array(
            'project_restriction'   => null,
            'action_name'           => '[default]',
            'confirmation_question' => '[default]',
            'detour'                => null,
            'settings_template'     => 'modifieddate="<TIMESTAMP>"',
            'collateral_actions'    => 'round_release_collateral',
        )
    );

    new ProjectTransition(
        $round->project_bad_state,
        $round->project_unavailable_state,
        'manager',
        array(
            'project_restriction'   => null,
            'action_name'           => '[default]',
            'confirmation_question' => null,
            'detour'                => null,
            'settings_template'     => '',
            'collateral_actions'    => null,
        )
    );

    new ProjectTransition(
        $round->project_bad_state,
        'automodify',
        'manager',
        array(
            'project_restriction'   => null,
            'action_name'           => _('automodify'),
            'confirmation_question' => null,
            'detour'                => "$code_url/tools/project_manager/automodify.php?project=<PROJECTID>",
            'settings_template'     => '',
            'collateral_actions'    => null,
        )
    );
    // automodify.php will leave the project in the appropriate
    // BAD, AVAILABLE, or COMPLETE state.
    // ($settings_template of this ProjectTransition
    // will never be consulted.)

    new ProjectTransition(
        $round->project_available_state,
        $round->project_unavailable_state,
        'manager',
        array(
            'project_restriction'   => null,
            'action_name'           => '[default]',
            'confirmation_question' => '[default]',
            'detour'                => null,
            'settings_template'     => '',
            'collateral_actions'    => null,
        )
    );

    new ProjectTransition(
        $round->project_available_state,
        'automodify',
        'manager',
        array(
            'project_restriction'   => null,
            'action_name'           => _('automodify'),
            'confirmation_question' => null,
            'detour'                => "$code_url/tools/project_manager/automodify.php?project=<PROJECTID>",
            'settings_template'     => '',
            'collateral_actions'    => null,
        )
    );

    new ProjectTransition(
        $round->project_waiting_state,
        $round->project_bad_state,
        'auto',
        array(
            'project_restriction'   => null,
            'action_name'           => null,
            'confirmation_question' => null,
            'detour'                => null,
            'settings_template'     => '',
            'collateral_actions'    => null,
        )
    );

    new ProjectTransition(
        $round->project_available_state,
        $round->project_bad_state,
        'auto',
        array(
            'project_restriction'   => null,
            'action_name'           => null,
            'confirmation_question' => null,
            'detour'                => null,
            'settings_template'     => '',
            'collateral_actions'    => 'bad_project_collateral',
        )
    );

    new ProjectTransition(
        $round->project_bad_state,
        $round->project_available_state,
        'auto',
        array(
            'project_restriction'   => null,
            'action_name'           => null,
            'confirmation_question' => null,
            'detour'                => null,
            'settings_template'     => '',
            'collateral_actions'    => null,
        )
    );

    new ProjectTransition(
        $round->project_available_state,
        $round->project_complete_state,
        'auto',
        array(
            'project_restriction'   => null,
            'action_name'           => null,
            'confirmation_question' => null,
            'detour'                => null,
            'settings_template'     => '',
            'collateral_actions'    => 'round_complete_collateral',
        )
    );

    if ( $rn < MAX_NUM_PAGE_EDITING_ROUNDS )
    {
        $next_round = get_Round_for_round_number($rn+1);

        new ProjectTransition(
            $round->project_complete_state,
            $next_round->project_waiting_state,
            'auto',
            array(
                'project_restriction'   => null,
                'action_name'           => null,
                'confirmation_question' => null,
                'detour'                => null,
                'settings_template'     => 'modifieddate="<TIMESTAMP>"',
                'collateral_actions'    => null,
            )
        );
    }
    else
    {
        new ProjectTransition(
            $round->project_complete_state,
            PROJ_POST_FIRST_AVAILABLE,
            'auto',
            array(
                'project_restriction'   => null,
                'action_name'           => null,
                'confirmation_question' => null,
                'detour'                => null,
                'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby=''",
                'collateral_actions'    => 'round_to_PP_avail_collateral',
            )
        );

        new ProjectTransition(
            $round->project_complete_state,
            PROJ_POST_FIRST_CHECKED_OUT,
            'auto',
            array(
                'project_restriction'   => null,
                'action_name'           => null,
                'confirmation_question' => null,
                'detour'                => null,
                'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby=IF(checkedoutby!='',checkedoutby,username)",
                'collateral_actions'    => 'round_to_PP_out_collateral',
            )
        );
    }

    if (1)
    {
        // Transitions to skip rounds

        $from_states = array(
            $round->project_waiting_state,
            $round->project_unavailable_state,
        );

        foreach ( $from_states as $from_state )
        {
            if ( $rn > 1 )
            {
                // Transition to go back 1 round.

                $prev_round = get_Round_for_round_number($rn-1);

                new ProjectTransition(
                    $from_state,
                    $prev_round->project_waiting_state,
                    'site_manager',
                    array(
                        'project_restriction'   => null,
                        'action_name'           => sprintf( _('Send back to %s'), $prev_round->id ),
                        'confirmation_question' => '[default]',
                        'detour'                => null,
                        'settings_template'     => "", // don't set modifieddate
                        'collateral_actions'    => null,
                    )
                );
            }

            if ( $rn < MAX_NUM_PAGE_EDITING_ROUNDS )
            {
                $next_round = get_Round_for_round_number($rn+1);

                new ProjectTransition(
                    $from_state,
                    $next_round->project_waiting_state,
                    'site_manager',
                    array(
                        'project_restriction'   => null,
                        'action_name'           => _('Skip this round'),
                        'confirmation_question' => '[default]',
                        'detour'                => null,
                        'settings_template'     => "modifieddate='<TIMESTAMP>', nameofwork=CONCAT(nameofwork,' {{$round->id} skipped}')",
                        'collateral_actions'    => null,
                    )
                );
            }
            else
            {
                new ProjectTransition(
                    $from_state,
                    PROJ_POST_FIRST_AVAILABLE,
                    'site_manager',
                    array(
                        'project_restriction'   => 'proj_would_go_to_PP_avail',
                        'action_name'           => _('Skip this round'),
                        'confirmation_question' => '[default]',
                        'detour'                => null,
                        'settings_template'     => "modifieddate='<TIMESTAMP>', nameofwork=CONCAT(nameofwork,' {{$round->id} skipped}'), n_available_pages=0, checkedoutby=''",
                        'collateral_actions'    => 'round_to_PP_avail_collateral',
                    )
                );

                new ProjectTransition(
                    $from_state,
                    PROJ_POST_FIRST_CHECKED_OUT,
                    'site_manager',
                    array(
                        'project_restriction'   => 'proj_would_go_to_PP_out',
                        'action_name'           => _('Skip this round'),
                        'confirmation_question' => '[default]',
                        'detour'                => null,
                        'settings_template'     => "modifieddate='<TIMESTAMP>', nameofwork=CONCAT(nameofwork,' {{$round->id} skipped}'), n_available_pages=0, checkedoutby=IF(checkedoutby!='',checkedoutby,username)",
                        'collateral_actions'    => 'round_to_PP_out_collateral',
                    )
                );
            }
        }
    }
}

function round_release_collateral( $old_project, $project, $transition, $who )
{
    $round = get_Round_for_project_state($transition->to_state);
    assert( !is_null($round) );
    configure_gettext_for_user($project->username);

    if ( $who == PT_AUTO )
    {
        maybe_mail_project_manager(
            $project,
            sprintf(_("This project has just become available for work in round %s."), $round->id),
            _("Proofreading Started"));
    }
    else
    {
        maybe_mail_project_manager(
            $project,
            sprintf(_('This project has been manually released by %1$s and has just become available in %2$s.'), $who, $round->name),
            _("Proofreading Started (Manual Release)"));
    }

    configure_gettext_for_user();
    notify_project_event_subscribers( $project, 'round_available', array('round'=>$round) );

    global $auto_post_to_project_topic;
    if ( $auto_post_to_project_topic )
    {
        $project->ensure_topic();

        topic_add_post(
            $project->topic_id,
            "Project released in {$round->id}",
            "The project has just changed state from "
                . project_states_text($transition->from_state)
                . " to "
                . project_states_text($transition->to_state)
                . "\n\n(This post is automatically generated.)",
            '[Round Release Monitor]',
            FALSE
        );
    }
}

function bad_project_collateral( $old_project, $project, $transition, $who )
{
    configure_gettext_for_user($project->username);
    $body_blurb_messages[] = _("This project has been shut down. This is due to 10 or more problem reports, from at least 3 unique users, noting errors or problems with this project.");
    $body_blurb_messages[] = _("Please visit the Project Manager page to view a list of your bad projects and make any necessary changes. You will then be able to put the project back up on the site.");
    $body_blurb = implode("\n\n", $body_blurb_messages);
    maybe_mail_project_manager($project, $body_blurb, _("Bad Project"));
    configure_gettext_for_user();
}

function round_complete_collateral( $old_project, $project, $transition, $who )
{
    $round = get_Round_for_project_state($transition->to_state);
    assert( !is_null($round) );

    notify_project_event_subscribers( $project, 'round_complete', array('round'=>$round) );

    global $auto_post_to_project_topic;
    if ( $auto_post_to_project_topic )
    {
        $project->ensure_topic();

        topic_add_post(
            $project->topic_id,
            "Project completed {$round->id}",
            "The project has just changed state from "
                . project_states_text($transition->from_state)
                . " to "
                . project_states_text($transition->to_state)
                . "\n\n(This post is automatically generated.)",
            '[Round Completion Monitor]',
            FALSE
        );
    }
}

function proj_would_go_to_PP_avail( $project, $transition )
{
    assert( $project->state == $transition->from_state );
    return is_null(project_get_auto_PPer($project->projectid));
}

function proj_would_go_to_PP_out( $project, $transition )
{
    assert( $project->state == $transition->from_state );
    return !is_null(project_get_auto_PPer($project->projectid));
}

function round_to_PP_avail_collateral( $old_project, $project, $transition, $who )
{
    global $site_abbreviation;
    $from_round = get_Round_for_project_state($transition->from_state);
    generate_post_files( $project->projectid, $from_round->id, 'LE', False, '' );

    // Always notify the PM
    configure_gettext_for_user($project->username);
    $body_blurb_messages[] = _("This project has completed all rounds and has been made available for someone else to do the post-processing.");
    $body_blurb_messages[] = _("You will be notified once it has completed post-processing.");
    $body_blurb = implode("\n\n", $body_blurb_messages);
    maybe_mail_project_manager($project, $body_blurb, _("Post-Processing Started"));
    configure_gettext_for_user();

    // Was it reserved for someone to PP? If so, tell them they 
    // are not getting it
    if ( !empty($old_project->checkedoutby))
    {
        $reserved_PPer = $old_project->checkedoutby;
        global $site_manager_email_addr;

        $PPer = new User($reserved_PPer);
        $PPer_email_addr = $PPer->email;
        $body_blurb =
             "Hello $reserved_PPer,\n\n".
             $project->email_introduction().
             "This project, which was reserved for you to Post-Process,\n".
             "has completed the rounds and has been sent to the PP pool.\n" .
             "It has not been checked out to you because you are not currently  \n" .
             "permitted to check projects out for PPing.\n" .
             "If you believe you are receiving this message in error, please contact\n" .
             "a site manager by emailing $site_manager_email_addr.\n";

        maybe_mail(
            $PPer_email_addr,
            "$site_abbreviation: Reserved book sent to PP pool",
            $body_blurb);
   }

    notify_project_event_subscribers( $project, 'pp_enter' );
}

function round_to_PP_out_collateral( $old_project, $project, $transition, $who )
{
    global $site_abbreviation;
    $from_round = get_Round_for_project_state($transition->from_state);
    generate_post_files( $project->projectid, $from_round->id, 'LE', False, '' );

    // The project has been checked out to someone for PPing.
    // We want to notify the PPer and the PM,
    // but if they're the same person, just send one message.

    if ( $project->checkedoutby == $project->username )
    {
        // Yes, PPer == PM, send one message.

        configure_gettext_for_user($project->username);
        $body_blurb = _("This project has completed all rounds and is ready for you to do the post-processing. If you do not want to work on it, set it to Available for Post-Processing.");
        maybe_mail_project_manager($project, $body_blurb, _("Post-Processing Started"));
        configure_gettext_for_user();
    }
    else
    {
        // No, PPer != PM, send each a message.

        $PPer_username = $project->checkedoutby;

        // msg to PM:
        configure_gettext_for_user($project->username);
        $body_blurb_messages[] = sprintf(
            _("This project has completed all rounds and has been checked out to the PPer who had it reserved, %s."),
            $PPer_username);
        $body_blurb_messages[] = _("You will be notified once it has completed post-processing.");
        $body_blurb = implode("\n\n", $body_blurb_messages);
        maybe_mail_project_manager($project, $body_blurb, _("Post-Processing Started"));
        configure_gettext_for_user();

        // -------------

        // msg to PPer:

        $PPer = new User($PPer_username);
        $PPer_email_addr = $PPer->email;
        $body_blurb =
            "Hello $PPer_username,\n\n".
            $project->email_introduction().
            "This project, which is reserved for you to Post-Process,\n".
            "is now ready for you to work on, and should shortly appear in your list\n".
            "of checked out projects.\n";

        maybe_mail(
            $PPer_email_addr,
            "$site_abbreviation: Reserved book available for Post-Processing",
            $body_blurb);
    }

    notify_project_event_subscribers( $project, 'pp_enter' );
}

// -----------------------------------------------------------------------------
// X_AVAILABLE -> X_CHECKED_OUT
// Anyone who can work in stage X can check out the project.
// OR, anyone who is currently allowed to check out in stage X

new ProjectTransition(
    PROJ_POST_FIRST_AVAILABLE,
    PROJ_POST_FIRST_CHECKED_OUT,
    'user_can_check_out_in_stage PP',
    array(
        'project_restriction'   => null,
        'action_name'           => _("Check Out Book"),
        'confirmation_question' => _("Are you sure you want to check this book out for post processing?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby='<WHO>'",
        'collateral_actions'    => null,
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_POST_SECOND_CHECKED_OUT,
    'user_can_work_in_stage PPV',
    array(
        'project_restriction'   => null,
        'action_name'           => _("Check Out Book"),
        'confirmation_question' => _("Are you sure you want to check this book out for verifying post processing?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='<TIMESTAMP>', postproofer=checkedoutby, checkedoutby='<WHO>'",
        'collateral_actions'    => null,
    )
);

if ( $site_supports_corrections_after_posting )
{
new ProjectTransition(
    PROJ_CORRECT_AVAILABLE,
    PROJ_CORRECT_CHECKED_OUT,
    'user_can_work_in_stage CR',
    array(
        'project_restriction'   => null,
        'action_name'           => _("Check Out Book"),
        'confirmation_question' => _("Are you sure you want to check this book out to review corrections?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby='<WHO>'",
        'collateral_actions'    => null,
    )
);
}

// PPer can grab a project back from the PP pool
new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_POST_FIRST_CHECKED_OUT,
    'holder',
    array(
        'project_restriction'   => null,
        'action_name'           => _("Return to Post-Processing"),
        'confirmation_question' => _("Are you sure you want to take this book back for further work?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='<TIMESTAMP>', postproofer=''",
        'collateral_actions'    => null,
    )
);

// -----------------------------------------------------------------------------

// X_CHECKED_OUT -> X_AVAILABLE
// The user who has the project checked out for X can abandon/return it,
// making it available for others to check out.

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_FIRST_AVAILABLE,
    'holder',
    array(
        'project_restriction'   => null,
        'action_name'           => _("Return to Available"),
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/upload_text.php?project=<PROJECTID>&stage=return_1",
        'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby='', ppverifier=NULL, smoothread_deadline='0', postcomments=CONCAT(postcomments,'<G:postcomments>')",
        'collateral_actions'    => null,
        'disabled_during_SR'    => true,
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_SECOND_AVAILABLE,
    'holder',
    array(
        'project_restriction'   => null,
        'action_name'           => _("Return to Available"),
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/upload_text.php?project=<PROJECTID>&stage=return_2",
        'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby=postproofer, postcomments=CONCAT(postcomments,'<G:postcomments>')",
        'collateral_actions'    => null,
    )
);

if ( $site_supports_corrections_after_posting )
{
new ProjectTransition(
    PROJ_CORRECT_CHECKED_OUT,
    PROJ_CORRECT_AVAILABLE,
    'holder',
    array(
        'project_restriction'   => null,
        'action_name'           => _("Return to Available"),
        'confirmation_question' => _("Are you sure you want to make this book available to others for reviewing corrections?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby=''",
        'collateral_actions'    => null,
    )
);
}

// -----------------------------------------------------------------------------

// X_CHECKED_OUT -> something other than X_AVAILABLE
// The user who has the project checked out for X
// can check it in (or return it to a previous holder).

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_SECOND_AVAILABLE,
    'holder',
    array(
        'project_restriction'   => "project_was_not_returned_from_PPV",
        'action_name'           => _("Upload for Verification"),
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/upload_text.php?project=<PROJECTID>&stage=post_1",
        'settings_template'     => "modifieddate='<TIMESTAMP>', postproofer=checkedoutby, ppverifier=NULL, postcomments=CONCAT(postcomments,'<G:postcomments>')",
        'collateral_actions'    => 'PP_to_PPV_collateral',
        'disabled_during_SR'    => true,
    )
);

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_SECOND_AVAILABLE,
    'proj_facilitator',
    array(
        'project_restriction'   => "project_was_returned_from_PPV",
        'action_name'           => _("Upload for Verification"),
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/upload_text.php?project=<PROJECTID>&stage=post_1",
        'settings_template'     => "modifieddate='<TIMESTAMP>', postproofer=checkedoutby, ppverifier=NULL, postcomments=CONCAT(postcomments,'<G:postcomments>')",
        'collateral_actions'    => 'PP_to_PPV_collateral',
        'disabled_during_SR'    => true,
    )
);

function PP_to_PPV_collateral( $old_project, $project, $transition, $who )
{
    notify_project_event_subscribers( $project, 'ppv_enter' );
}

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_FIRST_CHECKED_OUT,
    'holder',
    array(
        'project_restriction'   => null,
        'action_name'           => _("Return to Post-Processor"),
        'confirmation_question' => _("Are you sure you want to return this book to the post-processor for further work?"),
        'detour'                => null,
        // formerly ppverifier was only set when the project was uploaded to PG
        // it is set here so the PPer can return it to the same PPVer
        // any transition out of PROJ_POST_FIRST_CHECKED_OUT sets it to ''
        // this will not affect how the PPVer is ultimately set.
        'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby=postproofer, postproofer='', ppverifier=checkedoutby",
        'collateral_actions'    => "return_to_PP_collateral",
    )
);

function return_to_PP_collateral( $old_project, $project, $transition, $who )
{
    global $site_abbreviation;

    $PPer_username = $project->checkedoutby;
    $PPer = new User($PPer_username);
    $PPer_email_addr = $PPer->email;

    configure_gettext_for_user($PPer_username);
    $body_blurb = $project->email_introduction() . "\n" .
        _("This project has been returned to you for further work.") . "\n";
    $subject = "$site_abbreviation: " . _("Return to Post-Processor");
    configure_gettext_for_user();

    maybe_mail($PPer_email_addr, $subject, $body_blurb);
}

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_SECOND_CHECKED_OUT,
    'holder',
    array(
        'project_restriction'   => "project_was_returned_from_PPV",
        'action_name'           => _("Return to Post Processing Verifier"),
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/upload_text.php?project=<PROJECTID>&stage=re_post_1",
        'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby=ppverifier, postproofer=checkedoutby, ppverifier=NULL",
        'collateral_actions'    => "return_from_PP_to_PPV_collateral",
        'disabled_during_SR'    => true,
    )
);

function project_was_not_returned_from_PPV($project, $transition )
{
    assert( $project->state == $transition->from_state );
    return $project->ppverifier == NULL;
}

function project_was_returned_from_PPV($project, $transition )
{
    assert( $project->state == $transition->from_state );
    return $project->ppverifier != NULL;
}

function return_from_PP_to_PPV_collateral( $old_project, $project, $transition, $who )
{
    global $site_abbreviation;

    $PPVer_username = $project->checkedoutby;
    $PPVer = new User($PPVer_username);
    $PPVer_email_addr = $PPVer->email;

    configure_gettext_for_user($PPVer_username);
    $body_blurb = $project->email_introduction() . "\n" .
        _("This project has been returned to you for verification after further work by the post-processor.") . "\n";
    $subject = "$site_abbreviation: " . _("Return to Post Processing Verifier");
    configure_gettext_for_user();

    maybe_mail($PPVer_email_addr, $subject, $body_blurb);
}

// -----------------------------------------------------------------------------
// Transitions to and from PROJ_POST_FIRST_UNAVAILABLE:

// To PROJ_POST_FIRST_UNAVAILABLE:

new ProjectTransition(
    PROJ_POST_FIRST_AVAILABLE,
    PROJ_POST_FIRST_UNAVAILABLE,
    'manager',
    array(
        'project_restriction'   => null,
        'action_name'           => '[default]',
        'confirmation_question' => '[default]',
        'detour'                => null,
        'settings_template'     => '',
        'collateral_actions'    => null,
    )
);

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_FIRST_UNAVAILABLE,
    'manager',
    array(
        'project_restriction'   => null,
        'action_name'           => '[default]',
        'confirmation_question' => _('Someone currently has this project checked out. Are you sure you want to make it unavailable?'),
        'detour'                => null,
        'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby='', ppverifier=NULL, smoothread_deadline='0'",
        'collateral_actions'    => null,
        'disabled_during_SR'    => true,
    )
);

// From PROJ_POST_FIRST_UNAVAILABLE:

new ProjectTransition(
    PROJ_POST_FIRST_UNAVAILABLE,
    PROJ_POST_FIRST_AVAILABLE,
    // PMs with project loads disabled can't move a project
    // out of unavailable, either
    'unrestricted_manager',
    array(
        'project_restriction'   => null,
        'action_name'           => _('Make Project Available for Post-Processing'),
        'confirmation_question' => '[default]',
        'detour'                => null,
        'settings_template'     => '',
        'collateral_actions'    => null,
    )
);

new ProjectTransition(
    PROJ_POST_FIRST_UNAVAILABLE,
    PROJ_POST_FIRST_CHECKED_OUT,
    // PMs with project load disabled can't move a project
    // out of unavailable, either
    'unrestricted_manager',
    array(
        'project_restriction'   => null,
        'action_name'           => _('Check Out Project for Post-Processing'),
        'confirmation_question' => _("It's unclear whether this transition should even be possible. Are you sure?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='<TIMESTAMP>', checkedoutby='<WHO>'",
        'collateral_actions'    => null,
    )
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
    array(
        'project_restriction'   => null,
        'action_name'           => '[default]',
        'confirmation_question' => $post_complete_question,
        'detour'                => null,
        'settings_template'     => "ppverifier=NULL",
        'collateral_actions'    => null,
        'disabled_during_SR'    => true,
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_POST_COMPLETE,
    'site_manager',
    array(
        'project_restriction'   => null,
        'action_name'           => '[default]',
        'confirmation_question' => $post_complete_question,
        'detour'                => null,
        'settings_template'     => '',
        'collateral_actions'    => null,
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_COMPLETE,
    'site_manager',
    array(
        'project_restriction'   => null,
        'action_name'           => '[default]',
        'confirmation_question' => $post_complete_question,
        'detour'                => null,
        'settings_template'     => '',
        'collateral_actions'    => null,
    )
);

// -----------------------------------------------------------------------------
// Transitions to PROJ_SUBMIT_PG_POSTED:

/*
    pseudo-code:
    IF postproofer == '' THEN
        # No postproofer has been recorded, so the project never reached PPV.
        # So whoever has it checked out must be cleared to post directly,
        # and must have been the PPer.
        postproofer=checkedoutby
    ELSE
        # A postproofer has been recorded, so the project did reach PPV.
        IF postproofer != checkedoutby THEN
            # checkedoutby must be PPVer...
            # (This assumes a project is only posted once!
            # otherwise, maybe also test that ppverifier is null?)
            ppverifier=checkedoutby
        ENDIF
    ENDIF

    i.e.:
        postproofer = IF( postproofer = '', checkedoutby, postproofer )
        ppverifier = IF( postproofer != '' AND postproofer != checkedoutby, checkedoutby, ppverifier )
*/

$pg_posted_settings_template =
    "modifieddate='<TIMESTAMP>',
    postproofer = IF( postproofer = '', checkedoutby, postproofer ),
    ppverifier = IF( postproofer != '' AND postproofer != checkedoutby, checkedoutby, ppverifier )";


new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_SUBMIT_PG_POSTED,
    'site_manager',
    array(
        'project_restriction'   => null,
        'action_name'           => '[default]',
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/project_manager/editproject.php?action=edit&project=<PROJECTID>&posted=1",
        'settings_template'     => $pg_posted_settings_template,
        'collateral_actions'    => 'pg_posted_collateral',
        'disabled_during_SR'    => true,
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_SUBMIT_PG_POSTED,
    'site_manager',
    array(
        'project_restriction'   => null,
        'action_name'           => '[default]',
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/project_manager/editproject.php?action=edit&project=<PROJECTID>&posted=1",
        'settings_template'     => $pg_posted_settings_template,
        'collateral_actions'    => 'pg_posted_collateral',
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_SUBMIT_PG_POSTED,
    'site_manager',
    array(
        'project_restriction'   => null,
        'action_name'           => '[default]',
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/project_manager/editproject.php?action=edit&project=<PROJECTID>&posted=1",
        'settings_template'     => $pg_posted_settings_template,
        'collateral_actions'    => 'pg_posted_collateral',
    )
);

new ProjectTransition(
    PROJ_POST_COMPLETE,
    PROJ_SUBMIT_PG_POSTED,
    'site_manager',
    array(
        'project_restriction'   => null,
        'action_name'           => '[default]',
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/project_manager/editproject.php?action=edit&project=<PROJECTID>&posted=1",
        'settings_template'     => $pg_posted_settings_template,
        'collateral_actions'    => 'pg_posted_collateral',
    )
);

if ( $site_supports_corrections_after_posting )
{
new ProjectTransition(
    PROJ_CORRECT_CHECKED_OUT,
    PROJ_SUBMIT_PG_POSTED,
    'holder',
    array(
        'project_restriction'   => null,
        'action_name'           => _("Posted to Project Gutenberg"),
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/project_manager/editproject.php?action=edit&project=<PROJECTID>&posted=1",
        'settings_template'     => $pg_posted_settings_template,
        'collateral_actions'    => 'pg_posted_collateral',
    )
);
}

function pg_posted_collateral( $old_project, $project, $transition, $who )
{
    notify_project_event_subscribers( $project, 'posted' );
}

// -----------------------------------------------------------------------------

if ( $site_supports_corrections_after_posting )
{
new ProjectTransition(
    PROJ_SUBMIT_PG_POSTED,
    PROJ_CORRECT_AVAILABLE,
    'user_can_work_in_stage PP', // not sure what it should really be
    array(
        'project_restriction'   => null,
        'action_name'           => _("Upload Corrections for Review"),
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/upload_text.php?project=<PROJECTID>&stage=correct",
        'settings_template'     => "modifieddate='<TIMESTAMP>', correctedby='<WHO>'",
        'collateral_actions'    => null,
    )
);
}

// -----------------------------------------------------------------------------
// Transitions to PROJ_DELETE.

foreach ( $PROJECT_STATES_IN_ORDER as $from_state )
{
    // Don't bother creating a transition for Delete -> Delete.
    if ( $from_state == PROJ_DELETE ) continue;

    // A NEW project can be deleted by its manager.
    // Anything else requires an SA.
    $who_may_delete = (
        $from_state == PROJ_NEW 
        ? 'manager'
        : 'site_manager'
    );

    new ProjectTransition(
        $from_state,
        PROJ_DELETE,
        $who_may_delete,
        array(
            'project_restriction'   => null,
            'action_name'           => _('Delete Project'),
            'confirmation_question' => _('You should only delete a project that is beyond repair. Are you sure you want to delete this project?'),
            'detour'                => null,
            'settings_template'     => "modifieddate='<TIMESTAMP>'",
            'collateral_actions'    => null,
        )
    );
}

// vim: sw=4 ts=4 expandtab
?>
