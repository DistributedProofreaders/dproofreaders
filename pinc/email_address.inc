<?php
include_once($relPath.'site_vars.php');

function check_email_address($email_address)
// Check whether $email_address is a reasonable/acceptable E-mail Address.
// If it is, return an empty string.
// If it isn't, return a string detailing the problem.
{
    $email_address_max_len = 100;
    // This is the length of the 'email' field in the 'users' table.

    if ($email_address == '') {
        $error = _("You did not supply an E-mail Address.");
        return $error;
    }

    if (strlen($email_address) > $email_address_max_len) {
        $error = _("Your E-mail Address is too long.<br>(The maximum is")." $email_address_max_len "._("characters").".)";
        return $error;
    }

    $parts = explode('@', $email_address);
    if (count($parts) == 1) {
        $error = _("Your E-mail Address does not contain an '@' sign.");
        return $error;
    }
    if (count($parts) > 2) {
        $error = _("Your E-mail Address contains more than one '@' sign.");
        return $error;
    }

    // See RFC 2822, section 3.2.4, 3.4, 3.4.1
    // for what constitutes a syntactically valid email address.
    // We should perhaps allow anything matching 'addr-spec' in the RFC,
    // but we're actually much more restrictive than that.

    $atext_re = "[A-Za-z0-9+_-]";
    // Theoretically, we should have
    // $atext_re = "[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]";
    // but it seems far more likely that if one of the "odd" characters appears,
    // it's a typo rather than intentional.
    // If someone actually has an email address that uses odd characters,
    // and we reject it, they can complain to dphelp.
    // (And we can insert that email address into the database by hand.)

    $dot_atom_re = "$atext_re+(\.$atext_re+)*";
    $local_part_re = $dot_atom_re;

    // If you look at
    // RFC 1034, section 3.5 or
    // RFC 1035, section 2.3.1
    // for domain name syntax, you'll see that
    // it's much more restrictive than RFC 822.
    // In fact, it's too restrictive:
    // many functioning domain names don't conform.
    // e.g. 3rivers.com and 6809.org.uk
    // (non-conforming because a "label" starts with a digit)
    // Perhaps the syntax in 1034+1035 has been updated by a later RFC.

    $let_dig_re = '[A-Za-z0-9]';
    $ldh_str_re = '[A-Za-z0-9-]+';
    $label_re = "$let_dig_re(($ldh_str_re)?$let_dig_re)?";
    $domain_re = "($label_re\.)+[A-Za-z]{2,63}";

    // Formerly:
    // $domain_re = "((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)";

    if (!preg_match("/^$local_part_re@$domain_re$/i", $email_address)) {
        $error = _("Your E-mail Address appears to be invalid.");
        return $error;
    }

    return '';
}
