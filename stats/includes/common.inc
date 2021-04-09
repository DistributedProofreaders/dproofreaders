<?php
// A file for (UI) code that's used by both user and team stats pages.

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

            // The way this function is used, this case will never happen
            // because $previous_rank is their best rank and $current_rank can
            // never be higher than their best rank without it also being their
            // best rank.
            $color = 'green';
            $change = $previous_rank - $current_rank;
            $sign = '+';
        }
        else
        {
            // The user's rank number has increased.
            // (i.e., their rank has worsened; they have moved 'down' the chart).
            $color = 'red';
            $change = $previous_rank - $current_rank;
            $sign = ''; // negative sign is automatic
        }
        $snippet = "&nbsp;<span style='color: $color;'>($sign".number_format($change).")</span>";
    }

    return $snippet;
}

function get_username_for_uid($u_id)
{
    $user = User::load_from_uid($u_id);
    return $user->username;
}

