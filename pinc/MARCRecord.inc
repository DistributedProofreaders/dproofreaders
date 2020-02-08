<?php
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'genres.inc'); // load_genre_translation_array

class MARCRecord
{
    // MARC record in the yaz_search 'array' format
    private $record = array();
    private $literary_form_array = array("a" => "Art", "b" => "Biography", 3 => "Comedy", "c" => "Comic Strip", 4 => "Cooking", "d" => "Drama", "e" => "Essay", 1 => "Fiction", "g" => "Geography", 5 => "Historical", 6 => "History", 7 => "Humor", "i" => "Letter", "l" => "Linguistics", 8 => "Math", 9 => "Medicine", "m" => "Mixed Form", "v" => 'Music', 0 => "Non-Fiction", "f" => "Novel", "y" => "Periodical", "p" => "Poetry", "r" => "Romance", "z" => "Science", "h" => "Satire", "j" => "Short Story", "s" => "Speech", "u" => "Unknown", "|" => "Unknown");

    public function __construct()
    {
    }

    public function __get($field)
    {
        $func = "get_$field";
        return $this->{$func}();
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

    private function _key_search($tag, $subfield) {
        foreach ($this->record as $key => $value) {
            if(!isset($value[0]))
                continue;

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
        $title_key = $this->_key_search("245","a");
        if(isset($this->record[$title_key]))
        {
            $marc_title = $this->record[$title_key][1];
            $marc_title = trim(preg_replace("/\/$|:$/", "", $marc_title));
        }
        else
        {
            $marc_title = '';
        }

        $edition_key = $this->_key_search("250","a");
        if(isset($this->record[$edition_key]))
        {
            $marc_edition = $this->record[$edition_key][1];
            $marc_edition = trim($marc_edition);
        }
        else
        {
            $marc_edition = '';
        }

        if($marc_title && $marc_edition)
        {
            $marc_title = "$marc_title, $marc_edition";
        }

        // Task 849, strip all trailing comma/semicolon/colon from title.
        // Space is needed below as there is one at the end of $marc_title
        return preg_replace('/[,;: ]+$/', '', $marc_title);
    }

    public function get_author()
    {
        $arthor = "";

        $author_fields = array("100", "700", "710");
        foreach($author_fields as $field)
        {
            $record_key = $this->_key_search($field, "a");
            if(isset($this->record[$record_key]))
            {
                $arthor = $this->record[$record_key][1];
                $arthor = trim(preg_replace("/,$/", "", $arthor));
                if($arthor)
                    break;
            }
        }

        return $arthor;
    }

    public function get_lccn()
    {
        $marc_lccn = "";
        $key = $this->_key_search("010", "a");
        if(isset($this->record[$key]))
        {
            $marc_lccn = $this->record[$key][1];
            $marc_lccn = trim($marc_lccn);
        }
        return $marc_lccn;
    }

    public function get_isbn()
    {
        $marc_isbn = "";
        $key = $this->_key_search("020", "a");
        if(isset($this->record[$key]))
        {
            $marc_isbn = $this->record[$key][1];
            $marc_isbn = trim(substr($marc_isbn, 0, 10));
        }
        return $marc_isbn;
    }

    public function get_pages()
    {
        $marc_pages = "";
        $key = $this->_key_search("300", "a");
        if(isset($this->record[$key]))
        {
            $marc_pages = $this->record[$key][1];
            $marc_pages = trim(preg_replace("/:$/", "", $marc_pages));
        }
        return $marc_pages;
    }

    public function get_date()
    {
        $marc_date = "";
        $key = $this->_key_search("260", "c");
        if(isset($this->record[$key]))
        {
            $marc_date = $this->record[$key][1];
            $marc_date = trim(preg_replace("/.$/", "", $marc_date));
        }
        return $marc_date;
    }

    public function get_language()
    {
        $lang_name = "";
        $key = $this->_key_search("008", "");
        if(isset($this->record[$key]))
        {
            $marc_language = substr($this->record[$key][1], 35, 3);
            $decoded_lang_name = langname_for_langcode3($marc_language);
            if($decoded_lang_name)
            {
                $lang_name = $decoded_lang_name;
            }
        }
        return $lang_name;
    }

    public function get_literary_form()
    {
        $literary_form = "";
        $key = $this->_key_search("008", "");
        if(isset($this->record[$key]))
        {
            $marc_literary_form = substr($this->record[$key][1], 33, 1);
            $marc_literary_form = @$this->literary_form_array[$marc_literary_form];
            $genres = load_genre_translation_array();
            if(array_key_exists($marc_literary_form, $genres))
            {
                $literary_form = $marc_literary_form;
            }
        }
        return $literary_form;
    }

    public function get_subject()
    {
        $i = 0;
        $marc_subject = "";

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
        $key = $this->_key_search("520", "a");
        if(isset($this->record[$key]))
        {
            $marc_description = $this->record[$key][1];
            $marc_description = trim(preg_replace("/:$/", "", $marc_description));
        }
        return $marc_description;
    }

    public function get_publisher()
    {
        $marc_publisher = "";
        $publisher_key = $this->_key_search("260", "b");
        if(isset($this->record[$publisher_key]))
        {
            $marc_publisher = $this->record[$publisher_key][1];
            $marc_publisher = trim(preg_replace("/,$/", "", $marc_publisher));
        }

        $date_key = $this->_key_search("260", "c");
        if(isset($this->record[$date_key]))
        {
            $date = $this->record[$date_key][1];
            $date = trim(preg_replace("/.$/", "", $date));
            $marc_publisher .= ", $date";
        }

        return $marc_publisher;
    }

    public function set_title($title)
    {
        $this->record[$this->_key_search("245","a")][1] = $title;
    }

    public function set_author($author)
    {
        $author_fields = array("100", "700", "710");
        foreach($author_fields as $field)
        {
            if (!empty($this->record[$this->_key_search($field,"a")][1])) {
                $this->record[$this->_key_search($field,"a")][1] = $author;
                break;
            }
        }
    }

    public function set_language($language)
    {
        $marc_008 = $this->record[$this->_key_search("008","")][1];
        $this->record[$this->_key_search("008","")][1] = substr($marc_008, 0, 35)."$language".substr($marc_008, 38);
    }

    public function set_literary_form($genre)
    {
        $key = array_search($genre, $this->literary_form_array);
        $marc_008 = $this->record[$this->_key_search("008","")][1];
        $this->record[$this->_key_search("008","")][1] = substr($marc_008, 0, 33)."$key".substr($marc_008, 34);
    }

    public function __toString() {
        $directory = "";
        $data = "";
        $stack = array();

        foreach ($this->record as $tag => $tag_value) {
            if (strlen(@$tag_value[0]) == 7) {
                $i = 1;
                $length = 0;
                $directory .= substr($tag_value[0], 3, 3);

                while (substr(@$this->record[$tag+$i][0], 0, 7) == $tag_value[0] && strlen(@$this->record[$tag+$i][0]) != 7) {
                    if (count($this->record[$tag+$i]) == 1) {
                        if (strlen($this->record[$tag+$i][0]) == 12) { $length++; } else { $length = $length + 2; }
                    } elseif (count($this->record[$tag+$i]) == 2) {
                        if (!preg_match("/^\(3,...\)\(3,@\)$/", $this->record[$tag+$i][0])) { $length = $length + 2; }
                    }
                    $length = $length + strlen(@$this->record[$tag+$i][1]);
                    $i++;
                }

                if (strlen($directory) == 3) { $start = "00000"; } else { $start = (substr($directory, -12, 4) + substr($directory, -8, 5)); }

                $start = str_pad($start, 5, "0", STR_PAD_LEFT);
                $length = str_pad($length+1, 4, "0", STR_PAD_LEFT);
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
        $leader = str_pad((strlen($directory)+strlen($data)+24), 5, "0", STR_PAD_LEFT)."cam  22".str_pad((strlen($directory)+25), 5, "0", STR_PAD_LEFT)." u 4500";
        $raw_marc = $leader.$directory.$data;
        return $raw_marc;
    }
}
