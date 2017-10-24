<?php
include_once($relPath.'Project.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'ProjectTransition.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'ProjectSearchResultsConfig.inc');
include_once($relPath.'forum_interface.inc'); // get_topic_details() & get_url_to_view_topic()

class Column
{
    public function __construct($id, $label, $long_label, $css_class, $default_display, $sortable, $db_column, $print_data)
    {
        $this->id = $id;
        $this->label = $label;
        $this->long_label = $long_label;
        $this->css_class = $css_class;
        $this->default_display = $default_display;
        $this->sortable = $sortable;
        $this->db_column = $db_column;
        $this->print_data = $print_data;
    }
    
    public function get_label($time_format)
    {
        return $this->label;
    }

    public function get_long_label()
    {
        // can also be overridden
        if(empty($this->long_label))
            return $this->label;
        return $this->long_label;
    }

    public function get_sort_sql()
    {
        return $this->db_column; // overridden by state column
    }

    public function is_active($userSettings)
    {
        switch ($userSettings->get_value("PM$this->id"))
        {
        case "yes":
            return true;
        case "no":
            return false;
        default:
            return $this->default_display;
        }
    }

    public function echo_header_cell($sort_column_id, $sql_sort_dir, $show_param, $sec_sort, $time_format)
    {
        $label = $this->get_label($time_format);
        if($this->sortable)
        {
            if($sort_column_id == $this->id)
            {
                $sec_sorter = "";
                // The header-link reverses the current sort direction.
                if($sql_sort_dir == "asc")
                {
                    $dir_code = "D";
                    $marker = "&#9650;";
                }
                else
                {
                    $dir_code = "A";
                    $marker = "&#9660;";
                }
            }
            else
            { // sort ascending
                $dir_code = "A";
                $marker = "";
                if($sec_sort == $this->id)
                    $sec_sorter = "&nbsp;^";
                else
                    $sec_sorter = "&nbsp;<a href='{$_SERVER['PHP_SELF']}?show=$show_param&amp;re_search&amp;sec_sort=$this->id&amp;results_offset=0#head'>^</a>";
            }
            $label = "<a href='{$_SERVER['PHP_SELF']}?show=$show_param&amp;re_search&amp;sort=$this->id$dir_code&amp;results_offset=0#head'>$label</a>$marker$sec_sorter";
        }
        echo "<th class='$this->css_class'>$label</th>\n";
    }

    public function echo_data_cell($project, $time_format)
    {
        echo "<td class='$this->css_class'>",  $this->print_cell_data($project, $time_format), "</td>\n";
    }

    public function print_cell_data($project, $time_format)
    {
        $key = $this->db_column;
        call_user_func($this->print_data, $project->$key, $time_format);
    }
}

class TimeColumn extends Column
{
    public function get_label($time_format)
    {
        $label = $this->label;
        if($time_format == 'days')
            // TRANSLATORS: e.g. 'Last proofed (days ago)' 
            $label = sprintf(_("%s (days&nbsp;ago)"), $label);
        return $label;
    }
}

class TitleColumn extends Column
{
    public function print_cell_data($project, $time_format)
    {
        global $code_url;
        echo "<a href='$code_url/project.php?id=$project->projectid&amp;detail_level=3'>" . html_safe($project->nameofwork) . "</a>";
    }
}

class GenreColumn extends Column
{
    public function get_long_label()
    {
        return _('Difficulty') . " + " . $this->label;
    }

    public function print_cell_data($project, $time_format)
    {
        $diff_prefix = array(
            "beginner"  => _("BEGINNERS"),
            "average"   => "",
            "easy"      => _("EASY"),
            "hard"      => _("HARD")
        );
        $genre = $project->genre;
        $prefix = $diff_prefix[$project->difficulty];
        if($prefix != "")
            $genre = $prefix." ".$genre;
        echo $genre;
    }
}

class StateColumn extends Column
{
    public function get_sort_sql()
    {
        return sql_collater_for_project_state('state');
    }

    public function print_cell_data($project, $time_format)
    {
        global $pguser, $code_url;

        $transitions = get_valid_transitions( $project, $pguser );
        if ( count($transitions) > 0 )
        {
            $here = attr_safe($_SERVER['REQUEST_URI']);
            echo "
                <form
                    name='$project->projectid'
                    method='POST'
                    action='$code_url/tools/changestate.php'>
                <input
                    type='hidden'
                    name='projectid'
                    value='$project->projectid'>
                <input
                    type='hidden'
                    name='curr_state'
                    value='$project->state'>
                <input
                    type='hidden'
                    name='return_uri'
                    value='$here'>
                <select
                    name='next_state'
                    onchange='this.form.submit()'>
            ";

            $this->_echo_project_state_option( $project->state, 1 );

            foreach ( $transitions as $transition )
            {
                $this->_echo_project_state_option( $transition->to_state, 0 );
            }

            echo "
                </select>
                </form>
            ";
        }
        else
        {
            echo get_medium_label_for_project_state($project->state), "\n";
        }
    }

    private function _echo_project_state_option($project_state,$selected)
    {
        echo "<option value='$project_state'";
        if ($selected) echo " SELECTED";
        echo ">";
        if ($project_state == 'automodify')
        {
            echo _('automodify');
        }
        else
        {
            echo get_medium_label_for_project_state($project_state);
        }
        echo "</option>\n";
    }
}

class OptionsColumn extends Column
{
    public function print_cell_data($project, $time_format)
    {
        global $pguser, $projects_url, $code_url;
        $projectid = $project->projectid;
        $actions = '';

        $topic_id = $project->topic_id;
        if (!empty($topic_id))
        {
            $details = get_topic_details($topic_id);
            $url = get_url_to_view_topic($details["topic_id"]);
            $actions .= "<a href='$url' target='_blank'>" . _("View Forum") . "</a><br>\n";
        }
        if ( user_is_a_sitemanager() || user_is_proj_facilitator() || $project->username == $pguser )
        {
            $actions .= "<a href='$code_url/../noncvs/project_quick_check.php?projectid=$projectid' target='_blank'>"._("Project Quick Check")."</a><br>
            <a href='$code_url/tools/project_manager/editproject.php?action=edit&amp;project=$projectid' target='_blank'>" . _("Edit Info") . "</a><br>
            <a href='$code_url/tools/project_manager/edit_project_word_lists.php?projectid=$projectid' target='_blank'>" . _("Edit Word Lists") . "</a><br>
            <a href='$code_url/../noncvs/send_email.php?to=db-req&amp;subject=$projectid' target='_blank'>"._("Email db-req")."</a><br>
            <a href='$code_url/../noncvs/send_email.php?to=dp-format&amp;subject=$projectid' target='_blank'>"._("Email dp-format")."</a><br>\n";

            // Should we show an "attention" icon?
            // Currently, we only do this if suggestions have been added since
            // the Good Words file was last modified.
            // In future, there might be various reasons to do so.
            // (But then what would we put in the tooltip?)
            $f_g  = get_project_word_file( $projectid, 'good' );
            $count = count_wordcheck_suggestion_events($projectid, $f_g->mod_time);
            if ( $count >= 1 )
            {
                $tooltip = attr_safe(_('"Suggestions from proofreaders" list has changed; click here to view'));
                echo "<a href='$code_url/tools/project_manager/show_good_word_suggestions.php?projectid=$projectid' target='_blank'>
                    <img src='$code_url/graphics/exclamation.gif' title='$tooltip' border='0' alt='!'></a>";
            }
        }
        if(user_can_work_in_stage($pguser, 'PP'))
        {
            if ($project->state == PROJ_POST_FIRST_UNAVAILABLE ||
                $project->state == PROJ_POST_FIRST_AVAILABLE ||
                $project->state == PROJ_POST_FIRST_CHECKED_OUT)
            {
                $actions .="<a href=\"$projects_url/$projectid/$projectid.zip\">" . _("Download") . "</a><br>\n";
            }
            if ($project->state == PROJ_POST_SECOND_CHECKED_OUT ||
                $project->state == PROJ_POST_COMPLETE)
            {
                $actions .="<a href=\"$projects_url/$projectid/".$projectid."_second.zip\">" . _("Download") . "</a><br>\n";
            }
        }
        if(!empty($actions))
        {
            // identify the buttons by projectid
            echo "<div class='dropdown'>
                <button class='dropdown-button' onclick='toggleList(\"$projectid\")'>", _("Links"), "</button>
                <div id='$projectid' class='dropdown-content'>$actions</div>
                </div>";
        }
    }
}

function print_safe($data)
{
    echo html_safe($data);
}

function print_plain($data)
{
   echo $data;
}

function print_time($data, $time_format)
{
    if(0 == $data)
        echo _("never");
    else
    {
        switch($time_format) {
        case 'days':
            echo sprintf(_("%.1f"), (time() - $data)/86400);
            break;
        default: // case 'date':
            // TRANSLATORS: This is a strftime-formatted string for the date with year
            echo "<span class='nowrap'>" . strftime(_("%b %e, %Y"), $data) . "</span>";
            break;
        }
    }
}

function print_hold($data)
{
    if($data)
        echo "&#x2714;"; // heavy check mark
}

function make_link($name)
{
    if($name != '')
    {
        $contact_url = attr_safe(get_url_to_compose_message_to_user($name));
        echo "<a href='$contact_url'>$name</a>";
    }
}

class ColumnData
{
    public function __construct()
    {
        global $pguser;

        $this->columns = array(
            new TitleColumn('title', _("Title"), '', 'left-align', true, true, 'nameofwork', ''),
            new Column("author", _('Author'), '', 'left-align', true, true, 'authorsname', 'print_safe'),
            new Column("projectid", _('Project ID'), '', 'left-align', false, true, 'projectid', 'print_plain'),
            new Column("language", _("Language"), '', 'left-align', false, true, 'language', 'print_plain'),
            new GenreColumn("genre", _("Genre"), '', 'left-align', true, true, 'genre', ""),
            new Column("project_manager", pgettext("project manager", "PM"), _('Project Manager'), 'left-align', true, true, 'username', 'make_link'),
            new Column("checkedoutby", _('Checked Out By'), '', 'left-align', false, true, 'checkedoutby', 'make_link'),
            // TRANSLATORS: Abbreviation for Post Processor
            new Column("pp_er", _('PP'), _('Post-processor'), 'left-align', false, true, 'postproofer', 'make_link'),
            // TRANSLATORS: Abbreviation for Post-processing Verifier
            new Column("ppv_er", _('PPV'), _('Post-processing Verifier'), 'left-align', false, true, 'ppverifier', 'make_link'),
            new TimeColumn("last_proof", _('Last proofed'), '', 'right-align', true, true, 't_last_page_done', 'print_time'),
            new Column("postednum", _('PG etext number'), '', 'right-align', false, true, 'postednum', 'print_plain'),
            new Column('p_avail', _('Available pages'), '',  'right-align', false, true, 'n_available_pages', 'print_plain'),
            new Column('p_total', _('Total pages'), '', 'right-align', false, false, 'n_pages', 'print_plain'),
            new TimeColumn("days_avail", _('Last State Change'), '', 'right-align', true, true, 'modifieddate', 'print_time'),
            new StateColumn('state', pgettext('project state', 'State'), '', 'left-align', true, true, 'state', ''),
            new Column('hold', _('Hold'), _('On hold'), 'center-align', false, false, 'hold', 'print_hold')
        );
        if(user_can_work_in_stage($pguser, 'PP') || user_is_PM())
            $this->columns[] = new OptionsColumn('options', _('Actions'), '', 'left-align nowrap', true, false, '', '');
    }
}

class ProjectSearchResults extends ColumnData
{
    public function __construct($show_param)
    {
        global $pguser;

        parent::__construct();
        $option_data = new OptionData();
        $this->show_param = $show_param;
        $userSettings =& Settings::get_Settings($pguser);
        $this->page_size = $option_data->results_per_page->get_value($userSettings);
        $this->time_format = $option_data->time_format->get_value($userSettings);
        // Determine whether to use special colors or not
        // (this does not affect the alternating between two
        // background colors) in the project listing.
        $this->show_special_colors = !$userSettings->get_boolean('hide_special_colors');
        $this->get_sorting($userSettings);

        // construct active columns array so we don't have to keep checking
        // whether the column is active (these are references)
        foreach($this->columns as $column)
        {
            if($column->is_active($userSettings))
                $this->active_columns[] = $column;
        }
    }


    private function results_navigator($numrows, $num_found_rows, $results_offset)
    {
        $url_base = "{$_SERVER['PHP_SELF']}?show=$this->show_param&amp;re_search&amp;";

        if ( $results_offset > 0 )
        {
            $text = _('Previous');
            $prev_offset = max(0, $results_offset - $this->page_size);
            $url = $url_base . "results_offset=$prev_offset#head";
            echo "<a href='$url'>$text</a> | ";
        }
        echo sprintf(
            // TRANSLATORS: these are paging results: eg: "Projects 1 to 100 of 495"
            _("Projects %1\$d to %2\$d of %3\$d"),
            $results_offset + 1,
            $results_offset + $numrows,
            $num_found_rows
        );
        echo "\n";

        if ( $results_offset + $numrows < $num_found_rows )
        {
            $text = _('Next');
            $next_offset = $results_offset + $this->page_size;
            $url = $url_base . "results_offset=$next_offset#head";
            echo " | <a href='$url'>$text</a>";
        }
    }

    private function get_sort_sql($col_id)
    {
        foreach($this->columns as $column)
        {
            if($column->sortable && ($column->id == $col_id))
            {
                $valid = true;
                return $column->get_sort_sql();
            }
        }
        return NULL;
    }

    private function get_sorting($userSettings)
    {
        // sets $this->sort_column_id, sort_sql and sql_sort_dir
        // if sort parameter is given, use it
        // else do same as last time if stored
        // else default to 'state'
        // store for next time
        if(isset($_GET['sort']))
            $sort_param = $_GET['sort'];
        else
            $sort_param = $userSettings->get_value("PMSort", " "); // could be invalid if we change this file

        // parse and check if valid
        // remove terminating char A or D
        $this->sort_column_id = substr($sort_param, 0, -1);
        $sort_dir = substr($sort_param, -1); // last char A or D

        $this->sort_sql = $this->get_sort_sql($this->sort_column_id);

        if($this->sort_sql)  // check direction D is ok else assume A
        {
            if($sort_dir == "D")
                $this->sql_sort_dir = "desc";
            else // A or not
                $this->sql_sort_dir = "asc";
        }
        else // use default
        {
            $sort_param = "stateA";
            $this->sort_column_id = 'state';
            $this->sql_sort_dir = "asc";
            $this->sort_sql = sql_collater_for_project_state('state');
        }
        if($sort_param != $userSettings->get_value("PMSort", " "))
        {
            $userSettings->set_value("PMSort", $sort_param);
        }
        $this->sort_sql .= " $this->sql_sort_dir";
        
        // get secondary sorting
        if(isset($_GET['sec_sort']))
            $this->sec_sort = $_GET['sec_sort'];
        else
            $this->sec_sort = $userSettings->get_value("PMSecSort", " ");
        $sec_sort_sql = $this->get_sort_sql($this->sec_sort);
        if(NULL == $sec_sort_sql)
        {
            $this->sec_sort = 'title';
            $sec_sort_sql = 'nameofwork';
        }
        if($this->sec_sort != $userSettings->get_value("PMSecSort", " "))
        {
            $userSettings->set_value("PMSecSort", $this->sec_sort);
        }

        $this->sort_sql .= ", $sec_sort_sql asc";
    }

    private function _search($where_condition, $results_offset)
    {
        global $testing;
        $sql = "
            SELECT SQL_CALC_FOUND_ROWS projects.*,
            project_holds.projectid IS NOT NULL AS hold
            FROM projects
            LEFT JOIN project_holds
            USING (projectid, state)
            WHERE $where_condition
            ORDER BY $this->sort_sql
            LIMIT $this->page_size OFFSET $results_offset
        ";
        if ($testing)
            echo_html_comment($sql);
        $result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));
        return $result;
    }

    public function get_search_configure_link()
    {
        $results_offset = intval(@$_GET['results_offset']);
        return get_search_configure_link("{$_SERVER['PHP_SELF']}?show=$this->show_param&amp;re_search&amp;results_offset=$results_offset");
    }

    public function render($where_condition)
    {
        global $pguser, $theme, $code_url;

        $results_offset = intval(@$_GET['results_offset']);

        $qtime = microtime(true);
        $result = $this->_search($where_condition, $results_offset);
        $qtime = microtime(true) - $qtime;

        $numrows = mysqli_num_rows($result);

        $res_found = mysqli_query(DPDatabase::get_connection(), "SELECT FOUND_ROWS()");
        $row = mysqli_fetch_row($res_found);
        $num_found_rows = $row[0];
        if ( $numrows == 0 )
        {
            echo "<b>", _("No projects matched the search criteria."), "</b>";
            return;
        }

        echo "<p>" . _("To set primary sort term, click on the column name; to set secondary sort term, click on the <u>^</u> that follows the column name.") . "</p>";
        $this->results_navigator($numrows, $num_found_rows, $results_offset);
        echo " (", sprintf(_("%.2f milliseconds"), $qtime * 1000), ")";

        echo "\n<table class='themed theme_striped'>\n";
        echo "<tr>\n";
        foreach($this->active_columns as $column)
        {
            $column->echo_header_cell($this->sort_column_id, $this->sql_sort_dir, $this->show_param, $this->sec_sort, $this->time_format);
        }
        echo "</tr>\n";

        while ($project_assoc = mysqli_fetch_assoc($result)) {
            $project = new Project($project_assoc);
            $projectid = $project->projectid;

            $bgcolor = '';
            // Special colours for special books of various types
            if ($this->show_special_colors)
            {
                $special_color = get_special_color_for_project($project_assoc);
                if (!is_null($special_color)) {
                    $bgcolor = $special_color;
                }
            }

            $row_style = '';
            if($bgcolor)
                $row_style = "style='background-color: $bgcolor;'";
            echo "<tr $row_style>\n";
            foreach($this->active_columns as $column)
            {
                $column->echo_data_cell($project, $this->time_format);
            }
            echo "</tr>\n";
        }
        echo "</table>\n";

        $this->results_navigator($numrows, $num_found_rows, $results_offset);
        // special colours legend
        // Don't display if the user has selected the
        // setting "Show Special Colors: No".
        // The legend has been put at the bottom of the page
        // because the use of colors is presumably mostly
        // useful as a check that no typo was made. The
        // exact color probably doesn't matter and,
        // furthermore, the PM 'knows' the project and
        // what's so special about it.
        if ($this->show_special_colors)
        {
            echo_special_legend(" 1 = 1");
        }
    }
}

function get_refine_search_link()
{
    return "<a href='{$_SERVER['PHP_SELF']}?show=search_form'>" . _("Refine Search") . "</a>";
}

// vim: sw=4 ts=4 expandtab
