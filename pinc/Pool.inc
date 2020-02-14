<?php

include_once($relPath.'misc.inc');
include_once($relPath.'Stage.inc');

// -----------------------------------------------------------------------------

$Pool_for_id_ = array();
$Pool_for_state_ = array();

class Pool extends Stage
// A container for various constants relating to a particular pool.
{
    function __construct(
        $pool_id,
        $pool_name,
        $access_minima,
        $after_satisfying_minima,
        $evaluation_criteria,
        $access_change_callback,
        $description,
        $document,
        $listing_bgcolors,
            // These parameters have the same semantics as the
            // corresponding parameters of the Stage constructor.
            // See Stage.inc for documentation.

        $project_checkedout_state,
        $project_available_state,
            // Eventually, these will be generated based on $pool_id

        $foo_Header,
        $foo_field_name,
            // The relevant person to display in pool listings, both
            // as shown to the user and as a field in the projects table
            // (e.g. "postprocessor" when listing books available for PPV). 
            // Used by pinc/showavailablebooks.inc

        $blather
            // An array of strings to echo on the pool's home page.
    )
    {
        parent::__construct(
            $pool_id,
            $pool_name,
            $access_minima,
            $after_satisfying_minima,
            $evaluation_criteria,
            $access_change_callback,
            $description,
            $document,
            $listing_bgcolors,
            "tools/pool.php?pool_id=$pool_id"
        );

        $this->project_checkedout_state = $project_checkedout_state;
        $this->project_available_state  = $project_available_state;
        $this->foo_Header               = $foo_Header;
        $this->foo_field_name           = $foo_field_name;
        $this->blather                  = $blather;

        global $Pool_for_id_;
        $Pool_for_id_[$this->id] =& $this;

        global $Pool_for_state_;
        $Pool_for_state_[$this->project_checkedout_state] =& $this;
        $Pool_for_state_[$this->project_available_state]  =& $this;
    }
}

// ---------------------------

function get_Pool_for_id( $pool_id )
{
    global $Pool_for_id_;
    return array_get( $Pool_for_id_, $pool_id, NULL );
}

function get_Pool_for_state( $state )
{
    global $Pool_for_state_;
    return array_get( $Pool_for_state_, $state, NULL );
}

// ---------------------------

// vim: sw=4 ts=4 expandtab
?>
