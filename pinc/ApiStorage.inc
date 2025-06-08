<?php

class ApiStorage
{
    private JsonStorage $storage;
    private string $setting;

    public function __construct(string $storage_key, string $username)
    {
        if (!in_array($storage_key, SiteConfig::get()->api_storage_keys)) {
            throw new ValueError("$storage_key is not an accepted storage key");
        }
        $this->setting = "api:$storage_key";
        $this->storage = new JsonStorage($username);
    }

    // -------------------------------------------------------------------------

    public function set(string $value): void
    {
        $this->storage->set($this->setting, $value);
    }

    public function get(): string
    {
        // if the setting isn't set, JsonStorage will return null but we want
        // to always return valid JSON for the API so we return an empty JSON
        // doc.
        return $this->storage->get($this->setting) ?? "{}";
    }

    public function delete(): void
    {
        $this->storage->delete($this->setting);
    }
}
