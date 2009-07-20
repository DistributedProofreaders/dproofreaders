<?php

function check_username( $username )
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
	$error = _("Your User Name is too long.<br>(The maximum is")." $username_max_len "._("characters").".)";
	return $error;
    }

    if ( ereg( "[^-a-zA-Z0-9@._ ]", $username) )
    {
	$error = _("Your User Name contains invalid characters.<br>(Valid characters are:")." a-z A-Z 0-9 @ - . _ "._("space").")";
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

    if ( ereg( "  ", $username ) )
    {
	$error = _("Your User Name contains adjacent space characters, which is not allowed.");
	return $error;
    }

    return '';
}

?>
