<?php
// This file defines the class 'Stage', which (depending on your point of view)
// represents:
// -- a stage in the life of a project, or
// -- the set of all projects currently in that stage, or
// -- an area that houses that set of projects.
//
// It's a generalization of the existing ideas of 'round' and 'pool',
// and a specialization of the concept of 'Activity'.

include_once($relPath.'Activity.inc');
include_once($relPath.'misc.inc'); // attr_safe

$Stage_for_id_ = array();

class Stage extends Activity
{
    function __construct(
        $id,
        $name,
        $access_minima,
        $after_satisfying_minima,
        $evaluation_criteria,
        $access_change_callback,
            // The above parameters have the same semantics as for Activity.

        $description,
            // A sentence or two explaining what happens in this stage.
        $document,
            // The path (relative to $code_url/faq/) of a document that tells you
            // everything you need to know about working in this stage.
            // Or NULL if there is no such document.
        $relative_url
            // The "home" location (relative to $code_url/) of the stage.
    )
    {
        parent::__construct(
            $id,
            $name,
            $access_minima,
            $after_satisfying_minima,
            $evaluation_criteria,
            $access_change_callback
        );

        $this->description   = $description;
        $this->document      = $document;
        $this->relative_url  = $relative_url;

        global $Stage_for_id_;
        $Stage_for_id_[$id] =& $this;
    }


    function page_header( $title )
    // Display a page-header, either an image (if available) or a textual title
    // for this stage.
    {
        $header_string = get_translated_graphic_or_text(
            "page_header", $this->id, $title);
        echo "<h1>$header_string</h1>\n";
    }

    function page_top( $uao )
    {
        $this->page_header( "{$this->id}: {$this->name}" );

        if ( !$uao->can_access )
        {
            echo "<p>";
            echo sprintf( _("Welcome to %s!"), $this->id ), "\n";
            echo _("Feel free to explore this stage."), "\n";
            echo _("You can find out what happens here, and follow the progress of projects from earlier rounds."), "\n";
            echo _("If you're interested in working in this stage, see below to find out how you can qualify."), "\n";
            echo "</p>";
            echo "\n";
        }

        echo "<p>";
        echo "<b>";
        echo _('What happens in this stage');
        echo ":</b><br>";
        echo $this->description;
        echo "</p>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function & get_Stage_for_id( $id )
{
    global $Stage_for_id_;
    if ( array_key_exists( $id, $Stage_for_id_ ) )
    {
        return $Stage_for_id_[$id];
    }
    else
    {
        die( "There is no stage with id='$id'." );
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function user_can_work_in_stage( $username, $stage_id )
{
    $stage = get_Stage_for_id($stage_id);
    $uao = $stage->user_access($username);
    return $uao->can_access;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_stages_user_can_work_in( $username )
{
    global $Stage_for_id_;

    $accessible_stages = array();
    foreach ( $Stage_for_id_ as $stage )
    {
        $uao = $stage->user_access($username);
        if($uao->can_access)
        {
            $accessible_stages[$stage->id] = $stage;
        }
    }

    return $accessible_stages;
}

function get_stages_for_which_user_has_access_to_prereqs( $username, $include_accessible_stages = FALSE )
// Return an array of stages for which the user has access to all prereq
// stages. If $include_accessible_stages is true, this array also includes
// stages the user has access to.
{
    global $Stage_for_id_;

    $satisfied_prereq_stages = array();

    // get stages that the user can currently access
    $accessible_stages = get_stages_user_can_work_in($username);

    // if the caller wants the accessible stages too (not just the prereq
    // stages) start with the accessible list
    if($include_accessible_stages)
    {
        $satisfied_prereq_stages = $accessible_stages;
    }

    foreach ( $Stage_for_id_ as $stage )
    {
        // skip stages for which the user already has access
        if(isset($accessible_stages[$stage->id]))
        {
            continue;
        }

        // get all pre-reqs for this stage
        $prereq_criteria = array();
        foreach($stage->access_minima as $criterion => $value)
        {
            $prereq_criteria = array_merge($prereq_criteria, explode('+',$criterion));
        }
        $prereq_criteria = array_unique($prereq_criteria);

        // don't include rounds that have no round access requirements (eg: PPV)
        if(count($prereq_criteria) == 0)
        {
            $has_access_to_all_prereq_stages = false;
        }
        else
        {
            // check to see if the user has access to all pre-req stages
            // assume they do and see about disproving it
            $has_access_to_all_prereq_stages = true;

            foreach($prereq_criteria as $prereq_criterion)
            {
                // check that the criterion in question is a stage
                // and that the user can access it
                if(@$Stage_for_id_[$prereq_criterion] && !isset($accessible_stages[$prereq_criterion]))
                {
                    $has_access_to_all_prereq_stages = false;
                    break;
                }
            }
        }

        if($has_access_to_all_prereq_stages)
        {
            $satisfied_prereq_stages[$stage->id] = $stage;
        }
    }

    return $satisfied_prereq_stages;
}

// vim: sw=4 ts=4 expandtab
?>
