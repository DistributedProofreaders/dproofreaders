<?PHP
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'stages.inc');

if (!(user_is_a_sitemanager() || user_is_an_access_request_reviewer())) die("permission denied");

foreach ( $Stage_for_id_ as $stage )
{
    if ( $stage->after_satisfying_minima == 'REQ-HUMAN' )
    {
        $activity_ids[] = $stage->id;
    }
}

$activity_ids[] = 'P2_mentor';

// Look for unexpected activity_ids
$res = mysql_query("
    SELECT DISTINCT REPLACE(setting,'.access', '')
    FROM usersettings
    WHERE setting LIKE '%.access' AND value='requested'
") or die(mysql_error());
while ( list($activity_id) = mysql_fetch_row($res) )
{
    if ( !in_array( $activity_id, $activity_ids ) )
    {
        $activity_ids[] = $activity_id;
    }
}

// ----------------------------------

foreach ( $activity_ids as $activity_id )
{
    echo "<h3>";
    echo sprintf( _('Users requesting access to %s'), $activity_id );
    echo "</h3>\n";

    $access_name = "$activity_id.access";

    $res = mysql_query("
        SELECT usersettings.username, users.u_id
        FROM usersettings
            LEFT OUTER JOIN users USING (username)
        WHERE setting = '$access_name' AND value='requested'
        ORDER BY username
    ") or die(mysql_error());

    echo "<ul>";

    if ( mysql_num_rows($res) == 0 )
    {
        $word = _('none');
        echo "<li>($word)</li>";
    }
    else
    {
        while ( list($username,$u_id) = mysql_fetch_row($res) )
        {
            echo "<li>";
            echo "<a href='$code_url/tools/proofers/review_work.php?username=$username'>";
            echo "[rw]";
            echo "</a>";
            echo " ";
            echo "<a href='$code_url/stats/members/mdetail.php?id=$u_id'>";
            echo $username;
            echo "</a>";
            echo "</li>";
            echo "\n";
        }
    }

    echo "</ul>";
}

// vim: sw=4 ts=4 expandtab
?>
