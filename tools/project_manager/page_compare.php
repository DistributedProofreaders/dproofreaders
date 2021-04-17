<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath."PageUnformatter.inc"); // PageUnformatter()

require_login();

$comparator = new Comparator();
$comparator->get_data();
$comparator->render();

class Comparator
{
    public function __construct()
    {
        global $PROJECT_STATES_IN_ORDER;

        $this->L_round_options = ['P1', 'P2', 'P3', 'F1'];
        $this->R_round_options = ['F1', 'F2'];
        $this->state_index = array_flip($PROJECT_STATES_IN_ORDER);
    }

    public function get_data()
    {
        $this->projectid = get_projectID_param($_GET, 'project');
        $this->L_round_id = get_enumerated_param($_GET, "L_round_id", "P3", $this->L_round_options);
        $this->R_round_id = get_enumerated_param($_GET, "R_round_id", "F1", $this->R_round_options);
        $this->page_set = get_enumerated_param($_GET, "page_set", "all", ['left', 'right', 'all']);
        $this->go_compare = isset($_GET['compare']);
    }

    public function render()
    {
        global $pguser, $code_url;

        $this->project = new Project($this->projectid);

        $title = _('Compare pages with formatting removed');
        $sub_title = $this->project->nameofwork;
        output_header("$title: $sub_title", NO_STATSBAR);

        echo "<h1>$title</h1>\n";
        echo "<h2>" . html_safe($sub_title) . "</h2>\n";

        $state = $this->project->state;
        $project_url = "$code_url/project.php?id=$this->projectid&amp;expected_state=$state";

        $label = _("Return to Project Page");
        echo "<p><a href='$project_url'>$label</a></p>\n";

        if (!$this->project->check_pages_table_exists($warn_message)) {
            echo "<p class='warning'>$warn_message</p>\n";
            exit();
        }

        // draw the round selectors
        echo "<form action='page_compare.php' method='GET'>
            <input type='hidden' name='project' value='$this->projectid'>
            <input type='hidden' name='compare'>",
            "<div>", _("Compare rounds:"), "</div>\n",
            "<div class='grid-wrapper'>\n",
            // TRANSLATORS: "Round 1" and "Round 2" are repeated below in "Pages I worked on in Round 1" etc.
            "<div>", _("Round 1"), "</div><div>", $this->selector_string($this->L_round_id, "L_round_id", $this->L_round_options), "</div>\n",
            "<div>", _("Round 2"), "</div><div>", $this->selector_string($this->R_round_id, "R_round_id", $this->R_round_options), "</div></div>\n",
            "<p>", _("Show:"), "<br>\n",
            $this->radio_string('all', _("All pages")), "<br>\n",
            $this->radio_string('left', _("Pages I worked on in Round 1")), "<br>\n",
            $this->radio_string('right', _("Pages I worked on in Round 2")), "</p>\n",
            "<input type='submit' value=", attr_safe(_('Go')), "></form>\n";

        // if this is first entry don't do anything else
        if (!$this->go_compare) {
            exit();
        }

        if (!$this->has_project_started_round($this->L_round_id) || !$this->has_project_started_round($this->R_round_id)) {
            exit();
        }

        $L_round = get_Round_for_round_id($this->L_round_id);
        $R_round = get_Round_for_round_id($this->R_round_id);
        $L_text_column_name = $L_round->text_column_name;
        $R_text_column_name = $R_round->text_column_name;
        $L_round_num = $L_round->round_number;
        $R_round_num = $R_round->round_number;

        $username = $pguser;
        switch ($this->page_set) {
            case 'right':
                $condition = sprintf(
                    "$R_round->user_column_name = '%s'",
                    DPDatabase::escape($username));
                break;
            case 'left':
                $condition = sprintf(
                    "$L_round->user_column_name = '%s'",
                    DPDatabase::escape($username));
                break;
            default: // all
                $condition = "1";
                break;
        }

        $right_complete = ($this->state_index[$this->project->state] >= $this->state_index["{$this->R_round_id}.proj_done"]);
        if (!$right_complete) {
            $condition .= sprintf(" AND state='%s'", $R_round->page_save_state);
        }

        validate_projectID($this->projectid);
        $sql = "
            SELECT image, $L_text_column_name, $R_text_column_name
            FROM $this->projectid
            WHERE $condition
            ORDER BY image ASC";
        $res = DPDatabase::query($sql);

        $num_rows = mysqli_num_rows($res);
        if ($num_rows == 0) {
            echo "<p>", _("There are no pages to compare"), "</p>\n";
            exit();
        } else {
            echo "<p>", sprintf(_("Comparing %d pages"), $num_rows), "</p>\n";
        }

        // make an array of imagenames of pages with diffs
        $diff_pages = [];
        $un_formatter = new PageUnformatter();
        while ($page_res = mysqli_fetch_assoc($res)) {
            // also unwrap
            $L_text = $un_formatter->remove_formatting($page_res[$L_text_column_name], true);
            $R_text = $un_formatter->remove_formatting($page_res[$R_text_column_name], true);
            if (0 != strcmp($L_text, $R_text)) {
                $diff_pages[] = $page_res['image'];
            }
        }

        if (empty($diff_pages)) {
            echo "<p>", _("There are no differences"), "</p>\n";
            exit();
        }

        // Draw the results
        echo "<p>", _("Clicking on a link will show the differences in a new window or tab."), "</p>\n";
        echo "<p>";
        foreach ($diff_pages as $imagename) {
            echo "<a href='$code_url/tools/project_manager/diff.php?project=$this->projectid&amp;image=$imagename&amp;L_round_num=$L_round_num&amp;R_round_num=$R_round_num&amp;format=remove' target='_blank'>$imagename</a>\n";
        }
        echo "</p>";
    }

    public function selector_string($selected_round, $name, $rounds)
    {
        $sel_str = "<select name=$name>";
        foreach ($rounds as $round) {
            $sel_str .= "<option value='$round'";
            if ($round == $selected_round) {
                $sel_str .= " selected";
            }
            $sel_str .= ">$round</option>";
        }
        $sel_str .= "</select>";
        return $sel_str;
    }

    public function radio_string($value, $label)
    {
        $checked = ($this->page_set === $value) ? " checked" : "";
        return "<input type='radio' name='page_set' value='$value'$checked>" . html_safe($label);
    }

    public function has_project_started_round($round_id)
    {
        if ($this->state_index[$this->project->state] < $this->state_index["{$round_id}.proj_avail"]) {
            echo "<p>", sprintf(_("%s has not started"), $round_id), "</p>";
            return false;
        }
        return true;
    }
}
