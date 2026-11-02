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
        $storage->delete("setting");
    }

    public function test_invalid_json(): void
    {
        $this->expectException(ValueError::class);
        $storage = new JsonStorage("username");
        $storage->set("setting", "blearg");
    }

    //------------------------------------------------------------------------
    // API storage test

    public function test_valid_storagekey(): void
    {
        SiteConfig::get()->api_storage_keys = ["valid"];

        $storage = new ApiStorage("valid", "username");
        $storage->set("{}");
        $value = $storage->get();
        $this->assertEquals("{}", $value);
        $storage->delete();
    }

    public function test_invalid_storagekey(): void
    {
        $this->expectException(ValueError::class);

        new ApiStorage("invalid", "username");
    }
}
