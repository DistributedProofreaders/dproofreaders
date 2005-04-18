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
                $this->state == 'proj_post_first_checked_out'
                && $this->checkedoutby == $pguser;

            $this->PPVer_is_current_user =
                ( $this->state == 'proj_post_first_checked_out'
                    || $this->state == 'proj_post_second_checked_out' )
                && $this->checkedoutby == $pguser
                && user_is_post_proof_verifier();
            // You might think that we should only allow
            //     $this->state == 'proj_post_second_checked_out'
            // But if someone with PPV-ability is PPing a project,
            // they can (and probably will) directly post to PG,
            // with no explicit PPV phase. Thus, they are effectively
            // both the PPer and PPVer of the project.
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
