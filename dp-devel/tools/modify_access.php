<?PHP
$relPath = '../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'stages.inc');

header('Content-type: text/plain');

list($can_grant,$can_revoke) = user_can_modify_access();

if ( !$can_grant && !$can_revoke ) die( "Error: you are not permitted to execute this script" );

$subject_username = @$_POST['subject_username'];
if (empty($subject_username)) die( "parameter 'subject_username' is empty" );

echo "subject_username='$subject_username'\n";

unset($_POST['subject_username']); // so we don't hit it in the following loop

$actions = array();
foreach ( $_POST as $name => $value )
{
    echo "\n";
    echo "Considering '$name' => '$value'\n";

    if ( $value != 'on' ) die( "Error: unexpected value in parameter '$name' => '$value'" );

    $a = explode( '|', $name );
    if ( count($a) != 2 ) die( "Error: bad parameter name '$name'" );

    list( $stage_id, $grant_or_revoke ) = $a;

    if ( $grant_or_revoke != 'grant' && $grant_or_revoke != 'revoke' )
    {
        die( "Error: bad parameter name '$name'" );
    }

    $stage = get_Stage_for_id( $stage_id );
    if ( is_null($stage) )
    {
        die( "Error: no stage named '$stage_id'" );
    }

    // Okay, it's a meaningful request.
    echo "    i.e., $grant_or_revoke access to/from $stage_id\n";

    if ( array_key_exists( $stage_id, $actions ) )
    {
        die( "Error: You have more than one modification for $stage_id" );
    }

    if ( $grant_or_revoke == 'grant' && !$can_grant )
    {
        die( "Error: You are not permitted to grant access." );
    }
    elseif ( $grant_or_revoke == 'revoke' && !$can_revoke )
    {
        die( "Error: You are not permitted to revoke access." );
    }

    // And it's a request that the current user is permitted to make.

    $uao = $stage->user_access($subject_username);

    if ( $grant_or_revoke == 'grant' )
    {
        if ( $uao->can_access )
        {
            die( "Error: The user already has access to $stage_id" );
        }
    } 
    elseif ( $grant_or_revoke == 'revoke' )
    {
        if ( !$uao->can_access )
        {
            die( "Error: The user does not have access to $stage_id" );
        }

        if ($uao->request_status == 'sat-unneeded' )
        {
            die( "Error: you can't revoke access when it's IMMEDIATE" );
        }

        if ($stage->after_satisfying_minima == 'REQ-AUTO')
        {
            echo "Warning: you can revoke access, but it can just be auto-granted again.\n";
        }
    }
    else
    {
        assert( 0 );
    }

    // And it's a request that is valid for the subject user at the current time.

    // So put it on the queue.
    // (Don't execute any requests unless they're all valid.)

    $actions[$stage_id] = $grant_or_revoke;
}

if ( count($actions) == 0 )
{
    die( "Warning: you did not specify any modifications" );
}

echo "\n";
echo "Those modifications appear to be valid.\n";
echo "Performing them now...\n";

foreach ( $actions as $stage_id => $grant_or_revoke )
{
    echo "\n";
    echo "$grant_or_revoke $stage_id ...\n";
    $yesno = ( $grant_or_revoke == 'grant' ? 'yes' : 'no' );
    delete_and_insert( $subject_username, "$stage_id.access", $yesno );
}

echo "\n";
echo "Done\n";
echo "Hit 'Back' to return to user's detail page. (And you may need to reload.)\n";

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
