<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');
include_once($relPath.'DPage.inc'); // project_drop_pages Pages_prepForRound
include_once($relPath.'f_move_post.inc'); // move_project_thread
include_once($relPath.'pg.inc'); // get_pg_catalog_url_for_etext
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'../tools/project_manager/post_files.inc');

define('PT_AUTO', '[AUTO]');

$PROJECT_TRANSITIONS = array();

class ProjectTransition
{
    function ProjectTransition(
        $from_state,
        $to_state,
        $who_restriction,
        $options
            // action_name
            // confirmation_question
            // detour
            // settings_template
            // collateral_actions
    )
    // A user who satisfies the $who_restriction can cause
    // a project to transit from $from_state to $to_state.
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

        die("transition has bad 'restriction' value: '$simple_who_restriction'");
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

        assert( $this->is_valid_for( $project, $who ) );

        $projectid = $project->projectid;

        $from_round = get_Round_for_project_state($this->from_state);
        $to_round   = get_Round_for_project_state($this->to_state);
        if ( !is_null($to_round) )
        {
            if ( $this->to_state == $to_round->project_waiting_state
              || $this->to_state == $to_round->project_available_state )
            {
                $errors = project_pre_release_check( get_object_vars($project), $to_round );
                if ($errors)
                {
                    $error = _("disallowed because of project problems").":\n<pre>\n$errors</pre>";
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

            if ( $this->from_state == PROJ_NEW )
            {
                $result = mysql_query("
                    DELETE FROM projects WHERE projectid = '$projectid'
                ");

                return '';
            }
            else
            {
                // It's somewhat problematic to delete the entry from the
                // 'projects' table.
                // Instead, keep the entry, but change its state to 'deleted'.
            }
        }

        // ----------------------------------------------------------------------

        $settings = "state='{$this->to_state}'";
        if ( !empty($this->settings_template) )
        {
            // echo "transition->settings_template = $this->settings_template\n";

            // expand template
            $patterns = array(); $replacements = array();

            $braced_pattern = '/{[^}]*}/';
            $r = preg_match_all( $braced_pattern, $this->settings_template, $matches );
            if ( $r === FALSE )
            {
                return "preg_match_all returned FALSE";
            }
            foreach( $matches[0] as $braced_thing )
            {
                switch ( $braced_thing )
                {
                    case '{TIMESTAMP}':
                        $replacement = time();
                        break;

                    case '{WHO}':
                        if ( $who == PT_AUTO )
                        {
                            return "\$who == PT_AUTO shouldn't happen for {WHO}";
                        }
                        $replacement = $who;
                        break;

                    case '{G:postcomments}':
                        global $postcomments;
                        $replacement = $postcomments;
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

        if ($testing)
        {
            echo "    settings: $settings<br>\n";
        }

        $res = mysql_query("
            UPDATE projects SET $settings WHERE projectid = '$projectid'
        ");
        if (!$res)
        {
            return "mysql error: " . mysql_error();
        }

        // ---------------------------------------------------------------------
        // Okay, so the update to the 'projects' table succeeded.
        // Now make any collateral changes to other tables.
        // (This needs to be expanded.)

        if ( $from_round != $to_round && !is_null($to_round) )
        {
            // Transiting into a round from either a different round or a non-round.
            // Update the corresponding projectID* table.
            Pages_prepForRound( $projectid, $to_round->round_number );
        }

        // move the project topic in synch with the project's progress
        move_project_thread(
            $projectid, get_forum_id_for_project_state($this->to_state) );

        if ( $this->collateral_actions )
        {
            // Because of the "UPDATE projects" command, $project is now out of date.
            // So get an up-to-date version before passing $project around.
            $project = new Project($projectid);

            $function_name = $this->collateral_actions;
            $function_name( $project, $this, $who );
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

    $res = mysql_query("
        SELECT image, LENGTH($round->prevtext_column_name) AS input_text_length
        FROM $projectid
        ORDER BY image ASC
    ");

    if (!$res)
    {
        $errors .= "  "._("There is no page-table.")."\n";
        return $errors;
    }


    if (mysql_num_rows($res) == 0) $errors .= "  "._("There are no pages.")."\n";

    while ( $page = mysql_fetch_assoc($res) )
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
        'manager',
        array(
            'action_name'           => '[default]',
            'confirmation_question' => null,
            'detour'                => null,
            'settings_template'     => 'modifieddate="{TIMESTAMP}"',
            'collateral_actions'    => null,
        )
    );

    new ProjectTransition(
        $round->project_waiting_state,
        $round->project_unavailable_state,
        'manager',
        array(
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
            'action_name'           => '[default]',
            'confirmation_question' => '[default]',
            'detour'                => null,
            'settings_template'     => 'modifieddate="{TIMESTAMP}"',
            'collateral_actions'    => 'round_release_collateral',
        )
    );

    new ProjectTransition(
        $round->project_bad_state,
        $round->project_unavailable_state,
        'manager',
        array(
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
            'action_name'           => null,
            'confirmation_question' => null,
            'detour'                => null,
            'settings_template'     => '',
            'collateral_actions'    => null,
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
                'action_name'           => null,
                'confirmation_question' => null,
                'detour'                => null,
                'settings_template'     => 'modifieddate="{TIMESTAMP}"',
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
                'action_name'           => null,
                'confirmation_question' => null,
                'detour'                => null,
                'settings_template'     => "modifieddate='{TIMESTAMP}'",
                'collateral_actions'    => 'round_to_PP_avail_collateral',
            )
        );

        new ProjectTransition(
            $round->project_complete_state,
            PROJ_POST_FIRST_CHECKED_OUT,
            'auto',
            array(
                'action_name'           => null,
                'confirmation_question' => null,
                'detour'                => null,
                'settings_template'     => "modifieddate='{TIMESTAMP}', checkedoutby=IF(checkedoutby!='',checkedoutby,username)",
                'collateral_actions'    => 'round_to_PP_out_collateral',
            )
        );
    }
}

function round_release_collateral( $project, $transition, $who )
{
    $round = get_Round_for_project_state($transition->to_state);
    assert( !is_null($round) );

    if ( $who == PT_AUTO )
    {
        maybe_mail_project_manager(
            get_object_vars($project),
            "This project has just become available for work in round {$round->id}.",
            "DP Proofreading Started");
    }
    else
    {
        maybe_mail_project_manager(
            get_object_vars($project),
            "This project has been manually released by $who and has just become available in '{$round->name}'.",
            "DP Proofreading Started (Manual Release)");
    }
}

function bad_project_collateral( $project, $transition, $who )
{
    $body_blurb =
"This project has been shut down.
This is due to 10 or more problem reports, from at least
3 unique users, noting errors or problems with this project.
Please visit the Project Manager page to view a list
of your bad projects and make any necessary changes.
You will then be able to put the project back up on the site.";
    maybe_mail_project_manager(
        get_object_vars($project), $body_blurb, "DP Bad Project");
}

function round_to_PP_avail_collateral( $project, $transition, $who )
{
    $from_round = get_Round_for_project_state($transition->from_state);
    generate_post_files( $project->projectid, $from_round->id, 'LE', '' );

    $body_blurb =
        "This project has completed all rounds\n".
        "and has been made available for someone else to do the post-processing.\n".
        "You will be notified once it has completed post-processing.";
    maybe_mail_project_manager(
        get_object_vars($project), $body_blurb, "DP Post-Processing Started" );
}

function round_to_PP_out_collateral( $project, $transition, $who )
{
    $from_round = get_Round_for_project_state($transition->from_state);
    generate_post_files( $project->projectid, $from_round->id, 'LE', '' );

    // The project has been checked out to someone for PPing.
    // We want to notify the PPer and the PM,
    // but if they're the same person, just send one message.

    if ( $project->checkedoutby == $project->username )
    {
        // Yes, PPer == PM, send one message.

        $body_blurb =
            "This project has completed all rounds\n".
            "and is ready for you to do the post-processing.\n".
            "If you do not want to work on it,\n".
            "set it to Available for Post-Processing.";
        maybe_mail_project_manager(
            get_object_vars($project), $body_blurb, "DP Post-Processing Started");
    }
    else
    {
        // No, PPer != PM, send each a message.

        $PPer = $project->checkedoutby;

        // msg to PM:
        $body_blurb =
            "This project has completed all rounds\n".
            "and has been checked out to the PPer who had it reserved,\n".
            "$PPer. You will be notified once it has completed post-processing.";
        maybe_mail_project_manager(
            get_object_vars($project), $body_blurb, "DP Post-Processing Started");

        // -------------

        // msg to PPer:
        global $auto_email_addr;

        $PPer_email_addr = mysql_result(mysql_query("
            SELECT email FROM users WHERE username = '$PPer'
        "),0);
        $body_blurb =
            "Hello $PPer,\n\n".
            "The project \"{$project->nameofwork}\", which is reserved for you to Post-Process,\n".
            "is now ready for you to work on, and should shortly appear in your list\n".
            "of checked out projects.\n".
            "\nThanks!\nDistributed Proofreaders\n";

        maybe_mail(
            $PPer_email_addr,
            "DP: Reserved book available for Post-Processing",
            $body_blurb,
            "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");
    }
}

// -----------------------------------------------------------------------------
// X_AVAILABLE -> X_CHECKED_OUT
// Anyone who can work in stage X can check out the project.

new ProjectTransition(
    PROJ_POST_FIRST_AVAILABLE,
    PROJ_POST_FIRST_CHECKED_OUT,
    'user_can_work_in_stage PP',
    array(
        'action_name'           => _("Check Out Book"),
        'confirmation_question' => _("Are you sure you want to check this book out for post processing?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='{TIMESTAMP}', checkedoutby='{WHO}'",
        'collateral_actions'    => null,
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_POST_SECOND_CHECKED_OUT,
    'user_can_work_in_stage PPV',
    array(
        'action_name'           => _("Check Out Book"),
        'confirmation_question' => _("Are you sure you want to check this book out for verifying post processing?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='{TIMESTAMP}', postproofer=checkedoutby, checkedoutby='{WHO}'",
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
        'action_name'           => _("Check Out Book"),
        'confirmation_question' => _("Are you sure you want to check this book out to review corrections?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='{TIMESTAMP}', checkedoutby='{WHO}'",
        'collateral_actions'    => null,
    )
);
}

// -----------------------------------------------------------------------------

// X_CHECKED_OUT -> X_AVAILABLE
// The user who has the project checked out for X can abandon/return it,
// making it available for others to check out.

new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_FIRST_AVAILABLE,
    'holder',
    array(
        'action_name'           => _("Return to Available"),
        'confirmation_question' => _("Are you sure you want to make this book available to others for post processing?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='{TIMESTAMP}', checkedoutby='', smoothread_deadline='0'",
        'collateral_actions'    => null,
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_SECOND_AVAILABLE,
    'holder',
    array(
        'action_name'           => _("Return to Available"),
        'confirmation_question' => _("Are you sure you want to make this book available to others to verify and lose your work?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='{TIMESTAMP}', checkedoutby=postproofer",
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
        'action_name'           => _("Return to Available"),
        'confirmation_question' => _("Are you sure you want to make this book available to others for reviewing corrections?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='{TIMESTAMP}', checkedoutby=''",
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
        'action_name'           => _("Upload for Verification"),
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/upload_text.php?project=<PROJECTID>&stage=post_1",
        'settings_template'     => "modifieddate='{TIMESTAMP}', postproofer=checkedoutby, postcomments='{G:postcomments}'",
        'collateral_actions'    => null,
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_FIRST_CHECKED_OUT,
    'holder',
    array(
        'action_name'           => _("Return to Post-Processor"),
        'confirmation_question' => _("Are you sure you want to return this book to the post-processor for further work?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='{TIMESTAMP}', checkedoutby=postproofer, postproofer=''",
        'collateral_actions'    => null,
    )
);

// -----------------------------------------------------------------------------
// Transitions to and from PROJ_POST_FIRST_UNAVAILABLE:

// To PROJ_POST_FIRST_UNAVAILABLE:

new ProjectTransition(
    PROJ_POST_FIRST_AVAILABLE,
    PROJ_POST_FIRST_UNAVAILABLE,
    'manager',
    array(
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
        'action_name'           => '[default]',
        'confirmation_question' => _('Someone currently has this project checked out. Are you sure you want to make it unavailable?'),
        'detour'                => null,
        'settings_template'     => "modifieddate='{TIMESTAMP}', checkedoutby='', smoothread_deadline='0'",
        'collateral_actions'    => null,
    )
);

// From PROJ_POST_FIRST_UNAVAILABLE:

new ProjectTransition(
    PROJ_POST_FIRST_UNAVAILABLE,
    PROJ_POST_FIRST_AVAILABLE,
    'manager',
    array(
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
    'manager',
    array(
        'action_name'           => _('Check Out Project for Post-Processing'),
        'confirmation_question' => _("It's unclear whether this transition should even be possible. Are you sure?"),
        'detour'                => null,
        'settings_template'     => "modifieddate='{TIMESTAMP}', checkedoutby='{WHO}'",
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
        'action_name'           => '[default]',
        'confirmation_question' => $post_complete_question,
        'detour'                => null,
        'settings_template'     => '',
        'collateral_actions'    => null,
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_POST_COMPLETE,
    'site_manager',
    array(
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
    "modifieddate='{TIMESTAMP}',
    postproofer = IF( postproofer = '', checkedoutby, postproofer ),
    ppverifier = IF( postproofer != '' AND postproofer != checkedoutby, checkedoutby, ppverifier )";


new ProjectTransition(
    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_SUBMIT_PG_POSTED,
    'site_manager',
    array(
        'action_name'           => '[default]',
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/project_manager/editproject.php?action=edit&project=<PROJECTID>&posted=1",
        'settings_template'     => $pg_posted_settings_template,
        'collateral_actions'    => 'pg_posted_collateral',
    )
);

new ProjectTransition(
    PROJ_POST_SECOND_AVAILABLE,
    PROJ_SUBMIT_PG_POSTED,
    'site_manager',
    array(
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
        'action_name'           => _("Posted to Project Gutenberg"),
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/project_manager/editproject.php?action=edit&project=<PROJECTID>&posted=1",
        'settings_template'     => $pg_posted_settings_template,
        'collateral_actions'    => 'pg_posted_collateral',
    )
);
}

function pg_posted_collateral( $project, $transition, $who )
{
    global $site_url, $auto_email_addr, $auto_email_addr;

    $projectid = $project->projectid;

    $url = get_pg_catalog_url_for_etext( $project->postednum );

    $result = mysql_query("
        SELECT username
        FROM usersettings
        WHERE setting = 'posted_notice' AND value = '$projectid'
    ");
    $numrows = mysql_numrows($result);
    while ( list($username) = mysql_fetch_row($result) )
    {
        $temp = mysql_query("
            SELECT user_email
            FROM phpbb_users
            WHERE username = '$username'
        ");
        $email = mysql_result($temp, 0, "user_email");
        maybe_mail(
            $email,
            "{$project->nameofwork} Posted to Project Gutenberg",
            "You had requested to be let known once {$project->nameofwork} was ready to be available for reading."
            ." It has been sent to Project Gutenberg and will soon be available for reading."
            ." Most files will be ready by the time you receive this mail;"
            ." sometimes there may be a delay of a day or so."
            ." You can download the files via PG's online catalog at <$url>."
            ."\n"
            ."\n"
            ."--"
            ."\n"
            ."Distributed Proofreaders"
            ."\n"
            ."$site_url"
            ."\n"
            ."\n"
            ."This is an automated message that you had requested,"
            ." please do not respond directly to this e-mail.",
            "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n"
        );
    }

    $del = mysql_query("
        DELETE FROM usersettings
        WHERE setting = 'posted_notice' AND value = '$projectid'
    ");

    $ins = mysql_query("
        UPDATE projects
        SET int_level = '$numrows'
        WHERE projectid = '$projectid'
    ");
}

// -----------------------------------------------------------------------------

if ( $site_supports_corrections_after_posting )
{
new ProjectTransition(
    PROJ_SUBMIT_PG_POSTED,
    PROJ_CORRECT_AVAILABLE,
    'user_can_work_in_stage PP', // not sure what it should really be
    array(
        'action_name'           => _("Upload Corrections for Review"),
        'confirmation_question' => null,
        'detour'                => "$code_url/tools/upload_text.php?project=<PROJECTID>&stage=correct",
        'settings_template'     => "modifieddate='{TIMESTAMP}', correctedby='{WHO}'",
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
            'action_name'           => _('Delete Project'),
            'confirmation_question' => _('<P><B>NOTE:</B> Deleting is only for a project that is beyond repair.<P>Are you sure you want to delete this project?'),
            'detour'                => null,
            'settings_template'     => "modifieddate='{TIMESTAMP}'",
            'collateral_actions'    => null,
        )
    );
}

// vim: sw=4 ts=4 expandtab
?>
