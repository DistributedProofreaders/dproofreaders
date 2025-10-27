<?php

class FormatPreview
{
    /**
     * Save a Format Preview event.
     */
    public static function save_event(string $projectid, string $round, string $page, string $proofer): void
    {
        $setters = join(", ", [
            set_col_str("projectid", $projectid),
            set_col_str("image", $page),
            set_col_str("round_id", $round),
            set_col_str("username", $proofer),
            set_col_num("timestamp", time()),
        ]);
        $sql = sprintf(
            "
            INSERT INTO format_preview_events
                SET $setters
            ON DUPLICATE KEY UPDATE
                timestamp = %d
            ",
            time()
        );
        DPDatabase::query($sql);
    }

    /**
     * Delete any format_preview_events for this project.
     *
     * This is called when deleting a project.
     */
    public static function delete_project_events(string $projectid): void
    {
        $sql = sprintf(
            "
            DELETE FROM format_preview_events
            WHERE projectid='%s'
            ",
            DPDatabase::escape($projectid)
        );
        DPDatabase::query($sql);
    }

    /**
     * Copy format_preview_events from one project to another.
     *
     * This is called when copying pages into a project.
     *
     * @param string[] $images
     */
    public static function copy_project_events(string $src_projectid, string $dest_projectid, array $images = []): void
    {
        if ($images) {
            $images_where = " AND image in (" . surround_and_join(array_map("DPDatabase::escape", $images), '"', '"', ",") . ")";
        } else {
            $images_where = "";
        }

        $sql = sprintf(
            "
            INSERT INTO format_preview_events
                (projectid, image, round_id, username, timestamp)
            SELECT '%s', image, round_id, username, timestamp
            FROM format_preview_events
            WHERE projectid = '%s' $images_where
            ",
            DPDatabase::escape($dest_projectid),
            DPDatabase::escape($src_projectid)
        );
        DPDatabase::query($sql);
    }
}
