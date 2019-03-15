<?php
include_once($relPath.'genres.inc');

function create_genre_table()
{
    $sql = "
    CREATE TEMPORARY TABLE genres
    (
        genre VARCHAR(25) NOT NULL,
        trans_genre VARCHAR(50) NOT NULL,

        PRIMARY KEY (genre)
    )
    ";
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $link = DPDatabase::get_connection();
    mysqli_query($link, $sql);

    global $GENRES;
    foreach($GENRES as $key => $value)
    {
        $key = mysqli_real_escape_string($link, $key);
        $value = mysqli_real_escape_string($link, $value);
        $sql = "INSERT INTO genres SET genre = '$key', trans_genre = '$value'";
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        mysqli_query($link, $sql);
    }
}
