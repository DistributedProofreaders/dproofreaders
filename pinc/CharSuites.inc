<?php
include_once($relPath."unicode.inc");

class CharSuiteNotEnabledException extends Exception
{
}

class PickerSet
{
    public string $name;
    /** @var (?string[])[] */
    private array $subsets;
    /** @var string[] */
    private array $titles;

    /** @param (?string[])[] $codepoints */
    public function add_subset(string $name, array $codepoints, ?string $title = null)
    {
        $this->subsets[$name] = $codepoints;

        if ($title) {
            $this->titles[$name] = $title;
        } else {
            $this->titles[$name] = $name;
        }
    }

    /** @return (?string[])[] */
    public function get_subsets(): array
    {
        return $this->subsets;
    }

    public function get_verbose_subsets(): array
    {
        $verbose_subsets = [];
        foreach ($this->subsets as $subset_name => $codepoint_rows) {
            $verbose_rows = [];
            foreach ($codepoint_rows as $row) {
                $chars = convert_codepoint_ranges_to_characters($row);
                $char_names = [];
                foreach ($chars as $char) {
                    $char_names[] = [$char, (null !== $char) ? utf8_character_name($char) : null];
                }
                $verbose_rows[] = $char_names;
            }
            $verbose_subsets[] = ["name" => (string)$subset_name, "title" => $this->get_title($subset_name), "rows" => $verbose_rows];
        }
        return $verbose_subsets;
    }

    public function get_title(string $name): string
    {
        return $this->titles[$name];
    }
}

/**
 * @property PickerSet $pickerset
 */
class CharSuite
{
    public string $name;
    public string $title;
    /** @var string[] */
    public ?array $codepoints;
    public ?string $description = null;
    public bool $adhoc;
    public $reference_urls = [];
    private ?PickerSet $_pickerset = null;

    /** @param ?string[] $codepoints */
    public function __construct(string $name, string $title, ?array $codepoints = null, bool $adhoc = false)
    {
        $this->name = $name;
        $this->title = $title;
        $this->codepoints = $codepoints;
        $this->adhoc = $adhoc;
    }

    public function __get(string $name): ?PickerSet
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
            E_USER_NOTICE
        );
        return null;
    }

    public function __set(string $name, PickerSet $value)
    {
        if ($name == 'pickerset') {
            $this->_pickerset = $value;
            $this->_pickerset->name = $this->name;
        } else {
            // If we don't recognize the property, raise a notice
            $trace = debug_backtrace();
            trigger_error(
                'Undefined property via __get(): ' . $name .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'],
                E_USER_NOTICE
            );
        }
    }

    public function is_enabled(): bool
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
    /** @var array<string, CharSuite> */
    private static array $_available_charsuites = [];

    private static function load(): void
    {
        // load all available charsuites if not already loaded

        global $relPath;

        if (self::$_available_charsuites) {
            return;
        }

        $charsuite_files = glob($relPath."charsuite-*.inc");
        foreach ($charsuite_files as $charsuite_file) {
            include($charsuite_file);
        }
    }

    public static function add(CharSuite $charsuite): void
    {
        self::$_available_charsuites[$charsuite->name] = $charsuite;
    }

    public static function get(string $name): CharSuite
    {
        self::load();

        if (!isset(self::$_available_charsuites[$name])) {
            throw new UnexpectedValueException("$name is not a valid charsuite");
        }
        return self::$_available_charsuites[$name];
    }

    /** @return CharSuite[] */
    public static function get_all(): array
    {
        self::load();

        return array_values(self::$_available_charsuites);
    }

    /** @return CharSuite[] */
    public static function get_enabled(): array
    {
        $sql = "
            SELECT name
            FROM charsuites
            WHERE enabled=1
        ";

        $result = DPDatabase::query($sql);

        $charsuites = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $charsuites[] = CharSuites::get($row["name"]);
        }
        mysqli_free_result($result);
        return $charsuites;
    }

    public static function enable(string $name): void
    {
        // Validate we have a valid charsuite
        $charsuite = CharSuites::get($name);

        $sql = sprintf(
            "
            INSERT INTO charsuites
            SET name='%s', enabled=1
            ON DUPLICATE KEY UPDATE
                enabled=1
            ",
            DPDatabase::escape($name)
        );

        DPDatabase::query($sql);
    }

    public static function disable(string $name): void
    {
        // We don't validate the charsuite is installed in case it was
        // uninstalled before being disabled.
        $sql = sprintf(
            "
            UPDATE charsuites
            SET enabled=0
            WHERE name='%s'
            ",
            DPDatabase::escape($name)
        );

        DPDatabase::query($sql);
    }

    /**
     * Resolve $charsuite to a CharSuite object
     *
     * Check to see if `$charsuite` is a real CharSuite object or a charsuite
     * name and if the latter, return a CharSuite object of that name.
     *
     * @param string|CharSuite $charsuite Character suite name or object
     */
    public static function resolve($charsuite): CharSuite
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
    public function get_verbose_pickersets(): array
    {
        $verbose_pickersets = [];
        foreach ($this->get_pickersets() as $pickerset) {
            $verbose_pickersets[] = [
                "name" => $pickerset->name,
                "subsets" => $pickerset->get_verbose_subsets(),
            ];
        }
        return $verbose_pickersets;
    }

    /** @return PickerSet[] */
    public function get_pickersets(): array
    {
        $pickersets = [];
        foreach ($this->get_charsuites() as $charsuite) {
            $pickersets[] = $charsuite->pickerset;
        }
        return $pickersets;
    }

    /** @return string[] */
    public function get_valid_codepoints(): array
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

/** @return QuizCharSuites|Project */
function get_project_or_quiz(string $identifier)
{
    if ($identifier == "quiz") {
        return new QuizCharSuites();
    } else {
        return new Project($identifier);
    }
}
