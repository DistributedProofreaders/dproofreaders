<?

function check_email_address( $email_address )
// Check whether $email_address is a reasonable/acceptable E-mail Address.
// If it is, return an empty string.
// If it isn't, return a string detailing the problem.
{
    $email_address_max_len = 50;
    // This is the length of the 'email' field in the 'users' table.

    if ( $email_address == '' )
    {
	$error = _("You did not supply an E-mail Address.");
	return $error;
    }

    if ( strlen($email_address) > $email_address_max_len )
    {
	$error = _("Your E-mail Address is too long.<br>(The maximum is")." $email_address_max_len "._("characters").".)";
	return $error;
    }

    if (!ereg("^([a-zA-Z0-9_.-]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$", $email))
    {
	$error = _("Your E-mail Address contains invalid characters.<br>(Valid characters are:")." a-z A-Z 0-9 @ - . _ ".")";
	return $error;
    }

    return '';
}

?>
