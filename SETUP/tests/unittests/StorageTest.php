<?php

class StorageTest extends PHPUnit\Framework\TestCase
{
    //------------------------------------------------------------------------
    // Basic JSON storage test

    public function test_valid_json(): void
    {
        $storage = new JsonStorage("username");
        $storage->set("setting", "{}");
        $value = $storage->get("setting");
        $this->assertEquals("{}", $value);
        $value = $storage->delete("setting");
        $this->assertEquals(null, $value);
    }

    public function test_invalid_json(): void
    {
        $this->expectException(ValueError::class);
        $storage = new JsonStorage("username");
        $storage->set("setting", "blearg");
    }

    //------------------------------------------------------------------------
    // API client storage test

    public function test_valid_client(): void
    {
        global $api_client_storage_keys;
        $api_client_storage_keys = ["valid"];

        $storage = new ApiClientStorage("valid", "username");
        $storage->set("{}");
        $value = $storage->get();
        $this->assertEquals("{}", $value);
        $value = $storage->delete();
        $this->assertEquals(null, $value);
    }

    public function test_invalid_client(): void
    {
        global $api_client_storage_keys;
        $api_client_storage_keys = [];

        $this->expectException(ValueError::class);

        $storage = new ApiClientStorage("invalid", "username");
    }
}
