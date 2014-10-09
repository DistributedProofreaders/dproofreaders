<?php
// This file defines the class 'Stage', which (depending on your point of view)
// represents:
// -- a stage in the life of a project, or
// -- the set of all projects currently in that stage, or
// -- an area that houses that set of projects.
//
// It's a generalization of the existing ideas of 'round' and 'pool'.

include_once($relPath.'TallyBoard.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'quizzes.inc'); // get_Quiz_with_id
include_once($relPath.'misc.inc'); // startswith

// $Stage_for_id_ and $CRITERIA are extended as Stages are defined.

$Stage_for_id_ = array();

$CRITERIA = array(
    'days since reg' => _('days since registration'),
    'quiz/p_mod1'    => sprintf(_("<a href='%s'>moderate proofreading quiz 1</a> pass"),"$code_url/quiz/start.php?show_level=P_MOD"),
    'quiz/p_mod2'    => sprintf(_("<a href='%s'>moderate proofreading quiz 2</a> pass"),"$code_url/quiz/start.php?show_level=P_MOD"),
    'quiz/f_only'    => sprintf(_("<a href='%s'>formatting quiz</a> pass"),"$code_url/quiz/start.php?show_only=format"),
);
if ( TRUE )
{
    // kludge
    // TRANSLATORS: %s is a round ID, such as P1 or P1+P2
    $CRITERIA['R*+P1'] = sprintf( _("'%s' pages completed"), 'R1+R2+P1' );
}

class Stage
{
    function Stage(
        $id,
            // A very short mnemonic identifier for the stage.
            // (Should probably conform to the rules for a PHP variable name.)
        $name,
            // A gettext-translated name for the stage.
        $access_minima,
            // An array of minimum requirements that a user must satisfy
            // in order to be allowed to participate in this stage
            // (barring special permission).
            // If empty array, no minima: everyone can participate.
            // If NULL, users cannot qualify for this stage by satisfying minima.
        $after_satisfying_minima,
            // After satisfying the above minima, does the user have to do
            // anything else to work in this stage?
            //     'IMMEDIATE'  Nope, they get immediate access.
            //     'REQ-AUTO'   They must ask for access, but it is auto-granted.
            //     'REQ-HUMAN'  They must ask for access, and it must be human-granted.
        $evaluation_criteria,
           // A brief description of what the evaluation criteria are. 
        $description,
            // A sentence or two explaining what happens in this stage.
        $document,
            // The path (relative to $code_url/faq/) of a document that tells you
            // everything you need to know about working in this stage.
            // Or NULL if there is no such document.
        $listing_bgcolors,
            // An array of (two) HTML colors that will be used as the bgcolor
            // in alternating rows in the listing of projects in this round.

        $relative_url
            // The "home" location (relative to $code_url/) of the stage.
    )
    {
        $this->id            = $id;
        $this->name          = $name;
        $this->access_minima = $access_minima;
        $this->after_satisfying_minima = $after_satisfying_minima;
        $this->evaluation_criteria = $evaluation_criteria;
        $this->description   = $description;
        $this->document      = $document;
        $this->relative_url  = $relative_url;
        $this->listing_bgcolors = $listing_bgcolors;

        global $testing;
        if ($testing && !is_null($this->access_minima))
        {
            // Relax minima.
            foreach ( $this->access_minima as $criterion_code => $minimum )
            {
                if ( startswith( $criterion_code, 'quiz/' ) )
                {
                    // skip
                }
                else if ( $criterion_code == 'days since reg' )
                {
                    $this->access_minima[$criterion_code] = intval($minimum / 7);
                }
                else
                {
                    $this->access_minima[$criterion_code] = intval($minimum / 30);
                }
            }
        }

        global $Stage_for_id_;
        $Stage_for_id_[$id] =& $this;

        global $CRITERIA;
        if (is_a($this,'Round'))
        {
            $CRITERIA[$this->id] =
                sprintf( _("'%s' pages completed"), $this->id );
        }
    }

    function user_access( $username, $n_pages_completed=null )
    // Return an object with the following properties:
    // -- can_access:
    //         a boolean: TRUE iff the user can access this stage.
    // -- minima_table:
    //         an array of arrays (4-tuples):
    //         ( $criterion_str, $minimum, $user_score, $satisfied )
    // -- all_minima_satisfied:
    //         boolean
    // -- request_status:
    //         enumerated type: sat-unneeded, sat-granted, sat-available, sat-requested,
    //                          sat-wait, sat-denied
    //                          unsat-granted, unsat-requested, unsat-ungranted, 
    //                          unsat-denied
    // -- evaluation_criteria:
    //         message to display about evaluation
    // 
    // UNIMPLEMENTED:
    // If $n_pages_completed is non-null, use it as the number of pages
    // that the user has completed. Otherwise, consult the database.
    {
        if (is_null($username))
        {
            $uao = new StdClass; // user access object
            $uao->can_access = FALSE;
            return $uao;
        }

        $user_scores = get_user_scores($username);

        // -----------------------------------
        // recorded_access is the value recorded in the db for whether the user
        // can access this stage. It's read from the usersettings table.
        // If there is a relevant usersetting, it can take values 'yes', 'no'
        // or 'denied'.
        // No relevant usersetting has the same effect as 'no'.
        {
            $res = mysql_query("
                SELECT value
                FROM usersettings
                WHERE username='$username' AND setting='{$this->id}.access'
            ") or die(mysql_error());
            if ( mysql_num_rows($res) == 0 )
            {
                $recorded_access = 'no';
            }
            elseif ( mysql_num_rows($res) == 1 )
            {
                $recorded_access = mysql_result($res,0);
            }
            else
            {
                // Not sure what to do.
                $recorded_access = mysql_result($res,0);
            }
        }

        // -----------------------------------

        $uao = new StdClass; // user access object

        $uao->stage_id = $this->id;
        $uao->evaluation_criteria = $this->evaluation_criteria;
        // Considering the minima...
        global $CRITERIA;
        {
            $uao->minima_table = array();
            $uao->all_minima_satisfied = TRUE;
            foreach ( $this->access_minima as $criterion_code => $minimum )
            {
                $criterion_str = $CRITERIA[$criterion_code];
                $user_score = $user_scores[$criterion_code];

                $satisfied = ( $user_score >= $minimum );
                $uao->minima_table[$criterion_code] = array( $criterion_str, $minimum, $user_score, $satisfied );
                if ( !$satisfied ) $uao->all_minima_satisfied = FALSE;
            }

        }

        if ($recorded_access == 'denied')
        {
            // they are not allowed to access this stage or to request access
            $uao->can_access = FALSE;
            if ($uao->all_minima_satisfied)
            {
                $uao->request_status = 'sat-denied';
            }
            else
            {
                $uao->request_status = 'unsat-denied';
            }

        }
        else if ($uao->all_minima_satisfied)
        {
            // They've satisfied the requirements...
            switch ($this->after_satisfying_minima)
            {
                case 'IMMEDIATE':
                    // They get immediate access.
                    $uao->request_status = 'sat-unneeded';
                    $uao->can_access = TRUE;
                    break;

                case 'REQ-AUTO':
                    // They must ask for access, but it is auto-granted.
                    if ( $recorded_access == 'yes' )
                    {
                        // They already have access
                        $uao->request_status = 'sat-granted';
                        $uao->can_access = TRUE;
                    }
                    else
                    {
                        // They haven't asked yet, but can
                        $uao->request_status = 'sat-available';
                        $uao->can_access = FALSE;
                    }
                    break;

                case 'REQ-HUMAN':
                    // They must ask for access, and it must be human-granted.
                    if ( $recorded_access == 'yes' )
                    {
                        // They already have access
                        $uao->request_status = 'sat-granted';
                        $uao->can_access = TRUE;
                    }
                    elseif ( $recorded_access == 'requested' )
                    {
                        // They've asked, but it hasn't been granted yet
                        $uao->request_status = 'sat-requested';
                        $uao->can_access = FALSE;
                    }
                    else
                    {
                        // They haven't asked yet, but can
                        $uao->request_status = 'sat-available';
                        $uao->can_access = FALSE;
                    }
                    break;

                case 'NOREQ':
                    // They don't request access (or at least, we don't supply a link
                    // by which to request access). Instead, they just wait until
                    // they are approved.
                    if ( $recorded_access == 'yes' )
                    {
                        // They already have access
                        $uao->request_status = 'sat-granted';
                        $uao->can_access = TRUE;
                    }
                    else
                    {
                        // They are waiting
                        $uao->request_status = 'sat-wait';
                        $uao->can_access = FALSE;
                    }
                    break;

                default:
                    die( "bad after_satisfying_minima value: '$this->after_satisfying_minima'" );
            }
        }
        else
        {
            // They don't satisfy the requirements
            if ( $recorded_access == 'yes' )
            {
                // but they already have access anyway
                $uao->request_status = 'unsat-granted';
                $uao->can_access = TRUE;
            }
            elseif ( $recorded_access == 'requested' )
            {
                // but they've requested access, but don't have it yet
                // This is unusual, but can happen.
                // E.g., at some point in the past, they satisfied the minima and
                // requested access, but now they no longer satisfy the minima
                // (quiz-pass could expire, page-tally could be reduced by clears).
                $uao->request_status = 'unsat-requested';
                $uao->can_access = FALSE;
            }
            else
            {
                // and they can't ask for access
                $uao->request_status = 'unsat-ungranted';
                $uao->can_access = FALSE;
            }
        }

        return $uao;
    }


    function page_header( $title )
    // Display a page-header, either an image (if available) or a textual title
    // for this stage.
    {
        global $dyn_dir, $dyn_url;

        $header_images_dir = "$dyn_dir/header_images";
        $header_images_url = "$dyn_url/header_images";

        $img_base = $this->id;

        if ( file_exists("$header_images_dir/$img_base.jpg") )
        {
            echo "<br>";
            echo "<center>";
            echo "<img src='$header_images_url/$img_base.jpg' title='" 
                . attr_safe($title) . "' alt='" . attr_safe($title) . "'>";
            echo "</center>";
        }
        else
        {
            echo "<h1 align='center'>$title</h1>";
        }
        echo "\n";
    }

    function page_top( $uao )
    {
        $this->page_header( "{$this->id}: {$this->name}" );

        if ( !$uao->can_access )
        {
            echo "<p align='center'>";
            echo sprintf( _("Welcome to %s!"), $this->id ), "\n";
            echo _("Feel free to explore this stage."), "\n";
            echo _("You can find out what happens here, and follow the progress of projects from earlier rounds."), "\n";
            echo _("If you're interested in working in this stage, see below to find out how you can qualify."), "\n";
            echo "</p>";
            echo "\n";
        }

        echo "<p align='center'>";
        echo "<b>";
        echo _('What happens in this stage');
        echo ":</b><br>";
        echo $this->description;
        echo "</p>\n";
    }

}

// --------------------------------------------------------------------------

function get_user_scores($username)
{
    global $CRITERIA;

    assert( !is_null($username) );

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

    $user_scores = array();
    foreach ( $CRITERIA as $criterion_code => $criterion_descr )
    {
        $user_scores[$criterion_code] = get_user_score( $user_obj, $criterion_code );
    }
    return $user_scores;
}

function get_user_score( $user_obj, $criterion_code )
{
    $terms = explode( '+', $criterion_code );
    if ( count($terms) > 1 )
    {
        $sum = 0;
        foreach ( $terms as $term )
        {
            $sum += get_user_score( $user_obj, $term );
        }
        return $sum;
    }

    if ( $criterion_code == 'days since reg' )
    {
        $user_score = round( ( time() - $user_obj->date_created ) / 86400, 1 );
    }
    else if ( startswith( $criterion_code, 'quiz/' ) )
    {
        $quiz_id = substr($criterion_code, 5);
        $quiz = get_Quiz_with_id($quiz_id);
        $user_score = $quiz->user_has_passed($user_obj->username) ? 1 : 0;
    }
    else
    {
        $tally_name = $criterion_code;
        $tallyboard = new TallyBoard( $tally_name, 'U' );
        $user_score = $tallyboard->get_current_tally( $user_obj->u_id );
    }
    return $user_score;
}

// --------------------------------------------------------------------------

function show_user_access_object( $uao )
{
    global $code_url;

    echo "<a name='Entrance_Requirements'></a>";

    if ( count($uao->minima_table) == 0 )
    {
        echo "<p>" . _("There are no minimum requirements associated with this stage.") . "</p>";
    }
    else
    {
        echo "<h2>" . _('Entrance Requirements') . "</h2>\n";
        echo "<p>" . _('To access this round, you must satisfy the following requirements.') . "</p>";
        echo "<table border='1'>\n";

        echo "<tr>";
        echo "<th>" . _('Criterion') . "</th>";
        echo "<th>" . _('Minimum')  . "</th>";
        echo "<th>" . _('You')      . "</th>";
        echo "</tr>\n";

        foreach ( $uao->minima_table as $row )
        {
            list($criterion_str, $minimum, $user_score, $satisfied) = $row;
            $bgcolor = ( $satisfied ? '#ccffcc' : '#ffcccc' );
            echo "<tr>";
            echo "<td>$criterion_str</td>";
            echo "<td>$minimum</td>";
            echo "<td bgcolor='$bgcolor'>$user_score</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";

        echo "<p>";
        echo "<b>" . _('Eligibility status') . ":</b> ";
        if ( $uao->all_minima_satisfied )
        {
            echo _('You satisfy the requirements.');
        }
        else
        {
            echo _('Some requirements are not yet satisfied.') . ' ';
            echo $uao->evaluation_criteria;
        }
        echo "</p>";
    }
    echo "\n";

    $request_status_string="";
    switch( $uao->request_status )
    {
        case 'sat-unneeded':
            $request_status_string=_('So you are allowed to work in this stage.');
            break;

        case 'sat-granted':
            $request_status_string=_('You have received permission to work in this stage.');
            break;

        case 'sat-requested':
        case 'unsat-requested':
            $request_status_string=_('You have requested permission, but it has not been granted yet.');
            break;

        case 'sat-available':
            $request_status_string=$uao->evaluation_criteria;
            $request_status_string.= " " . sprintf(
                _('If you would like to work in this stage, <a href="%s">click here</a> to submit a request.'),
                "$code_url/tools/request_access.php?stage_id={$uao->stage_id}"
            );
            break;

        case 'sat-wait':
            $request_status_string=_('However, you must wait for approval to work in this stage.');
            $request_status_string.=" " . $uao->evaluation_criteria;
            // Users in this stage will monitor your work and will let you know once you have qualified for access
            break;

        case 'sat-denied':
            $request_status_string=_('However, you are not currently permitted to apply for access to this stage.');
            break;

        case 'unsat-granted':
            $request_status_string=_("However, you have been granted access to this stage.");
            break;

        case 'unsat-ungranted':
            $stage = get_Stage_for_id($uao->stage_id);
            if($stage->after_satisfying_minima == 'REQ-AUTO' || $stage->after_satisfying_minima == 'REQ-HUMAN')
            {
                $request_status_string=_('After you have satisfied the requirements, a link will appear here, allowing you to request access.');
            }
            else
            {
                // $request_status_string=_('After you have satisfied the requirements, you will be evaluated for access.');
            }
            // Don't mention possibility of special permission.
            // $request_status_string.=_("However, you can be granted access to this stage. See XXX for details.");
            break;

        case 'unsat-denied':
            // leave the string empty, so nothing prints out
            break;

        default:
            die( "bad request_status '$uao->request_status'" );
    }

    if(!empty($request_status_string))
        echo "<p>$request_status_string</p>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_user_access_chart( $username )
{
    global $CRITERIA, $code_url;

    if ( is_null($username) ) die('show_user_access_chart: $username is null');

    list($allow_grant,$allow_revoke) = user_can_modify_access_of($username);

    if ( $allow_grant || $allow_revoke )
    {
        echo "<form method='post' action='$code_url/tools/modify_access.php'>\n";
        echo "<input type='hidden' name='subject_username' value='$username'>\n";
    }

    echo "<table border='1'>";

    // top header line
    {
        $n_criteria = count($CRITERIA);
        echo "<tr>";
        echo "<th></th>";
        echo "<th colspan='$n_criteria'>" . _("Criteria") . "</th>";
        echo "<th rowspan='3'>" . _("all sat?") . "</th>";
        echo "<th rowspan='3'>" . _("request") . "</th>";
        if ( $allow_revoke )
        {
            echo "<th rowspan='3'>" . _("deny request?") . "</th>";
        }
        echo "<th rowspan='3'>" . _("can access?") . "</th>";
        if ( $allow_grant )
        {
            echo "<th rowspan='3'>" . _("grant?") . "</th>";
        }
        if ( $allow_revoke )
        {
            echo "<th rowspan='3'>" . _("revoke?") . "</th>";
        }
        echo "</tr>\n";
    }

    // bottom header line
    {
        echo "<tr>";
        echo "<th></th>";
        foreach ( $CRITERIA as $criterion_code => $criterion_descr )
        {
            echo "<th>$criterion_code</th>";
        }
        echo "</tr>\n";
    }

    // user scores line
    {
        $user_scores = get_user_scores($username);

        echo "<tr>";
        echo "<th>" . _("user score:") . "</th>";
        foreach ( $CRITERIA as $criterion_code => $criterion_descr )
        {
            $user_score = $user_scores[$criterion_code];
            echo "<td align='right'>$user_score</td>";
        }
        echo "</tr>\n";
    }

    // stage lines
	global $Stage_for_id_;
	foreach ( $Stage_for_id_ as $stage )
    {
		$uao = $stage->user_access($username);

        echo "<tr>";
        echo "<th>$stage->id</th>";
        foreach ( $CRITERIA as $criterion_code => $criterion_descr )
        {
            $row = @$uao->minima_table[$criterion_code];
            if (is_null($row))
            {
                echo "<td></td>";
            }
            else
            {
                list($criterion_str, $minimum, $user_score, $satisfied) = $row;
                echo td_w_bgcolor( $minimum, $satisfied );
            }
        }

        echo td_w_bgcolor(
            ($uao->all_minima_satisfied ? _('yes') : _('no')),
            $uao->all_minima_satisfied );

        $could_grant = FALSE;
        $could_revoke = FALSE;
        $could_deny_request = FALSE;
        global $code_url;
        echo "<td>";
        switch( $uao->request_status )
        {
            case 'sat-unneeded':
                // TRANSLATORS: abbreviation for "unnecessary"
                echo _('unnec');
                break;

            case 'sat-granted':
            case 'unsat-granted':
                echo _('granted');
                $could_revoke = TRUE;
                break;

            case 'sat-requested':
            case 'unsat-requested':
                echo _('requested');
                $could_grant = TRUE;
                $could_deny_request = TRUE;
                break;

            case 'sat-wait':
                echo _('waiting');
                $could_grant = TRUE;
                break;

            case 'sat-available':
                echo _('not yet');
                $could_grant = TRUE;
                break;

            case 'unsat-ungranted':
                $could_grant = TRUE;
                break;

            case 'sat-denied':
            case 'unsat-denied':
                echo _('denied');
                break;


            default:
                die( "bad request_status '$uao->request_status'" );
        }
        echo "</td>";

        if ( $allow_revoke )
        {
            echo "<td>";
            if ( $could_deny_request )
            {
                echo "<input type='checkbox' name='{$stage->id}|deny_request_for'>";
            }
            echo "</td>";
        }

        echo td_w_bgcolor( ($uao->can_access?_('yes'):_('no')), $uao->can_access );

        if ( $allow_grant )
        {
            echo "<td>";
            if ( $could_grant )
            {
                echo "<input type='checkbox' name='{$stage->id}|grant'>";
            }
            echo "</td>";
        }

        if ( $allow_revoke )
        {
            echo "<td>";
            if ( $could_revoke )
            {
                echo "<input type='checkbox' name='{$stage->id}|revoke'>";
            }
            echo "</td>";
        }

        echo "</tr>\n";
    }

    // mentor lines
	foreach ( $Stage_for_id_ as $stage )
    {
        if ( is_a( $stage, 'Round' ) && $stage->is_a_mentor_round() )
        {
            $activity_id = $stage->id . "_mentor";
            // We're assuming there isn't a *stage* whose id ends with "_mentor".
            // If there is, this construction could lead to problems.

            echo "<tr>";
            echo "<th>$activity_id</th>";
            foreach ( $CRITERIA as $criterion_code => $criterion_descr )
            {
                echo "<td></td>";
            }

            echo td_w_bgcolor( _('yes'), TRUE );

            $settings =& Settings::get_Settings($username);
            $can_access = $settings->get_boolean("$activity_id.access");

            $could_grant = FALSE;
            $could_revoke = FALSE;

            echo "<td>";
            if ( $can_access )
            {
                echo _('granted');
                $could_revoke = TRUE;
            }
            else
            {
                echo _("can't");
                $could_grant = TRUE;
            }
            echo "</td>";

            if ( $allow_revoke )
            {
                echo "<td>";
                echo "</td>";
            }

            echo td_w_bgcolor( ($can_access?_('yes'):_('no')), $can_access );

            if ( $allow_grant )
            {
                echo "<td>";
                if ( $could_grant )
                {
                    echo "<input type='checkbox' name='{$activity_id}|grant'>";
                }
                echo "</td>";
            }

            if ( $allow_revoke )
            {
                echo "<td>";
                if ( $could_revoke )
                {
                    echo "<input type='checkbox' name='{$activity_id}|revoke'>";
                }
                echo "</td>";
            }
            echo "</tr>\n";
        }
    }
    
    echo "<tr><td colspan='14' style='text-align: center;'>" .
           "<input type='checkbox' name='notify_user' checked />" . _("Notify user of change") .
         "</td></tr>";

    echo "</table>\n";

    if ( $allow_grant || $allow_revoke )
    {
        echo "<input type='submit' value='". attr_safe(_("Submit Changes")) . "'>\n";
        echo "</form>\n";
    }
}

function td_w_bgcolor( $text, $bool )
{
	$bgcolor = ( $bool ? '#ccffcc' : '#ffcccc' );
	return "<td align='right' bgcolor='$bgcolor'>$text</td>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function & get_Stage_for_id( $id )
{
    global $Stage_for_id_;
    if ( array_key_exists( $id, $Stage_for_id_ ) )
    {
        return $Stage_for_id_[$id];
    }
    else
    {
        die( "There is no stage with id='$id'." );
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function user_can_work_in_stage( $username, $stage_id )
{
    $stage = get_Stage_for_id($stage_id);
    $uao = $stage->user_access($username);
    return $uao->can_access;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_stages_user_can_work_in( $username )
{
    global $Stage_for_id_;

    $accessible_stages = array();
    foreach ( $Stage_for_id_ as $stage )
    {
        $uao = $stage->user_access($username);
        if($uao->can_access)
        {
            $accessible_stages[$stage->id] = $stage;
        }
    }

    return $accessible_stages;
}

function get_stages_for_which_user_has_access_to_prereqs( $username, $include_accessible_stages = FALSE )
// Return an array of stages for which the user has access to all prereq
// stages. If $include_accessible_stages is true, this array also includes
// stages the user has access to.
{
    global $Stage_for_id_;

    $satisfied_prereq_stages = array();

    // get stages that the user can currently access
    $accessible_stages = get_stages_user_can_work_in($username);

    // if the caller wants the accessible stages too (not just the prereq
    // stages) start with the accessible list
    if($include_accessible_stages)
    {
        $satisfied_prereq_stages = $accessible_stages;
    }

    foreach ( $Stage_for_id_ as $stage )
    {
        // skip stages for which the user already has access
        if(isset($accessible_stages[$stage->id]))
        {
            continue;
        }

        // get all pre-reqs for this stage
        $prereq_criteria = array();
        foreach($stage->access_minima as $criterion => $value)
        {
            $prereq_criteria = array_merge($prereq_criteria, explode('+',$criterion));
        }
        $prereq_criteria = array_unique($prereq_criteria);

        // don't include rounds that have no round access requirements (eg: PPV)
        if(count($prereq_criteria) == 0)
        {
            $has_access_to_all_prereq_stages = false;
        }
        else
        {
            // check to see if the user has access to all pre-req stages
            // assume they do and see about disproving it
            $has_access_to_all_prereq_stages = true;

            foreach($prereq_criteria as $prereq_criterion)
            {
                // check that the criterion in question is a stage
                // and that the user can access it
                if(@$Stage_for_id_[$prereq_criterion] && !isset($accessible_stages[$prereq_criterion]))
                {
                    $has_access_to_all_prereq_stages = false;
                    break;
                }
            }
        }

        if($has_access_to_all_prereq_stages)
        {
            $satisfied_prereq_stages[$stage->id] = $stage;
        }
    }

    return $satisfied_prereq_stages;
}

// vim: sw=4 ts=4 expandtab
?>
