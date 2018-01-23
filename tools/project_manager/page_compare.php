<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath."DPage.inc"); // remove_formatting()

require_login();

$L_round_options = array('P1', 'P2', 'P3', 'F1');
$R_round_options = array('F1', 'F2');

$projectid = validate_projectID('project', @$_GET['project']);
$L_round_id = get_enumerated_param($_GET, "L_round_id", "P3", $L_round_options);
$R_round_id = get_enumerated_param($_GET, "R_round_id", "F1", $R_round_options);
$page_set = get_enumerated_param($_GET, "page_set", "all", array('left', 'right', 'all'));
$go_compare = isset($_GET['compare']);

$project = new Project($projectid);

$state = $project->state;
$title = _('Compare pages with formatting removed');
$sub_title = $project->nameofwork;

output_header("$title: $sub_title", NO_STATSBAR);

echo "<h1>$title</h1>\n";
echo "<h2>$sub_title</h2>\n";

$project_url = "$code_url/project.php?id=$projectid&amp;expected_state=$state";

$label = _("Return to Project Page");
echo "<p><a href='$project_url'>$label</a></p>\n";

if (!$project->pages_table_exists)
{
    echo "<p class='warning'>";
    if ($project->archived != 0)
    {
        echo _("The project has been archived, so page details are not available.");
    }
    elseif ($project->state == PROJ_DELETE)
    {
        echo _("The project has been deleted, so page details are not available.");
    }
    else
    {
        echo _("Page details are not available for this project.");
    }
    echo "</p>";
    exit();
}

$round_selector = new RoundSelector($page_set);
$round_selector->render($projectid, $L_round_id, $R_round_id);

// if this is first entry don't do anything else
if(!$go_compare)
{
    exit();
}

$state_index = array_flip($PROJECT_STATES_IN_ORDER);

if(!has_project_started_round($L_round_id, $state_index, $project) || !has_project_started_round($R_round_id, $state_index, $project))
{
    exit();
}

$L_round = get_Round_for_round_id($L_round_id);
$R_round = get_Round_for_round_id($R_round_id);
$L_text_column_name = $L_round->text_column_name;
$R_text_column_name = $R_round->text_column_name;
$L_round_num = $L_round->round_number;
$R_round_num = $R_round->round_number;

$right_complete = ($state_index[$project->state] >= $state_index["{$R_round_id}.proj_done"]);
$username = $pguser;

switch($page_set) {
    case 'right':
        $condition = "$R_round->user_column_name = '$username'";
        break;
    case 'left':
        $condition = "$L_round->user_column_name = '$username'";
        break;
    default: // all
        $condition = "1";
        break;
}

if(!$right_complete)
{
    $condition .= " AND state='$R_round->page_save_state'";
}

$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT image, $L_text_column_name, $R_text_column_name
    FROM $projectid
    WHERE $condition
    ORDER BY image ASC
") or die(mysqli_error(DPDatabase::get_connection()));

$num_rows = mysqli_num_rows($res);
if($num_rows == 0)
{
    echo "<p>", _("There are no pages to compare"), "</p>\n";
    exit();
}
else
{
    echo "<p>", sprintf(_("Comparing %d pages"), $num_rows), "</p>\n";
}

// make an array of imagenames of pages with diffs
$diff_pages = array();
while($page_res = mysqli_fetch_assoc($res))
{
    // also unwrap
    $L_text = remove_formatting($page_res[$L_text_column_name], true);
    $R_text = remove_formatting($page_res[$R_text_column_name], true);
    if(0 != strcmp($L_text, $R_text))
    {
        $diff_pages[] = $page_res['image'];
    }
}

if(empty($diff_pages))
{
    echo "<p>", _("There are no differences"), "</p>\n";
    exit();
}

// Draw the results
echo "<p>", _("Clicking on a link will show the differences in a new window or tab."), "</p>\n";
echo "<p>";
foreach($diff_pages as $imagename)
{
    echo "<a href='$code_url/tools/project_manager/diff.php?project=$projectid&amp;image=$imagename&amp;L_round_num=$L_round_num&amp;R_round_num=$R_round_num&amp;format=remove' target='_blank'>$imagename</a>\n";
}
echo "</p>";

function has_project_started_round($round_id, $state_index, $project)
{
    if($state_index[$project->state] < $state_index["{$round_id}.proj_avail"])
    {
        echo "<p>", sprintf(_("%s has not started"), $round_id), "</p>";
        return false;
    }
    return true;
}

class RoundSelector
{
    function __construct($page_set)
    {
        $this->page_set = $page_set;
    }

    function render($projectid, $L_round_id, $R_round_id)
    {
        global $Round_for_round_id_, $L_round_options, $R_round_options;

        echo "<form action='page_compare.php' method='GET'>
            <input type='hidden' name='project' value='$projectid'>\n
            <input type='hidden' name='compare'>\n";
        // TRANSLATORS: %1$s and %2$s are selectors e.g. P3, %3$s etc. are radio button options
        echo '<p>', sprintf(_('Compare %1$s to %2$s for %3$s %4$s %5$s'),
            $this->selector_string($L_round_id, "L_round_id", $L_round_options),
            $this->selector_string($R_round_id, "R_round_id", $R_round_options),
            $this->radio_string('all', _("All pages")),
            $this->radio_string('left', _("My first")),
            $this->radio_string('right', _("My second")));
        echo "<input type='submit' value=", attr_safe(_('Go')), "></p></form>\n";
    }

    function selector_string($selected, $name, $rounds)
    {
        $sel_str = "<select name=$name>";
        foreach($rounds as $round)
        {
            $sel_str .= "<option value='$round'";
            if($round == $selected)
            {
                $sel_str .= " selected";
            }
            $sel_str .= ">$round</option>";
        }
        $sel_str .= "</select>\n";
        return $sel_str;
    }

    function radio_string($value, $label)
    {
        $checked = ($this->page_set === $value) ? " checked" : "";
        return "<input type='radio' name='page_set' value='$value'$checked>" . html_safe($label) ."\n";
    }
}

// vim: sw=4 ts=4 expandtab
