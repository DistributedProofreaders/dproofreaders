<?php

/**
 * Return an associative array of genres in the format
 *     english_name => translated_name
 * sorted by the translated name
 */
function load_genre_translation_array()
{
    $genres = [
        'Other' => _('Other'),
        'Adventure' => _('Adventure'),
        'Agriculture' => _('Agriculture'),
        'Animals' => _('Animals'),
        'Anthropology' => _('Anthropology'),
        'Archaeology' => _('Archaeology'),
        'Architecture' => _('Architecture'),
        'Art' => _('Art'),
        'Astronomy' => _('Astronomy'),
        'Autobiography' => _('Autobiography'),
        'Bibliography' => _('Bibliography'),
        'Biography' => _('Biography'),
        'Biology' => _('Biology'),
        'Business' => _('Business'),
        'Chemistry' => _('Chemistry'),
        'Collection' => _('Collection'),
        'Comics' => _('Comics'),
        'Cooking' => _('Cooking'),
        'Correspondence' => _('Correspondence'),
        'Crafts' => _('Crafts'),
        'Diary' => _('Diary'),
        'Dictionary' => _('Dictionary'),
        'Drama' => _('Drama'),
        'Economics' => _('Economics'),
        'Education' => _('Education'),
        'Encyclopedia' => _('Encyclopedia'),
        'Engineering' => _('Engineering'),
        'Essay' => _('Essay'),
        'Folklore' => _('Folklore'),
        'General Fiction' => _('General Fiction'),
        'Geography' => _('Geography'),
        'Geology' => _('Geology'),
        'Grammar' => _('Grammar'),
        'Health' => _('Health'),
        'Historical Fiction' => _('Historical Fiction'),
        'History' => _('History'),
        'Horror' => _('Horror'),
        'Horticulture' => _('Horticulture'),
        'Humor' => _('Humor'),
        'Instructional' => _('Instructional'),
        'Juvenile' => _('Juvenile'),
        'Law' => _('Law'),
        'Linguistics' => _('Linguistics'),
        'Literature' => _('Literature'),
        'Mathematics' => _('Mathematics'),
        'Medicine' => _('Medicine'),
        'Military' => _('Military'),
        'Mixed Form' => _('Mixed Form'),
        'Music' => _('Music'),
        'Musicology' => _('Musicology'),
        'Mystery' => _('Mystery'),
        'Mythology' => _('Mythology'),
        'Natural Science' => _('Natural Science'),
        'Nature' => _('Nature'),
        'Non-Fiction' => _('Non-Fiction'),
        'Periodical' => _('Periodical'),
        'Philosophy' => _('Philosophy'),
        'Physics' => _('Physics'),
        'Picture Book' => _('Picture Book'),
        'Poetry' => _('Poetry'),
        'Political Science' => _('Political Science'),
        'Psychology' => _('Psychology'),
        'Recreation' => _('Recreation'),
        'Reference' => _('Reference'),
        'Religious' => _('Religious'),
        'Romance' => _('Romance'),
        'Satire' => _('Satire'),
        'Science' => _('Science'),
        'Science Fiction & Fantasy' => _('Science Fiction & Fantasy'),
        'Short Story' => _('Short Story'),
        'Sociology' => _('Sociology'),
        'Speech' => _('Speech'),
        'Spirituality' => _('Spirituality'),
        'Sports' => _('Sports'),
        'Technology' => _('Technology'),
        'Travel' => _('Travel'),
        'Veterinary' => _('Veterinary'),
        'Western' => _('Western'),
        'Zoology' => _('Zoology'),
    ];

    asort($genres, SORT_LOCALE_STRING);

    return $genres;
}

function maybe_create_temporary_genre_translation_table()
{
    $genres = load_genre_translation_array();

    $sql = "
    CREATE TEMPORARY TABLE genre_translations
    (
        genre VARCHAR(50) NOT NULL,
        trans_genre VARCHAR(50) NOT NULL,
        PRIMARY KEY (genre)
    )
    ";

    try {
        // if the create table succeeded, populate the table
        DPDatabase::query($sql, true, false);
        foreach ($genres as $key => $value) {
            $sql = sprintf("
                INSERT INTO genre_translations
                SET
                    genre = '%s',
                    trans_genre = '%s'
                ",
                DPDatabase::escape($key),
                DPDatabase::escape($value));
            DPDatabase::query($sql);
        }
    } catch (DBQueryError $error) {
        // if it didn't, we assume the table is already created
    }
}
