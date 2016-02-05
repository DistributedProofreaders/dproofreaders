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
}
