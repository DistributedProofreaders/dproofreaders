<?php

// DPDatabase basic tests

class DPDatabaseTest extends PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass(): void
    {
        // Sanity check: Remove the existing table so we start from a clean state.
        $r = DPDatabase::query("DROP TABLE IF EXISTS `dpdb_tests`");
        if ($r === false) {
            throw new Exception("Failed to clean-up test table");
        }

        $r = DPDatabase::query("CREATE TABLE `dpdb_tests` (`id` int(20) NOT NULL, `comment` varchar(25) NOT NULL default '', PRIMARY KEY (`id`))");
        if ($r === false) {
            throw new Exception("Failed to create test table");
        }
    }

    public function test_does_table_exit_existing_table(): void
    {
        $this->assertSame(true, DPDatabase::does_table_exist("dpdb_tests"));
    }

    public function test_does_table_exit_inexistent_table(): void
    {
        $this->assertSame(false, DPDatabase::does_table_exist("inexistent_for_sure"));
    }

    public function test_insert_delete_table()
    {
        $r = DPDatabase::query("INSERT INTO `dpdb_tests` VALUES(0, \"test\")");
        $this->assertTrue($r);

        // Fetch the row back.
        $r = DPDatabase::query("SELECT * FROM `dpdb_tests` where id=0");
        if (is_bool($r)) {
            throw new Exception("Got a boolean from select on DPDatabase::query: {$r}");
        }
        $this->assertSame(1, $r->num_rows);
        $this->assertSame(1, DPDatabase::affected_rows());
        $row = mysqli_fetch_array($r);
        $this->assertSame('0', $row['id']);
        $this->assertSame('test', $row['comment']);

        // Clean-up.
        $r = DPDatabase::query("DELETE FROM `dpdb_tests`");
        $this->assertTrue($r);
    }

    public function test_fail_insert_inexistent_table()
    {
        $this->expectException(DBQueryError::class);
        $r = DPDatabase::query("INSERT INTO `inexistent_for_sure` VALUES(0)");
    }

    public function test_fail_insert_inexistent_table_no_exception()
    {
        $r = DPDatabase::query("INSERT INTO `inexistent_for_sure` VALUES(0)", /*throw_on_failure*/ false);
        $this->assertSame(false, $r);
    }

    public function test_fail_insert_missing_values()
    {
        $this->expectException(DBQueryError::class);
        $r = DPDatabase::query("INSERT INTO `dpdb_tests` VALUES()");
    }

    public function test_fail_insert_missing_values_no_exception()
    {
        $r = DPDatabase::query("INSERT INTO `dpdb_tests` VALUES()", /*throw_on_failure*/ false);
        $this->assertSame(false, $r);
    }

    public function test_close_then_reopen()
    {
        DPDatabase::close();
        $this->assertNull(DPDatabase::get_connection());
        DPDatabase::connect("localhost", "dp_test_db", "dp_test_user", "dp_test_password");
        $this->assertIsObject(DPDatabase::get_connection());
    }
}
