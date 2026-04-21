<?php
include_once($relPath.'DPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'page_tally.inc');

// -----------------------------------------------------------------------------

function page_del($projectid, $image)
{
    validate_projectID($projectid);
    $sql = sprintf(
        "
        SELECT image
        FROM $projectid
        WHERE image = '%s'
        ",
        DPDatabase::escape($image)
    );
    $result = DPDatabase::query($sql);
    if (mysqli_num_rows($result) == 0) {
        return _("There is no page with that imagename.");
    }

    // Delete related page records from events tables.
    // Note that we don't delete the page_events records to retain the fact
    // that this page at one point existed and had operations done against it.
    // Indeed, the delete operation below will add a delete event_type.
    foreach (["wordcheck_events", "format_preview_events"] as $table_name) {
        $query = sprintf(
            "
            DELETE FROM %s
            WHERE projectid = '%s' AND image = '%s'
            ",
            $table_name,
            DPDatabase::escape($projectid),
            DPDatabase::escape($image)
        );
        DPDatabase::query($query);
    }

    global $pguser;
    Page_delete($projectid, $image, $pguser);

    // Delete the image file from the project directory.
    $project = new Project($projectid);
    $image_path = "$project->dir/$image";
    if (! unlink($image_path)) {
        return sprintf(_('Unable to remove file %s'), $image_path);
    }
}

// -----------------------------------------------------------------------------

function page_clear($projectid, $image)
{
    // echo "page_clear( $projectid, $image )<br>\n";

    $project = new Project($projectid);
    $projstate = $project->state;

    $round = get_Round_for_project_state($projstate);

    if (is_null($round)) {
        return _("The project is not in a state that allows pages to be cleared.");
    }

    validate_projectID($projectid);
    $sql = sprintf(
        "
        SELECT state, %s
        FROM $projectid
        WHERE image = '%s'
        ",
        $round->user_column_name,
        DPDatabase::escape($image)
    );
    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_row($result);
    if (!$row) {
        return _("There is no page with that imagename.");
    }
    [$page_state, $proofer] = $row;
    if ($page_state != $round->page_save_state) {
        return _("The page is not in a state that allows it to be cleared.");
    }

    // ------------------------------------------------
    // page will be cleared, so decrement page tallies for user & teams

    page_tallies_add($round->id, $proofer, -1);

    // ------------------------------------------------
    // now clear the page

    global $pguser;
    Page_clearRound($projectid, $image, $round, $pguser);
}
