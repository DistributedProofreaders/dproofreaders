<?

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
	return "You did not supply a User Name (ID).";
    }

    if ( strlen($username) > $username_max_len )
    {
	return "Your User Name is too long.<br>(The maximum is $username_max_len characters.)";
    }

    if ( ereg( "[^-a-zA-Z0-9@._ ]", $username) )
    {
	return "Your User Name contains invalid characters.<br>(Valid characters are: a-z A-Z 0-9 @ - . _ space)";
    }

    return '';
}

?>
