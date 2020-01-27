<?php
include_once($relPath."unicode.inc");

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
}

#----------------------------------------------------------------------------

// load all available glyphsets
$glyphset_files = glob($relPath."glyphset-*.inc");
foreach($glyphset_files as $glyphset_file)
{
    include_once($glyphset_file);
}
