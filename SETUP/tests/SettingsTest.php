<?php

class SettingsTest extends PHPUnit_Framework_TestCase
{
    private $TEST_USERNAME;
    private $PREFIX = 'STU_';
    private $NONEXISTENT_USERNAME = 'SettingsTestUser';

    private $cleanup = FALSE;

    protected function setUp()
    {
        // Load a valid username from the database for use during the tests
        $sql = "SELECT username FROM usersettings LIMIT 1";
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        mysql_free_result($result);
        $this->TEST_USERNAME = $row['username'];
    }

    protected function tearDown()
    {
        $sql = sprintf("
            DELETE FROM usersettings
            WHERE username='%s' AND setting like '%s%%'
        ", $this->TEST_USERNAME, $this->PREFIX);
        mysql_query($sql);
    }

    public function testExisting()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $this->assertGreaterThan(0, $settings->settings_count());
    }

    public function testNonexisting()
    {
        $settings = new Settings($this->NONEXISTENT_USERNAME);
        $this->assertEquals(0, $settings->settings_count());
    }

    public function testSetTrue()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $settings->set_true($this->PREFIX . "boolean_true");

        $settings = new Settings($this->TEST_USERNAME);
        $this->assertTrue(
            $settings->get_boolean($this->PREFIX . "boolean_true"));
    }

    public function testSetFalse()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $settings->set_false($this->PREFIX . "boolean_false");

        $settings = new Settings($this->TEST_USERNAME);
        $this->assertFalse(
            $settings->get_boolean($this->PREFIX . "boolean_false"));
    }

    public function testSetSingleValue()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $settings->set_value($this->PREFIX . "single_value", "single");

        $settings = new Settings($this->TEST_USERNAME);
        $this->assertEquals(
            $settings->get_value($this->PREFIX . "single_value"), "single");
    }

    public function testSetSingleValueDefault()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $this->assertEquals(
            $settings->get_value($this->PREFIX . "nonexistent_value", "default"), "default");
    }

    public function testSetSingleValueAgain()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $settings->set_value($this->PREFIX . "single_value", "single1");
        $settings->set_value($this->PREFIX . "single_value", "single2");

        $settings = new Settings($this->TEST_USERNAME);
        $this->assertEquals(
            $settings->get_value($this->PREFIX . "single_value"), "single2");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetUnsettableValue()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $settings->set_true("sitemanager");
    }

    public function testAddMultivaluedSetting()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $settings->add_value($this->PREFIX . "multi_value", "value1");
        $settings->add_value($this->PREFIX . "multi_value", "value2");

        $values = $settings->get_values($this->PREFIX . "multi_value");
        $this->assertEquals(2, count($values));
        $this->assertContains("value1", $values);
        $this->assertContains("value2", $values);
    }

    public function testRemoveMultivaluedSetting()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $settings->add_value($this->PREFIX . "multi_value", "value1");
        $settings->add_value($this->PREFIX . "multi_value", "value2");
        $settings->remove_value($this->PREFIX . "multi_value", "value1");

        $values = $settings->get_values($this->PREFIX . "multi_value");
        $this->assertEquals(1, count($values));
        $this->assertContains("value2", $values);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetMultivaluedSettingAsSingle()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $settings->add_value($this->PREFIX . "multi_value", "value1");
        $settings->add_value($this->PREFIX . "multi_value", "value2");

        $settings->get_value($this->PREFIX . "multi_value");
    }

    public function testSetMultivaluedSettingAsSingle()
    {
        $settings = new Settings($this->TEST_USERNAME);
        $settings->add_value($this->PREFIX . "multi_value", "value1");
        $settings->add_value($this->PREFIX . "multi_value", "value2");

        $settings->set_value($this->PREFIX . "multi_value", "value3");
        $this->assertEquals(
            $settings->get_value($this->PREFIX . "multi_value"), "value3");
    }

    public function testGetUsernamesWithSetting()
    {
        // Ensure no one has this setting already
        $usernames = Settings::get_users_with_setting(
            $this->PREFIX . "specific_setting");
        $this->assertEquals(0, count($usernames));

        // Add a user with this setting and confirm there is only one
        $settings = new Settings($this->TEST_USERNAME);
        $settings->add_value($this->PREFIX . "specific_setting", "value1");

        $usernames = Settings::get_users_with_setting(
            $this->PREFIX . "specific_setting");
        $this->assertEquals(1, count($usernames));

        // Add a second setting with a different value and confirm there is
        // still only one user with this setting, despite having multiple
        // values.
        $settings->add_value($this->PREFIX . "specific_setting", "value2");
        $usernames = Settings::get_users_with_setting(
            $this->PREFIX . "specific_setting");
        $this->assertEquals(1, count($usernames));

        // Get a list querying for a specific setting and value
        $usernames = Settings::get_users_with_setting(
            $this->PREFIX . "specific_setting", "value2");
        $this->assertEquals(1, count($usernames));
    }
}
