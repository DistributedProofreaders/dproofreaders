<?PHP
// A file for (UI) code that's used by both user and team stats pages.

include_once($relPath.'v_site.inc'); // $code_url

// -----------------------------------------------------------------------------

$_normal_color    = $theme['color_navbar_bg'];
$_highlight_color = $theme['color_mainbody_bg'];

$rollover_highlight_attrs =
    "bgcolor='$_normal_color'"
    . " onmouseover='javascript:style.background=\"$_highlight_color\"'"
    .  " onmouseout='javascript:style.background=\"$_normal_color\"'"
;

unset($_normal_color);
unset($_highlight_color);

// -----------------------------------------------------------------------------

function showChangeInRank( $previous_rank, $current_rank )
// Return a snippet of HTML that visually conveys
// the change in rank between $previous_rank and $current_rank.
{
    if ( empty($current_rank) || empty($previous_rank) )
    {
        // Normally, this means that the rank is zero,
        // which indicates that the user's page tally is zero.
        // (Uniformity would prefer that a page tally of zero
        // gave a very high rank number, but it doesn't.)
        $snippet = "&nbsp;";
    }
    elseif ( $current_rank == $previous_rank )
    {
        // No change in rank.
        $snippet = "&nbsp;";
    }
    else
    {
        // A change in rank! We will highlight it.
        if ( $current_rank < $previous_rank )
        {
            // The user's rank number has decreased.
            // (i.e., their rank has improved; they have moved 'up' the chart).
            $color = 'green';
            $arrow = 'up';
            $sign  = '+';
            $abs   = $previous_rank - $current_rank;
        }
        else
        {
            // The user's rank number has increased.
            // (i.e., their rank has worsened; they have moved 'down' the chart).
            $color = 'red';
            $arrow = 'down';
            $sign  = '-';
            $abs   = $current_rank - $previous_rank;
        }
        $snippet = "&nbsp;<font color='$color'>(<img src='".$GLOBALS['code_url']."/graphics/$arrow.gif' border='0' alt='$sign$abs'>&nbsp;".number_format($abs).")</font>";
    }

    return $snippet;
}

// vim: sw=4 ts=4 expandtab
?>
