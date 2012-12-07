<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');

require_login();

theme("Copyright Approval", "header");

if (!$site_supports_metadata)
{
    echo '$site_supports_metadata is false, so exiting.';
    exit;
}

if (!user_is_a_sitemanager())
{
    echo _('You are not authorized to invoke this script.');
    exit;
}

//----------------------------------------------------------------------------------

$projectid = @$_GET['projectid'];
if (isset($projectid))
{
    //update project approval status
    if ($_GET['metadata'] =='approved')
    {
        $statuschange = 'project_new_app';
    }
    else
    {
        $statuschange = 'project_new_unapp';     
    }

    $result = mysql_query("
        UPDATE projects
        SET state = '$statuschange'
        WHERE projectid = '$projectid'
    ");
}

echo "<table border=1>\n";
    // Header row
    echo "
        <tr>
            <td align='center' colspan='4'><b>Books Waiting for Copyright Approval</b></td>
        </tr>
        <tr>
            <td align='center' colspan='4'>The following books need to be approved/disapproved for copyright clearance.</td>
        </tr>
        <tr>
            <td align='center' colspan='1'><b>Title</b></td>
            <td align='center' colspan='1'><b>Author</b></td>
            <td align='center' colspan='1'><b>Clearance Line</b></td>
            <td align='center' colspan='1'><b>Approved/Disapproved</b></td>
        </tr>
    ";

    $result = mysql_query("
        SELECT projectid, nameofwork, authorsname, clearance, state
        FROM projects
        WHERE state = 'project_new_waiting_app'
    ");
    $numrows = mysql_num_rows($result);
    $rownum = 0;

    while ($rownum < $numrows) {
        $projectid = mysql_result($result, $rownum, "projectid");
        $state = mysql_result($result, $rownum, "state");
        $name = mysql_result($result, $rownum, "nameofwork");
        $author = mysql_result($result, $rownum, "authorsname");
        $clearance = mysql_result($result, $rownum, "clearance");

        if ($rownum % 2 ) {
            $row_color = $theme['color_mainbody_bg'];
        } else {
            $row_color = $theme['color_navbar_bg'];
        }

        echo "
            <tr bgcolor='$row_color'>
            <td align='right'><a href='$code_url/project.php?id=$projectid'>$name</a></td>
            <td align='right'>$author</td>
            <td><input type='text' size='67' name='clearance' value='$clearance'></td>
            <td>
                <form action='proj_approvals.php?projectid=$projectid'>
                Approved<input type='radio' name='metadata' value='approved'>
                Disapproved<input type='radio' name='metadata' value='disapproved'>
                <INPUT TYPE=SUBMIT VALUE='update'>
                </form>
            </td>
            </tr>
        ";

        $rownum++;
    }

    echo "<tr></tr>\n";
    echo "<tr></tr>\n";
    echo "<tr></tr>\n";
    echo "<tr></tr>\n";
    echo "<tr></tr>\n";
    echo "<tr></tr>\n";


echo "</table>";
echo "<br>";
theme("","footer");

// vim: sw=4 ts=4 expandtab
