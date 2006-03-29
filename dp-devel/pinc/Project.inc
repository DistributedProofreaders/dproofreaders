<?PHP
include_once($relPath.'connect.inc');
include_once($relPath.'user_is.inc');
new dbConnect;

class Project
{
    function Project( $arg )
    {
        if ( is_string($arg) )
        {
            // $arg is the projectid.
            $res = mysql_query("
                SELECT *
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
            // whose username should be recorded in image_provider, and the credit handled by
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

        if (is_null($pguser))
        {
            $this->PPer_is_current_user = FALSE;
            $this->PPVer_is_current_user = FALSE;
        }
        else
        {
            $this->PPer_is_current_user =
                $this->postproofer == $pguser
                ||
                (
                    $this->checkedoutby == $pguser
                    &&
                    (
                        $this->state != 'proj_post_second_checked_out'
                        &&
                        $this->state != 'proj_correct_checked_out'
                    )
                );

            $this->PPVer_is_current_user =
                ( $this->state == 'proj_post_first_checked_out'
                    || $this->state == 'proj_post_second_checked_out' )
                && $this->checkedoutby == $pguser
                && user_can_work_in_stage($pguser, 'PPV');
            // You might think that we should only allow
            //     $this->state == 'proj_post_second_checked_out'
            // But if someone with PPV-ability is PPing a project,
            // they can (and probably will) directly post to PG,
            // with no explicit PPV phase. Thus, they are effectively
            // both the PPer and PPVer of the project.
        }
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

    function _create_credit_line()
    // The string will not be localized, since it should be ready
    // to be included with the finished project.
    {
        global $site_url;

        $credits = array();

        $creditables = array(
            'pm' => $this->username,
            'pp' => $this->postproofer,
            'ip' => $this->image_preparer,
            'tp' => $this->text_preparer,
            'ec' => $this->extra_credits,
            'cp' => $this->scannercredit
        );

        foreach ($creditables as $role => $name)
        {
            if ( $name == '' ) continue;
    
            $credit = NULL;
    
            // Unlike $project->username, $project->postproofer, 
            // $project->image_preparer and $project->text_preparer
            // $project->scannercredit isn't necessarily a username;
            // it might just be a typed-in string.
            if ($role == 'cp')
            {
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

            // Unlike  the other credit-holding fields, like $project->username, $project->postproofer, 
            // $project->image_preparer, $project->text_preparer and $project->scannercredit (deprecated)
            // $project->extra_credits should not be a username;
            // It is just a typed-in string, and will be presented as is, as part of the list. 
            if ($role == 'ec')
            {
                // $name is not a username.
                // So use it as the credit.
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
