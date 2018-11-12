<?php
include_once($relPath.'Project.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'ProjectTransition.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'ProjectSearchResultsConfig.inc');
include_once($relPath.'forum_interface.inc'); // get_topic_details() & get_url_to_view_topic()
include_once($relPath.'misc.inc'); // array_get()

class Column
{
    public function __construct($id, $label, $long_label, $css_class, $default_display, $sortable, $db_column)
    {
        $this->id = $id;
        $this->label = $label;
        $this->long_label = $long_label;
        $this->css_class = $css_class;
        $this->default_display = $default_display;
        $this->sortable = $sortable;
        $this->db_column = $db_column;
    }

    public function get_label()
    {
        return $this->label;
    }

    public function get_warning()
    {
        return '';
    }

    public function get_long_label()
    {
        // can also be overridden
        if(empty($this->long_label))
            return $this->label;
        return $this->long_label;
    }

    public function get_tooltip()
    {
        return $this->get_long_label();
    }

    public function get_sort_sql()
    {
        return $this->db_column; // overridden by state column
    }

    public function set_datastore($userSettings, $search_origin)
    {
        $this->userSettings = $userSettings;
        $this->search_origin = $search_origin;
    }

    public function set_active($setting)
    {
        // We can't use set_boolean() because this actually has three states, not two:
        // * true
        // * false
        // * undefined (in which case we use the default)
        if($setting)
            $value = "yes";
        else
            $value = "no";

        $this->userSettings->set_value("search:{$this->search_origin}.column:$this->id", $value);
    }

    public function is_active()
    {
        // See comment in set_active() for why we can't use get/set_boolean()
        $value = $this->userSettings->get_value("search:{$this->search_origin}.column:$this->id");
        if($value === NULL)
            return $this->default_display;

        return $value === 'yes';
    }

    public function echo_header_cell($sort_column_id, $sql_sort_dir, $sec_sort)
    {
        $label = $this->get_label();
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
                {
                    $ar1 = array('sec_sort' => $this->id, 'results_offset' => 0);
                    $sec_sorter = "&nbsp;" . make_ref($ar1, "^", "#head");
                }
            }
            $ar1 = array('sort' => "$this->id$dir_code", 'results_offset' => 0);
            $label = make_ref($ar1, $label, "#head") . "$marker$sec_sorter";
        }
        echo "<th class='$this->css_class' title='", attr_safe($this->get_tooltip()), "'>$label</th>\n";
    }

    public function echo_data_cell($project)
    {
        echo "<td class='$this->css_class'>";
        $this->print_cell_data($project);
        echo "</td>\n";
    }

    public function print_cell_data($project)
    {
        echo $project->{$this->db_column};
    }
}

class TimeColumn extends Column
{
    public function __construct($id, $label, $long_label, $css_class, $default_display, $sortable, $db_column, $time_format)
    {
        parent::__construct($id, $label, $long_label, $css_class, $default_display, $sortable, $db_column);
        $this->time_format = $time_format;
    }

    public function get_label()
    {
        $label = $this->label;
        if($this->time_format == 'days')
            // TRANSLATORS: e.g. 'Last proofed (days ago)'
            $label = sprintf(_("%s (days&nbsp;ago)"), $label);
        return $label;
    }

    public function print_cell_data($project)
    {
        $data = $project->{$this->db_column};
        if(0 == $data)
            echo _("never");
        else
        {
            switch($this->time_format) {
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
}

class TitleColumn extends Column
{
    public function print_cell_data($project)
    {
        global $code_url;
        echo "<a href='$code_url/project.php?id=$project->projectid&amp;detail_level=3'>$project->nameofwork</a>";
    }
}

class LinkColumn extends Column
{
    public function print_cell_data($project)
    {
        $name = $project->{$this->db_column};
        if($name != '')
        {
            $contact_url = attr_safe(get_url_to_compose_message_to_user($name));
            echo "<a href='$contact_url'>$name</a>";
        }
    }
}

class GenreColumn extends Column
{
    public function get_long_label()
    {
        return _('Difficulty') . " + " . $this->label;
    }

    public function print_cell_data($project)
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

class StateChangeColumn extends TimeColumn
{
    public function get_warning()
    {
        return _("The 'Last State Change' column tracks forward progress through the proofing and formatting rounds well, but does not reflect Post-processing state changes or state changes to Unavailable accurately.");
    }

    public function get_tooltip()
    {
        return _("Last State Change (see note above)");
    }
}

class StateColumn extends Column
{
    public function get_sort_sql()
    {
        return sql_collater_for_project_state('state');
    }

    public function print_cell_data($project)
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
                $disabled = $transition->is_disabled($project);
                $optional_reason = $transition->why_disabled($project);

                $this->_echo_project_state_option( $transition->to_state, 0, $disabled, $optional_reason);
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

    private function _echo_project_state_option($project_state, $selected, $disabled = 0, $reason = '')
    {
        echo "<option value='$project_state'";
        if ($selected) echo " SELECTED";
        if ($disabled) echo " disabled";
        echo ">";
        if ($project_state == 'automodify')
        {
            echo _('automodify');
        }
        else
        {
            echo get_medium_label_for_project_state($project_state);
        }
        if ($disabled) echo " [$reason]";
        echo "</option>\n";
    }
}

class OptionsColumn extends Column
{
    public function print_cell_data($project)
    {
        global $pguser, $projects_url, $code_url;
        $projectid = $project->projectid;
        $actions = '';

        $topic_id = $project->topic_id;
        if (!empty($topic_id))
        {
            $details = get_topic_details($topic_id);
            $url = get_url_to_view_topic($details["topic_id"]) . "&amp;view=unread#unread";
            $actions .= "<a href='$url' target='_blank'>" . _("View Forum") . "</a><br>\n";
        }
        if($project->user_can_do_quick_check())
        {
            $actions .= "<a href='$code_url/tools/project_manager/project_quick_check.php?projectid=$projectid' target='_blank'>"._("Project Quick Check")."</a><br>";
        }
        if($project->can_be_managed_by_user($pguser))
        {
            $actions .= "<a href='$code_url/tools/project_manager/editproject.php?action=edit&amp;project=$projectid' target='_blank'>" . _("Edit Info") . "</a><br>
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

class HoldColumn extends Column
{
    public function print_cell_data($project)
    {
        if($project->hold)
            echo "&#x2714;"; // heavy check mark
    }
}

class ColumnData
{
    public function __construct($userSettings, $search_origin, $time_format = '')
    {
        global $pguser;

        $this->userSettings = $userSettings;
        $this->search_origin = $search_origin;

        $this->columns = array(
            new TitleColumn('title', _("Title"), '', 'left-align', true, true, 'nameofwork'),
            new Column("author", _('Author'), '', 'left-align', true, true, 'authorsname'),
            new Column("projectid", _('Project ID'), '', 'left-align', false, true, 'projectid'),
            new Column("language", _("Language"), '', 'left-align', false, true, 'language'),
            new GenreColumn("genre", _("Genre"), '', 'left-align', true, true, 'genre'),
            new LinkColumn("project_manager", pgettext("project manager", "PM"), _('Project Manager'), 'left-align', true, true, 'username'),
            new LinkColumn("checkedoutby", _('Checked Out By'), '', 'left-align', false, true, 'checkedoutby'),
            // TRANSLATORS: Abbreviation for Post Processor
            new LinkColumn("pp_er", _('PP'), _('Post-processor'), 'left-align', false, true, 'postproofer'),
            // TRANSLATORS: Abbreviation for Post-processing Verifier
            new LinkColumn("ppv_er", _('PPV'), _('Post-processing Verifier'), 'left-align', false, true, 'ppverifier'),
            new TimeColumn("last_proof", _('Last proofed'), '', 'right-align', true, true, 't_last_page_done', $time_format),
            new Column("postednum", _('PG etext number'), '', 'right-align', false, true, 'postednum'),
            new Column('p_avail', _('Available pages'), '',  'right-align', false, true, 'n_available_pages'),
            new Column('p_total', _('Total pages'), '', 'right-align', false, false, 'n_pages'),
            new StateChangeColumn("days_avail", _('Last State Change'), '', 'right-align', true, true, 'modifieddate', $time_format),
            new StateColumn('state', pgettext('project state', 'State'), '', 'left-align', true, true, 'state'),
            new HoldColumn('hold', _('Hold'), _('On hold'), 'center-align', false, false, 'hold')
        );
        if(user_can_work_in_stage($pguser, 'PP') || user_is_PM())
            $this->columns[] = new OptionsColumn('options', _('Actions'), '', 'left-align nowrap', true, false, '');

        // Set column datastore
        foreach($this->columns as $column)
        {
            $column->set_datastore($this->userSettings, $this->search_origin);
        }
    }
}

class ProjectSearchResults
{
    public function __construct($search_origin)
    {
        global $pguser;

        assert(($search_origin === 'PM') || ($search_origin === 'PS'));

        $this->userSettings =& Settings::get_Settings($pguser);
        $this->search_origin = $search_origin;
        $option_data = new OptionData($this->userSettings, $this->search_origin);
        $this->page_size = $option_data->results_per_page->get_value();
        $time_format = $option_data->time_format->get_value();

        $column_data = new ColumnData($this->userSettings, $this->search_origin, $time_format);

        // Determine whether to use special colors or not
        // (this does not affect the alternating between two
        // background colors) in the project listing.
        $this->show_special_colors = !$this->userSettings->get_boolean('hide_special_colors');

        // construct active columns array so we don't have to keep checking
        // whether the column is active (these are references)
        foreach($column_data->columns as $column)
        {
            if($column->is_active())
                $this->active_columns[] = $column;
        }
        $this->get_sorting();
    }


    private function results_navigator($numrows, $num_found_rows, $results_offset)
    {
        if ( $results_offset > 0 )
        {
            $prev_offset = max(0, $results_offset - $this->page_size);
            echo make_ref(array('results_offset' => $prev_offset), _('Previous'), "#head") . " | ";

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
            $next_offset = $results_offset + $this->page_size;
            echo " | " . make_ref(array('results_offset' => $next_offset), _('Next'), "#head");
        }
    }

    private function get_sort_sql($col_id)
    {
        foreach($this->active_columns as $column)
        {
            if($column->sortable && ($column->id == $col_id))
            {
                $valid = true;
                return $column->get_sort_sql();
            }
        }
        return NULL;
    }

    private function get_sorting()
    {
        // sets $this->sort_column_id, sort_sql and sql_sort_dir
        // if sort parameter is given, use it
        // else do same as last time if stored
        // else default to 'state'
        // store for next time
        $saved_sort = $this->userSettings->get_value("search:{$this->search_origin}.sort", " ");
        $sort_param = array_get($_GET, "sort", $saved_sort);

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
        if($sort_param != $saved_sort)
        {
            $this->userSettings->set_value("search:{$this->search_origin}.sort", $sort_param);
        }
        $this->sort_sql .= " $this->sql_sort_dir";


        // get secondary sorting
        $saved_sec_sort = $this->userSettings->get_value("search:{$this->search_origin}.sort_sec", " ");
        $this->sec_sort = array_get($_GET, "sec_sort", $saved_sec_sort);
        $sec_sort_sql = $this->get_sort_sql($this->sec_sort);
        if(NULL == $sec_sort_sql)
        {
            $this->sec_sort = 'title';
            $sec_sort_sql = 'nameofwork';
        }
        if($this->sec_sort != $saved_sec_sort)
        {
            $this->userSettings->set_value("search:{$this->search_origin}.sort_sec", $this->sec_sort);
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

    public function render($where_condition)
    {
        global $pguser, $theme, $code_url;

        $results_offset = intval(@$_GET['results_offset']);

        $result = $this->_search($where_condition, $results_offset);

        $numrows = mysqli_num_rows($result);

        $res_found = mysqli_query(DPDatabase::get_connection(), "SELECT FOUND_ROWS()");
        $row = mysqli_fetch_row($res_found);
        $num_found_rows = $row[0];
        if ( $numrows == 0 )
        {
            echo "<p><b>", _("No projects matched the search criteria."), "</b></p>";
            return;
        }

        $warning = '';
        foreach($this->active_columns as $column)
        {
            $warning .= $column->get_warning();
        }
        if(!empty($warning))
            echo "\n<p class='warning'>$warning</p>\n";

        echo "<p id='head'>" . _("To set primary sort term, click on the column name; to set secondary sort term, click on the <u>^</u> that follows the column name.") . "</p>";
        $this->results_navigator($numrows, $num_found_rows, $results_offset);

        echo "\n<table class='themed theme_striped'>\n";
        echo "<tr>\n";
        foreach($this->active_columns as $column)
        {
            $column->echo_header_cell($this->sort_column_id, $this->sql_sort_dir, $this->sec_sort);
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
                $column->echo_data_cell($project);
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

function get_search_configure_link()
{
    $origin = urlencode($_SERVER['REQUEST_URI']);
    return "<a href='{$_SERVER['PHP_SELF']}?show=config&amp;origin=$origin'>" . _("Configure Result") . "</a>";
}

function make_ref($ar1, $txt, $anchor = '')
{
    $url = "{$_SERVER['PHP_SELF']}?" . http_build_query(array_replace($_GET, $ar1)) . "$anchor";
    return "<a href='" . attr_safe($url) . "'>$txt</a>";
}

function get_refine_search_link()
{
    return make_ref(array('show' => 'search_form'), _("Refine Search"));
}

// vim: sw=4 ts=4 expandtab
