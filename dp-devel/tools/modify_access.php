<?PHP
$relPath = '../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'maybe_mail.inc');

header('Content-type: text/plain');

list($can_grant,$can_revoke) = user_can_modify_access();

if ( !$can_grant && !$can_revoke ) die( "Error: you are not permitted to execute this script" );

$subject_username = @$_POST['subject_username'];
if (empty($subject_username)) die( "parameter 'subject_username' is empty" );

if ($_POST['notify_user'] == 'on') $notify_user = true;

echo "subject_username='$subject_username'\n";

// so we don't hit them in the following loop
unset($_POST['subject_username']);
unset($_POST['notify_user']);

$actions = array();
foreach ( $_POST as $name => $value )
{
    echo "\n";
    echo "Considering '$name' => '$value'\n";

    if ( $value != 'on' ) die( "Error: unexpected value in parameter '$name' => '$value'" );

    $a = explode( '|', $name );
    if ( count($a) != 2 ) die( "Error: bad parameter name '$name'" );

    list( $activity_id, $grant_or_revoke ) = $a;

    if ( $grant_or_revoke != 'grant' && $grant_or_revoke != 'revoke' )
    {
        die( "Error: bad parameter name '$name'" );
    }

    if ( $activity_id == 'P2_mentor' )
    {
        // fine
    }
    else
    {
        $stage = get_Stage_for_id( $activity_id );
        if ( is_null($stage) )
        {
            die( "Error: no activity named '$activity_id'" );
        }
    }

    // Okay, it's a meaningful request.
    echo "    i.e., $grant_or_revoke access to/from $activity_id\n";

    if ( array_key_exists( $activity_id, $actions ) )
    {
        die( "Error: You have more than one modification for $activity_id" );
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

    if ( $activity_id == 'P2_mentor' )
    {
        $settings =& Settings::get_Settings($subject_username);

        $uao = new StdClass;
        $uao->can_access = $settings->get_boolean("$activity_id.access");
        $uao->request_status = 'place-holder';
    }
    else
    {
        $uao = $stage->user_access($subject_username);
    }

    if ( $grant_or_revoke == 'grant' )
    {
        if ( $uao->can_access )
        {
            die( "Error: The user already has access to $activity_id" );
        }
    } 
    elseif ( $grant_or_revoke == 'revoke' )
    {
        if ( !$uao->can_access )
        {
            die( "Error: The user does not have access to $activity_id" );
        }

        if ($uao->request_status == 'sat-unneeded' )
        {
            die( "Error: you can't revoke access when it's IMMEDIATE" );
        }

        if (isset($stage) && $stage->after_satisfying_minima == 'REQ-AUTO')
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

    $actions[$activity_id] = $grant_or_revoke;
}

if ( count($actions) == 0 )
{
    die( "Warning: you did not specify any modifications" );
}

echo "\n";
echo "Those modifications appear to be valid.\n";
echo "Performing them now...\n";

foreach ( $actions as $activity_id => $grant_or_revoke )
{
    echo "\n";
    echo "$grant_or_revoke $activity_id ...\n";
    $yesno = ( $grant_or_revoke == 'grant' ? 'yes' : 'no' );
    delete_and_insert( $subject_username, "$activity_id.access", $yesno );
}

echo "\n";
echo "Done\n\n";
if ($notify_user)
  echo "Notifying user... ".notify_user($subject_username,$actions)."\n\n";
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

function notify_user($username,$actions)
{
  $result = mysql_query("SELECT email FROM users WHERE username ='$username'");
  $email_addr = mysql_result($result,0,"email");
  if ((count($actions) == 1) && (array_search('grant',$actions) !== false))
  {
    // Special case: If the user has been granted access to
    // a single round, send a congratulations! email.
    $stage = array_keys($actions);
    $subject = "DP: You have been granted access to $stage[0]!";
    $message = "Hello $username,\n\nThis is a message from the Distributed Proofreaders website.\n\n" .
               "Congratulations, you have been granted access to $stage[0] projects!\n" .
               "You can access this stage by following the link to it at the Activity Hub.\n\n" .
               "Thank you!\nDistributed Proofreaders";
    $add_headers = "";
    maybe_mail($email_addr,$subject,$message,$add_headers);
    return "congratulated user.";
  }
  else
  {
    $subject =  "DP: Your access has been modified";
    $message =  "Hello $username,\n\nThis is a message from the Distributed Proofreaders website.\n\n" .
                "The following modifications have been made to the stages in which you can work:\n";
    foreach ( $actions as $activity_id => $grant_or_revoke )
    {
        $message .= "\n";
        $message .= "  $activity_id: Access ";
        $message .= ( $grant_or_revoke == 'grant' ? 'granted.' : 'revoked.' );
    }
    $message .= "\n\nThank you!\nDistributed Proofreaders";
    $add_headers = "";
    maybe_mail($email_addr,$subject,$message,$add_headers);
    return "notified user.";
  }
}
// vim: sw=4 ts=4 expandtab
?>
