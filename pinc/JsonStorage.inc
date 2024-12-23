<?php

class JsonStorage
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    // -------------------------------------------------------------------------

    /**
     * Set or update a json_storage object.
     */
    public function set(string $setting, string $value): void
    {
        // It's possible $value could be very large and including it twice
        // in the query will cause it to be too big. The default max query
        // size is 64MB which means this will fail if the JSON blob is
        // just shy of 32MB. That seems plenty big.
        $sql = sprintf(
            "
            INSERT INTO json_storage
            SET
                username = '%s',
                setting = '%s',
                value = '%s',
                timestamp = UNIX_TIMESTAMP()
            ON DUPLICATE KEY UPDATE
                value = '%s',
                timestamp = UNIX_TIMESTAMP()
            ",
            DPDatabase::escape($this->username),
            DPDatabase::escape($setting),
            DPDatabase::escape($value),
            DPDatabase::escape($value)
        );
        // We rely on MySQL to validate the JSON is valid or throw an exception.
        // It's going to do the check anyway and saves us from de-serializing
        // it here.
        try {
            DPDatabase::query($sql, true, false);
        } catch (DBQueryError $e) {
            if (startswith($e->getPrevious()->getMessage(), "Invalid JSON")) {
                throw new ValueError("Error persisting data, invalid JSON");
            }
            throw $e;
        }
    }

    /**
     * Get a json_storage object.
     */
    public function get(string $setting): ?string
    {
        $sql = sprintf(
            "
            SELECT value
            FROM json_storage
            WHERE username = '%s' AND setting = '%s'
            ",
            DPDatabase::escape($this->username),
            DPDatabase::escape($setting)
        );
        $result = DPDatabase::query($sql);
        $value = null;
        while ($row = mysqli_fetch_assoc($result)) {
            $value = $row['value'];
        }
        mysqli_free_result($result);
        return $value;
    }

    /**
     * Delete a json_storage object.
     */
    public function delete(string $setting): void
    {
        $sql = sprintf(
            "
            DELETE FROM json_storage
            WHERE username = '%s' AND setting = '%s'
            ",
            DPDatabase::escape($this->username),
            DPDatabase::escape($setting)
        );
        DPDatabase::query($sql);
    }
}
