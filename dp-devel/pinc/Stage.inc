<?
// This file defines the class 'Stage', which (depending on your point of view)
// represents:
// -- a stage in the life of a project, or
// -- the set of all projects currently in that stage, or
// -- an area that houses that set of projects.
//
// It's a generalization of the existing ideas of 'round' and 'pool'.

include_once($relPath.'TallyBoard.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'quizzes.inc');
include_once($relPath.'misc.inc'); // startswith
include_once($relPath.'page_header.inc');

// $Stage_for_id_ and $CRITERIA are extended as Stages are defined.

$Stage_for_id_ = array();

$CRITERIA = array(
    'days since reg' => _('days since registration'),
    'quiz/P'         => sprintf(_("<a href='%s'>proofreading quiz</a> pass"),"$code_url/quiz/start.php?show_only=PQ"),
    'quiz/F'         => sprintf(_("<a href='%s'>formatting quiz</a> pass"),"$code_url/quiz/start.php?show_only=FQ"),
);
if ( TRUE )
{
    // kludge
    $CRITERIA['R*+P1'] = sprintf( _("'%s' pages completed"), 'R1+R2+P1' );
    $CRITERIA['P1+P2'] = sprintf( _("'%s' pages completed"), 'P1+P2' );
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
    //         enumerated type
    // 
    // UNIMPLEMENTED:
    // If $n_pages_completed is non-null, use it as the number of pages
    // that the user has completed. Otherwise, consult the database.
    {
        $user_scores = get_user_scores($username);

        // -----------------------------------

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

        if ($uao->all_minima_satisfied)
        {
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
                        $uao->request_status = 'sat-granted';
                        $uao->can_access = TRUE;
                    }
                    else
                    {
                        $uao->request_status = 'sat-available';
                        $uao->can_access = FALSE;
                    }
                    break;

                case 'REQ-HUMAN':
                    // They must ask for access, and it must be human-granted.
                    if ( $recorded_access == 'yes' )
                    {
                        $uao->request_status = 'sat-granted';
                        $uao->can_access = TRUE;
                    }
                    elseif ( $recorded_access == 'requested' )
                    {
                        $uao->request_status = 'sat-requested';
                        $uao->can_access = FALSE;
                    }
                    else
                    {
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
                        $uao->request_status = 'sat-granted';
                        $uao->can_access = TRUE;
                    }
                    else
                    {
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
            if ( $recorded_access == 'yes' )
            {
                $uao->request_status = 'unsat-granted';
                $uao->can_access = TRUE;
            }
            elseif ( $recorded_access == 'requested' )
            {
                // This is unusual, but can happen.
                // E.g., at some point in the past, they satisfied the minima and
                // requested access, but now they no longer satisfy the minima
                // (quiz-pass could expire, page-tally could be reduced by clears).
                $uao->request_status = 'unsat-requested';
                $uao->can_access = FALSE;
            }
            else
            {
                $uao->request_status = 'unsat-ungranted';
                $uao->can_access = FALSE;
            }
        }

        return $uao;
    }

    function page_top( $uao )
    {
        page_header( $this->id, "{$this->id}: {$this->name}" );

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
    else if ( $criterion_code == 'quiz/P' )
    {
        global $PQ;
        $user_score = $PQ->user_has_passed($user_obj->username) ? 1 : 0;
    }
    else if ( $criterion_code == 'quiz/F' )
    {
        global $FQ;
        $user_score = $FQ->user_has_passed($user_obj->username) ? 1 : 0;
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

    if ( count($uao->minima_table) == 0 )
    {
        echo _("There are no minimum requirements associated with this stage.");
    }
    else
    {
        echo _('Entrance Requirements') . ":\n";
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

        if ( $uao->all_minima_satisfied )
        {
            echo _('You satisfy the requirements.');
        }
        else
        {
            echo _('Some requirements are not yet satisfied.');
        }
    }
    echo "\n";

    switch( $uao->request_status )
    {
        case 'sat-unneeded':
            echo _('So you are allowed to work in this stage.');
            break;

        case 'sat-granted':
            echo _('You have received permission to work in this stage.');
            break;

        case 'sat-requested':
        case 'unsat-requested':
            echo _('You have requested permission, but it has not been granted yet.');
            break;

        case 'sat-available':
            echo sprintf(
                _('If you would like to work in this stage, <a href="%s">click here</a> to submit a request.'),
                "$code_url/tools/request_access.php?stage_id={$uao->stage_id}"
            );
            break;

        case 'sat-wait':
            echo _('However, you must wait for approval to work in this stage.');
            // Users in this stage will monitor your work and will let you know once you have qualified for access
            break;

        case 'unsat-granted':
            echo _("However, you have been granted access to this stage.");
            break;

        case 'unsat-ungranted':
            // Don't mention possibility of special permission.
            // echo _("However, you can be granted access to this stage. See XXX for details.");
            break;

        default:
            die( "bad request_status '$uao->request_status'" );
    }
    echo "\n";
    
    echo "<br>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_user_access_chart( $username )
{
    global $CRITERIA, $code_url;

    list($allow_grant,$allow_revoke) = user_can_modify_access();

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
        echo "<th colspan='$n_criteria'>Criteria</th>";
        echo "<th rowspan='3'>all sat?</th>";
        echo "<th rowspan='3'>request</th>";
        if ( $allow_revoke )
        {
            echo "<th rowspan='3'>deny request?</th>";
        }
        echo "<th rowspan='3'>ACCESS?</th>";
        if ( $allow_grant )
        {
            echo "<th rowspan='3'>grant?</th>";
        }
        if ( $allow_revoke )
        {
            echo "<th rowspan='3'>revoke?</th>";
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
        echo "<th>user score:</th>";
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
            ($uao->all_minima_satisfied ? 'yes' : 'no'),
            $uao->all_minima_satisfied );

        $could_grant = FALSE;
        $could_revoke = FALSE;
        $could_deny_request = FALSE;
        global $code_url;
        echo "<td>";
        switch( $uao->request_status )
        {
            case 'sat-unneeded':
                echo 'unnec';
                break;

            case 'sat-granted':
            case 'unsat-granted':
                echo 'granted';
                $could_revoke = TRUE;
                break;

            case 'sat-requested':
            case 'unsat-requested':
                echo 'requested';
                $could_grant = TRUE;
                $could_deny_request = TRUE;
                break;

            case 'sat-wait':
                echo 'waiting';
                $could_grant = TRUE;
                break;

            case 'sat-available':
                echo 'not yet';
                $could_grant = TRUE;
                break;

            case 'unsat-ungranted':
                $could_grant = TRUE;
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

        echo td_w_bgcolor( ($uao->can_access?'YES':'NO'), $uao->can_access );

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

    // kludge for P2_mentor
    $activity_id = 'P2_mentor';
    {
        echo "<tr>";
        echo "<th>$activity_id</th>";
        foreach ( $CRITERIA as $criterion_code => $criterion_descr )
        {
            echo "<td></td>";
        }

        echo td_w_bgcolor( 'yes', TRUE );

        $settings =& Settings::get_Settings($username);
        $can_access = $settings->get_boolean("$activity_id.access");

        $could_grant = FALSE;
        $could_revoke = FALSE;

        echo "<td>";
        if ( $can_access )
        {
            echo 'granted';
            $could_revoke = TRUE;
        }
        else
        {
            echo "can't";
            $could_grant = TRUE;
        }
        echo "</td>";

        if ( $allow_revoke )
        {
            echo "<td>";
            echo "</td>";
        }

        echo td_w_bgcolor( ($can_access?'YES':'NO'), $can_access );

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
    
    echo "<tr><td colspan='14' style='text-align: center;'>" .
           "<input type='checkbox' name='notify_user' checked />Notify user of change" .
         "</td></tr>";

    echo "</table>\n";

    if ( $allow_grant || $allow_revoke )
    {
        echo "<input type='submit' value='Submit Changes'>\n";
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

// vim: sw=4 ts=4 expandtab
?>
