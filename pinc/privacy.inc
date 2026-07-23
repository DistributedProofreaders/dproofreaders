<?php
include_once($relPath.'prefs_options.inc'); // PRIVACY_*

/**
 * Can we reveal (to the requestor) details about the given user?
 * @param PRIVACY_PRIVATE|PRIVACY_ANONYMOUS $user_privacy_setting
 */
function can_reveal_details_about(string $username, int $user_privacy_setting): bool
{
    global $pguser; // the "requestor"

    switch ($user_privacy_setting) {
        case PRIVACY_PRIVATE:
            // Details are visible to anyone logged in.
            return !is_null($pguser);

        case PRIVACY_ANONYMOUS:
            // Details are visible to the user him/herself and to Site Admins.
            // and Project Facilitators
            return !is_null($pguser) && ($pguser == $username || user_is_proj_facilitator());
    }
}
