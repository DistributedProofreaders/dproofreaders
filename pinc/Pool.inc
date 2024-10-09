<?php
include_once($relPath.'Stage.inc');

/*
 * A singleton container class for accessing Pool objects.
 * See the Activities class definition for more information.
 */
class Pools extends Stages
{
    public static function get_by_state(string $state): ?Pool
    {
        return self::_get("states", $state);
    }
}

class Pool extends Stage
{
    public string $foo_Header;
    public string $foo_field_name;
    /** @var string[] */
    public array $blather;
    public string $project_available_state;
    public string $project_checkedout_state;
    /** @var string[] */
    public array $states = [];

    /**
     * Pool constructor
     *
     * A container for various constants relating to a particular pool.
     *
     * @param string $foo_Header
     * @param string $foo_field_name
     *   The relevant person to display in pool listings, both
     *   as shown to the user and as a field in the projects table
     *   (e.g. "postprocessor" when listing books available for PPV).
     *   Used by `pinc/showavailablebooks.inc`
     * @param string[] $blather
     *   An array of strings to echo on the pool's home page.
     */
    public function __construct(
        string $id,
        string $name,
        /** @var array<string,int> */
        array $access_minima,
        string $after_satisfying_minima,
        string $evaluation_criteria,
        ?string $access_change_callback,
        string $description,
        ?string $document,
        string $foo_Header,
        string $foo_field_name,
        array $blather
    ) {
        parent::__construct(
            $id,
            $name,
            $access_minima,
            $after_satisfying_minima,
            $evaluation_criteria,
            $access_change_callback,
            $description,
            $document,
            "tools/pool.php?pool_id=$id"
        );

        // The following project-state related attributes are created & set in project_states.inc
        //     $this->project_checkedout_state
        //     $this->project_available_state
        //     $this->states

        $this->foo_Header = $foo_Header;
        $this->foo_field_name = $foo_field_name;
        $this->blather = $blather;
    }
}

// ---------------------------

function get_Pool_for_id(string $pool_id): ?Pool
{
    return Pools::get_by_id($pool_id);
}

function get_Pool_for_state(string $state): ?Pool
{
    return Pools::get_by_state($state);
}
