<?php
include_once($relPath.'http_headers.inc');
include_once($relPath.'slim_header.inc');

function echo_proof_frame_std( $ppage )
{
    $user = User::load_current();

    if ($user->profile->i_layout == '1')
    {
        $rows_or_cols = "COLS='*,{$user->profile->v_tframe}%'";
    }
    else
    {
        $rows_or_cols = "ROWS='*,{$user->profile->h_tframe}%'";
    }

    $params = $ppage->urlencoded(TRUE);

    if ( ($user->profile->i_layout == '1' && $user->profile->v_tscroll == 0)
        ||
         ($user->profile->i_layout == '0' && $user->profile->h_tscroll == 0) )
    {
        $text_scrolling = "SCROLLING='no'";
    }
    else
    {
        $text_scrolling = "";
    }

    $margins = "marginwidth='2' marginheight='2'";

    // -------------------------------------

    slim_header_frameset($ppage->projectid());
    echo "
        <FRAMESET $rows_or_cols>
        <FRAME NAME='imageframe' SRC='image_frame_std.php?$params' $margins>
        <FRAME NAME='textframe' SRC='text_frame_std.php?$params' $margins $text_scrolling>
        </FRAMESET>
        <NOFRAMES>"._("Your browser is not frames capable!")."</NOFRAMES>
    ";
}

// vim: sw=4 ts=4 expandtab
