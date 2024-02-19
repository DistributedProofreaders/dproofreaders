<?php
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'genres.inc'); // load_genre_translation_array
include_once($relPath.'unicode.inc'); // guess_string_encoding()

class InvalidMARCRecord extends Exception
{
}

// An example MARC record for Title: "Frederick Douglass", Author: "Washington"
// as returned by yaz_search()
// The keys represent hierarchially named MARC fields, indicators, and subfields.
// There are two standards here Z39.50, and MARC.
// All MARC fields/subfields/indicators are named as(3,ID).
//
// Ex 1: "(3,leader)" for the field "leader"
//       See https://www.loc.gov/marc/bibliographic/bdleader.html
// Ex 2: "(3,041)(3,0 )(3,d)" for the Language code (041) of an item without
//       a translation (indicator 0), that is a "sung or spoken text"
//       (subfield $d).
//       See https://www.loc.gov/marc/bibliographic/bd041.html
//
// See https://www.loc.gov/marc/bibliographic/ for an overview.
//
// TODO: Add more explanation about the mapping of MARC onto yaz's arrays.
// In particular, what do the `@`s and signficant whitespaces in IDs
// mean; and how the `#` and `$`s in the MARC spec represent structure.
//
// [
//    ["(3,leader)", "02348cim a2200613 i 4500"],
//    ["(3,001)"],
//    ["(3,001)(3,@)", "6207828"],
//    ["(3,005)"],
//    ["(3,005)(3,@)", "20221117140825.0"],
//    ["(3,007)"],
//    ["(3,007)(3,@)", "sd bmmennmplue"],
//    ["(3,008)"],
//    ["(3,008)(3,@)", "860714s1974    nyunnn|  z     l  | eng d"],
//    ["(3,906)"],
//    ["(3,906)(3,  )"],
//    ["(3,906)(3,  )(3,a)", "7"],
//    ["(3,906)(3,  )(3,b)", "cbc"],
//    ["(3,906)(3,  )(3,c)", "copycat"],
//    ["(3,906)(3,  )(3,d)", "2"],
//    ["(3,906)(3,  )(3,e)", "ncip"],
//    ["(3,906)(3,  )(3,f)", "20"],
//    ["(3,906)(3,  )(3,g)", "y-soundrec"],
//    ["(3,925)"],
//    ["(3,925)(3,0 )"],
//    ["(3,925)(3,0 )(3,a)", "acquire"],
//    ["(3,925)(3,0 )(3,b)", "2 shelf copies"],
//    ["(3,925)(3,0 )(3,x)", "policy default"],
//    ["(3,955)"],
//    ["(3,955)(3,  )"],
//    ["(3,955)(3,  )(3,a)", "qr40 2022-11-09 z-client"],
//    ["(3,955)(3,  )(3,i)", "qr40 2022-11-09"],
//    ["(3,010)"],
//    ["(3,010)(3,  )"],
//    ["(3,010)(3,  )(3,a)", "   74750158 "],
//    ["(3,028)"],
//    ["(3,028)(3,02)"],
//    ["(3,028)(3,02)(3,a)", "TC 2070"],
//    ["(3,028)(3,02)(3,b)", "Caedmon"],
//    ["(3,035)"],
//    ["(3,035)(3,  )"],
//    ["(3,035)(3,  )(3,a)", "(OCoLC)ocm62426011"],
//    ["(3,040)"],
//    ["(3,040)(3,  )"],
//    ["(3,040)(3,  )(3,a)", "MNU"],
//    ["(3,040)(3,  )(3,b)", "eng"],
//    ["(3,040)(3,  )(3,e)", "rda"],
//    ["(3,040)(3,  )(3,c)", "MNU"],
//    ["(3,040)(3,  )(3,d)", "OCLCQ"],
//    ["(3,040)(3,  )(3,d)", "OCLCG"],
//    ["(3,040)(3,  )(3,d)", "OCLCA"],
//    ["(3,040)(3,  )(3,d)", "OCLCF"],
//    ["(3,040)(3,  )(3,d)", "OCLCO"],
//    ["(3,040)(3,  )(3,d)", "OCLCQ"],
//    ["(3,040)(3,  )(3,d)", "SNN"],
//    ["(3,040)(3,  )(3,d)", "OCLCQ"],
//    ["(3,040)(3,  )(3,d)", "OCLCO"],
//    ["(3,040)(3,  )(3,d)", "DLC"],
//    ["(3,041)"],
//    ["(3,041)(3,0 )"],
//    ["(3,041)(3,0 )(3,d)", "eng"],
//    ["(3,041)(3,0 )(3,e)", "eng"],
//    ["(3,042)"],
//    ["(3,042)(3,  )"],
//    ["(3,042)(3,  )(3,a)", "lccopycat"],
//    ["(3,043)"],
//    ["(3,043)(3,  )"],
//    ["(3,043)(3,  )(3,a)", "n-us---"],
//    ["(3,045)"],
//    ["(3,045)(3,  )"],
//    ["(3,045)(3,  )(3,a)", "w3w9"],
//    ["(3,050)"],
//    ["(3,050)(3,00)"],
//    ["(3,050)(3,00)(3,a)", "Caedmon TC 2070"],
//    ["(3,245)"],
//    ["(3,245)(3,00)"],
//    ["(3,245)(3,00)(3,a)", "Great black speeches."],
//    ["(3,245)(3,00)(3,n)", "Vol. 1."],
//    ["(3,246)"],
//    ["(3,246)(3,3 )"],
//    ["(3,246)(3,3 )(3,a)", "Great black speeches."],
//    ["(3,246)(3,3 )(3,n)", "Volume 1"],
//    ["(3,246)"],
//    ["(3,246)(3,3 )"],
//    ["(3,246)(3,3 )(3,a)", "Great black speeches."],
//    ["(3,246)(3,3 )(3,n)", "Vol. one"],
//    ["(3,264)"],
//    ["(3,264)(3, 1)"],
//    ["(3,264)(3, 1)(3,a)", "New York, N.Y. :"],
//    ["(3,264)(3, 1)(3,b)", "Caedmon,"],
//    ["(3,264)(3, 1)(3,c)", "[1974]"],
//    ["(3,264)"],
//    ["(3,264)(3, 4)"],
//    ["(3,264)(3, 4)(3,c)", "℗1974"],
//    ["(3,300)"],
//    ["(3,300)(3,  )"],
//    ["(3,300)(3,  )(3,a)", "2 audio discs :"],
//    ["(3,300)(3,  )(3,b)", "33 1/3 rpm, stereo ;"],
//    ["(3,300)(3,  )(3,c)", "12 in."],
//    ["(3,306)"],
//    ["(3,306)(3,  )"],
//    ["(3,306)(3,  )(3,a)", "013011"],
//    ["(3,336)"],
//    ["(3,336)(3,  )"],
//    ["(3,336)(3,  )(3,a)", "spoken word"],
//    ["(3,336)(3,  )(3,b)", "spw"],
//    ["(3,336)(3,  )(3,2)", "rdacontent"],
//    ["(3,337)"],
//    ["(3,337)(3,  )"],
//    ["(3,337)(3,  )(3,a)", "audio"],
//    ["(3,337)(3,  )(3,b)", "s"],
//    ["(3,337)(3,  )(3,2)", "rdamedia"],
//    ["(3,338)"],
//    ["(3,338)(3,  )"],
//    ["(3,338)(3,  )(3,a)", "audio disc"],
//    ["(3,338)(3,  )(3,b)", "sd"],
//    ["(3,338)(3,  )(3,2)", "rdacarrier"],
//    ["(3,340)"],
//    ["(3,340)(3,  )"],
//    ["(3,340)(3,  )(3,a)", "vinyl"],
//    ["(3,340)(3,  )(3,2)", "rdamat"],
//    ["(3,344)"],
//    ["(3,344)(3,  )"],
//    ["(3,344)(3,  )(3,a)", "analog"],
//    ["(3,344)(3,  )(3,2)", "rdatr"],
//    ["(3,344)"],
//    ["(3,344)(3,  )"],
//    ["(3,344)(3,  )(3,c)", "33 1/3 rpm"],
//    ["(3,344)"],
//    ["(3,344)(3,  )"],
//    ["(3,344)(3,  )(3,d)", "microgroove"],
//    ["(3,344)(3,  )(3,2)", "rdagw"],
//    ["(3,500)"],
//    ["(3,500)(3,  )"],
//    ["(3,500)(3,  )(3,a)", "Title from disc label."],
//    ["(3,500)"],
//    ["(3,500)(3,  )"],
//    ["(3,500)(3,  )(3,a)", "John Graham, editor."],
//    ["(3,511)"],
//    ["(3,511)(3,0 )"],
//    ["(3,511)(3,0 )(3,a)", "Read by Claudia McNeil and Norman Matlock."],
//    ["(3,500)"],
//    ["(3,500)(3,  )"],
//    ["(3,500)(3,  )(3,a)", "In one container."],
//    ["(3,500)"],
//    ["(3,500)(3,  )"],
//    ["(3,500)(3,  )(3,a)", "Program notes on container."],
//    ["(3,505)"],
//    ["(3,505)(3,0 )"],
//    ["(3,505)(3,0 )(3,a)", "O ye sons of Africa, Feb. 27, 1833 / Frances Maria Stewart [aka Maria W. Stewart] -- Rather die freemen than live to be slaves, Aug., 1843 / Henry Highland Garnet -- An appeal to the British people, May 12, 1846 / Frederick Douglass -- Atlanta exposition address, Sept. 18, 1893 / Booker T. Washington."],
//    ["(3,650)"],
//    ["(3,650)(3, 0)"],
//    ["(3,650)(3, 0)(3,a)", "African Americans."],
//    ["(3,650)"],
//    ["(3,650)(3, 0)"],
//    ["(3,650)(3, 0)(3,a)", "Slavery"],
//    ["(3,650)(3, 0)(3,z)", "United States."],
//    ["(3,650)"],
//    ["(3,650)(3, 0)"],
//    ["(3,650)(3, 0)(3,a)", "Speeches, addresses, etc., American."],
//    ["(3,655)"],
//    ["(3,655)(3, 7)"],
//    ["(3,655)(3, 7)(3,a)", "Speeches."],
//    ["(3,655)(3, 7)(3,2)", "lcgft"],
//    ["(3,655)"],
//    ["(3,655)(3, 7)"],
//    ["(3,655)(3, 7)(3,a)", "Literary readings."],
//    ["(3,655)(3, 7)(3,2)", "lcgft"],
//    ["(3,655)"],
//    ["(3,655)(3, 7)"],
//    ["(3,655)(3, 7)(3,a)", "Sound recordings."],
//    ["(3,655)(3, 7)(3,2)", "lcgft"],
//    ["(3,700)"],
//    ["(3,700)(3,1 )"],
//    ["(3,700)(3,1 )(3,a)", "Stewart, Maria W.,"],
//    ["(3,700)(3,1 )(3,d)", "1803-1879,"],
//    ["(3,700)(3,1 )(3,e)", "author."],
//    ["(3,700)"],
//    ["(3,700)(3,1 )"],
//    ["(3,700)(3,1 )(3,a)", "Garnet, Henry Highland,"],
//    ["(3,700)(3,1 )(3,d)", "1815-1882,"],
//    ["(3,700)(3,1 )(3,e)", "author."],
//    ["(3,700)"],
//    ["(3,700)(3,1 )"],
//    ["(3,700)(3,1 )(3,a)", "Douglass, Frederick,"],
//    ["(3,700)(3,1 )(3,d)", "1818-1895,"],
//    ["(3,700)(3,1 )(3,e)", "author."],
//    ["(3,700)"],
//    ["(3,700)(3,1 )"],
//    ["(3,700)(3,1 )(3,a)", "Washington, Booker T.,"],
//    ["(3,700)(3,1 )(3,d)", "1856-1915,"],
//    ["(3,700)(3,1 )(3,e)", "author."],
//    ["(3,700)"],
//    ["(3,700)(3,1 )"],
//    ["(3,700)(3,1 )(3,a)", "McNeil, Claudia,"],
//    ["(3,700)(3,1 )(3,e)", "speaker."],
//    ["(3,700)"],
//    ["(3,700)(3,1 )"],
//    ["(3,700)(3,1 )(3,a)", "Matlock, Norman,"],
//    ["(3,700)(3,1 )(3,e)", "speaker."],
//    ["(3,700)"],
//    ["(3,700)(3,1 )"],
//    ["(3,700)(3,1 )(3,a)", "Graham, John,"],
//    ["(3,700)(3,1 )(3,d)", "1926-2007,"],
//    ["(3,700)(3,1 )(3,e)", "editor."],
//  ]

class MARCRecord
{
    // MARC record in the yaz_search 'array' format
    private $record = [];
    private $literary_form_array = [
        "a" => "Art",
        "b" => "Biography",
        3 => "Comedy",
        "c" => "Comic Strip",
        4 => "Cooking",
        "d" => "Drama",
        "e" => "Essay",
        1 => "Fiction",
        "g" => "Geography",
        5 => "Historical",
        6 => "History",
        7 => "Humor",
        "i" => "Letter",
        "l" => "Linguistics",
        8 => "Math",
        9 => "Medicine",
        "m" => "Mixed Form",
        "v" => "Music",
        0 => "Non-Fiction",
        "f" => "Novel",
        "y" => "Periodical",
        "p" => "Poetry",
        "r" => "Romance",
        "z" => "Science",
        "h" => "Satire",
        "j" => "Short Story",
        "s" => "Speech",
        "u" => "Unknown",
        "|" => "Unknown",
    ];

    // https://www.loc.gov/marc/bibliographic/bdleader.html
    private $type_of_record_array = [
        "a" => "Language material", // "resources that are basically textual in nature"
        "c" => "Notated music",
        "d" => "Manuscript notated music",
        "e" => "Cartographic material",
        "f" => "Manuscript cartographic material",
        "g" => "Projected medium",
        "i" => "Nonmusical sound recording",
        "j" => "Musical sound recording",
        "k" => "Two-dimensional nonprojectable graphic",
        "m" => "Computer file",
        "o" => "Kit",
        "p" => "Mixed materials",
        "r" => "Three-dimensional artifact or naturally occurring object",
        "t" => "Manuscript language material", // material in handwriting, typescript, or computer printout including printed materials completed by hand or by keyboard
    ];

    private $force_utf8;

    public function __construct($force_utf8 = true)
    {
        $this->force_utf8 = $force_utf8;
    }

    public function __get($field)
    {
        $func = "get_$field";
        $string = $this->{$func}();
        if ($this->force_utf8) {
            $encoding = guess_string_encoding($string);
            if ($encoding && $encoding != 'UTF-8') {
                $string = mb_convert_encoding($string, "UTF-8", $encoding);
            }
        }
        return $string;
    }

    public function __set($field, $value)
    {
        $func = "set_$field";
        $this->{$func}($value);
    }

    public function load_yaz_array($yaz_array)
    {
        $this->record = $yaz_array;
    }

    public function get_yaz_array()
    {
        return $this->record;
    }

    private function _key_search($tag, $subfield)
    {
        foreach ($this->record as $key => $value) {
            if (!isset($value[0])) {
                continue;
            }

            if (!empty($subfield)) {
                if (preg_match("/^\(3,$tag\)\(3,[0123456789[:space:]]+\)\(3,$subfield\)$/", $value[0])) {
                    return $key;
                }
            } else {
                if (preg_match("/^\(3,$tag\)\(3,[0123456789@[:space:]]+\)$/", $value[0])) {
                    return $key;
                }
            }
        }
    }

    public function get_title()
    {
        // https://www.loc.gov/marc/bibliographic/concise/bd245.html Title Statement
        // Subfield $a - Title
        $title_key = $this->_key_search("245", "a");
        if (isset($this->record[$title_key])) {
            $marc_title = $this->record[$title_key][1];
            $marc_title = trim(preg_replace("/\/$|:$/", "", $marc_title));
        } else {
            $marc_title = '';
        }

        // https://www.loc.gov/marc/bibliographic/concise/bd250.html Edition Statement
        // Subfield $a - Edition statement
        $edition_key = $this->_key_search("250", "a");
        if (isset($this->record[$edition_key])) {
            $marc_edition = $this->record[$edition_key][1];
            $marc_edition = trim($marc_edition);
        } else {
            $marc_edition = '';
        }

        if ($marc_title && $marc_edition) {
            $marc_title = "$marc_title, $marc_edition";
        }

        // Task 849, strip all trailing comma/semicolon/colon from title.
        // Space is needed below as there is one at the end of $marc_title
        return preg_replace('/[,;: ]+$/', '', $marc_title);
    }

    public function get_author()
    {
        $author = "";

        $author_fields = [
            "100", // https://www.loc.gov/marc/bibliographic/concise/bd100.html Main Entry--Personal Name
            "700", // https://www.loc.gov/marc/bibliographic/concise/bd700.html Added Entry--Personal Name
            "710", // https://www.loc.gov/marc/bibliographic/concise/bd710.html Added Entry--Corporate Name
        ];
        foreach ($author_fields as $field) {
            $record_key = $this->_key_search($field, "a");
            if (isset($this->record[$record_key])) {
                $author = $this->record[$record_key][1];
                $author = trim(preg_replace("/,$/", "", $author));
                if ($author) {
                    break;
                }
            }
        }

        return $author;
    }

    public function get_lccn()
    {
        $marc_lccn = "";
        // https://www.loc.gov/marc/bibliographic/concise/bd010.html Library of Congress Control Number
        // Subfield $a - LC control number
        $key = $this->_key_search("010", "a");
        if (isset($this->record[$key])) {
            $marc_lccn = $this->record[$key][1];
            $marc_lccn = trim($marc_lccn);
        }
        return $marc_lccn;
    }

    public function get_isbn()
    {
        $marc_isbn = "";
        // https://www.loc.gov/marc/bibliographic/concise/bd020.html International Standard Book Number
        // Subfield $a - International Standard Book Number
        $key = $this->_key_search("020", "a");
        if (isset($this->record[$key])) {
            $marc_isbn = $this->record[$key][1];
            $marc_isbn = trim(substr($marc_isbn, 0, 10));
        }
        return $marc_isbn;
    }

    public function get_pages()
    {
        $marc_pages = "";
        // https://www.loc.gov/marc/bibliographic/concise/bd300.html Physical Description
        // Subfield $a - Extent
        $key = $this->_key_search("300", "a");
        if (isset($this->record[$key])) {
            $marc_pages = $this->record[$key][1];
            $marc_pages = trim(preg_replace("/:$/", "", $marc_pages));
        }
        return $marc_pages;
    }

    public function get_date()
    {
        $marc_date = "";
        // https://www.loc.gov/marc/bibliographic/concise/bd260.html Publication, Distribution, Etc. (Imprint)
        // Subfield $c - Date of publication, distribution, etc.
        $key = $this->_key_search("260", "c");
        if (isset($this->record[$key])) {
            $marc_date = $this->record[$key][1];
            $marc_date = trim(preg_replace("/.$/", "", $marc_date));
        }
        return $marc_date;
    }

    public function get_type_of_record()
    {
        $lang_name = "";
        // https://www.loc.gov/marc/bibliographic/bdleader.html Leader
        // Character 6 - Type of record
        $leader = $this->record[0][1]; // The leader is always the first record.
        $code = substr($leader, 6, 1);
        return array_get($this->type_of_record_array, $code, "");
    }

    public function get_language()
    {
        $lang_name = "";
        // https://www.loc.gov/marc/bibliographic/bd008.html Fixed-Length Data Elements-General Information
        // Characters 35-37 - Language
        $key = $this->_key_search("008", "");
        if (isset($this->record[$key])) {
            $marc_language = substr($this->record[$key][1], 35, 3);
            $decoded_lang_name = langname_for_langcode3($marc_language);
            if ($decoded_lang_name) {
                $lang_name = $decoded_lang_name;
            }
        }
        return $lang_name;
    }

    public function get_literary_form()
    {
        $literary_form = "";
        $key = $this->_key_search("008", "");
        // https://www.loc.gov/marc/bibliographic/bd008.html Fixed-Length Data Elements-General Information
        // https://www.loc.gov/marc/bibliographic/bd008b.html
        // Character 33 - Literary form
        if (isset($this->record[$key])) {
            $marc_literary_form = substr($this->record[$key][1], 33, 1);
            $marc_literary_form = @$this->literary_form_array[$marc_literary_form];
            $genres = load_genre_translation_array();
            if (array_key_exists($marc_literary_form, $genres)) {
                $literary_form = $marc_literary_form;
            }
        }
        return $literary_form;
    }

    public function get_subject()
    {
        $i = 0;
        $marc_subject = "";
        // https://www.loc.gov/marc/bibliographic/bd650.html Subject Added Entry-Topical Term
        // Any indicator
        // Subfield $a - Topical term or geographic name entry element
        while ($i < count($this->record)) {
            if (preg_match("/^\(3,650\)\(3,([^)]*)\)\(3,a\)$/", @$this->record[$i][0])) {
                $marc_subject .= " ".trim($this->record[$i][1]).",";
            }
            $i++;
        }

        $marc_subject = trim(preg_replace("/,$/", "", $marc_subject));
        return $marc_subject;
    }

    public function get_description()
    {
        $marc_description = "";
        // https://www.loc.gov/marc/bibliographic/bd520.html Summary, Etc
        // Subfield $a - Summary, etc.
        $key = $this->_key_search("520", "a");
        if (isset($this->record[$key])) {
            $marc_description = $this->record[$key][1];
            $marc_description = trim(preg_replace("/:$/", "", $marc_description));
        }
        return $marc_description;
    }

    public function get_publisher()
    {
        $marc_publisher = "";
        // https://www.loc.gov/marc/bibliographic/bd260.html
        // Subfield $b - Name of publisher, distributor, etc.
        // Subfield $c - Date of publication, distribution, etc.
        $publisher_key = $this->_key_search("260", "b");
        if (isset($this->record[$publisher_key])) {
            $marc_publisher = $this->record[$publisher_key][1];
            $marc_publisher = trim(preg_replace("/,$/", "", $marc_publisher));
        }

        $date_key = $this->_key_search("260", "c");
        if (isset($this->record[$date_key])) {
            $date = $this->record[$date_key][1];
            $date = trim(preg_replace("/.$/", "", $date));
            $marc_publisher .= ", $date";
        }

        return $marc_publisher;
    }

    public function set_title($title)
    {
        $this->record[$this->_key_search("245", "a")][1] = $title;
    }

    public function set_author($author)
    {
        $author_fields = ["100", "700", "710"];
        foreach ($author_fields as $field) {
            if (!empty($this->record[$this->_key_search($field, "a")][1])) {
                $this->record[$this->_key_search($field, "a")][1] = $author;
                break;
            }
        }
    }

    public function set_language($language)
    {
        $marc_008 = $this->record[$this->_key_search("008", "")][1];
        $this->record[$this->_key_search("008", "")][1] = substr($marc_008, 0, 35)."$language".substr($marc_008, 38);
    }

    public function set_literary_form($genre)
    {
        $key = array_search($genre, $this->literary_form_array);
        $marc_008 = $this->record[$this->_key_search("008", "")][1];
        $this->record[$this->_key_search("008", "")][1] = substr($marc_008, 0, 33)."$key".substr($marc_008, 34);
    }

    public function __toString()
    {
        $directory = "";
        $data = "";
        $stack = [];

        foreach ($this->record as $tag => $tag_value) {
            if (strlen(@$tag_value[0]) == 7) {
                $i = 1;
                $length = 0;
                $directory .= substr($tag_value[0], 3, 3);

                while (substr(@$this->record[$tag + $i][0], 0, 7) == $tag_value[0] && strlen(@$this->record[$tag + $i][0]) != 7) {
                    if (count($this->record[$tag + $i]) == 1) {
                        if (strlen($this->record[$tag + $i][0]) == 12) {
                            $length++;
                        } else {
                            $length = $length + 2;
                        }
                    } elseif (count($this->record[$tag + $i]) == 2) {
                        if (!preg_match("/^\(3,...\)\(3,@\)$/", $this->record[$tag + $i][0])) {
                            $length = $length + 2;
                        }
                    }
                    $length = $length + strlen(@$this->record[$tag + $i][1]);
                    $i++;
                }

                if (strlen($directory) == 3) {
                    $start = "00000";
                } else {
                    $start = (substr($directory, -12, 4) + substr($directory, -8, 5));
                }

                $start = str_pad($start, 5, "0", STR_PAD_LEFT);
                $length = str_pad($length + 1, 4, "0", STR_PAD_LEFT);
                $directory .= $length.$start;

                foreach ($this->record as $field => $field_value) {
                    if (preg_match("/^\(3,".substr($tag_value[0], 3, 3)."\)\(3,[012[:space:]]+\)$/", $field_value[0]) && !in_array(substr($tag_value[0], 3, 3), $stack)) {
                        $data .= "\x1E".substr($field_value[0], 10, 2);
                    }
                    if (preg_match("/^\(3,".substr($tag_value[0], 3, 3)."\)\(3,[012[:space:]]+\)\(3,.\)$/", $field_value[0]) && !in_array(substr($tag_value[0], 3, 3), $stack)) {
                        $data .= "\x1F".substr($field_value[0], -2, 1).$field_value[1];
                    }
                    if (preg_match("/^\(3,".substr($tag_value[0], 3, 3)."\)\(3,@\)$/", $field_value[0])) {
                        $data .= "\x1E".$field_value[1];
                    }
                }
                array_push($stack, substr($tag_value[0], 3, 3));
            }
        }

        $data .= "\x1E\x1D";
        $leader = str_pad((strlen($directory) + strlen($data) + 24), 5, "0", STR_PAD_LEFT)."cam  22".str_pad((strlen($directory) + 25), 5, "0", STR_PAD_LEFT)." u 4500";
        $raw_marc = $leader.$directory.$data;
        return $raw_marc;
    }
}
