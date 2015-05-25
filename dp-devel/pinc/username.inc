<?php

function check_username( $username, $registering=FALSE )
// Check whether $username is a reasonable/acceptable User Name (ID).
// If it is, return an empty string.
// If not, return a string detailing the problem.
// (This is used at both registration and login.)
{
    $username_max_len = 25;
    // This is the length of the 'username' field in the 'users' table.

    if ( $username == '' )
    {
        $error = _("You did not supply a User Name (ID).");
        return $error;
    }

    if ( strlen($username) > $username_max_len )
    {
        $error = sprintf(_("Your User Name is too long.<br>(The maximum is %s characters.)"), $username_max_len);
        return $error;
    }

    // For new registrations, we disallow underscore (because it confuses the wiki software).
    // Elsewhere, we still have to allow underscore in usernames, for pre-existing users.
    if ($registering)
    {
        $bad_char_regex = "/[^-a-zA-Z0-9@. ]/";
        $valid_username_chars_statement = _("(Valid characters are: a-z A-Z 0-9 @ - . space)");
    }
    else
    {
        $bad_char_regex = "/[^-a-zA-Z0-9@._ ]/";
        $valid_username_chars_statement = _("(Valid characters are: a-z A-Z 0-9 @ - . _ space)");
    }
    if ( preg_match( $bad_char_regex, $username) )
    {
        $error = _("Your User Name contains invalid characters.")
            . "<br>"
            . $valid_username_chars_statement;
        return $error;
    }

    // In order to prevent one class of impersonations,
    // any space character in $username must have a non-space on either side.
    // Thus, no leading or trailing spaces, and no adjacent spaces.

    if ( trim($username) != $username )
    {
        $error = _("Your User Name has leading or trailing whitespace, which is not allowed.");
        return $error;
    }

    if (str_contains($username, "  "))
    {
        $error = _("Your User Name contains adjacent space characters, which is not allowed.");
        return $error;
    }

    return '';
}

// vim: sw=4 ts=4 expandtab
