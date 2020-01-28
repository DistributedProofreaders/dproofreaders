<?php
include_once($relPath."unicode.inc");

class GlyphsetNotEnabledException extends Exception { }

class PickerSet
{
    public $name;

    public function add_subset($name, $codepoints, $title=NULL)
    {
        $this->subsets[$name] = $codepoints;

        if($title)
            $this->titles[$name] = $title;
        else
            $this->titles[$name] = $name;
    }

    public function get_subsets()
    {
        return $this->subsets;
    }

    public function get_title($name)
    {
        return $this->titles[$name];
    }
}

class Glyphset
{
    public $name;
    public $title;
    public $codepoints;
    public $reference_urls = [];
    private $_pickerset = NULL;

    function __construct($name, $title, $codepoints=NULL)
    {
        $this->name = $name;
        $this->title = $title;
        $this->codepoints = $codepoints;
    }

    public function __get($name)
    {
        if($name == 'pickerset')
            return $this->_pickerset;

        // If we don't recognize the property, raise a notice and return null
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function __set($name, $value)
    {
        if($name == 'pickerset')
        {
            $this->_pickerset = $value;
            $this->_pickerset->name = $this->name;
        }
        else
        {
            // If we don't recognize the property, raise a notice and return null
            $trace = debug_backtrace();
            trigger_error(
                'Undefined property via __get(): ' . $name .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'],
                E_USER_NOTICE);
            return null;
        }
    }

    public function is_enabled()
    {
        $enabled_sets = Glyphsets::get_enabled();
        return in_array($this->name, $enabled_sets);
    }
}

class Glyphsets
{
    static private $_available_glyphsets = [];

    public static function add_glyphset($glyphset, $set='default')
    {
        if(!isset(self::$_available_glyphsets[$set]))
            self::$_available_glyphsets[$set] = [];
        self::$_available_glyphsets[$set][$glyphset->name] = $glyphset;
    }

    public static function get_glyphsets($set='default')
    {
        if(!isset(self::$_available_glyphsets[$set]))
            return [];
        return self::$_available_glyphsets[$set];
    }

    public static function get_glyphset($name, $set='default')
    {
        if(!isset(self::$_available_glyphsets[$set][$name]))
        {
            throw new UnexpectedValueException("$name is not a valid glyphset");
        }
        return self::$_available_glyphsets[$set][$name];
    }

    public static function get_enabled()
    {
        $sql = "
            SELECT name
            FROM glyphsets
            WHERE enabled=1
        ";

        $result = mysqli_query(DPDatabase::get_connection(), $sql);

        $glyphsets = [];
        while($row = mysqli_fetch_assoc($result))
        {
            $glyphsets[] = $row["name"];
        }
        mysqli_free_result($result);
        return $glyphsets;
    }

    public static function enable($name)
    {
        // Validate we have a valid glyphset
        $glyphset = Glyphsets::get_glyphset($name);

        $sql = sprintf("
            INSERT INTO glyphsets
            SET name='%s', enabled=1
            ON DUPLICATE KEY UPDATE
                enabled=1
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $name));

        mysqli_query(DPDatabase::get_connection(), $sql);
    }

    public static function disable($name)
    {
        // We don't validate the glyphset is installed in case it was
        // uninstalled before being disabled.
        $sql = sprintf("
            UPDATE glyphsets
            SET name='%s', enabled=0
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $name));

        mysqli_query(DPDatabase::get_connection(), $sql);
    }
}

#----------------------------------------------------------------------------

// load all available glyphsets
$glyphset_files = glob($relPath."glyphset-*.inc");
foreach($glyphset_files as $glyphset_file)
{
    include_once($glyphset_file);
}
