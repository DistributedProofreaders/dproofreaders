<?php

// ID: $Id$

require_once 'v_site.inc';
require_once "Profileclass.inc" ;

/**
 * User Class.
 *
 * <p>This class should probably be responsible for knowing if this user is logged in or
 * not based on whether logIn() has succeeded and logOut() has not subsequently occurred.
 * Hence the var $logged_in for the purpose.</p>
 *
 * <p>This class is still in alpha, do not fully rely on it yet until it has gone
 * through unit testing successfully.</p>
 */

/*
 * No class variables should be available outside - everything should be abstracted
 * to a business-rule level property.
 * Look for every opportunity to restrict access to internal field values if their
 * only legitimate use is to support derived business-rule level properties.
 * Decompose whenever possible. Example: methods with an argument indicating whether
 * to switch something between True and False (with logic of the form:
 * func($bool) { if($bool) what; else what-not; should be split into two methods
 * funcTrue() { what; } and funcFalse() {what-not}.
 */

/* Questions:
 *
 * Is it desirable to make an object of this class simultaneous with a session?
 * i.e. the constructor would process an submitted username and password pair,
 * and return '' or an error message, per standard usage. (If it failed, it would
 * linger as a zombie (all properties returning Null) until it went out of scope
 * and died. If we can do this, the username/password handling becomes clearer.
 */


class User
{
    var $my_username;      // the usename for which we want to instantiate this instance
    var $users_phpbb;   // this is for one record from mysql, as an array.
    var $users_phpbb_meta; // metadata for users_phpbb columns.
    var $settings;         // This is for the records from usersettings for this user.
    var $profiles;         // this is for the records from userprofiles for this user.
    var $stats;            // this is for the fields from a summary query from member_stats.
    var $logged_in;        // boolean = True when they are and False when they're not.
    var $teams;
    var $neighbors;
    var $ten;

    /**
     * Class Initializer
     *
     * <p>This function is the initializer (because the name - "user" is the
     * same as the class.)  So it automatically runs when " $user = new user($pguser) " is called.
     * One instance should serve for all user info for a session.
     * It might be a good idea to update the "last login" field here, too, rather than in login.php
     * or wherever it is.</p>
     */

    // optional parameter username - if empty use $pguser
    function User($username = 0)
    {
        global $pguser ;
        // if null username submitted but there is $pguser, use it.
        // ($this->subject is the username of the User of concern here,
        // whether logged on for theselves, or being administered.
        // using $pguser is the quickanddirty way to hitchhike on the
        // existing security.

        if(!($this->my_username = $username))
        {
            $this->my_username = $pguser ;
        }
        assert($pguser) ;
        assert($this->my_username);

        // temporary comment out validuser etc. - work on it later.
        // if(! $this->_isValidUser()) {
        $this->_queryPhpbb_Users($username);
        $this->_querySettings();
        // }

        // switching logged-in-ness to be the presence of GLOBAL['username'];
        // $this->logged_in = ($this->logIn($password) == '' );
        
        // And now we have a configured user!
    }

    function _queryPhpbb_Users($username)
    {
        $sql = "SELECT u.*, bbu.*, t1.*, t2.*, t3.*
                FROM users AS u
                LEFT OUTER JOIN phpbb_users AS bbu ON u.username = bbu.username
                LEFT OUTER JOIN user_teams AS t1 ON u.team_1 = t1.id
                LEFT OUTER JOIN user_teams AS t2 ON u.team_2 = t2.id
                LEFT OUTER JOIN user_teams AS t3 ON u.team_3 = t3.id
                WHERE u.username = '$username'" ;

        $result = mysql_query($sql);

        if( ! mysql_num_rows($result) )
        {
            $this->logged_in = False ;
            return ;
        }
    
        $this->logged_in = True ;

        // collect metadata about each of the columns
        for( $i = 0 ; $i < mysql_num_fields( $result ) ; $i++ )
        {
            $meta = mysql_fetch_field($result, $i) ;
            $users_phpbb_meta[$meta->name] = $meta;
        }

        // take the result set as an associative array and tuck it away to use whenever
        // somebody asks for an attribute of our user.
        $this->users_phpbb = mysql_fetch_assoc($result);
        mysql_free_result($result);
    }

    function _querySettings()
    {
        // Now query the "usersettings" table and get all the rows for our user.
        // build an array with the settings, to use when somebody asks.
        $sql = "SELECT *
            FROM usersettings
            WHERE username = '$username'";
        $result = mysql_query($sql);

        while ($row = mysql_fetch_assoc($result)) {
            $this->settings[$row['setting']] = $row['value'];
        }
        mysql_free_result($result);
    }

    function _queryProfiles()
    {
        // get the user's profiles ;
        $this->profiles = new Profiles($this->userName());
        $this->profile = $this->standardProfile() ;

        // And now we have a configured user!
    }

    /**
     * Saves User Information to Database
     *
     * Once block of code has made changes to the User object, that information
     * needs to sometimes go back into the database. This function puts all that
     * information back in.</p>
     *
     * <p><b>NOTE:</b>If save() is not manually called before end of life of object,
     * all changes made to User object will be lost.</p>
     *
     * - returns true if it is successful
     * - returns false otherwise
     */

    function _save()
    {

    }

    /**
     * Attempts to log the user $this->userName() out of the site
     *
     * - returns true if it was successful logging out
     * - returns false if it was unsuccessful logging out
     */

    function _logOut()
    {
        $this->logged_in = False; 

    }

    /**
     * Is the currently defined user is logged in?
     *
     * - returns true if they are logged in
     * - returns false if they are not logged in
     */

    function isLoggedIn()
    {
        return (isset($GLOBALS['pguser'])) ;
        // return $this->logged_in;
    }

    /**
     * Verifies that the UserName is Valid
     *
     * <p>Checks whether the username is reasonable/acceptable User Name (ID).
     * This should be run when taking unknown input as the username to confirm
     * that the username provided is actually a valid Username (eg: registration
     * and at login). Was taken from pinc/username.inc</p>
     *
     * - returns a blank string (no error) if the name can be used as a username
     * - returns an error message explaining why it cannot be used.
     */

    function _fieldLength($fieldname)
    {
        return $user_phpbb_meta[$fieldname];
    }

    function _isValidUserName()
    {
        // This is the length of the 'username' field in the 'users' table.
        // $username_max_len = 25;

        if ($this->userName() == '') {
            $error = _("You did not supply a User Name (ID).");
            return $error;
        }

        // if (strlen($this->userName()) > $username_max_len)
        if (strlen($this->userName()) > $this->fieldLength('username') )
        {
            $error = _("Your User Name is too long.<br>(The maximum is")." $this->fieldLength('username') "._("characters").".)";
            return $error;
        }

        if (ereg("[^-a-zA-Z0-9@._ ]", $this->userName())) {
            $error = _("Your User Name contains invalid characters.<br>(Valid characters are:")." a-z A-Z 0-9 @ - . _ "._("space").")";
            return $error;
        }

        /*
        // DAK: Just trim username when submitted in case it was inadvertent on the user's part.
        // In order to prevent one class of impersonations,
        // any space character in $this->userName() must have a non-space on either side.
        // Thus, no leading or trailing spaces, and no adjacent spaces.

        if (trim($this->userName()) != $this->userName()) {
        $error = _("Your User Name has leading or trailing whitespace, which is not allowed.");
        return $error;
        }
         */

        if (ereg("  ", $this->userName())) {
            $error = _("Your User Name contains adjacent space characters, which is not allowed.");
            return $error;
        }

        return '';
    }

    /**
     * Verifies that the User exists on the Site
     *
     * <p>This should be run to verify that the user actually exists on the site.
     * It is different from _isValidUserName() in that it not just verifies the name,
     * but also makes sure that the user has an account with the site.</p>
     *
     * - returns a blank string if the user is valid
     * - returns an error message otherwise
     */

    function _isValidUser()
    {
        return Null ;
    }

    /**
     * Creates a New User Account
     *
     * <p>Takes the current User instance and creates an account for the user so that
     * _isValidUser() will return true for the user.
     *
     * - returns an empty string if it is successful in creating an account
     * - returns an error message otherwise
     */

    function _createNewUser()
    {

    }

    /**
     * Error checking on Real Name of user
     *
     * <p>Future versions of this function will return an error string explaining what is wrong.</p>
     *
     * - returns true if the real name is valid
     * - returns false otherwise
     */

    function _isValidRealName($realName)
    {

    }

    /**
     * Set the Real Name of the User
     *
     * - returns true if successful at setting user's real name
     * - returns false otherwise
     */

    function setRealName($realName)
    {
        if (isValidRealName($realName)) {
            $this->_setRowVal('real_name', $realName);
            return true;
        } else return false;
    }

    /**
     * Real Name of User
     */

    function realName()
    {
        return $this->_rowVal('u.real_name');
    }

    /**
     * Status if able to E-mail Updates to User
     *
     * - returns true if able to e-mail updates to user
     * - returns false otherwise
     */

    function updatesEmailed()
    {
        return ($this->_rowVal('u.email_updates') == 1);
    }


    /**
     * pagecount for the user
     */

    function pageCount()
    {
        // return $this->stats['pagecount']
        return $this->users_phpbb['pagescompleted'] ;
    }

    /**
     * Enable E-mail Updates for User
     */

    function enableEmailUpdates()
    {
        $this->_setRowVal('u.email_updates', 1);
    }

    /**
     * Disable E-mail Updates for User
     */

    function disableEmailUpdates()
    {
        $this->_setRowVal('u.email_updates', 0);
    }

    /**
     * Returns the username provided at creation of User object
     */

    function userName()
    {
        // return $this->users_phpbb['username'];
        return $this->_rowVal('username');
    }

    /**
     * Sets the Password of the User
     *
     * <p>Function assumes you have authority to do so, will reset the password for
     * all parts of the site.
     *
     * - returns true if successful
     * - returns false otherwise
     */

    function _setPassword($password)
    {

    }

    /**
     * MD5 version of User's Password
     */

    function _passwordMD5()
    {
        return $this->users_phpbb['bbu.password'];
    }

    /**
     * Checks to see if a Password can be used
     *
     * <p>Takes a string that may be used as a password and makes sure it matches this
     * user's password.
     *
     * - returns an empty string if the password matches
     * - returns an error message otherwise
     */

    function _isValidPassword($password)
    {
        if (MD5($password) != $this->_passwordMD5()) {
            $error = _("Password is invalid.");
        } else $error = "";

        return $error;
    }

    /**
     * Attempts to log the user $this->userName() into the site
     *
     * - returns an empty string if it was successful logging in
     * - returns an error message otherwise
     */

    /*
    function logIn($password)
    {
        return ($this->_isValidUser() == ''
                && $this->_isValidPassword($password) == '') ;
    }
    */

    /**
     * Sets the E-mail Address of the User
     *
     * <p>Sets the User's e-mail address. If the user is not created yet, it will store
     * it in memory for now.</p>
     *
     * - returns true if successful
     * - returns false otherwise
     */

    function setEmailAddress($email)
    {

    }

    /**
     * E-mail Address of the User
     *
     * <p>Returns the User's e-mail address if they have one, otherwise will return "".</p>
     */

    function emailAddress()
    {
        return $this->_rowVal('u.email');
    }

    /**
     * Checks to see if an e-mail address is valid
     *
     * <p>Verifies the current e-mail address is a reasonable/acceptable e-mail address.
     * Was taken from pinc/email_address.inc</p>
     *
     * This belongs here iff no other code needs to check it (which probably is a goal.)
     *
     * - returns an empty string if it is valid
     * - returns an error message otherwise
     */

    function _isValidEmailAddress()
    {
        // This is the length of the 'email' field in the 'users' table.
        $email_address_max_len = 50;

        if ($this->emailAddress() == '')
        {
            $error = _("You did not supply an E-mail Address.");
            return $error;
        }

        if (strlen($this->emailAddress()) > $email_address_max_len)
        {
            $error = _("Your E-mail Address is too long.<br>(The maximum is")." $email_address_max_len "._("characters").".)";
            return $error;
        }

        $parts = explode('@', $this->emailAddress());
        if (count($parts) == 1)
        {
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
        $label_re   = "$let_dig_re(($ldh_str_re)?$let_dig_re)?";
        $domain_re  = "($label_re\.)+[A-Za-z]{2,4}";

        // Formerly:
        // $domain_re = "((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)";

        if (!ereg("^$local_part_re@$domain_re$", $this->emailAddress())) {
            $error = _("Your E-mail Address appears to be invalid.");
            return $error;
        }

        return '';
    }

    /**
     * Internal user ID # of the User object
     */

    function _userId()
    {
        return $this->users_phpbb['u_id'];
    }

    /**
     * Align, whatever that is
     */

    function align()
    {
        return $this->_rowVal('u.u_align') ;   
    }

    /**
     * Error Checking on Theme Name
     * Needs to go to a Theme class.
     */

    function _isValidTheme($name)
    {

    }

    /**
     * Error Checking to see if the Theme exists
     */

    function isTheme($name)
    {

    }

    /**
     * Returns the user's preferred theme.
     */

    function theme()
    {
        return $this->_rowVal('u.i_theme');
    }

    /**
     * Set the Active Theme to the one provided
     *
     * <p>If the theme given does not exist, it will create a new theme using the existing
     * preferences.</p>
     */

    function setTheme($name)
    {
        $this->_setRowVal('u.i_theme');
    }

    /**
     * All Theme Names registered with this user.
     */

    function allThemes()
    {
        return True ;
    }

    /**
     * Is the Proofreader wanting Round 1 Projects Showing?
     */

    function wantsR1()
    {

    }

    /**
     * Enable Showing Round 1 Projects to Proofreader
     */

    function enableWantsR1()
    {

    }

    /**
     * Disable Showing Round 1 Projects to Proofreader
     */

    function disableWantsR1()
    {

    }

    /**
     * Is the Proofreader wanting Round 2 Projects Showing?
     */

    function wantsR2()
    {

    }

    /**
     * Enable Showing Round 2 Projects to Proofreader
     */

    function enableWantsR2()
    {

    }

    /**
     * Disable Showing Round 2 Projects to Proofreader
     */

    function disableWantsR2()
    {

    }

    /**
     * Wants to see the top 10
     */

    function topTen()
    {
        if( ! $this->isLoggedIn() ) return ;
        if( ! $this->ten )
            $this->_queryTopTen();
        assert( $this->ten ) ;
        return $this->ten ;
    }

    function wantsTopTen()
    {
        return ($this->_rowVal('u.u_top10')>0) ;
    }

    /**
     * Error-checking that the language being selected is a valid language
     */

    function _isValidLanguage($name)
    {

    }

    /**
     * Set the User's language
     */

    function setLanguage($name)
    {

    }

    /**
     * Returns current language preference
     */

    function language()
    {

    }

    /**
     * Returns a list of all possible languages available
     */

    function allLanguages()
    {

    }

    /**
     * User rank
     */

    function rank()
    {
        $this->_maybeQueryStats() ;
        return $this->stats['rank'] ;
    }


    function wantsNeighbors()
    {
        return $this->_rowVal('u.u_neigh') ;
    }

    function _queryTopTen()
    {
        $sql = "SELECT u.username,
                    CASE
                        WHEN u.u_privacy = 1
                        THEN 'Anonymous'
                        ELSE u.username
                        END AS neighborname,
                    u.pagescompleted AS pages,
                    u.u_privacy AS privacy
                FROM users as u
                ORDER BY u.pagescompleted DESC
                LIMIT 10" ;

        $result = mysql_query( $sql ) ;
        while( $row = mysql_fetch_assoc( $result ) ) {
            $this->ten[$row['username']] = $row ;
        } 
        mysql_free_result( $result ) ;
    } 

    /**
     * returns the 5 nearest neighbors
     */

    function nearestFiveNeighbors()
    {
        return $this->_neighbors(5);
    }

    /**
     * returns the 10 nearest neighbors
     */

    function nearestTenNeighbors()
    {
        return $this->_neighbors(10);
    }

    /**
     * Returns a set of the n nearest neighbors in rank in both directions
     */

    function _neighbors($count)
    {
        if( ! $this->neighbors || count($this->neighbors < $count * 2 ) )
        {
            $this->_getNeighbors($count) ;
        }

        return $this->neighbors ;
    }


    /**
     * creates the user's neighbor list.
     */

/*
The first query gets the users above
SELECT
    'Above' AS direction,
    u.username, 
    CASE WHEN u.u_privacy = 0
        THEN u.username
        ELSE 'Anonymous' END
        AS neighborname,
    u.pagescompleted AS pages,
    u.u_privacy AS privacy
FROM users AS u, users AS ume
WHERE ume.username = 'dkretz'
    AND u.pagescompleted >= ume.pagescompleted
ORDER BY u.pagescompleted
LIMIT 10 

SELECT
    'Below' AS direction,
    u.username,
    CASE 
        WHEN u.u_privacy = 0
        THEN u.username
        ELSE 'Anonymous' END
        AS neighborname,
    u.pagescompleted AS pages,
    u.u_privacy as privacy
FROM users AS u, users AS ume
WHERE ume.username = 'dkretz'
    AND u.pagescompleted < ume.pagescompleted
ORDER BY u.pagescompleted DESC
LIMIT 10 
*/

    function _getNeighbors($count)
    {
        $sql = "
            SELECT
                'Above' AS direction,
                u.username, 
                CASE WHEN u.u_privacy = 0
                    THEN u.username
                    ELSE 'Anonymous' END
                    AS neighborname,
                u.pagescompleted AS pages,
                u.u_privacy AS privacy
                FROM users AS u, users AS ume
            WHERE ume.username = '" . $this->userName() . "'
                AND u.pagescompleted >= ume.pagescompleted
            ORDER BY u.pagescompleted
            LIMIT " . number_format($count+1) ;

        // print $sql ;
        $result = mysql_query( $sql ) ;
        assert( $result );
        $this->neighbors = array() ;
        for( $i = $this->rank() ; $row = mysql_fetch_assoc( $result ) ; $i++ )
        {
            array_push($this->neighbors,$row) ;
            // print "<br>$i " . $row['username'] . " " . $row['pages'] ;
            // $this->neighbors[$row['username']] = $row ;
        } 
        mysql_free_result( $result ) ;

        $this->neighbors = array_reverse( $this->neighbors ) ;
        $num_above = count($this->neighbors) ;

        $sql = "
            SELECT
                'Below' AS direction,
                u.username, 
                CASE WHEN u.u_privacy = 0
                    THEN u.username
                    ELSE 'Anonymous' END
                    AS neighborname,
                u.pagescompleted AS pages,
                u.u_privacy AS privacy
                FROM users AS u, users AS ume
            WHERE ume.username = '" . $this->userName() . "'
                AND u.pagescompleted < ume.pagescompleted
                AND u.pagescompleted > 0
            ORDER BY u.pagescompleted DESC
            LIMIT " . number_format($count) ;

        // print $sql ;
        $result = mysql_query( $sql ) ;
        assert( $result ) ;
        for( $i = $this->rank() -1 ; $row = mysql_fetch_assoc( $result ) ; $i-- )
        {
            array_push($this->neighbors,$row) ;
            // $this->neighbors[--$i] = $row ;
            // print "<br>$i" . $row['username'] . " " . $row['pages'] ;
        } 
        mysql_free_result( $result ) ;

        return '' ;
    }

    /**
     * Error checking on the Number of Rank Neighbors
     */

    function _isValidNumRankNeighbors($num)
    {

    }

    /**
     * Number of Rank Neighbors to be shown in Statistics Side Bar
     */

    function numRankNeighbors()
    {

    }

    /**
     * Set Number of Rank Neighbors to be shown in Statistics Side Bar
     */

    function setNumRankNeighbors($num)
    {

    }

    /**
     * Returns the interface language of the User
     */

    function interfaceLanguage()
    {

    }

    /**
     * Error checking on the interface language provided
     */

    function _isValidInterfaceLanguage($lang)
    {

    }

    /**
     * Sets the interface language
     */

    function setInterfaceLanguage($lang)
    {

    }

    /**
     * Returns a list of all possible interface languages
     */

    function allInterfaceLanguages()
    {

    }

    /**
     * Is User's Statistics Public
     *
     * <p>Public means that anyone can see this person's statistics. This is the default
     * setting for all users.</p>
     *
     * - returns true if user has chosen to make their statistics public
     * - returns false otherwise
     */

    function isPublic()
    {
        return ($this->users_phpbb['u_privacy'] == 0);
    }

    /**
     * Enable Public User Statistics Setting
     *
     * <p>No matter what their privacy setting was before, this will make them public.</p>
     */

    function enablePublic()
    {
        $this->users_phpbb['u_privacy'] = 0;
    }

    /**
     * Is User's Statistics Anonymous
     *
     * <p>Anonymous means that only users with accounts can see this person's statistics.</p>
     *
     * - returns true if user has chosen to make their statistics anonymous
     * - returns false otherwise
     */

    function isAnonymous()
    {
        return ($this->users_phpbb['u_privacy'] == 1);
    }

    /**
     * Enable Anonymous User Statistics Setting
     *
     * <p>No matter what their privacy setting was before, this will make them anonymous.</p>
     */

    function enableAnonymous()
    {
        $this->users_phpbb['u_privacy'] = 1;
    }

    /**
     * Is User's Statistics Private
     *
     * <p>Private means that no one can see this person's statistics.</p>
     *
     * - returns true if user has chosen to make their statistics private
     * - returns false otherwise
     */

    function isPrivate()
    {
        return ($this->users_phpbb['u_privacy'] == 2);
    }

    /**
     * Enable Private User Statistics Setting
     *
     * <p>No matter what their privacy setting was before, this will make them private.</p>
     */

    function enablePrivate()
    {
        $this->users_phpbb['u_privacy'] = 2;
    }

    /**
     * Does User Name Need to be name's or names'
     *
     * <p>This function is used when showing member statistics (stats/member/*).</p>
     *
     * - returns "s" if the username does not end with "s"
     * - returns "" otherwise
     */

    /*
       function _needsApostrophe()
       {
       if ((substr($this->userName(), -1) != "s")
       ||  {
       $needsApostrophe = "s";
       } else {
       $needsApostrophe = "";
       }

       return $needsApostrophe;
       }
     */

    /**
     * Date User Account was Created
     */

    function dateCreated()
    {
        return $this->_rowVal('u.date_created');
    }

    /**
     * Set Date User Account was Created
     */

    function setDateCreated($date)
    {
        $this->users_phpbb['date_created'] = $date;
    }

    /**
     * Return the number of days between the dates on 
     * which two date values occurred, removing funny
     * stuff for earliness or lateness within day of each.
     */

    function _daysBetween($earlierdate, $laterdate)
    {
        return $laterdate / 24 / 60 / 60 - $earlierdate / 24 / 60 / 60 ;
    }

    /**
     * Number of Days Since User Account was Created
     * Abstracted dates should be in native php form, not unix seconds.
     */

    function daysSinceCreated()
    {
        return _daysBetween( $this->dateCreated(), getdate() ) ;
        // return number_format(floor((((time() - $this->dateCreated()) / 24) / 60) / 60));
    }

    /**
     * Date User Last Logged In
     */

    function lastLogin()
    {
        return $users_phpbb['last_login'];
    }

    /**
     * Set Date User Last Logged In
     */

    function setLastLogin($date)
    {
        $users_phpbb['last_login'] = $date;
    }

    /**
     * Number of Days Since User Last Logged In
     */

    function daysSinceLogin()
    {
        return daysBetween( $this->lastLogin(), getdate() ) ;
        // return number_format(floor((((time() - $this->lastLogin()) / 24) / 60) / 60);
    }

    /**
     * Location of the User's Avatar
     *
     * - returns a default avatar location if user has none setup
     * - returns otherwise the location of their avatar
     */

    function userAvatar()
    {
        if (empty($this->users_phpbb['user_avatar'])) {
            $avatar = $GLOBALS['code_url']."/graphics/default_avatar.gif";
        } else {
            $avatar = $GLOBALS['forums_url']."/images/avatars/".$this->users_phpbb['user_avatar'];
        }
        return $avatar;
    }

    /**
     * Tests to see if the location is valid for an Avatar
     */

    function _isValidUserAvatar($location)
    {

    }

    function setUserAvatar($location)
    {
        if ($this->isValidUserAvatar($location)) {
            $this->users_phpbb['user_avatar'] = $location;
            return true;
        } else return false;
    }

    /**
     * Displays User Ranking
     *
     * <p>Based on user's page, returns the user ranking name.</p>
     */

    function rankingDescription()
    {
        if ($this->pagesCompleted() <= 25) {
            return _("Newbie");
        }
        if ($this->pagesCompleted() <= 150) {
            return _("Proofreader");
        }
        if ($this->pagesCompleted() <= 400) {
            return _("Ace");
        }
        if ($this->pagesCompleted() <= 1000) {
            return _("Proofreader Savant");
        }
        if ($this->pagesCompleted() <= 2000) {
            return _("Master Proofreader");
        }
        if ($this->pagesCompleted() <= 5000) {
            return _("Maestro");
        }
        if ($this->pagesCompleted() <= 10000) {
            return _("Connoisseur");
        }

        if ($this->pagesCompleted() <= 20000) {
            return _("Rare Talent");
        }
        if ($this->pagesCompleted() <= 30000) {
            return _("Super Proofreader");
        }
        if ($this->pagesCompleted() <= 40000) {
            return _("Profound Proofreader");
        }
        if ($this->pagesCompleted() <= 50000) {
            return _("Priceless Proofreader");
        }
        if ($this->pagesCompleted() <= 50100) {
            return _("Prishan Level Proofreader");
        }
        if ($this->pagesCompleted() <= 60000) {
            return _("Prime Proofreader");
        }
        if ($this->pagesCompleted() <= 70000) {
            return _("Peerless Proofreader");
        }
        if ($this->pagesCompleted() <= 75000) {
            return _("Primordial Proofreader");
        }
        if ($this->pagesCompleted() <= 80000) {
            return _("Proofreader Magnifico");
        }
        if ($this->pagesCompleted() <= 90000) {
            return _("Proofreader Supreme");
        }
        if ($this->pagesCompleted() <= 99000) {
            return _("Prooferissimo");
        }
        if ($this->pagesCompleted() <= 99500) {
            return _("Master of Proofreading");
        }
        if ($this->pagesCompleted() <= 100000) {
            return _("Doctor of Proofreading");
        }
        if ($this->pagesCompleted() <= 101000) {
            return _("Prishan of Proofreaders");
        }
        if ($this->pagesCompleted() <= 110000) {
            return _("Proofer of Proofers");
        }
        if ($this->pagesCompleted() <= 120000) {
            return _("Proofreader Extraordinaire");
        }
        if ($this->pagesCompleted() <= 125000) {
            return _("Professor of Proofreading");
        }
        // return = _("Presidential Proofreader");
    }

    /**
     * Returns the phpBB forums internal user ID # of the User object
     */

    function phpUserId() {
        return $this->users_phpbb['ubb.user_id'];
    }

    /**
     * Get Generic User Setting
     *
     * <p>An intermediate-level function for generically obtaining 
     * a setting - better to add a property but this can be used also,
     * and is probably entirely appropriately locally within the class.</p>
     */

    function _getSetting($settingname)
    {
        return $this->settings[$settingname];
    }

    /**
     * Set Generic User Setting
     *
     * <p>An intermediate-level function for generically setting 
     * a setting - better to add a property but this can be used also,
     * and is probably entirely appropriately locally within the class.</p>
     */

    function _setSetting($settingname, $value)
    {
        $this->settings[$settingname] = $value;
    }

    /**
     * Get Item from Preference group
     *
     * <p>Takes the name of one of the user's preference groups and the name
     * of one of the preferences. If no preference is set in $prefname, then
     * it will take it from the 'default' preference group.</p>
     *
     * <p>Returns the value for that preference</p>
     */

    function _getPref($prefname, $itemname)
    {
        if (!$prefname) {
            $prefname = 'default';
        }

        return $prefs[$prefname][$itemname];
    }

    /**
     * Set Item in Preference group
     *
     * <p>Takes the name of one of the user's preference groups, the name
     * of one of the preferences, and a value. If no preference is set in $prefname, then
     * it will put it into the 'default' preference group.</p>
     */

    function _setPref($prefname, $itemname, $value)
    {
        if (!$prefname) {
            $prefname = 'default';
        }

        $prefs[$prefname][$itemname] = $value;
    }

    /**
     * Value in the Users or phpBB row of the User object
     *
     * <p>Takes the name of a field in either the Users table or the phpbb_users table</p>
     *
     * <p>Returns the value of that field of the User object</p>
     */

    function _rowVal($name)
    {
        return $this->users_phpbb[$name];
    }

    function _setRowVal($name, $value)
    {
        $this->users_phpbb[$name] = $value;
    }

    /**
     * Pages Completed by the User
     *
     * <p>One would hope that we can derive this by calculation and no longer
     * need to maintain it in the user record. For now though, it takes it from
     * the user's value.</p>
     *
     * <p>Returns the number of pages proofread by the User</p>
     */

    function pagesCompleted()
    {
        return $user['pagescompleted'];
    }

    function pagesPosition()
    {

    }

    /**
     * Increment the number of pages completed by the User
     *
     * <p>Updates the database and the User object for the number of pages competed.
     * Once the value can be calculated in the big table of pages, this function will
     * be removed.</p>
     */

    function incrementPagesCompleted()
    {
        $user['pagescompleted']++;
    }

    /**
     * Decrement the number of pages completed by the User
     *
     * <p>Updates the database and the User object for the number of pages competed.
     * Once the value can be calculated in the big table of pages, this function will
     * be removed.</p>
     */

    function decrementPagesCompleted()
    {
        $user['pagescompleted']--;
    }

    /*
     * Helper Function to Enable User Attribute
     *
     * <p><b>It is not recommended to use this function outside of this class,
     * as it is dependent upon the current structure of the sub-system.</b></p>
     */

    function _enableBoolean($name)
    {
        $this->users_phpbb['$name'] = 'yes';
    }

    /*
     * Helper Function to Disable User Attribute
     *
     * <p><b>It is not recommended to use this function outside of this class,
     * as it is dependent upon the current structure of the sub-system.</b></p>
     */

    function _disableBoolean($name)
    {
        $this->users_phpbb['$name'] = '';
    }

    /*
     * Helper Function to Enable User Setting
     *
     * <p><b>It is not recommended to use this function outside of this class,
     * as it is dependent upon the current structure of the sub-system.</b></p>
     */

    function _enableBooleanSetting($name)
    {
        // $this->settings['$name'] = 'yes';
        _enableBoolean($this->settings['$name']) ;
    }

    /*
     * Helper Function to Disable User Setting
     *
     * <p><b>It is not recommended to use this function outside of this class,
     * as it is dependent upon the current structure of the sub-system.</b></p>
     */

    function _disableBooleanSetting($name)
    {
        // $this->settings['$name'] = '';
        disableBoolean($this->settings['$name']) ;
    }

    /**
     * Is the Project Manager Field Set?
     *
     * <P>Only tells you if the user's project manager field is set to 'yes'.
     * If you want to know if the user may manage projects, use the mayManageProjects() function.</p>
     *
     * - returns true if the field is set to 'yes'
     * - returns false otherwise
     */

    // This sort of field could go into the "usersettings" table in the future.

    function isProjectManager()
    {
        return ($this->users_phpbb['manager'] == 'yes');
    }

    /**
     * Enable User to be a Project Manager
     *
     * <p>Changes the user's project manager field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enableProjectManager()
    {
        $this->_enableBoolean('manager');
    }

    /**
     * Disable User to be a Project Manager
     *
     * <p>Changes the user's project manager field to be '',
     * if it is not already set to ''.</p>
     */

    function disableProjectManager()
    {
        $this->_disableBoolean('manager');
    }

    /**
     * May User Manage Projects?
     *
     * <p>This is different from isProjectManager() in that Site Managers, in their very nature
     * of being Site Managers, are allowed to do anything.</p>
     *
     * - Returns true if the User may manage projects
     * - Returns false otherwise.
     */

    function mayManageProjects()
    {
        return ($this->isSiteManager()
                || $this->isProjectManager());
    }

    /**
     * Is the Site Manager Field Set?
     *
     * <P>Only tells you if the user's site manager field is set to 'yes'.
     * If you want to know if the user may manage the site, use the mayManageSite() function.</p>
     *
     * - returns true if the field is set to 'yes'
     * - returns false otherwise
     */

    // this sort of field could go into "usersettings".

    function isSiteManager() {
        return ($this->users_phpbb['sitemanager'] == 'yes');
    }

    /**
     * Enable User to be a Site Manager
     *
     * <p>Changes the user's site manager field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enableSiteManager()
    {
        $this->_enableBoolean('sitemanager');
    }

    /**
     * Disable User to be a Site Manager
     *
     * <p>Changes the user's site manager field to be '',
     * if it is not already set to ''.</p>
     */

    function disableSiteManager()
    {
        $this->_disableBoolean('sitemanager');
    }

    /**
     * May User Manage Site?
     *
     * <p>This function has the same action as isSiteManager(), but is included to keep
     * the interface for this class to be consistent.</p>
     *
     * - Returns true if the User may manage the site
     * - Returns false otherwise.
     */

    function mayManageSite()
    {
        return $this->isSiteManager();
    }

    /**
     * Is the Task Center Manager Field Set?
     *
     * <P>Only tells you if the user's task center manager field is set to 'yes'.
     * If you want to know if the user may manage the task center, use the mayManageTaskCenter() function.</p>
     *
     * - returns true if the field is set to 'yes'
     * - returns false otherwise
     */

    function isTaskCenterManager()
    {
        return ($this->settings['task_center_mgr'] == 'yes');
    }

    /**
     * Enable User to be a Task Center Manager
     *
     * <p>Changes the user's task center manager field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enableTaskCenterManager()
    {
        $this->_enableBooleanSetting('task_center_mgr');
    }

    /**
     * Disable User to be a Task Center Manager
     *
     * <p>Changes the user's task center manager field to be '',
     * if it is not already set to ''.</p>
     */

    function disableTaskCenterManager()
    {
        $this->_disableBooleanSetting('task_center_mgr');
    }

    /**
     * May User Manage Task Center?
     *
     * <p>This is different from isTaskCenterManager() in that Site Managers, in their very nature
     * of being Site Managers, are allowed to do anything.</p>
     *
     * - Returns true if the User may manage the task center.
     * - Returns false otherwise.
     */

    function mayManageTaskCenter()
    {
        return ($this->isSiteManager() 
                || $this->isTaskCenterManager());
    }

    /**
     * Is the Project Facilitator Field Set?
     *
     * <P>Only tells you if the user's project facilitator field is set to 'yes'.
     * If you want to know if the user may facilitate projects, use the mayFacilitateProjects() function.</p>
     *
     * - returns true if the field is set to 'yes'
     * - returns false otherwise
     */

    function isProjectFacilitator()
    {
        return ($this->settings['proj_facilitator'] == 'yes');
    }

    /**
     * Enable User to be a Project Facilitator
     *
     * <p>Changes the user's project facilitator field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enableProjectFacilitator()
    {
        $this->_enableBooleanSetting('proj_facilitator');
    }

    /**
     * Disable User to be a Project Facilitator
     *
     * <p>Changes the user's project facilitator field to be '',
     * if it is not already set to ''.</p>
     */

    function disableProjectFacilitator()
    {
        $this->_disableBooleanSetting('proj_facilitator');
    }

    /**
     * May User Facilitate Projects?
     *
     * <p>This is different from isProjectFacilitator() in that Site Managers, in their very nature
     * of being Site Managers, are allowed to do anything.</p>
     *
     * - Returns true if the User may facilitate projects.
     * - Returns false otherwise.
     */

    function mayFacilitateProjects()
    {
        return ($this->isSiteManager() 
                || $this->isProjectFacilitator());
    }

    /**
     * Is the Project Mentor Field Set?
     *
     * <P>Only tells you if the user's project mentor field is set to 'yes'.
     * If you want to know if the user may mentor projects, use the mayMentorProjects() function.</p>
     *
     * - returns true if the field is set to 'yes'
     * - returns false otherwise
     */

    function isProjectMentor()
    {
        return $this->settings['proj_mentor'];
    }

    /**
     * Enable User to be a Project Mentor
     *
     * <p>Changes the user's project mentor field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enableProjectMentor()
    {
        $this->_enableBooleanSetting('proj_mentor');
    }

    /**
     * Disable User to be a Project Mentor
     *
     * <p>Changes the user's project mentor field to be '',
     * if it is not already set to ''.</p>
     */

    function disableProjectMentor()
    {
        $this->_disableBooleanSetting('proj_mentor');
    }

    /**
     * May User Mentor Projects?
     *
     * <p>This is different from isProjectMentor() in that Site Managers, in their very nature
     * of being Site Managers, are allowed to do anything. In addition, project facilitators
     * are there to help new users.</p>
     *
     * - Returns true if the User may mentor projects.
     * - Returns false otherwise.
     */

    function mayMentorProjects()
    {
        return ($this->isSiteManager() 
                || $this->isProjectFacilitator() 
                || $this->isProjectMentor());
    }

    /**
     * Is the Post Processor Field Set?
     *
     * <P>Only tells you if the user's post processor field is set to 'yes'.
     * If you want to know if the user may post process, use the mayPostProcess() function.</p>
     *
     * - returns true if the field is set to 'yes'
     * - returns false otherwise
     */

    function isPostProcessor()
    {
        return ($this->users_phpbb['postprocessor'] == 'yes');
    }

    /**
     * Enable User to be a Post Processor
     *
     * <p>Changes the user's post processor field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enablePostProcessor()
    {
        $this->_enableBoolean('postprocessor');
    }

    /**
     * Disable User to be a Post Processor
     *
     * <p>Changes the user's post processor field to be '',
     * if it is not already set to ''.</p>
     */

    function disablePostProcessor()
    {
        $this->_disableBoolean('postprocessor');
    }

    /**
     * May User Post Process?
     *
     * <p>This is different from isPostProcessor() in that Site Managers, in their very nature
     * of being Site Managers, are allowed to do anything. Also, a user may post process
     * if they have completed proofreading 400 pages.</p>
     *
     * - Returns true if the User may post process
     * - Returns false otherwise.
     */

    function mayPostProcess()
    {
        return ($this->isSiteManager() 
                || $this->isPostProcessor()
                || $this->pagesCompleted() >= 400);
    }

    /**
     * Is the Post Process Verifier Field Set?
     *
     * <P>Only tells you if the user's post process verifier field is set to 'yes'.
     * If you want to know if the user may post process verify, use the mayPostProcessVerify() function.</p>
     *
     * - returns true if the field is set to 'yes'
     * - returns false otherwise
     */

    function isPostProcessVerifier()
    {
        return ($this->settings['post_proof_verifier'] == 'yes');
    }

    /**
     * Enable User to be a Post Process Verifier
     *
     * <p>Changes the user's post process verifier field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enablePostProcessVerifier()
    {
        $this->_enableBooleanSetting('post_proof_verifier');
    }

    /**
     * Disable User to be a Post Process Verifier
     *
     * <p>Changes the user's post process verifier field to be '',
     * if it is not already set to ''.</p>
     */

    function disablePostProcessVerifier()
    {
        $this->_disableBooleanSetting('post_proof_verifier');
    }

    /**
     * May User Post Process Verify
     *
     * <p>This is different from isPostProcessVerifier()
     * in that Site Managers, in their very nature
     * of being Site Managers, are allowed to do anything.</p>
     *
     * - Returns true if the User may manage projects
     * - Returns false otherwise.
     */

    function mayPostProcessVerify()
    {
        return ($this->isSiteManager() 
                || $this->isPostProcessVerifier());
    }

    /**
     * Is the Site News Editor Field Set?
     *
     * <P>Only tells you if the user's site news editor field is set to 'yes'.
     * If you want to know if the user may edit the site news, use the mayEditSiteNews() function.</p>
     *
     * - returns true if the field is set to 'yes'
     * - returns false otherwise
     */

    function isSiteNewsEditor()
    {
        return ($this->settings['site_news_editor'] == 'yes');
    }

    /**
     * Enable User to be a Site News Editor
     *
     * <p>Changes the user's site news editor field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enableSiteNewsEditor()
    {
        $this->_enableBooleanSetting('site_news_editor');
    }

    /**
     * Disable User to be a Site News Editor
     *
     * <p>Changes the user's site news editor field to be '',
     * if it is not already set to ''.</p>
     */

    function disableSiteNewsEditor()
    {
        $this->_disableBooleanSetting('site_news_editor');
    }

    /**
     * May User Edit Site News?
     *
     * <p>This is different from isSiteNewsEditor() in that Site Managers, in their very nature
     * of being Site Managers, are allowed to do anything.</p>
     *
     * - Returns true if the User may edit the site news.
     * - Returns false otherwise.
     */

    function mayEditSiteNews()
    {
        return ($this->isSiteManager() 
                || $this->isSiteNewsEditor());
    }

    /**
     * Is the See Begin Projects in Round 1 Field Set?
     *
     * <P>Only tells you if the user's see begin projects in round 1 field is set to 'yes'.
     * If you want to know if the user may see the begin projects in round 1, use the maySeeBeginR1() function.</p>
     *
     * - returns true if the field is set to 'yes'
     * - returns false otherwise
     */

    function isSeeBeginR1()
    {
        return ($this->settings['see_BEGIN_R1'] == 'yes');
    }

    /**
     * Enable User to See Begin Projects in Round 1
     *
     * <p>Changes the user's see begin projects in round 1 field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enableSeeBeginR1()
    {
        $this->_enableBooleanSetting('see_BEGIN_R1');
    }

    /**
     * Disable User to be a See Begin Projects in Round 1
     *
     * <p>Changes the user's see begin projects in round 1 field to be '',
     * if it is not already set to ''.</p>
     */

    function disableSeeBeginR1()
    {
        $this->_disableBooleanSetting('see_BEGIN_R1');
    }

    /**
     * May User See Begin Projects in Round 1?
     *
     * <p>This is different from isSeeBeginR1() in that Site Managers, in their very nature
     * of being Site Managers, are allowed to do anything. In addition, project facilitators
     * are there to help new users and users that have completed less than 40 pages should
     * be allowed to see beginner projects.</p>
     *
     * - Returns true if the User may see begin projects in round 1.
     * - Returns false otherwise.
     */

    function maySeeBeginR1()
    {
        return ($this->isSiteManager()
                || $this->isProjectFacilitator()
                || $this->pagesCompleted() <= 40
                || $this->isSeeBeginR1());
    }

    /**
     * Is the See Begin Projects in Round 2 Field Set?
     *
     * <P>Only tells you if the user's see begin projects in round 2 field is set to 'yes'.
     * If you want to know if the user may see begin projects in round 2, use the maySeeBeginR2() function.</p>
     *
     * - returns true if the field is set to 'yes'
     * - returns false otherwise
     */

    function isSeeBeginR2()
    {
        return ($this->settings['see_BEGIN_R2'] == 'yes');
    }

    /**
     * Enable User to See Begin Projects in Round 2
     *
     * <p>Changes the user's see begin projects in round 2 field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enableSeeBeginR2()
    {
        $this->_enableBooleanSetting('see_BEGIN_R2');
    }

    /**
     * Disable User to See Begin Projects in Round 2
     *
     * <p>Changes the user's see begin projects in round 2 field to be '',
     * if it is not already set to ''.</p>
     */

    function disableSeeBeginR2()
    {
        $this->_disableBooleanSetting('see_BEGIN_R2');
    }

    /**
     * May User See Begin Projects in Round 2?
     *
     * <p>This is different from isSeeBeginR2() in that Site Managers, in their very nature
     * of being Site Managers, are allowed to do anything. In addition, project facilitators
     * are there to help new users and users that have completed less than 300 pages should
     * be allowed to see beginner projects in round 2.</p>
     *
     * - Returns true if the User may manage projects
     * - Returns false otherwise.
     */

    function maySeeBeginR2()
    {
        return ($this->isSiteManager()
                || $this->isProjectFacilitator()
                || $this->pagesCompleted() >= 300
                || $this->isSeeBeginR2());
    }

    // **********************************************************************************
    //
    //  The following functions are more complex, providing information that isn't
    //  available by just checking a field or two. Please keep the code below relating
    //  only to the User object that this instance involves. For multiple user functions,
    //  create a "Users" class.
    //
    // **********************************************************************************

    /**
     * Number of Unread Private Messages
     *
     * <p>Returns the number of private messages that are waiting for the User to read.</p>
     */

    function numberOfUnreadPMs()
    {
        static $PMcount = Null;

        // presumably this will let me know if it's been set yet or not.
        // If it is set (even if zero), return the count.
        // TODO - put a time limit on when to check again.
        if (!isset($PMcount)) {
            $sql = "SELECT COUNT(1) AS PMcount
                    FROM phpbb_privmsgs
                    WHERE
                    privmsgs_to_userid = '$this->_userId()'
                         && privmsgs_type IN (1, 5)";
            $result = mysql_query($sql);
            $PM_count = mysql_result($result, 0);
            mysql_free_result($result);
        }
        return $PM_count;
    }

    /**
     * Queries the member_status table for statistic data
     *
     * <p>This is an internal-only function, not to be called from the outside.
     * Over time, will need to calculate other statistics to store them in memory.</p>
     */

    function _queryStats()
    {
        assert( $this->userName() ) ;
        $sql = "SELECT
                    AVG(daily_pagescompleted) AS avgCount,
                    MAX(daily_pagescompleted) as maxCount
                FROM member_stats
                WHERE u_id = " . $this->_userID()  ;

        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        $this->stats['avgCount'] = $row['avgCount'] ;
        $this->stats['maxCount'] = $row['maxCount'] ;
        mysql_free_result($result);

        $sql = "
                SELECT
                    COUNT(1) AS membercount,
                    SUM(
                        CASE WHEN u.pagescompleted <= ume.pagescompleted
                        THEN 1 ELSE 0 END) AS rank
                FROM users AS u, users AS ume
                WHERE u.pagescompleted > 0
                    AND ume.username = '" . $this->userName() . "'" ;
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        $this->stats['membercount'] = $row['membercount'] ;
        $this->stats['rank'] = $row['rank'] ;
        mysql_free_result($result);
    }

    /**
     * Maybe Query Statistics
     *
     * <p>This is an internal-only function, not to be called from the outside.
     * In order to reduce the load of instatiating this class, the statistics
     * about the user will not be calculated until they are needed for the first time.</p>
     */

    function _maybeQueryStats()
    {
        if (!$stats) {
            $this->_queryStats();
        }
    }

    /**
     * Daily Average of Pages Proofread
     *
     * <p>Calculates the daily average from the member statistics table.</p>
     *
     * - Returns 0 if there are no daily statistics for the User
     * - Otherwise will return the average.
     */

    function dailyPageAverage()
    {
        $this->_maybeQueryStats() ;
        return ( $avg = $this->stats['avgCount'] ) ? $avg : 0 ;
    }

    function memberCount()
    {
        $this->_maybeQueryStats() ;
        return ( $i = $this->stats['memberCount'] ) ? i : 0 ;
    }

    /**
     * Best Proofreading Day Count Ever for User
     *
     * <p>Gives the number of pages proofread on the user's best proofreading day.
     * See bestDayEver() for the date that this occurred on.</p>
     *
     * - Returns 0 if there is no best day for them.
     * - Returns the page count otherwise.
     */

    function bestDayEverCount()
    {
        $this->_maybeQueryStats();
        return ($max = $this->stats['maxCount']) ? $max : 0 ;
    }

    /**
     * Best Proofreading Day Ever for User
     *
     * <p>Pulls from the member_stats table the best proofreading day for the user.
     * See bestDayEverCount() for the count that this occurred on.</p>
     *
     * - Returns today if they have no best day.
     * - Returns the date it occurred otherwise.
     */

    function bestDayEver() {
        static $best;

        $this->_maybeQueryStats();

        // this extra query can be eliminated when we have correlated subqueries
        // in mysql. But in the meantime...

        if (!isset($best)) {
            $sql = "SELECT UNIX_TIMESTAMP(date_updated) AS BestDay
                FROM member_stats
                WHERE u_id = $this->_userID()
                AND daily_pagescompleted = $this->bestDayEverCount()
                ORDER BY date_updated DESC
                LIMIT 1";
            $result = mysql_query($sql);
            $best = mysql_result($result, 0, "BestDay");
            // This is returned in a field that is a 
            mysql_free_result($result);
        }

        return $best;
    }

    /**
     * Total Percentage User Has Proofread of All Pages Proofread
     *
     * <p>As our site grows, this percentage will need to grow or be removed due to most
     * users will end up being in the 0.01 or lower category.</p>
     */

    /* I suggest it's time to quit table-scanning our largest table for a meaningless stat.
       function pageCountPercentage()
       {
       $sql = "SELECT SUM(pages)
       AS total_pagescompleted
       FROM pagestats";
       $result = mysql_query($sql);
       $total_pagescompleted = mysql_result($result, 0, "total_pagescompleted");
       $percentTotal = number_format( $this->pagesCompleted()
       / $this->total_pagescompleted * 100), 2);
       if ($this->pagesCompleted() > 0 && $percentTotal == 0.00) {
       $percentTotal = 0.01;
       }
       return $percentTotal;
       }
     */

    function teams()
    {
        return array(
                $this->_rowVal('t1.teamname') => $this->_rowVal('t1.pagecount'),
                $this->_rowVal('t2.teamname') => $this->_rowVal('t2.pagecount'),
                $this->_rowVal('t3.teamname') => $this->_rowVal('t3.pagecount')) ;

        /*
           if( ! isset($this->teams) )
           {
           $this->teams = array(
           $this->_rowVal('t1.teamname') => $this->_rowVal('t1.pagecount'),
           $this->_rowVal('t2.teamname') => $this->_rowVal('t2.pagecount'),
           $this->_rowVal('t3.teamname') => $this->_rowVal('t3.pagecount')
           )
           }

           return $this->teams ;
         */
    }


    /**
     * Join A Team
     *
     * <p>Takes as a parameter the team's ID # and attempts to join the user to that team.
     * If they are already part of that team, do not allow them to join again
     * <b>NOTE:</b> This only changes the user's preference, it does not modify the team tables.</p>
     *
     * - returns true if successful
     * - returns false otherwise
     */

    function joinTeam($teamID)
    {

    }

    /**
     * Is User a Member of a Team?
     *
     * <p>Takes a parameter of the team's ID # and sees if the user's proferences says they are
     * in that team.
     *
     * - returns true if they are
     * - returns false otherwise
     */

    function isTeamMember($teamID)
    {

    }

    /**
     * Quit A Team
     *
     * <p>Takes as a parameter the team's ID # and attempts to remove the user from that team.
     * If it's the site-wide team ($teamID 1), do not allow them to quit that team.
     * <b>NOTE:</b> This only changes the user's preference, it does not modify the team tables.</p>
     * DAK: If team 1 haappened to get into one of their slots, they *better" be able to remove it!
     *
     * - returns true if successful
     * - returns false otherwise
     */

    function quitTeam($teamID)
    {
        if ($this->users_phpbb['team_1'] == $teamID) {
            $this->users_phpbb['team_1'] = 0;
            return true;
        } elseif ($this->users_phpbb['team_2'] == $teamID) {
            $this->users_phpbb['team_2'] = 0;
            return true;
        } elseif ($this->users_phpbb['team_3'] == $teamID) {
            $this->users_phpbb['team_3'] = 0;
            return true;
        } else {
            return false;
        }
    }
}

?>
