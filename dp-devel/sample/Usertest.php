<?php

require_once 'Userclass.inc';
require_once 'PHPUnit.php';

/**
 * User Class Testing
 *
 * <p>Tests the Userclass.inc by utilizing the PEAR module PHPUnit_TestCase</p>
 */

class UserTest extends PHPUnit_TestCase
{
    var $validUser;
    var $validUserName;
    var $validPassword;

    var $invalidUser;
    var $invalidUserName;
    var $invalidPassword;

    var $newUser;
    var $newUserName;
    var $newPassword;

    function UserTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    /**
     * Tear Down Class
     *
     * <p>Required for the PHPUnit_TestCase, destroys the User objects created in setUp()</p>
     */

    function tearDown()
    {
        unset($this->validUser);
        unset($this->invalidUser);
    }

    /**
     * Set Up Class
     *
     * <p>Required for the PHPUnit_TestCase, sets up the User objects to be used for testing</p>
     */

    function setUP()
    {
        $this->validUserName = "test";
        $this->validPassword = "test";
        $this->validUser = new User($this->validUserName);

        $this->invalidUserName = "blahblahblahblah";
        $this->invalidPassword = "blahblahblahblah";
        $this->invalidUser = new User($this->invalidUserName);

        $this->newUserName = "newbie";
        $this->newPassword = "newbie";
        $this->newUser = new User($this->newUserName);
        $this->newUser->deleteUser();
        $this->newUser->setPassword($this->newPassword);
        $this->newUser->createUser();
    }

    /**
     * Check Valid User
     *
     * <p>Takes an already created user and checking to see if they exist.</p>
     */

    function testValidUser()
    {
        $this->assertTrue($this->validUser->isValidUser());
    }

    /**
     * Check Invalid User
     *
     * <p>Takes a non-existant user and checks to see if they exist.</p>
     */

    function testInvalidUser()
    {
        $this->assertTrue($this->invalidUser->isValidUser());
    }

    /**
     * Login Valid User
     *
     * <p>Takes an already created user and attempts to login.</p>
     */

    function testLoginValidUser()
    {
        $this->validUser->logIn($this->validPassword);
        $this->assertTrue($this->validUser->isLoggedIn());
    }

    /**
     * Login Invalid User
     *
     * <p>Takes a non-existant user and attempts to login.</p>
     */

    function testLoginInvalidUser()
    {
        $this->invalidUser->logIn("blahblahblah");
        $this->assertTrue($this->invalidUser->isLoggedIn());
    }


    /**
     * Logout User Currently Logged In
     *
     * <p>Takes an already logged in user and attempts to log them out.</p>
     */

    function testLoggedInUser()
    {
        $this->validUser->logIn($this->validPassword);
        $this->validUser->logOut();

        $this->assertTrue(!$this->validUser->isLoggedIn());
    }

    /**
     * Logout User Currently Logged Out
     *
     * <p>Takes a user not logged in and attempts to log them out.</p>
     */

    function testLoggedOutUser()
    {
        $this->validUser->logIn($this->validPassword);
        $this->validUser->logOut();

        $this->assertTrue(!$this->validUser->logOut());
    }

    /**
     * Test User Name
     *
     * <p>Tests a variety of user names that are valid/invalid.</p>
     */

    function testUserName()
    {
        $invalidName1 = new User("   blah   ");  // Extra spaces around name
        $invalidName2 = new User("");            // Empty Name
        $invalidName3 = new User("This Is The Name That Never Ends, It Goes On And On My Friend");  // Extra long
        $invalidName4 = new User("$~%!+");  // Odd Characters In Name
        $invalidName5 = new User("The   Queen");  // Extra space in name

        $validName1 = new User("me@home.net");
        $validName2 = new User($validUserName);
        $validName3 = new User("The Skunk_Lives");

        $this->assertTrue((!empty($invalidName1->validUserName()))
                       && (!empty($invalidName2->validUserName()))
                       && (!empty($invalidName3->validUserName()))
                       && (!empty($invalidName4->validUserName()))
                       && (!empty($invalidName5->validUserName()))
                       && (empty($validName1->validUserName()))
                       && (empty($validName2->validUserName()))
                       && (empty($validName3->validUserName())));
                       && ($validName1->userName() == "me@home.net")
                       && ($validName2->userName() == $validUserName)
                       && ($validName3->userName() == "The Skunk_Lives"));

    }

    /**
     * Test Real Name
     *
     * <p>Simple test of setting a real name and seeing if it is saved to the database.</p>
     */

    function testRealName()
    {
        $this->validUser->setRealName("   blah   ");  // Extra spaces around name
        $invalidName1 = $this->validUser->isValidRealName();

        $this->validUser->setRealName("");            // Empty Name
        $invalidName2 = $this->validUser->isValidRealName();

        $this->validUser->setRealName("This Is The Name That Never Ends, It Goes On And On My Friend");  // Extra long
        $invalidName3 = $this->validUser->isValidRealName();

        $this->validUser->setRealName("$~%!+");  // Odd Characters In Name
        $invalidName4 = $this->validUser->isValidRealName();

        $this->validUser->setRealName("The   Queen");  // Extra space in name
        $invalidName5 = $this->validUser->isValidRealName();

        $this->validUser->setRealName("Charles Aldarondo");
        $this->validUser->saveUser();
        $duplicateValidUser = new User($validUserName);

        $this->assertTrue((!$invalidName1) && (!$invalidName2) && (!$invalidName3)
                                 && (!$invalidName4) && (!$invalidName5) &&
                          ($duplicateValidUser->userName() == "Charles Aldarondo"));
    }

    /**
     * Test E-mail Updates
     *
     * <p>Simple test of disable/enable e-mail updates and seeing if changes saved.</p>
     */

    function testEmailUpdates()
    {
        $this->validUser->disableEmailUpdates();
        $this->validUser->enableEmailUpdates();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isUpdatesEmailed());
    }

    /**
     * Test E-mail Address
     *
     * <p></p>
     */

    function testEmailAddress()
    {
        $this->validUser->setEmailAddress("   blah@home.net   ");  // Extra spaces around Address
        $invalidEmail1 = $this->validUser->isValidEmailAddress();

        $this->validUser->setEmailAddress("");            // Empty Address
        $invalidEmail2 = $this->validUser->isValidEmailAddress();

        $this->validUser->setEmailAddress("This.Is.The.Name.That.Never.Ends@It.Goes.On.And.On.My.Friend.net");  // Extra long
        $invalidEmail3 = $this->validUser->isValidEmailAddress();

        $this->validUser->setEmailAddress("$~%!+@home.net");  // Odd Characters In Address
        $invalidEmail4 = $this->validUser->isValidEmailAddress();

        $this->validUser->setEmailAddress("The_Queen.com");  // No @ symbol
        $invalidEmail5 = $this->validUser->isValidEmailAddress();

        $this->validUser->setEmailAddress("The@Queen@home.net");  // Two @ symbol
        $invalidEmail6 = $this->validUser->isValidEmailAddress();

        $this->validUser->setEmailAddress("dphelp@pgdp.net");
        $this->validUser->saveUser();
        $duplicateValidUser = new User($validUserName);

        $this->assertTrue((!$invalidEmail1) && (!$invalidEmail2) && (!$invalidEmail3) &&
                          (!$invalidEmail4) && (!$invalidEmail5) && (!$invalidEmail6) &&
                          ($duplicateValidUser->emailAddress() == "Charles Aldarondo"));

    }

    /**
     * Theme Test
     *
     * <p></p>
     */

    function testTheme()
    {

    }

    /**
     * Profile Test
     *
     * <p></p>
     */

    function testProfile()
    {

    }

    /**
     * Proofreading Show Rounds 1 & 2 Test
     *
     * <p></p>
     */

    function testShowRound()
    {

    }

    /**
     * Site-wide Language Test
     *
     * <p></p>
     */

    function testLanguage()
    {

    }

    /**
     * Site-wide Rank Neighbors Test
     *
     * <p></p>
     */

    function testRankNeighbors()
    {

    }

    /**
     * Site-wide Interface Language Test
     *
     * <p></p>
     */

    function testInterfaceLanguage()
    {

    }

    /**
     * Proofreading Resolution Test
     *
     * <p></p>
     */

    function testResolution()
    {

    }

    /**
     * Proofreading Interface Layout Test
     *
     * <p></p>
     */

    function testInterfaceLayout()
    {

    }

    /**
     * Proofreading in New Window Test
     *
     * <p>Takes a valid user and enables new proofreading window, along with testing the
     * status bar and the toolbar of the browser in this new window.</p>
     */

    function testNewWindow()
    {
        $this->validUser->disableLaunchNewWindow();
        $this->validUser->enableLaunchNewWindow();

        $this->validUser->disableShowToolbar();
        $this->validUser->enableShowToolbar();

        $this->validUser->disableShowStatusBar();
        $this->validUser->enableShowStatusBar();
        $this->validUser->saveUser();

        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isNewWindowLaunched() &&
                          $duplicateValidUser->isToolbarShown() &&
                          $duplicateValidUser->isStatusBarShown());
    }

    /**
     * Site-Wide Top 10 Proofreaders Display Test
     *
     * <p></p>
     */

    function testTop10()
    {
        $this->validUser->disableShowTop10();
        $this->validUser->enableShowTop10();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isTop10Shown());
    }

    /**
     * Site-Wide Statistics Bar Test
     *
     * <p></p>
     */

    function testStatisticsBar()
    {
        $this->validUser->enableNoStatusBar();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);
        $validBar1 = (($duplicateValidUser->isNoStatusBar()) &&
                      (!$duplicateValidUser->isLeftStatusBar()) &&
                      (!$duplicateValidUser->isRightStatusBar()));

        $this->validUser->enableLeftStatusBar();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);
        $validBar2 = ((!$duplicateValidUser->isNoStatusBar()) &&
                      ($duplicateValidUser->isLeftStatusBar()) &&
                      (!$duplicateValidUser->isRightStatusBar()));

        $this->validUser->enableRightStatusBar();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);
        $validBar3 = ((!$duplicateValidUser->isNoStatusBar()) &&
                      (!$duplicateValidUser->isLeftStatusBar()) &&
                      ($duplicateValidUser->isRightStatusBar()));

        $this->assertTrue($validBar1 && $validBar2 && $validBar3);
    }

    /**
     * Proofreading Font Face Test
     *
     * <p></p>
     */

    function testFontFace()
    {

    }

    /**
     * Proofreading Font Size Test
     *
     * <p></p>
     */

    function testFontSize()
    {

    }

    /**
     * Proofreading Image Zoom Test
     *
     * <p></p>
     */

    function testImageZoom()
    {

    }

    /**
     * Proofreading Text Frame Size Test
     *
     * <p></p>
     */

    function testTextFrameSize()
    {

    }

    /**
     * Proofreading Text Frame Scrolled Test
     *
     * <p></p>
     */

    function testTextFrameScrolled()
    {

    }

    /**
     * Test Wrap Text in Proofreading Interface
     *
     * <p>Takes a valid user and changes their text wrapping preferences.</p>
     */

    function testWrapText()
    {

    }

    /**
     * Test Default Page Shown When Logging In
     *
     * <p>Takes a valid user and changes the default page that they get when they login.</p>
     */

    function testDefaultPage()
    {

    }

    /**
     * Test Proofreading Layout
     *
     * <p>Takes a valid user and attempts to change their layout and checks to see if it sticks.</p>
     */

    function testLayout()
    {

    }

    /**
     * Test Number of Columns in Proofreading Interface
     *
     * <p>Takes a valid user and attempts valid/invalid number of columns for the user.</p>
     */

    function testNumberColumns()
    {

    }

    /**
     * Test Number of Rows in Proofreading Interface
     *
     * <p>Takes a valid user and attempts valid/invalid number of rows for the user.</p>
     */

    function testNumberRows()
    {

    }

    /**
     * Test Public/Anonymous/Private Statistics
     *
     * <p>Takes a valid user and attempts to change each of their privacy options for
     * statistics and sees if the changes stick.</p>
     */

    function testPublicAnonPrivate()
    {

    }

    /**
     * Test if User Name Needs an Apostrophe
     *
     * <p>Takes two names (1 that needs an apostrophe, 1 that does not) and creates
     * users with those names, seeing if the correct one gets an apostrophe.</p>
     */

    function testNeedApostrophe()
    {

    }

    /**
     * Test Date Account was Created
     *
     * <p>Takes a valid user and attempts to update and verifies the changes were made.</p>
     */

    function testCreatedDate()
    {

    }

    /**
     * Test's User Last Login
     *
     * <p>Takes a valid user and changes their login date and verifies the changes were made.</p>
     */

    function testLastLogin()
    {
        
    }

    /**
     * Test Location of User Avatar
     *
     * <p>Attempts valid and invalid locations of user avatars.</p>
     */

    function testUserAvatar()
    {

    }

    /**
     * Test Ranking Descriptions of User
     *
     * <p>Creates a new user and increments their page count, checking to see if
     * the name changes are correct.</p>
     */

    function testRankingDescription()
    {

    }

    /**
     * Page Position in Relation to Other Users
     *
     * <p>Takes a new user and another user with a known page/position and increments
     * the new user's page count until it below, matches, and above the known user to
     * see if it's position is below, equal, and above the known user.</p>
     */

    function testPagePostion()
    {

    }

    /**
     * Increment User Pages
     *
     * <p>Takes a user with a known amount of user pages and attempts to increments pages.</p>
     */

    function testIncrementPages()
    {
        $total = $this->validUser->pagesCompleted();
        $duplicateValidUser = new User($this->validUserName);
        $duplicateValidUser->incrementPagesCompleted();

        $this->assertTrue($total + 1 == $duplicateValidUser->pagesCompleted());
    }

    /**
     * Decrement User Pages
     *
     * <p>Takes a user with a known amount of user pages and attempts to decrement pages.</p>
     */

    function testDecrementPages()
    {
        $total = $this->validUser->pagesCompleted();
        $duplicateValidUser = new User($this->validUserName);
        $duplicateValidUser->decrementPagesCompleted();

        $this->assertTrue($total - 1 == $duplicateValidUser->pagesCompleted());
    }

    /**
     * Project Manager Test
     *
     * <p>Takes a valid user and attempts to remove/add project manager permissions.</p>
     */

    function testProjectManager()
    {
        $this->validUser->disableProjectManager();
        $this->validUser->enableProjectManager();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isProjectManager());
    }

    /**
     * Site Manager Test
     *
     * <p>Takes a valid user and attempts to remove/add site manager permissions.</p>
     */

    function testSiteManager()
    {
        $this->validUser->disableSiteManager();
        $this->validUser->enableSiteManager();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isSiteManager());
    }

    /**
     * Task Center Manager Test
     *
     * <p>Takes a valid user and attempts to remove/add task center manager permissions.</p>
     */

    function testTaskCenterManager()
    {
        $this->validUser->disableTaskCenterManager();
        $this->validUser->enableTaskCenterManager();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isTaskCenterManager());
    }

    /**
     * Project Facilitator Test
     *
     * <p>Takes a valid user and attempts to remove/add project facilitator permissions.</p>
     */

    function testProjectFacilitator()
    {
        $this->validUser->disableProjectFacilitator();
        $this->validUser->enableProjectFacilitator();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isProjectFacilitator());
    }

    /**
     * Project Mentor Test
     *
     * <p>Takes a valid user and attempts to remove/add project manager permissions.</p>
     */

    function testProjectMentor()
    {

        $this->validUser->disableProjectMentor();
        $this->validUser->enableProjectMentor();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isProjectMentor());
    }

    /**
     * Post Processor Test
     *
     * <p>Takes a valid user and attempts to remove/add post processor permissions.</p>
     */

    function testPostProcessor()
    {
        $this->validUser->disablePostProcessor();
        $this->validUser->enablePostProcessor();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isPostProcessor());
    }

    /**
     * Post Process Verifier Test
     *
     * <p>Takes a valid user and attempts to remove/add post process verifier permissions.</p>
     */

    function testPostProcessVerifier()
    {
        $this->validUser->disablePostProcessVerifier();
        $this->validUser->enablePostProcessVerifier();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isPostProcessVerifier());
    }

    /**
     * Site News Editor Test
     *
     * <p>Takes a valid user and attempts to remove/add site news editor permissions.</p>
     */

    function testSiteNewsEditor()
    {
        $this->validUser->disableSiteNewsEditor();
        $this->validUser->enableSiteNewsEditor();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isSiteNewsEditor());
    }

    /**
     * See BEGIN Projects in Round 1 Test
     *
     * <p>Takes a valid user and attempts to remove/add see BEGIN projects in round 1 permissions.</p>
     */

    function testSeeBeginR1()
    {
        $this->validUser->disableSeeBeginR1();
        $this->validUser->enableSeeBeginR1();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isSeeBeginR1());
    }

    /**
     * See BEGIN Projects in Round 2 Test
     *
     * <p>Takes a valid user and attempts to remove/add see BEGIN projects in round 2 permissions.</p>
     */

    function testSeeBeginR2()
    {
        $this->validUser->disableSeeBeginR2();
        $this->validUser->enableSeeBeginR2();
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isSeeBeginR2());
    }

    /**
     * Unread PM in the phpBB forum
     *
     * <p>Takes 2 known users (1 without messages, 1 with messages) and attempts to find out
     * how many messages they have unread.</p>
     */

    function testUnreadPM()
    {

    }

    /**
     * Daily Pages Proofread Average Test
     *
     * <p>Creates a new user and sees if their average is 0. Also takes 2 known users and
     * sees if their average matches the known amount (low 0.01 and high amount).</p>
     */

    function testDailyPageAverage()
    {

    }

    /**
     * Best Proofreading Day Ever Test
     *
     * <p>Creates a new user and sees if today is their best day ever. Also takes a known
     * user and gets their best day ever values.</p>
     */

    function testBestDayEver()
    {
        $newDate = $this->newUser->bestDayEver();
        $newCount = $this->newUser->bestDayEverCount();

        $validDate = $this->validUser->bestDayEver();
        $validCount = $this->validUser->bestDayEverCount();

        $this->assertTrue(($newDate == date() && ($newCount == 0)
                       && ($validDate == date() && ($validCount == 100));
    }

    /**
     * Teams Test
     *
     * <p>Takes a valid user and attempts to remove/add user from a team.</p>
     */

    function testTeams()
    {
        $this->validUser->quitTeam(3);
        $this->validUser->quitTeam(1);
        $this->validUser->quitTeam(2);
        $this->validUser->joinTeam(1);
        $this->validUser->saveUser();
        $duplicateValidUser = new User($this->validUserName);

        $this->assertTrue($duplicateValidUser->isTeamMember(1)
                       && $duplicateValidUser->isTeamMember(2)
                       && (!$duplicateValidUser->isTeamMember(3)));
    }

}

?>
