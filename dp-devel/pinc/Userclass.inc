<?php

/**
 * User Class
 *
 * <p>Liberally scavenged from "user_is.inc". All the heavy lifting was developed
 * there by mdyck and big_bill - I simply rearranged it a bit into a class for
 * my own convenenience (and that of the hard-working database server.)</p>
 *
 * <p>This class is still in alpha, do not fully rely on it yet until it has gone
 * through unit testing successfully.</p>
 */

class User
{
    var $users_phpbb;   // this is for one record from mysql, as an array.
    var $settings;      // This is for the records from usersettings for this user.
    var $prefs;         // this is for the records from userprofiles for this user
    var $logged_in;     // Shows if user is logged in or not

    /**
     * Class Initializer
     *
     * <p>This function is the initializer (because the name - "user" is the
     * same as the class.)  So it automatically runs when " $user = new user($pguser) " is called.
     * One instance should serve for all user info for a session.
     * It might be a good idea to update the "last login" field here, too, rather than in login.php
     * or wherever it is.</p>
     */

    function User($username)
    {
        $this->logged_in = False;
        
        $sql = "SELECT u.*, bbu.*
                FROM users AS u
                LEFT OUTER JOIN phpbb_users AS bbu
                ON u.username = bbu.username
                WHERE u.username = '$username'";
        $result = mysql_query($sql);

        // take the result set as an associative array and tuck it away to use whenever
        // somebody asks for an attribute of our user.
        $this->users_phpbb = mysql_fetch_assoc($result);
        mysql_free_result($result);


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

        // And the same for preferences,
        // except here we store multiple rows (full named sets of preferences.)
        $sql = "SELECT *
                FROM user_profiles
                WHERE u_ref = '$this->userid()'";
        $result = mysql_query($sql);

        while ($row = mysql_fetch_object($result)) {
            $this->prefs[$row['profilename']] = $row;
        }
        mysql_free_result($result);

        // And now we have a configured user!
    }

    /**
     * Attempts to log the user $this->userName() into the site
     *
     * - returns true if it was successful logging in
     * - returns false if it was unsuccessful logging in
     */

    function logIn($password)
    {
        return ( $this->logged_in = ( MD5($password) == $this->passwordMD5() ) ) ;
    }

    /**
     * Attempts to log the user $this->userName() out of the site
     *
     * - returns true if it was successful logging out
     * - returns false if it was unsuccessful logging out
     */

    function logOut()
    {
        $this->logged_in = False ; 

    }

    /**
     * Is the currently defined user is logged in?
     *
     * - returns true if they are logged in
     * - returns false if they are not logged in
     */

    function isLoggedIn()
    {
        return $this->logged_in ;
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

    function isValidUserName()
    {

        // This is the length of the 'username' field in the 'users' table.
        $username_max_len = 25;

        if ($this->userName() == '') {
            $error = _("You did not supply a User Name (ID).");
            return $error;
        }

        if (strlen($this->userName()) > $username_max_len) {
            $error = _("Your User Name is too long.<br>(The maximum is")." $username_max_len "._("characters").".)";
            return $error;
        }

        if (ereg("[^-a-zA-Z0-9@._ ]", $this->userName())) {
            $error = _("Your User Name contains invalid characters.<br>(Valid characters are:")." a-z A-Z 0-9 @ - . _ "._("space").")";
            return $error;
        }

        // In order to prevent one class of impersonations,
        // any space character in $this->userName() must have a non-space on either side.
        // Thus, no leading or trailing spaces, and no adjacent spaces.

        if (trim($this->userName()) != $this->userName()) {
            $error = _("Your User Name has leading or trailing whitespace, which is not allowed.");
            return $error;
        }

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
     * It is different from isValidUserName() in that it not just verifies the name,
     * but also makes sure that the user has an account with the site.</p>
     *
     * - returns true if the name is someone registered
     * - returns false otherwise
     */

    function isValidUser()
    {

    }

    /**
     * Creates a New User Account
     *
     * <p>Takes the current User instance and creates an account for the user so that
     * isValidUser() will return true for the user.
     *
     * - returns an empty string if it is successful in creating an account
     * - returns an error message otherwise
     */

    function createNewUser()
    {

    }

    function setRealName($realName)
    {

    }

    function realName()
    {

    }

    function updatesEmailed()
    {

    }

    function enableEmailUpdates()
    {

    }

    function disableEmailUpdates()
    {

    }

    /**
     * Returns the username provided at creation of User object
     */

    function userName()
    {
        return $this->users_phpbb['username'];
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

    function setPassword($password)
    {

    }

    function passwordMD5()
    {
        return $this->users_phpbb['bbu.password'];
    }

    /**
     * Checks to see if a Password can be used
     *
     * <p>Takes a string that may be used as a password and verifies that it does not
     * have any spaces

    function isValidPassword($password)
    {

    }

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

    }

    /**
     * Checks to see if an e-mail address is valid
     *
     * <p>Verifies the current e-mail address is a reasonable/acceptable e-mail address.
     * Was taken from pinc/email_address.inc</p>
     *
     * - returns an empty string if it is valid
     * - returns an error message otherwise
     */

    function isValidEmailAddress()
    {
        // This is the length of the 'email' field in the 'users' table.
        $email_address_max_len = 50;

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
        $label_re   = "$let_dig_re(($ldh_str_re)?$let_dig_re)?";
        $domain_re  = "($label_re\.)+[A-Za-z]{2,4}";

        // Formerly:
        // $domain_re = "((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)";

        if (!ereg("^$local_part_re@$domain_re$", $email_addres)) {
            $error = _("Your E-mail Address appears to be invalid.");
            return $error;
        }

        return '';
    }

    /**
     * Returns the internal user ID # of the User object
     */

    function userId()
    {
        return $this->users_phpbb['u_id'];
    }

    function themeName()
    {
        return $this->users_phpbb['i_theme'];
    }

    function align()
    {
        return $this->users_phpbb['u_align'];
    }

    function isAnonymous()
    {

    }

    function enableAnonymous()
    {

    }

    function disableAnonymous()
    {

    }

    function isPrivate()
    {

    }

    function enablePrivate()
    {

    }

    function disablePrivate()
    {

    }

    /**
     * Does User Name Need to be name's or names'
     *
     * <p>This function is used when showing member statistics (stats/member/*).</p>
     *
     * - returns "s" if the username does not end with "s"
     * - returns "" otherwise
     */

    function needsApostrophe()
    {
        if ((substr($this->userName(), -1) != "s")
            ||  {
            $needsApostrophe = "s";
        } else {
            $needsApostrophe = "";
        }

        return $needsApostrophe;
    }


    function dateCreated()
    {
        return $this->users_phpbb['date_created'];
    }

    function createdDaysAgo()
    {
        return ((((time() - $this->time_created) / 24) / 60) / 60);
    }

    function daysAMember()
    {
        return date() - $this->dateCreated();
    }

    function lastLogin()
    {

    }

    function daysSinceLogin()
    {

    }

    /**
     * Displays User Ranking
     *
     * <p>Based on user's page, returns the user ranking name.</p>
     */
 
    function rankingDescription()
    {

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
     * <p>An intermediate-level function for genererically obtaining 
     * a setting - better to add a property but this can be used also,
     * and is probably entirely appropriately locally within the class.</p>
     */

    function getSetting($settingname)
    {
        return $this->settings($settingname);
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

    function getPref($prefname,$itemname)
    {
        if (!$prefname) {
            $prefname = 'default';
        }

        return $prefs['$prefname']['$itemname'];
    }

    /**
     * Value in the Users or phpBB row of the User object
     *
     * <p>Takes the name of a field in either the Users table or the phpbb_users table</p>
     *
     * <p>Returns the value of that field of the User object</p>
     */

    function rowVal($name)
    {
        return $this->users_phpbb['$name'];
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

    /**
     * Increment the number of pages completed by the User
     *
     * <p>Updates the database and the User object for the number of pages competed.
     * Once the value can be calculated in the big table of pages, this function will
     * be removed.</p>
     */

    function incrementPagesCompleted()
    {

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

    }

    function enableBoolean($name)
    {
        if ($this->users_phpbb['$name'] != 'yes') {
            $this->users_phpbb['$name'] = 'yes';
            // TODO: Update database to have 'yes' value
        }
    }

    function disableBoolean($name)
    {
        if ($this->users_phpbb['$name'] != '') {
            $this->users_phpbb['$name'] = '';
            // TODO: Update database to have '' value
        }
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
        $this->enableBoolean('manager');
    }

    /**
     * Disable User to be a Project Manager
     *
     * <p>Changes the user's project manager field to be '',
     * if it is not already set to ''.</p>
     */

    function disableProjectManager()
    {
        $this->disableBoolean('manager');
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

    }

    /**
     * Disable User to be a Site Manager
     *
     * <p>Changes the user's site manager field to be '',
     * if it is not already set to ''.</p>
     */

    function disableSiteManager()
    {

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

    }

    /**
     * Disable User to be a Task Center Manager
     *
     * <p>Changes the user's task center manager field to be '',
     * if it is not already set to ''.</p>
     */

    function disableTaskCenterManager()
    {

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

    }

    /**
     * Disable User to be a Project Facilitator
     *
     * <p>Changes the user's project facilitator field to be '',
     * if it is not already set to ''.</p>
     */

    function disableProjectFacilitator()
    {

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
        return true;
    }

    /**
     * Enable User to be a Project Mentor
     *
     * <p>Changes the user's project mentor field to be 'yes',
     * if it is not already set to 'yes'.</p>
     */

    function enableProjectMentor()
    {

    }

    /**
     * Disable User to be a Project Mentor
     *
     * <p>Changes the user's project mentor field to be '',
     * if it is not already set to ''.</p>
     */

    function disableProjectMentor()
    {

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

    }

    /**
     * Disable User to be a Post Processor
     *
     * <p>Changes the user's post processor field to be '',
     * if it is not already set to ''.</p>
     */

    function disablePostProcessor()
    {

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

    }

    /**
     * Disable User to be a Post Process Verifier
     *
     * <p>Changes the user's post process verifier field to be '',
     * if it is not already set to ''.</p>
     */

    function disablePostProcessVerifier()
    {

    }

    /**
     * May User Post Process Verify
     *
     * <p>This is different from isPostProcessVerifier() in that Site Managers, in their very nature
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

    }

    /**
     * Disable User to be a Site News Editor
     *
     * <p>Changes the user's site news editor field to be '',
     * if it is not already set to ''.</p>
     */

    function disableSiteNewsEditor()
    {

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

    }

    /**
     * Disable User to be a See Begin Projects in Round 1
     *
     * <p>Changes the user's see begin projects in round 1 field to be '',
     * if it is not already set to ''.</p>
     */

    function disableSeeBeginR1()
    {

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

    }

    /**
     * Disable User to See Begin Projects in Round 2
     *
     * <p>Changes the user's see begin projects in round 2 field to be '',
     * if it is not already set to ''.</p>
     */

    function disableSeeBeginR2()
    {

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
                    privmsgs_to_userid = $pguser_id
                         && privmsgs_type IN (1, 5)";
            $result = mysql_query($sql);
            $PM_count = mysql_result($result, 0);
            mysql_free_result($result);
        }
        return $PM_count;
    }

    /**
     * Daily Average of Pages Proofread
     *
     * <p>Calculates the daily average from the member statistics table.</p>
     *
     * - Returns 0 if there are no daily statistics for the User
     * - Otherwise will return the average.
     *
     * <p>Code was previously in stats/include/member.php</p>
     */

    function dailyPageAverage()
    {
        $sql = "SELECT AVG(daily_pagescompleted) AS avgCount
                FROM member_stats
                WHERE
                u_id = ".$this->userID()";
	$avgPageCount = mysql_query($sql);
	$daily_average = mysql_result($avgPageCount, 0, "avgCount");
        mysql_free_result($avgPageCount);

	if (empty($daily_average)) {
            $daily_average = 0;
        }

	return $daily_average;
    }

    /**
     * Best Proofreading Day Ever for User
     *
     * <p>Pulls from the member_stats table their best day and places it into an array with
     * two slots called:</p>
     *
     * - 'count' which is the total for that day.
     * - 'time' which is a text form of the date that it occurred on in.
     *
     * <p>If they have no best day ever, then it will return 0 for 'count' and today for 'time'</p>
     *
     * <p>Code was previously in stats/include/member.php</p>
     */

    function bestDayEver() {
        $sql = "SELECT date_updated, daily_pagescompleted
                FROM member_stats
                WHERE
                u_id = $this->userID()
                ORDER BY daily_pagescompleted
                DESC LIMIT 1";
        $result = mysql_query($sql);
        $bestDay['count'] = mysql_result($result, 0, "daily_pagescompleted");
        $bestDay['time'] = date("M. jS, Y", (mysql_result($result, 0, "date_updated")-86400));

        if (empty($bestDay['count'])) {
            $bestDay['count'] = 0;
            $bestDay['time'] = date("M. jS, Y", time());
        }

        return $bestDay;
    }

}

?>
