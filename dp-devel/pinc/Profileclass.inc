<?php

// $Id $

class Profiles
{
    var $profiles ;

    function Profiles($username)
    {
        $sql = "SELECT *
                FROM user_profiles
                WHERE u_ref = '$username'" ;
        $result = mysql_query($sql);

        while ($row = mysql_fetch_object($result)) {
            $this->profiles[$row->profilename] = $row;
        }
        mysql_free_result($result);

    }

    /**
     * get the user's profile named $profilename
     *
     */

    function profile($profilename)
    {
        return $this->profiles[$profilename] ;
    }

    /**
     * Validate a candidate string as a profile name.
     *
     */

    function _isValidProfile($name)
    {

    }

    /**
     * Checks to see if the profile exists in the current collection.
     */

    function isProfile($name)
    {
        return isset($profiles[$name]) ;
    }

    /**
     * Create a new Profile, starting with the current profile.
     */

    function AddPProfile($profile)
    {

    }

    /**
     * Delete a Profile
     */

    function deleteProfile($name)
    {

    }

    /**
     * Active Profile's Name
     */

    function activeProfileName()
    {

    }

    /**
     * All Profile Names registered with User
     */

    function allProfileNames()
    {

    }

    /**
     * Set Preferred Profile
     */

    function setPreferredProfile($name)
    {

    }

    /**
     * Set Active Profile
     */

    function setProfile($name)
    {

    }


}

class Profile
{
    var $elements ;

    function Profile($elements)
    {
        $this->elements = $elements ;
    }

    /**
     * Is Vertical Screen Resolution Valid?
     */

    function __isValidVerticalResolution($num)
    {

    }

    /**
     * Set User's Vertical Screen Resolution
     */

    function setVerticalResolution($num)
    {

    }

    /**
     * Returns the User's Vertical Screen Resolution
     */

    function verticalResolution()
    {

    }

    /**
     * Is Horiziontal Screen Resolution Valid?
     */

    function __isValidHoriziontalResolution($num)
    {

    }

    /**
     * Set User's Horiziontal Screen Resolution
     */

    function setHoriziontalResolution($num)
    {

    }

    /**
     * Returns the User's Horiziontal Screen Resolution
     */

    function horiztionalResolution()
    {

    }

    /**
     * Is User Proofreading in Standard Interface?
     */

    function isStandardInterface()
    {

    }

    /**
     * Enable Standard Proofreading Interface
     */

    function enableStandardInterface()
    {

    }

    /**
     * Is User Proofreading in Enhanced Interface?
     */

    function isEnhancedInterface()
    {

    }

    /**
     * Enable Enhanced Proofreading Interface
     */

    function enableEnhancedInterface()
    {

    }

    /**
     * Is the Proofreading Interface Launched in a New Window?
     */

    function isLaunchedNewWindow()
    {

    }

    /**
     * Enable Launching of Proofreading Interface in a New Window
     */

    function enableLaunchNewWindow()
    {

    }

    /**
     * Disable Launching of Proofreading Interface in a New Window
     */

    function disableLaunchNewWindow()
    {

    }

    /**
     *
     */

    function isToolbarShown()
    {

    }

    /**
     *
     */

    function enableShowToolbar()
    {

    }

    /**
     *
     */

    function disableShowToolbar()
    {

    }

    /**
     *
     */

    function isStatusBarShown()
    {

    }

    /**
     *
     */

    function enableShowStatusBar()
    {

    }

    /**
     *
     */

    function disableShowStatusBar()
    {

    }

    /**
     *
     */

    function isTop10Shown()
    {

    }

    /**
     *
     */

    function enableShowTop10()
    {

    }

    /**
     *
     */

    function disableShowTop10()
    {

    }

    /**
     *
     */

    function enableNoStatisticsBar()
    {

    }

    /**
     *
     */

    function isNoStatisticsBar()
    {

    }

    /**
     *
     */

    function enableLeftStatisticsBar()
    {

    }

    /**
     *
     */

    function isLeftStatisticsBar()
    {

    }

    function enableRightStatisticsBar()
    {

    }

    /**
     *
     */

    function isRightStatisticsBar()
    {

    }

    /**
     *
     */

    function _isValidFontFace($name)
    {

    }

    /**
     *
     */

    function setVerticalFontFace($name)
    {

    }

    /**
     *
     */

    function setHoriziontalFontFace($name)
    {

    }

    /**
     * Internal user ID # of the User object.
     * Should not be useful outside this class.
     */

    function setActiveFontFace($name)
    {

    }

    /**
     *
     */

    function activeFontFace()
    {

    }

    /**
     *
     */

    function horiziontalFontFace()
    {

    }

    /**
     *
     */

    function verticalFontFace()
    {

    }

    /**
     *
     */

    function allFontFaces()
    {

    }

    /**
     *
     */

    function __isValidFontSize($name)
    {

    }

    /**
     *
     */

    function setVerticalFontSize($name)
    {

    }

    /**
     *
     */

    function setHoriziontalFontSize($name)
    {

    }

    /**
     *
     */

    function setActiveFontSize($name)
    {

    }

    /**
     *
     */

    function activeFontSize()
    {

    }

    /**
     *
     */

    function horiziontalFontSize()
    {

    }

    /**
     *
     */

    function verticalFontSize()
    {

    }

    /**
     *
     */

    function allFontSizes()
    {

    }

    /**
     *
     */

    function __isValidImageZoom($num)
    {

    }

    /**
     *
     */

    function setHoriziontalImageZoom($num)
    {

    }

    /**
     *
     */

    function setVerticalImageZoom($num)
    {

    }

    /**
     *
     */

    function setActiveImageZoom($num)
    {

    }

    /**
     *
     */

    function activeImageZoom()
    {

    }

    /**
     * At the 'right time', this stuff should be spun off to
     * a UI attribute class.
     */

    function horiziontalImageZoom()
    {

    }

    /**
     *
     */

    function verticalImageZoom()
    {

    }

    /**
     *
     */

    function __isValidTextFrameSize($num)
    {

    }

    /**
     *
     */

    function setHoriziontalTextFrameSize($num)
    {

    }

    /**
     *
     */

    function setVerticalTextFrameSize($num)
    {

    }

    /**
     *
     */

    function setActiveTextFrameSize($num)
    {

    }

    /**
     *
     */

    function activeTextFrameSize()
    {

    }

    /**
     *
     */

    function horiziontalTextFrameSize()
    {

    }

    /**
     *
     */

    function verticalTextFrameSize()
    {

    }

    /**
     *
     */

    function isActiveTextFrameScrolled()
    {

    }

    /**
     *
     */

    function enableActiveTextFrameScrolled()
    {

    }

    /**
     *
     */

    function disableActiveTextFrameScrolled()
    {

    }

    /**
     *
     */

    function enableHoriziontalTextFrameScrolled()
    {

    }

    /**
     *
     */

    function disableHoriziontalTextFrameScrolled()
    {

    }

    /**
     *
     */

    function enableVerticalTextFrameScrolled()
    {

    }

    /**
     *
     */

    function disableVerticalTextFrameScrolled()
    {

    }

    /**
     *
     */

    function isHoriziontalTextFrameScrolled()
    {

    }

    /**
     *
     */

    function isVerticalTextFrameScrolled()
    {

    }

    /**
     *
     */

    function isActiveTextWrapped()
    {

    }

    /**
     *
     */

    function isHoriziontalTextWrapped()
    {

    }

    /**
     *
     */

    function isVerticalTextWrapped()
    {

    }

    /**
     *
     */

    function enableActiveWrapText()
    {

    }

    /**
     *
     */

    function disableActiveWrapText()
    {

    }

    /**
     *
     */

    function enableHoriziontalWrapText()
    {

    }

    /**
     *
     */

    function disableHoriziontalWrapText()
    {

    }

    /**
     *
     */

    function enableVerticalWrapText()
    {

    }

    /**
     *
     */

    function disableVerticalWrapText()
    {

    }

    /**
     *
     */

    function isSearchDefaultPage()
    {

    }

    /**
     *
     */

    function enableSearchDefaultPage()
    {

    }

    /**
     *
     */

    function isActiveProjectsDefaultPage()
    {

    }

    /**
     *
     */

    function enableActiveProjectsDefaultPage()
    {

    }

    /**
     *
     */

    function isAllProjectsDefaultPage()
    {

    }

    /**
     *
     */

    function enableAllProjectsDefaultPage()
    {

    }

    /**
     *
     */

    function isProofreadDefaultPage()
    {

    }

    /**
     *
     */

    function enableProofreadDefaultPage()
    {

    }

    /**
     *
     */

    function isPostProcessingDefaultPage()
    {

    }

    /**
     *
     */

    function enablePostProcessingDefaultPage()
    {

    }

    /**
     *
     */

    function allDefaultPages()
    {

    }

    /**
     * Is User's Proofreading Layout Vertical
     *
     * - returns true if user wants vertical layout
     * - returns false otherwise
     */

    function isVerticalLayout()
    {
        return ($this->users_phpbb['i_layout'] == 1);
    }

    /**
     * Sets User's Proofreading Layout to Vertical
     */

    function enableVerticalLayout()
    {
        $this->users_phpbb['i_layout'] = 1;
    }

    /**
     * Is User's Proofreading Layout Horiziontal
     *
     * - returns true if user wants horiziontal layout
     * - returns false otherwise
     */

    function isHoriziontalLayout()
    {
        return ($this->users_phpbb['i_layout'] == 0);
    }

    /**
     * Sets User's Proofreading Layout to Horiziontal
     */

    function enableHoriziontalLayout()
    {
        $this->users_phpbb['i_layout'] = 0;
    }

    /**
     * Number of Proofreading Rows for Active Layout & Profile
     *
     * <p>Returns the number of columns of characters for the active proofreading layout.</p>
     */

    function numberActiveRows()
    {
        if ($this->isHoriziontalLayout()) {
            return $this->getPref($this->activeProfile(), 'h_tlines');
        } else return $this->getPref($this->activeProfile(), 'v_tlines');
    }

    /**
     *
     */

    function numVerticalRows()
    {

    }

    /**
     *
     */

    function numHoriziontalRows()
    {

    }

    /**
     * Number of Proofreading Columns for Active Layout & Profile
     *
     * <p>Returns the number of columns of characters for the active proofreading layout.</p>
     */

    function numberActiveColumns()
    {
        if ($this->isHoriziontalLayout()) {
            return $this->getPref($this->activeProfile(), 'h_tchars');
        } else return $this->getPref($this->activeProfile(), 'v_tchars');
    }

    /**
     *
     */

    function numVerticalColumns()
    {

    }

    /**
     *
     */

    function numHoriziontalColumns()
    {

    }

    /**
     * Set Number of Horiziontal Proofreading Columns For Active Profile
     * 
     * - returns true if number given is valid number
     * - returns false otherwise
     */

    function setNumHoriziontalColumns($num)
    {
        if (($num >= 10) && ($num <= 160)) {
            $this->setPref($this->activeProfile(), 'h_tchars', $num);
            return true;
        } else return false;
    }

    /**
     * Set Number of Vertical Proofreading Columns For Active Profile
     * 
     * - returns true if number given is valid number
     * - returns false otherwise
     */

    function setNumVerticalColumns($num)
    {
        if (($num >= 10) && ($num <= 160)) {
            $this->setPref($this->activeProfile(), 'v_tchars', $num);
            return true;
        } else return false;
    }

    /**
     * Set Number of Horiziontal Proofreading Rows For Active Profile
     * 
     * - returns true if number given is valid number
     * - returns false otherwise
     */

    function setNumHoriziontalRows($num)
    {
        if (($num >= 1) && ($num <= 100)) {
            $this->setPref($this->activeProfile(), 'h_tlines', $num);
            return true;
        } else return false;
    }

    /**
     * Set Number of Vertical Proofreading Rows For Active Profile
     * 
     * - returns true if number given is valid number
     * - returns false otherwise
     */

    function setNumVerticalRows($num)
    {
        if (($num >= 1) && ($num <= 100)) {
            $this->setPref($this->activeProfile(), 'v_tlines', $num);
            return true;
        } else return false;
    }
}
?>
