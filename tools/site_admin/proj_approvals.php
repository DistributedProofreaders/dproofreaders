<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');

require_login();

output_header("Copyright Approval");

if (!$site_supports_metadata)
{
    die('$site_supports_metadata is false, so exiting.');
}

if (!user_is_a_sitemanager())
{
    die('You are not authorized to invoke this script.');
}

//----------------------------------------------------------------------------------

$projectid = validate_projectID('projectid', @$_GET['projectid'], true);
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

    $result = mysqli_query(DPDatabase::get_connection(), "
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

    $result = mysqli_query(DPDatabase::get_connection(), "
        SELECT projectid, nameofwork, authorsname, clearance, state
        FROM projects
        WHERE state = 'project_new_waiting_app'
    ");
    $rownum = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $projectid = $row["projectid"];
        $state = $row["state"];
        $name = $row["nameofwork"];
        $author = $row["authorsname"];
        $clearance = $row["clearance"];

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

// vim: sw=4 ts=4 expandtab
