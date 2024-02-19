<?php
include_once($relPath.'Stage.inc');

/*
 * A singleton container class for accessing Pool objects.
 * See the Activities class definition for more information.
 */
class Pools extends Stages
{
    public static function get_by_state($state)
    {
        return self::_get("states", $state);
    }
}

class Pool extends Stage
{
    /**
     * Pool constructor
     *
     * A container for various constants relating to a particular pool.
     *
     * @param string $project_checkedout_state
     * @param string $project_available_state
     * @param $foo_Header
     * @param $foo_field_name
     *   The relevant person to display in pool listings, both
     *   as shown to the user and as a field in the projects table
     *   (e.g. "postprocessor" when listing books available for PPV).
     *   Used by `pinc/showavailablebooks.inc`
     * @param $blather
     *   An array of strings to echo on the pool's home page.
     */
    public function __construct(
        $id,
        $name,
        $access_minima,
        $after_satisfying_minima,
        $evaluation_criteria,
        $access_change_callback,
        $description,
        $document,
        $foo_Header,
        $foo_field_name,
        $blather
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

        $this->foo_Header = $foo_Header;
        $this->foo_field_name = $foo_field_name;
        $this->blather = $blather;
    }
}

// ---------------------------

function get_Pool_for_id($pool_id)
{
    return Pools::get_by_id($pool_id);
}

function get_Pool_for_state($state)
{
    return Pools::get_by_state($state);
}
