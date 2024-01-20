<?php

include_once($relPath.'Stage.inc');

// -----------------------------------------------------------------------------

global $Pool_for_id_, $Pool_for_state_;
$Pool_for_id_ = [];
$Pool_for_state_ = [];

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
        $project_available_state,
        $project_checkedout_state,
        $available_short_label,
        $available_long_label,
        $checked_out_short_label,
        $checked_out_long_label,
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

        $this->project_available_state = $project_available_state;
        $this->project_checkedout_state = $project_checkedout_state;
        $this->available_short_label = $available_short_label;
        $this->available_long_label = $available_long_label;
        $this->checked_out_short_label = $checked_out_short_label;
        $this->checked_out_long_label = $checked_out_long_label;
        $this->foo_Header = $foo_Header;
        $this->foo_field_name = $foo_field_name;
        $this->blather = $blather;

        global $Pool_for_id_;
        $Pool_for_id_[$id] = $this;

        global $Pool_for_state_;
        $Pool_for_state_[$project_available_state] = $this;
        $Pool_for_state_[$project_checkedout_state] = $this;
    }
}

// ---------------------------

function get_Pool_for_id($pool_id)
{
    global $Pool_for_id_;
    return array_get($Pool_for_id_, $pool_id, null);
}

function get_Pool_for_state($state)
{
    global $Pool_for_state_;
    return array_get($Pool_for_state_, $state, null);
}

// ---------------------------
