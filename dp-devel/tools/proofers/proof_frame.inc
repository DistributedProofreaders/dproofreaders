<?php
// Invoking this file via include() causes the "image & text & buttons" parts
// of the proofing interface (i.e., everything but the "AEIOU+" panel)
// to be sent to the user.

include_once($relPath.'dp_main.inc');
include_once('proof_frame_std.inc');
include_once('proof_frame_enh.inc');

function echo_proof_frame( $ppage )
{
    global $userP;

    $interface_type = $userP['i_type'];
    switch ($interface_type)
    {
        case 0:
            // standard interface
            echo_proof_frame_std( $ppage );
            break;

        case 1:
            // enhanced interface
            echo_proof_frame_enh( $ppage );
            break;

        default:
            die( "unknown i_type: '$interface_type'" );
    }
}

// vim: sw=4 ts=4 expandtab
?>
