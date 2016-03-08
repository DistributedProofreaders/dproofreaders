<?php
include_once($relPath.'misc.inc');

class NonexistentUserException extends Exception { }

class NonuniqueUserException extends Exception { }

class User
{
    private $table_row;

    // List of fields that when set for a user should never change
    private $immutable_fields = array(
        "id",
        "username",
        "u_id",
    );

    // Fields are assumed to be integers unless included here
    private $string_fields = array(
        "id",
        "real_name",
        "username",
        "email",
        "manager",
        "i_theme",
        "u_intlang",
    );


    public function __construct($username=NULL)
    {
        if($username)
        {
            $this->load("username", $username);
        }
    }

    // The __set() and __get() methods allow access to user fields without
    // creating accessors for them all individually.
    // See the PHP docs for "magic methods".
    public function __set($name, $value)
    {
        if(isset($this->$name) && in_array($name, $this->immutable_fields))
        {
            throw new DomainException(sprintf(
                _("%s is an immutable field"), $name)
            );
        }

        $this->table_row[$name] = $value;
    }

    public function __get($name)
    {
        return $this->table_row[$name];
    }

    public function __isset($name)
    {
        return isset($this->table_row[$name]);
    }

    public function load($field, $value)
    {
        if(in_array($field, $this->string_fields))
        {
            $value = sprintf("'%s'", mysql_real_escape_string($value));
        }

        $sql = "
            SELECT *
            FROM users
            WHERE $field=$value
        ";

        $result = mysql_query($sql);
        if(!$result)
        {
            throw new UnexpectedValueException(mysql_error());
        }
        elseif(mysql_num_rows($result) == 0)
        {
            throw new NonexistentUserException(sprintf(
                _('No user found with %1$s = %2$s'),
                    $field, $value)
            );
        }
        elseif(mysql_num_rows($result) > 1)
        {
            throw new NonuniqueUserException(sprintf(
                _('Multiple users found with %1$s = %2$s'),
                    $field, $value)
            );
        }
        $this->table_row = mysql_fetch_assoc($result);
        mysql_free_result($result);
    }

    public function save()
    {
        throw new NotImplementedException();
    }

    // Static function to determine if the specified username is associated
    // with a valid user without the overhead of creating an object.
    public static function is_valid_user($username)
    {
        $sql = sprintf("
            SELECT username
            FROM users
            WHERE username='%s'
        ", mysql_real_escape_string($username));
        $result = mysql_query($sql);
        if(mysql_num_rows($result) == 0)
        {
            $is_valid = FALSE;
        }
        else
        {
            $is_valid = TRUE;
        }

        mysql_free_result($result);
        return $is_valid;
    }

}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$valid_username_chars_statement_for_reg_form  = _("(Valid characters are: a-z A-Z 0-9 @ - . space)");
$valid_username_chars_statement_for_elsewhere = _("(Valid characters are: a-z A-Z 0-9 @ - . _ space)");

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
        global $valid_username_chars_statement_for_reg_form;
        $bad_char_regex = "/[^-a-zA-Z0-9@. ]/";
        $valid_username_chars_statement = $valid_username_chars_statement_for_reg_form;
    }
    else
    {
        global $valid_username_chars_statement_for_elsewhere;
        $bad_char_regex = "/[^-a-zA-Z0-9@._ ]/";
        $valid_username_chars_statement = $valid_username_chars_statement_for_elsewhere;
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
