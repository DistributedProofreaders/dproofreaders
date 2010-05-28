<?php
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'stages.inc');

    $project   = validate_projectID('project', @$_GET['project']);
    $image     = validate_page_image_filename('image', @$_GET['image']);
    $round_num = get_integer_param($_GET, 'round_num', null, 0, MAX_NUM_PAGE_EDITING_ROUNDS);

    if ($round_num == 0) {
        $text_column_name = 'master_text';
    } else {
        $round = get_Round_for_round_number($round_num);
        $text_column_name = $round->text_column_name;
    }

    $result = mysql_query("SELECT $text_column_name FROM $project WHERE image = '$image'"); 
    if (mysql_num_rows($result) == 0)
    {
        die("Could not find text for $image in $project");
    }

    $data = mysql_result($result, 0, $text_column_name);

    header("Content-type: text/plain; charset=$charset");
    // SENDING PAGE-TEXT TO USER
    // It's a text/plain document, so no encoding is necessary.
    echo $data;

// vim: sw=4 ts=4 expandtab
?> 
