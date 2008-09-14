<?PHP
include_once($relPath.'connect.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'topic.inc'); // topic_create

new dbConnect;

class Project
{
    function Project( $arg )
    {
        if ( is_string($arg) )
        {
            // $arg is the projectid.
            $res = mysql_query("
                SELECT *, UNIX_TIMESTAMP() AS t_retrieved
                FROM projects
                WHERE projectid = '$arg'
            ") or die(mysql_error());
            if (mysql_num_rows($res) == 0)
            {
                die("no project with projectid='$arg'");
            }
            $row = mysql_fetch_assoc($res);
        }
        elseif ( is_array($arg) )
        {
            // $arg is assumed to be an associative array, such
            // as would be returned by mysql_fetch_assoc().
            $row = $arg;
        }
        else
        {
            $arg_type = gettype($arg);
            die( "Project::Project(): 'arg' has unexpected type $arg_type" );
        }

        foreach ( $row as $key => $value )
        {
            $this->$key = $value;
        }

        // -------------------------------------------------

        // Maybe set $this->image_source_name.
        if (isset($this->image_source))
        {

            $imso_code = $this->image_source;
            if ( strcmp($imso_code,'_internal') != 0 )
            // if $imso_code = _internal, the images were done by or for a DP User, 
            // whose username should be recorded in image_preparer, and the credit handled by
            // _create_credit_line() below; otherwise, we need to look up info on the
            // specific "external" image source
            {
                $imso_res = mysql_fetch_assoc(mysql_query("
                    SELECT full_name, credit
                    FROM image_sources
                    WHERE code_name = '$imso_code'
                "));
                $this->image_source_name = $imso_res['full_name'];
                $image_source_credit = $imso_res['credit'];
            }
        }

        // Set $this->credits_line.
        $this->credits_line = $this->_create_credit_line();
        if (isset($image_source_credit))
        {
            // Can't put a . at the end of the output of _create_credit_line, since it ends
            // with a url and the . breaks the url in some browsers; yet the image source credit,
            // grammatically and aestehtically, needs some sort of separation from the 
            // "main" credit line - so we enclose it in ( )
            $this->credits_line = $this->credits_line." (".$image_source_credit.")";
        }

        // -------------------------------------------------

        global $projects_url, $projects_dir;

        $this->url = "$projects_url/$this->projectid";
        $this->dir = "$projects_dir/$this->projectid";
        $this->dir_exists = is_dir($this->dir);

        $this->pages_table_exists = 
            ( mysql_query("DESCRIBE $this->projectid") != FALSE );

        // -------------------------------------------------

        global $pguser;

        $this->can_be_managed_by_current_user =
            $this->can_be_managed_by_user( $pguser );
        $this->names_can_be_seen_by_current_user =
            $this->names_can_be_seen_by_user( $pguser );
        $this->PPer_is_current_user =
            $this->PPer_is_user( $pguser);
        $this->PPVer_is_current_user =
            $this->PPVer_is_user( $pguser);
    }

    // -------------------------------------------------------------------------

    function PPer_is_user($username) 
    {
        if ( is_null($username) ) return FALSE;
        return 
            ($this->postproofer == $username
             ||
             (
              $this->checkedoutby == $username
              &&
              (
               $this->state != 'proj_post_second_checked_out'
               &&
               $this->state != 'proj_correct_checked_out'
               )
              )
             );
    }

    // -------------------------------------------------------------------------

    function PPVer_is_user($username)
    {
        if ( is_null($username) ) return FALSE;
        return 
            ( $this->state == 'proj_post_first_checked_out'
              || $this->state == 'proj_post_second_checked_out' )
            && $this->checkedoutby == $username
            && user_can_work_in_stage($username, 'PPV');
        // You might think that we should only allow
        //     $this->state == 'proj_post_second_checked_out'
        // But if someone with PPV-ability is PPing a project,
        // they can (and probably will) directly post to PG,
        // with no explicit PPV phase. Thus, they are effectively
        // both the PPer and PPVer of the project.
    }

    // -------------------------------------------------------------------------

    function can_be_managed_by_user( $username )
    {
        if ( is_null($username) ) return FALSE;
        return
            ( $username == $this->username
            || that_user_is_a_sitemanager($username)
            || that_user_is_proj_facilitator($username) );
    }

    // -------------------------------------------------------------------------

    function names_can_be_seen_by_user( $username)
    {
        global $public_page_details;
        if ( is_null($username) ) return FALSE;
        if ( $public_page_details ) return TRUE;
        return
            ($this->can_be_managed_by_user($username)
             || $this->PPer_is_user($username)
             || $this->PPVer_is_user($username) );
        
    }
    // -------------------------------------------------------------------------

    // These should be treated as constants.
    // (PHP doesn't appear to allow constants as class members.)
    var $CBP_OKAY                         = 0;
    var $CBP_PROJECT_NOT_IN_ROUND         = 1;
    var $CBP_PROJECT_NOT_AVAILABLE        = 2;
    var $CBP_REQUESTOR_NOT_LOGGED_IN      = 3;
    var $CBP_USER_NOT_QUALIFIED_FOR_ROUND = 4;

    function can_be_proofed_by_current_user()
    // (where "proofed" means "worked on in a round, right now".)
    // Returns an array consisting of:
    // -- one of the above codes, and
    // -- a suggested error message.
    {
        global $code_url, $pguser;

        $state = $this->state;
        $round = get_Round_for_project_state($state);

        if (is_null($round))
        {
            // The project is not in any round.
            return array(
                $this->CBP_PROJECT_NOT_IN_ROUND,
                _('The project is not in a round.')
            );
        }

        if ( $state != $round->project_available_state )
        {
            // The project is in a round, but isn't available.
            return array(
                $this->CBP_PROJECT_NOT_AVAILABLE,
                _('Users are not allowed to work on the project in its current state.')
            );
        }

        if ( is_null($pguser) )
        {
            return array(
                $this->CBP_REQUESTOR_NOT_LOGGED_IN,
                _('Registered users can work on the project, but you are not logged in.')
            );
        }

        $uao = $round->user_access($pguser);
        if ( !$uao->can_access )
        {
            // The project is available, but the current user isn't
            // allowed to work in the round that the project is in.
            return array(
                $this->CBP_USER_NOT_QUALIFIED_FOR_ROUND,
                sprintf(
                    _('You have not yet been cleared to work on projects in %s (%s).'),
                    $round->name,
                    $round->id
                )
                . "<br>"
                . sprintf(
                    _("Please visit <a href='%s'>the %s home</a> to find out what happens in this round and how you can qualify to work in it."),
                    "$code_url/tools/proofers/round.php?round_id=$round->id",
                    $round->id
                )
            );
        }

        return array(
            $this->CBP_OKAY,
            "You may work on this project."
        );
    }

    // -------------------------------------------------------------------------

    function _create_credit_line()
    // The string will not be localized, since it should be ready
    // to be included with the finished project.
    {
        global $site_url;

        $credits = array();

        $creditables = array(
            'pm' => $this->username,        // username
            'pp' => $this->postproofer,     // username
            'ip' => $this->image_preparer,  // username
            'tp' => $this->text_preparer,   // username
            'ec' => $this->extra_credits,   // arbitrary text
            'cp' => $this->scannercredit    // username or arbitrary text
        );

        foreach ($creditables as $role => $name)
        {
            if ( $name == '' ) continue;
    
            $credit = NULL;
    
            if ($role == 'cp')
            {
                // $name ($project->scannercredit) could be
                // a username or just a typed-in string
                $res = mysql_query("
                    SELECT username
                    FROM users
                    WHERE username='$name'
                ");
                if (!$res)
                {
                    // $name is not a username.
                    // So use it as the credit.
                    $credit = $name;
                }
            }

            if ($role == 'ec')
            {
                // $name ($project->extra_credits) should not be a username;
                // It is just a typed-in string, and will be presented as is,
                // as part of the list. 
                $credit = $name;
            }
    
            if ( is_null($credit) )
            {
                // $name is a username.
                $username = $name;
    
                if (!wants_anonymity($username, $role))
                {
                    $credit = get_credit_name($username);
                }
            }
    
            if ( !is_null($credit) )
            {
                if (!in_array($credit, $credits))
                    array_push($credits, $credit);
            }
        }

        if (count($credits) > 0)
        {
            $credits_line = join(', ', $credits) . " and the Online Distributed Proofreading Team at $site_url";
        }
        else
        {
            $credits_line = "The Online Distributed Proofreading Team at $site_url";
        }

        return $credits_line;
    }

    // -------------------------------------------------------------------------

    function ensure_topic()
    {
        if ( !empty($this->topic_id) ) return $this->topic_id;

        // Find out PM's preference about being signed up for notifications of replies to this topic.
        $res = mysql_query("
            SELECT value
            FROM usersettings
            WHERE username = '{$this->username}' AND setting = 'auto_proj_thread'
        ") or die(mysql_error());
        $signup_pref = @mysql_result($res,0); // @ because the result set may be empty
        $sign_PM_up = ($signup_pref == 'yes');

        // determine appropriate forum to create thread in
        $forum_id = get_forum_id_for_project_state($this->state);

        $post_subject = "\"{$this->nameofwork}\"    by {$this->authorsname}";

        global $code_url;
        $post_body = <<<EOS
This thread is for discussion specific to "$this->nameofwork" by $this->authorsname.

If you have a question about this project, please review the [url=$code_url/project.php?id={$this->projectid}&detail_level=1]Project Comments[/url], as well as any posts below, as your question may already be answered there.

If you haven't found the answer to your question, or want to make a comment about this project, click on the [b]Post Reply[/b] button to post your question or comment in this thread.

(This post is automatically generated.)
EOS;

        $topic_id = topic_create(
            $forum_id,
            $post_subject,
            $post_body,
            $this->username,
            TRUE,
            $sign_PM_up );

        // Save $topic_id in db and in $this.
        $update_project = mysql_query("
            UPDATE projects
            SET topic_id=$topic_id
            WHERE projectid='{$this->projectid}'
        ");
        $this->topic_id = $topic_id;

        return $topic_id;
    }

    // -------------------------------------------------------------------------

    function email_introduction()
    // Return a string that can be included in the body of an email message,
    // introducing this project as the focus of the message.
    {
        global $site_name, $code_url;

        $projectid = $this->projectid;
        $title = $this->nameofwork;

        return
            sprintf(
                _("This is an automated message from the %s site regarding the following project:"),
                // Should really be localized according to the preferences of the *recipient*.
                $site_name
            )
            . "\n"
            . "    \"$title\"\n"
            . "    ($projectid)\n"
            . "    $code_url/project.php?id=$projectid\n"
            . "\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_get_auto_PPer( $projectid )
// Return the username of the user to whom the project will be
// automatically checked out for PPing when it reaches the PP stage,
// or NULL if the project will merely go into the available-for-PP state.
{
    $res = mysql_query("
        SELECT checkedoutby, username
        FROM projects
        WHERE projectid = '$projectid'
    ");
    list($checkedoutby, $username) = mysql_fetch_row($res);
    $possible_PPer = '';
    if ( $checkedoutby != '' )
    {
        // The project is reserved for a PPer, so it will be checked out to him/her.
        $possible_PPer = $checkedoutby;
    }
    else
    {
        // The project does not have a reserved PPer.

        $res = mysql_query("
            SELECT value
            FROM usersettings
            WHERE username = '$username' AND setting = 'send_to_post' AND value = 'yes'
        ");
        if ( mysql_num_rows($res) > 0 )
        {
            // The PM has send_to_post=yes, so his/her projects go straight to PP.avail.
            return NULL;
        }
        else
        {
            // Otherwise, his/her projects are auto-checked-out to him/her.
            $possible_PPer = $username;
        }
    }
    // now see if the possible PPer is allowed to check projects out
    // when the relevant bit is uncommented, 
    // note that it will mean that the send_to_post setting has no effect
    // if the PM does not have PPer status
    if ( $possible_PPer == ''
         || ! user_can_work_in_stage($possible_PPer, 'PP') 
         || that_user_is_over_PP_checked_out_limit($possible_PPer) )
    {
        return NULL;
    }
    else
    {
        return $possible_PPer;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// The following two functions don't particularly belong here, as they aren't
// project-specific. However, nobody else uses them yet.

// $activity should be one of 'cp', 'pm', 'pp', 'ip' and 'tp'.
function wants_anonymity( $login_name, $activity )
{
    $settings =& Settings::get_Settings($login_name);
    return $settings->get_boolean($activity . '_anonymous');
}

// -----------------------------------------------------------------------------

// Returns the real name OR the username OR a user-specified 'other'.
// (If the user hasn't specified anything in the preferences, the
// real name will be returned.
function get_credit_name( $login_name )
{
    if ($login_name == '')
    {
        return '(no name)';
    }

    $settings =& Settings::get_Settings($login_name);
    $credit = $settings->get_value('credit_name', 'real_name');

    if ($credit == 'username')
    {
        $name = $login_name;
    }
    else if ($credit == 'other')
    {
        $name = $settings->get_value('credit_other');
    }
    else // default: real_name
    {
        $res = mysql_query("
            SELECT real_name
            FROM users
            WHERE username='$login_name'
        ");
        if (mysql_num_rows($res) > 0)
        {
            $name = mysql_result($res, 0);
        }
        else
        {
            $name = $login_name;
        }
    }
    return $name;
}

// vim: sw=4 ts=4 expandtab
?>
