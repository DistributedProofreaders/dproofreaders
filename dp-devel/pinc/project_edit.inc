<?
include($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');

function abort_if_cant_edit_project( $projectid )
// Politely abort if the current user ($pguser)
// is not allowed to edit the specified project
// (i.e., is neither the project's manager nor a site manager).
{
    global $site_manager_email_addr;
    global $pguser;

    $res = mysql_query("SELECT username FROM projects WHERE projectid = '$projectid' LIMIT 1");
    if (mysql_num_rows($res) == 0)
    {
        echo "
            <P>
            There appears to be no such project ($projectid).
            <P>
            If this message is an error,
            contact the <a href=\"mailto:$site_manager_email_addr\">site manager</a>.
            <P>
            Back to <a href=\"projectmgr.php\">project manager</a> page.
        ";
        exit;
    }
    $project_manager = mysql_result($res, 0, "username");

    $res = mysql_query("SELECT sitemanager FROM users WHERE username = '$pguser' LIMIT 1");
    $sitemanager = mysql_result($res, 0, "sitemanager");

    if ( ($pguser != $project_manager) && ($sitemanager != 'yes') )
    {
        echo "
            <P>
            You ($pguser) are not allowed to change this project ($projectid).
            <P>
            If this message is an error,
            contact the <a href=\"mailto:$site_manager_email_addr\">site manager</a>.
            <P>
            Back to <a href=\"projectmgr.php\">project manager</a> page.
        ";
        exit;
    }
}

?>
