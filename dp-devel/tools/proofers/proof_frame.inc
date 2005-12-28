<?PHP
// Invoking this file via include() causes the "image & text & buttons" parts
// of the proofing interface (i.e., everything but the "AEIOU+" panel)
// to be sent to the user.

include_once($relPath.'dp_main.inc');

$interface_type = $userP['i_type'];
switch ($interface_type)
{
    case 0:
        // standard interface
        include('proof_frame_std.inc');
        break;

    case 1:
        // enhanced interface
        include('proof_frame_enh.inc');
        break;

    default:
        die( "unknown i_type: '$interface_type'" );
}

// vim: sw=4 ts=4 expandtab
?>
