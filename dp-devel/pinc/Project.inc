<?PHP
include_once($relPath.'connect.inc');
include_once($relPath.'user_is.inc');
new dbConnect;

class Project
{
    function Project( $id )
    {
        $res = mysql_query("
            SELECT *
            FROM projects
            WHERE projectid = '$id'
        ") or die(mysql_error());
        if (mysql_num_rows($res) == 0)
        {
            die("no project with projectid='$id'");
        }
        $row = mysql_fetch_assoc($res);
        foreach ( $row as $key => $value )
        {
            $this->$key = $value;
        }

        // -------------------------------------------------

        // Maybe set $this->image_provider_name.
        if (isset($this->image_provider))
        {
            $imp_code = $this->image_provider;
            if ( strcmp($imp_code,'DP User') != 0 )
            {
                if ( strncmp($imp_code,'O:',2) == 0 )
                {
                    $this->image_provider_name = substr($imp_code,2);
                }
                else
                {
                    $imp_res = mysql_fetch_assoc(mysql_query("
                        SELECT full_name, credit
                        FROM image_sources
                        WHERE code_name = '$imp_code'
                    "));
                    $this->image_provider_name = $imp_res['full_name'];
                    $image_provider_credit = $imp_res['credit'];
                }
            }
        }

        // Set $this->credits_line.
        $this->credits_line = $this->_create_credit_line();
        if (isset($image_provider_credit))
        {
            $this->credits_line = $this->credits_line." ".$image_provider_credit;
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
        if (is_null($pguser))
        {
            $this->can_be_managed_by_current_user = FALSE;
            $this->PPer_is_current_user = FALSE;
            $this->PPVer_is_current_user = FALSE;
        }
        else
        {
            $this->can_be_managed_by_current_user =
                ( $this->username == $pguser
                || user_is_a_sitemanager()
                || user_is_proj_facilitator() );

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

    function _create_credit_line()
    // The string will not be localized, since it should be ready
    // to be included with the finished project.
    {
        global $site_url;

        $credits = array();

        $creditables = array(
            'cp' => $this->scannercredit,
            'pm' => $this->username,
            'pp' => $this->postproofer,
        );

        foreach ($creditables as $role => $name)
        {
            if ( $name == '' ) continue;
    
            $credit = NULL;
    
            // Unlike $project->username and $project->postproofer,
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

// $activity should be one of 'cp', 'pm' and 'pp'.
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
