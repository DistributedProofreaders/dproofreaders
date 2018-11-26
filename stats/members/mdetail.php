<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'privacy.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'misc.inc'); // array_get(), get_integer_param()
include_once($relPath.'User.inc');
include_once('../includes/team.inc');
include_once('../includes/member.inc');

$tally_name = array_get( $_GET, 'tally_name', null );

$id = get_integer_param($_GET, 'id', null, 0, null);

$user = User::load_from_uid($id);

$can_reveal = can_reveal_details_about( $user->username, $user->u_privacy );
if ( $can_reveal )
{
    $user_referent = "'" . $user->username . "'";
}
else
{
    $user_referent = "#" . $user->u_id;
    // Note that this doesn't reveal anything;
    // the requestor already knows the subject's u_id,
    // because it was included in the request.
}

$desc = sprintf( _("Details for user %s"), $user_referent );
output_header($desc);

echo "<h1>$desc</h1>";

if ( $can_reveal )
{
    if ( $user->u_privacy == PRIVACY_ANONYMOUS )
    {
        $visibility_note = _("These stats are visible to Site Admins and the user only.");
        echo "<i>($visibility_note)</i><br>\n";
    }
    showMbrInformation( $user, $tally_name );
}
else
{
    $brushoff = _("This user has requested that their statistics remain private.");
    echo "<p>$brushoff</p>";
}

// vim: sw=4 ts=4 expandtab
