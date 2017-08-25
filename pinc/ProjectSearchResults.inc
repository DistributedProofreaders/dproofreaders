<?php
include_once($relPath.'Project.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'ProjectTransition.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'wordcheck_engine.inc');

class ProjectSearchResults
{
    public function __construct($page_size)
    {
        $this->page_size = $page_size;
    }

    private function results_navigator($numrows, $num_found_rows, $results_offset)
    {
        // The REQUEST_URI must have at least one query-string parameter,
        // otherwise the response would have been just the search form,
        // and this function wouldn't have been called.
        $url_base = $_SERVER['REQUEST_URI'] . '&';
        $url_base = preg_replace('/results_offset=[^&]*&/', '', $url_base);

        if ( $results_offset > 0 )
        {
            $t = _('Previous');
            $prev_offset = max(0, $results_offset - $this->page_size);
            $url = $url_base . "results_offset=$prev_offset";
            echo "<a href='$url'>$t</a> | ";
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
            $t = _('Next');
            $next_offset = $results_offset + $this->page_size;
            $url = $url_base . "results_offset=$next_offset";
            echo " | <a href='$url'>$t</a>";
        }
    }

    private function _search($where_condition, $results_offset)
    {
        $state_collater = sql_collater_for_project_state('state');
        $sql = "
            SELECT SQL_CALC_FOUND_ROWS *
            FROM projects
            WHERE $where_condition
            ORDER BY $state_collater, nameofwork asc
            LIMIT $this->page_size OFFSET $results_offset
        ";
        // echo "<pre>\n$sql\n</pre>\n";
        $result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));
        return $result;
    }

    public function render($where_condition, $results_offset, $show_special_colors=False)
    {
        global $pguser, $theme, $code_url;
        $result = $this->_search($where_condition, $results_offset);

        $numrows = mysqli_num_rows($result);

        $res_found = mysqli_query(DPDatabase::get_connection(), "SELECT FOUND_ROWS()");
        $row = mysqli_fetch_row($res_found);
        $num_found_rows = $row[0];

        if ( $numrows == 0 )
        {
            echo _("<b>No projects matched the search criteria.</b>");
            return;
        }

        $this->results_navigator($numrows, $num_found_rows, $results_offset);

        $user_can_see_download_links = user_can_work_in_stage($pguser, 'PP');
        $show_options_column = $user_can_see_download_links || user_is_PM();

        echo "<center><table border=1 width='99%' cellpadding=0 cellspacing=0 style='border: 1px solid #111; border-collapse: collapse'>";

        echo "<tr>";
        $this->_echo_header_cell( 175, _("Title") );
        $this->_echo_header_cell( 100, _("Author") );
        // TRANSLATORS: Abbreviation for difficulty
        $this->_echo_header_cell( 25, _("Diff.") );
        $this->_echo_header_cell( 50, _("Avail. Pages") );
        $this->_echo_header_cell( 50, _("Total Pages") );
        $this->_echo_header_cell(  75, pgettext("project manager", "PM") );
        $this->_echo_header_cell(  75, _("Checked Out By") );
        $this->_echo_header_cell( 180, _("Project Status") );
        if ( $show_options_column )
        {
            $this->_echo_header_cell(  30, _("Options") );
        }
        echo "</tr>";

        $tr_num = 0;
        while ($project_assoc = mysqli_fetch_assoc($result)) {
            $project = new Project($project_assoc);
            $projectid = $project->projectid;

            if ($tr_num % 2 ) {
                $bgcolor = $theme['color_mainbody_bg'];
            } else {
                $bgcolor = $theme['color_navbar_bg'];
            }

            // Special colours for special books of various types
            if ($show_special_colors)
            {
                $special_color = get_special_color_for_project($project_assoc);
                if (!is_null($special_color)) {
                    $bgcolor = "'$special_color'";
                }
            }

            echo "<tr bgcolor=$bgcolor>\n";

            // Title
            echo "<td><a href='$code_url/project.php?id=$projectid&amp;detail_level=3'>{$project->nameofwork}</a></td>\n";

            // Author
            echo "<td>{$project->authorsname}</td>\n";

            // Difficulty
            $diff = strtoupper(substr($project->difficulty,0,1));
            echo "<td align=\"center\">$diff</td>\n";


            // Avail. Pages
            echo "<td align=\"center\">{$project->n_available_pages}</td>\n";

            // Total Pages
            echo "<td align=\"center\">{$project->n_pages}</td>\n";


            // PM
            echo "<td align=\"center\">";
            if ( $project->username != '' )
            {
                $contact_url = get_url_to_compose_message_to_user($project->username);
                print "<a href='$contact_url'>{$project->username}</a>";
            }
            echo "</td>\n";

            // Checked Out By
            echo "<td align=\"center\">";
            if ($project->checkedoutby != "") {
                // Maybe we should get this info via a
                // left outer join in the big select query.
                // (Actually, I tried it in a few cases and the left outer join was always slower.)
                $contact_url = get_url_to_compose_message_to_user($project->checkedoutby);
                print "<a href='$contact_url'>{$project->checkedoutby}</a>";
            }
            echo "</td>\n";

            // Project Status

            echo "<td valign='middle'>\n";
            $this->_echo_project_state_changer($project);
            echo "</td>\n";

            // Options
            if ( $show_options_column )
            {
                echo "<td align=center>";
                if ( user_is_a_sitemanager() || user_is_proj_facilitator() || $project->username == $pguser )
                {
                    echo _("Edit") . ":";
                    echo " ";
                    echo "<a href=\"$code_url/tools/project_manager/editproject.php?action=edit&project=$projectid\">" . _("Info") . "</a>";
                    echo " | ";
                    echo "<a href=\"$code_url/tools/project_manager/edit_project_word_lists.php?projectid=$projectid\">" . _("Word&nbsp;Lists") . "</a>";

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
                        echo " <a href='$code_url/tools/project_manager/show_good_word_suggestions.php?projectid=$projectid' target='_blank'>";
                        echo "<img src='$code_url/graphics/exclamation.gif' title='$tooltip' border='0'>";
                        echo "</a>";
                    }

                    echo "<br>";
                }
                if ( $user_can_see_download_links )
                {
                    if ($project->state == PROJ_POST_FIRST_UNAVAILABLE ||
                        $project->state == PROJ_POST_FIRST_AVAILABLE ||
                        $project->state == PROJ_POST_FIRST_CHECKED_OUT)
                    {
                        echo " <a href=\"$projects_url/$projectid/$projectid.zip\">", _("D/L"), "</a>";
                    }
                    if ($project->state == PROJ_POST_SECOND_CHECKED_OUT ||
                        $project->state == PROJ_POST_COMPLETE)
                    {
                        echo " <a href=\"$projects_url/$projectid/".$projectid."_second.zip\">", _("D/L"), "</a>";
                    }
                }
                echo "</td>\n";
            }

            echo "</tr>\n";

            $tr_num++;
        }

        echo "<tr><td colspan=9 bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td></tr></table></center>";
        echo "\n";

        $this->results_navigator($numrows, $num_found_rows, $results_offset);
    }

    private function _echo_header_cell( $width, $text )
    {
        global $theme;

        echo "<td width='$width' align='center' bgcolor='{$theme['color_headerbar_bg']}'>";
        echo "<font color='{$theme['color_headerbar_font']}'>";
        echo "<b>$text</b>";
        echo "</font>";
        echo "</td>";
        echo "\n";
    }

    private function _echo_project_state_changer($project)
    {
        global $pguser, $code_url;

        $transitions = get_valid_transitions( $project, $pguser );

        if ( count($transitions) > 0 )
        {
            $here = $_SERVER['REQUEST_URI'];
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

// vim: sw=4 ts=4 expandtab
