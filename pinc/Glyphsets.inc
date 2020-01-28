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
        return in_array($this, $enabled_sets);
    }
}

class Glyphsets
{
    static private $_available_glyphsets = [];

    public static function add($glyphset)
    {
        self::$_available_glyphsets[$glyphset->name] = $glyphset;
    }

    public static function get($name)
    {
        if(!isset(self::$_available_glyphsets[$name]))
        {
            throw new UnexpectedValueException("$name is not a valid glyphset");
        }
        return self::$_available_glyphsets[$name];
    }

    public static function get_all()
    {
        return array_values(self::$_available_glyphsets);
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
            $glyphsets[] = Glyphsets::get($row["name"]);
        }
        mysqli_free_result($result);
        return $glyphsets;
    }

    public static function enable($name)
    {
        // Validate we have a valid glyphset
        $glyphset = Glyphsets::get($name);

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
            SET enabled=0
            WHERE name='%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $name));

        mysqli_query(DPDatabase::get_connection(), $sql);
    }

    // Check to see if $glyphset is a real Glyphset object or a glyphset name
    // and if the latter, return the former.
    public static function resolve($glyphset)
    {
        if($glyphset instanceof Glyphset)
        {
            return $glyphset;
        }
        else
        {
            return Glyphsets::get($glyphset_name);
        }
    }
}

#----------------------------------------------------------------------------

// load all available glyphsets
$glyphset_files = glob($relPath."glyphset-*.inc");
foreach($glyphset_files as $glyphset_file)
{
    include_once($glyphset_file);
}
