<?php

$GENRES = array(
    'Other'              => _('Other'),
    'Adventure'          => _('Adventure'),
    'Agriculture'        => _('Agriculture'),
    'Animals'            => _('Animals'),
    'Anthropology'       => _('Anthropology'),
    'Archaeology'        => _('Archaeology'),
    'Architecture'       => _('Architecture'),
    'Art'                => _('Art'),
    'Astronomy'          => _('Astronomy'),
    'Autobiography'      => _('Autobiography'),
    'Bibliography'       => _('Bibliography'),
    'Biography'          => _('Biography'),
    'Biology'            => _('Biology'),
    'Business'           => _('Business'),
    'Chemistry'          => _('Chemistry'),
    'Collection'         => _('Collection'),
    'Comics'             => _('Comics'),
    'Cooking'            => _('Cooking'),
    'Correspondence'     => _('Correspondence'),
    'Crafts'             => _('Crafts'),
    'Diary'              => _('Diary'),
    'Dictionary'         => _('Dictionary'),
    'Drama'              => _('Drama'),
    'Economics'          => _('Economics'),
    'Education'          => _('Education'),
    'Encyclopedia'       => _('Encyclopedia'),
    'Engineering'        => _('Engineering'),
    'Essay'              => _('Essay'),
    'Folklore'           => _('Folklore'),
    'General Fiction'    => _('General Fiction'),
    'Geography'          => _('Geography'),
    'Geology'            => _('Geology'),
    'Grammar'            => _('Grammar'),
    'Health'             => _('Health'),
    'Historical Fiction' => _('Historical Fiction'),
    'History'            => _('History'),
    'Horror'             => _('Horror'),
    'Horticulture'       => _('Horticulture'),
    'Humor'              => _('Humor'),
    'Instructional'      => _('Instructional'),
    'Juvenile'           => _('Juvenile'),
    'Law'                => _('Law'),
    'Linguistics'        => _('Linguistics'),
    'Literature'         => _('Literature'),
    'Mathematics'        => _('Mathematics'),
    'Medicine'           => _('Medicine'),
    'Military'           => _('Military'),
    'Mixed Form'         => _('Mixed Form'),
    'Music'              => _('Music'),
    'Musicology'         => _('Musicology'),
    'Mystery'            => _('Mystery'),
    'Mythology'          => _('Mythology'),
    'Natural Science'    => _('Natural Science'),
    'Nature'             => _('Nature'),
    'Non-Fiction'        => _('Non-Fiction'),
    'Periodical'         => _('Periodical'),
    'Philosophy'         => _('Philosophy'),
    'Physics'            => _('Physics'),
    'Picture Book'       => _('Picture Book'),
    'Poetry'             => _('Poetry'),
    'Political Science'  => _('Political Science'),
    'Psychology'         => _('Psychology'),
    'Recreation'         => _('Recreation'),
    'Reference'          => _('Reference'),
    'Religious'          => _('Religious'),
    'Romance'            => _('Romance'),
    'Satire'             => _('Satire'),
    'Science'            => _('Science'),
    'Science Fiction'    => _('Science Fiction'),
    'Short Story'        => _('Short Story'),
    'Sociology'          => _('Sociology'),
    'Speech'             => _('Speech'),
    'Spirituality'       => _('Spirituality'),
    'Sports'             => _('Sports'),
    'Technology'         => _('Technology'),
    'Travel'             => _('Travel'),
    'Veterinary'         => _('Veterinary'),
    'Western'            => _('Western'),
    'Zoology'            => _('Zoology'),
);

function create_temporary_genre_translation_table()
{
    $sql = "
    CREATE TEMPORARY TABLE genre_translations
    (
        genre VARCHAR(50) NOT NULL,
        trans_genre VARCHAR(50) NOT NULL,

        PRIMARY KEY (genre)
    )
    ";

    $link = DPDatabase::get_connection();
    mysqli_query($link, $sql);

    global $GENRES;
    foreach($GENRES as $key => $value)
    {
        $key = mysqli_real_escape_string($link, $key);
        $value = mysqli_real_escape_string($link, $value);
        $sql = "INSERT INTO genre_translations SET genre = '$key', trans_genre = '$value'";
        mysqli_query($link, $sql);
    }
}

function maybe_create_temporary_genre_translation_table()
{
    $link = DPDatabase::get_connection();
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $sql = "SELECT 1 FROM genre_translations LIMIT 1";
    try
    {
        mysqli_query($link, $sql);
    }
    catch(Exception $e)
    {
        if(1146 == $e->getCode())
        {
            // table doesn't exist
            create_temporary_genre_translation_table();
        }
        else
        {
            throw($e);
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
