<?php

function get_biography($id)
{
    $sql = sprintf(
        "
        SELECT bio, bio_format
        FROM biographies
        WHERE bio_id = %d
        ",
        $id
    );
    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        // not found!
        return sprintf(_("An error has occurred somewhere. The project manager of this project has requested that a biography be automatically inserted here, but no biography is known by the id provided: <b>%s</b>.<br><br>You may wish to contact the project manager so that the problem can be resolved."), $id);
    } else {
        if ($row["bio_format"] === 'markdown') {
            $Parsedown = new ParsedownExtra();
            $Parsedown->setSafeMode(true);
            $bio_text = $Parsedown->text($row["bio"]);
        } else {
            $bio_text = sanitize_html($row["bio"], 'td');
        }

        // wrap in commentaries and return
        return "<!-- begin bio retrieved from database: id=$id -->\n$bio_text\n<!-- end bio retrieved from database: id=$id -->\n";
    }
}
