<?php
include_once($relPath."unicode.inc");

class CharSuiteNotEnabledException extends Exception
{
}

class PickerSet
{
    public $name;

    public function add_subset($name, $codepoints, $title = null)
    {
        $this->subsets[$name] = $codepoints;

        if ($title) {
            $this->titles[$name] = $title;
        } else {
            $this->titles[$name] = $name;
        }
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

class CharSuite
{
    public $name;
    public $title;
    public $codepoints;
    public $description;
    public $adhoc;
    public $reference_urls = [];
    private $_pickerset = null;

    public function __construct($name, $title, $codepoints = null, $adhoc = false)
    {
        $this->name = $name;
        $this->title = $title;
        $this->codepoints = $codepoints;
        $this->adhoc = $adhoc;
    }

    public function __get($name)
    {
        if ($name == 'pickerset') {
            return $this->_pickerset;
        }

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
        if ($name == 'pickerset') {
            $this->_pickerset = $value;
            $this->_pickerset->name = $this->name;
        } else {
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
        if ($this->adhoc) {
            return true;
        }

        $enabled_sets = CharSuites::get_enabled();
        return in_array($this, $enabled_sets);
    }

    public function get_nonnormalized_codepoints()
    {
        return get_nonnormalized_codepoints($this->codepoints);
    }
}

class CharSuites
{
    private static $_available_charsuites = [];

    public static function add($charsuite)
    {
        self::$_available_charsuites[$charsuite->name] = $charsuite;
    }

    public static function get($name)
    {
        if (!isset(self::$_available_charsuites[$name])) {
            throw new UnexpectedValueException("$name is not a valid charsuite");
        }
        return self::$_available_charsuites[$name];
    }

    public static function get_all()
    {
        return array_values(self::$_available_charsuites);
    }

    public static function get_enabled()
    {
        $sql = "
            SELECT name
            FROM charsuites
            WHERE enabled=1
        ";

        $result = mysqli_query(DPDatabase::get_connection(), $sql);

        $charsuites = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $charsuites[] = CharSuites::get($row["name"]);
        }
        mysqli_free_result($result);
        return $charsuites;
    }

    public static function enable($name)
    {
        // Validate we have a valid charsuite
        $charsuite = CharSuites::get($name);

        $sql = sprintf("
            INSERT INTO charsuites
            SET name='%s', enabled=1
            ON DUPLICATE KEY UPDATE
                enabled=1
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $name));

        mysqli_query(DPDatabase::get_connection(), $sql);
    }

    public static function disable($name)
    {
        // We don't validate the charsuite is installed in case it was
        // uninstalled before being disabled.
        $sql = sprintf("
            UPDATE charsuites
            SET enabled=0
            WHERE name='%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $name));

        mysqli_query(DPDatabase::get_connection(), $sql);
    }

    // Check to see if $charsuite is a real CharSuite object or a charsuite name
    // and if the latter, return the former.
    public static function resolve($charsuite)
    {
        if ($charsuite instanceof CharSuite) {
            return $charsuite;
        } else {
            return CharSuites::get($charsuite);
        }
    }
}

//----------------------------------------------------------------------------

trait CharSuiteSet
{
    public function get_pickersets()
    {
        $pickersets = [];
        foreach ($this->get_charsuites() as $charsuite) {
            $pickersets[] = $charsuite->pickerset;
        }
        return $pickersets;
    }

    public function get_valid_codepoints()
    {
        // Codepoints applicable to all projects
        $global_codepoints = [
            'U+000A', // new line
            'U+000D', // carriage return
            'U+0020', // space
        ];

        $codepoints = $global_codepoints;
        foreach ($this->get_charsuites() as $charsuite) {
            $codepoints = array_merge($codepoints, $charsuite->codepoints);
        }
        return $codepoints;
    }
}

function get_project_or_quiz($identifier)
{
    if ($identifier == "quiz") {
        return new QuizCharSuites();
    } else {
        return new Project($identifier);
    }
}

//----------------------------------------------------------------------------

// load all available charsuites
$charsuite_files = glob($relPath."charsuite-*.inc");
foreach ($charsuite_files as $charsuite_file) {
    include_once($charsuite_file);
}
