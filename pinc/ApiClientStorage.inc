<?php

class ApiClientStorage
{
    private JsonStorage $storage;
    private string $setting;

    public function __construct(string $client, string $username)
    {
        global $api_client_storage_keys;

        if (!in_array($client, $api_client_storage_keys)) {
            throw new ValueError("$client is not an accepted client");
        }
        $this->setting = "client:$client";
        $this->storage = new JsonStorage($username);
    }

    // -------------------------------------------------------------------------

    public function set(string $value): void
    {
        $this->storage->set($this->setting, $value);
    }

    public function get(): ?string
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
