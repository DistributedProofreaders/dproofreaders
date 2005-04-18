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

        $this->can_be_managed_by_current_user =
            ( user_is_PM_of($this->projectid)
            || user_is_a_sitemanager()
            || user_is_proj_facilitator() );

        $this->PPer_is_current_user = user_is_PP_of($this->projectid);

        $this->PPVer_is_current_user = user_is_PPV_of($this->projectid);
    }
}

function user_is_PM_of($project)
{
    global $pguser;

    if (!isset($pguser)) return FALSE;
    if (user_is_a_sitemanager()) return TRUE;
    $result = mysql_query("SELECT username FROM projects WHERE projectid = '$project'");
    if (mysql_num_rows($result) == 0) return FALSE;
    return (mysql_result($result, 0, "username") == $pguser);
}

function user_is_PP_of($project)
{
    global $pguser;

    if (!isset($pguser)) return FALSE;
    if (user_is_a_sitemanager()) return TRUE;

    $result = mysql_query("SELECT checkedoutby FROM projects
                           WHERE projectid = '$project'
                            AND state = 'proj_post_first_checked_out'");
    if (mysql_num_rows($result) == 0) return FALSE;
    return (mysql_result($result, 0, "checkedoutby") == $pguser);
}

function user_is_PPV_of($project)
{
    global $pguser;

    if (user_is_a_sitemanager()) return TRUE;
    if (!user_is_post_proof_verifier()) return FALSE;

    // Note that if someone with PPV-ability is PPing a project,
    // they can (and probably will) directly post to PG,
    // with no explicit PPV phase. Thus, they are effectively
    // both the PPer and PPVer of the project.
    $result = mysql_query("SELECT checkedoutby FROM projects
                           WHERE projectid = '$project'
                            AND state IN ('proj_post_first_checked_out',
                                            'proj_post_second_checked_out')");
    if (mysql_num_rows($result) == 0) return FALSE;
    return (mysql_result($result, 0, "checkedoutby") == $pguser);
}

// vim: sw=4 ts=4 expandtab
?>
