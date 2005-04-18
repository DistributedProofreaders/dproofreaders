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

// vim: sw=4 ts=4 expandtab
?>
