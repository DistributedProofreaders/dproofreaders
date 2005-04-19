<?PHP
$relPath = '../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'slim_header.inc');

$stage_id = @$_GET['stage_id'];
if (empty($stage_id)) die( "parameter 'stage_id' is empty" );

$stage = get_Stage_for_id( $stage_id );

$title = sprintf( _('Requesting access to "%s"'), $stage->name );
slim_header( $title, TRUE, TRUE );

echo "<h2>$title</h2>\n";

$email_addr = 'db-req@pgdp.net';

echo "<p>";
echo sprintf( _('(In case of problems, please send email to %s.)'), $email_addr );
echo "</p>";

$uao = $stage->user_access( $pguser );
// echo "<pre>"; var_dump($uao); echo "</pre>";

echo "<p>";
if ($uao->can_access)
{
    echo _('You already have access to this stage.');
}
else
{
    switch ( $uao->request_status )
    {
        case 'sat-unneeded':
        case 'sat-granted':
        case 'unsat-granted':
            echo _("This shouldn't happen: you already have access.");
            break;

        case 'sat-requested':
            echo _('You have already requested access to this stage.');
            break;

        case 'unsat-ungranted':
            echo _('First you must satisfy the minimum requirements.');
            break;

        case 'sat-available':
            if ( $stage->after_satisfying_minima == 'REQ-AUTO' )
            {
                delete_and_insert( $pguser, "$stage_id.access", 'yes' );

                echo _('Access has been granted!');
            }
            elseif ( $stage->after_satisfying_minima == 'REQ-HUMAN' )
            {
                $body = sprintf(
                    _("User '%s' has requested access to stage '%s'"), 
                    $pguser,
                    $stage_id
                );
                maybe_mail( $email_addr, $title, $body, '' );

                delete_and_insert( $pguser, "$stage_id.access", 'requested' );

                echo _('Your request has been submitted and logged.');
            }
            else
            {
                die( "unexpected value for after_satisfying_minima: '$stage->after_satisfying_minima'" );
            }
            break;

        default:
            die( "bad request_status value: '$uao->request_status'" );
    }
}
echo "</p>\n";

echo "<p>";
echo sprintf(
    _('Back to <a href="%s">%s</a>'),
    "$code_url/activity_hub.php",
    _('Activity Hub')
);
echo "</p>\n";

// -----------------------------------------------------------------------------

function delete_and_insert( $username, $setting, $value )
{
    mysql_query("
        DELETE FROM usersettings
        WHERE username='$username' AND setting='$setting'
    ") or die(mysql_error());

    mysql_query("
        INSERT INTO usersettings
        SET
            username='$username',
            setting='$setting',
            value='$value'
    ") or die(mysql_error());
}
// vim: sw=4 ts=4 expandtab
?>
