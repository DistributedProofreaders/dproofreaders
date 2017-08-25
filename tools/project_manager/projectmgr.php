<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'ProjectTransition.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'forum_interface.inc');
include_once('projectmgr.inc');

require_login();

define( 'DEFAULT_N_RESULTS_PER_PAGE', 100 );

include_once($relPath.'iso_lang_list.inc');
class ProjectSearchWidget
{
    function __construct( $properties )
    {
        foreach ( $properties as $property => $value )
        {
            $this->$property = $value;
        }
    }

    function get_html_control()
    {
        // If you don't specify a size for a <select> control,
        // browsers vary widely in what they use for a default.
        // (e.g., Firefox 1.0 uses 20, IE 5.5 and Opera 8 use 4, Opera 9 uses 1.)
        // To avoid this, set a reasonable size.
        if ( $this->type == 'select' && !isset($this->size) )
        {
            $co = count($this->options);
            $this->size = ( $co <= 6 ? $co : 4 );
        }

        $size_attr = ( isset($this->size) ? "size='{$this->size}'" : '' );
        if ( $this->type == 'text' )
        {
            if ( isset($_GET[$this->id]) )
            {
                $value_attr = "value='" . attr_safe($_GET[$this->id]) . "'";
            }
            else if ( isset($this->initial_value) )
            {
                $value_attr = "value='" . attr_safe($this->initial_value) . "'";
            }
            else
            {
                $value_attr = '';
            }
            return "<input type='text' name='{$this->id}' $size_attr $value_attr>";
        }
        else if ( $this->type == 'select' )
        {
            if ( $this->can_be_multiple )
            {
                $r = "<select name='{$this->id}[]' $size_attr multiple>\n";
            }
            else
            {
                $r = "<select name='{$this->id}' $size_attr>\n";
            }
            foreach ( $this->options as $option_value => $option_label )
            {
                $selected_attr = ( 
                    ( isset($this->initial_value) && $option_value == $this->initial_value )
                    ? 'selected'
                    : ''
                );
                $r .= "<option value='" . attr_safe($option_value) . "' $selected_attr>" . html_safe($option_label) . "</option>\n";
            }
            $r .= "</select>\n";
            return $r;
        }
    }

    function get_sql_contribution()
    {
        $value = @$_GET[$this->id];
        if ( $value == '' ) return NULL;

        $contrib_template = $this->q_contrib;
        if ( is_string($contrib_template) && function_exists($contrib_template) )
        {
            $contribution = $contrib_template( $value );
        }
        else
        {
            if ( $this->q_part == 'WHERE' )
            {
                list($column_name,$comparator) = $this->q_contrib;
                if ( @$this->can_be_multiple )
                {
                    if ( $this->type == 'text' )
                    {
                        $values = preg_split( "($this->separator)",  trim($value) );
                    }
                    elseif ( $this->type == 'select' )
                    {
                        $values = $value;

                        // If $value isn't an array, someone is mucking with
                        // the URL -- return instead of erroring out below.
                        if( !is_array($values) ) return NULL;

                        // If the user picks the 'any' option as well as some others,
                        // it's as if they'd just picked the 'any' option.
                        if ( in_array( '', $values ) ) return NULL;
                    }

                    $values = array_map("escape_value", $values);

                    if ( $comparator == '=' )
                    {
                        $values_list = surround_and_join( $values, "'", "'", "," );
                        $contribution = "$column_name IN ($values_list)";
                    }
                    elseif ( $comparator == 'LIKE' )
                    {
                        $likes_str = surround_and_join( $values, "$column_name LIKE '%", "%'", ' OR ' );
                        $contribution = "($likes_str)";
                    }
                }
                else
                {
                    $value = mysqli_real_escape_string(DPDatabase::get_connection(), $value);
                    if ( $comparator == '=' )
                    {
                        $contribution = "$column_name = '$value'";
                    }
                    elseif ( $comparator == 'LIKE' )
                    {
                        $contribution = "$column_name LIKE '%$value%'";
                    }
                }
            }
            else
            {
                $contribution = str_replace('{VALUE}', $value, $contrib_template);
            }
        }

        return $contribution;
    }

}

class ProjectSearchForm
{
    public function __construct()
    {
        $this->define_form_widgets();
    }

    private function _get_options_special_day()
    {
        $special_day_options = array();
        $special_day_options[''] = _('Any day');
        $special_day_res = mysqli_query(DPDatabase::get_connection(), "
            SELECT
                spec_code,
                display_name,
                DATE_FORMAT(concat('2000-',open_month,'-',open_day),'%e %b')
            FROM special_days
            WHERE enable = 1
            ORDER BY open_month, open_day
        ");
        while ( list($s_code, $s_display_name, $s_start) = mysqli_fetch_row($special_day_res) )
        {
            $special_day_options[$s_code] = "$s_display_name ($s_start)";
        }
        return $special_day_options;
    }

    private function _get_options_lang()
    {
        global $lang_list;

        $lang_options[''] = _('Any');
        foreach($lang_list as $k=>$v)
        {
            $lang_options[$v['lang_name']] = $v['lang_name'];
        }
        return $lang_options;
    }

    private function _get_options_genre()
    {
        global $relPath;
        include($relPath.'genres.inc');

        return array_merge( array( '' => _('any') ), $GENRES );
    }

    private function _get_options_difficulty()
    {
        return array(
            ''         => _('any'),
            'beginner' => _('Beginner'),
            'easy'     => _('Easy'),
            'average'  => _('Average'),
            'hard'     => _('Hard'),
        );
    }

    private function _get_options_state()
    {
        global $PROJECT_STATES_IN_ORDER;

        $state_options[''] = _('any state');
        foreach ($PROJECT_STATES_IN_ORDER as $proj_state)
        {
            $state_options[$proj_state] = project_states_text($proj_state);
        }
        return $state_options;
    }

    public function define_form_widgets()
    {
        $this->widgets = array(
            new ProjectSearchWidget( array(
                'id'         => 'title',
                'label'      => _('Title'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('nameofwork', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'author',
                'label'      => _('Author'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('authorsname', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'language',
                'label'      => _('Language'),
                'type'       => 'select',
                'options'    => $this->_get_options_lang(),
                'can_be_multiple' => TRUE,
                'initial_value'   => '',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('language', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'genre',
                'label'      => _('Genre'),
                'type'       => 'select',
                'options'    => $this->_get_options_genre(),
                'can_be_multiple' => TRUE,
                'q_part'     => 'WHERE',
                'q_contrib'  => array('genre', '='),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'difficulty',
                'label'      => _('Difficulty'),
                'type'       => 'select',
                'options'    => $this->_get_options_difficulty(),
                'can_be_multiple' => TRUE,
                'q_part'     => 'WHERE',
                'q_contrib'  => array('difficulty', '='),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'special_day',
                'label'      => _('Special day'),
                'type'       => 'select',
                'options'    => $this->_get_options_special_day(),
                'can_be_multiple' => TRUE,
                'initial_value'   => '',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('special_code', '='),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'projectid',
                'label'      => _('Project ID'),
                'type'       => 'text',
                'size'       => 45, // big enough to show two projectids without scrolling.
                'can_be_multiple' => TRUE,
                'separator'  => '[\s,;]+',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('projectid', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'project_manager',
                'label'      => _('Project Manager'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('username', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'checkedoutby',
                'label'      => _('Checked Out By'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('checkedoutby', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'pp_er',
                'label'      => _('Post-processor'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('postproofer', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'ppv_er',
                'label'      => _('Post-processing Verifier'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('ppverifier', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'           => 'postednum',
                'label'        => _('PG etext number'),
                'type'         => 'text',
                'can_be_multiple' => TRUE,
                'separator'  => '[\s,;]+',
                'q_part'       => 'WHERE',
                'q_contrib'    => array('postednum', '='),
            )),
            new ProjectSearchWidget( array(
                'id'           => 'state',
                'label'        => pgettext('project state', 'State'),
                'type'         => 'select',
                'options'      => $this->_get_options_state(),
                'can_be_multiple' => TRUE,
                'q_part'       => 'WHERE',
                'q_contrib'    => array('state', '='),
            )),
            new ProjectSearchWidget( array(
                'id'           => 'n_results_per_page',
                'label'        => _('Number of results per page'),
                'type'         => 'select',
                'options'      => array( 30 => 30, 100 => 100, 300 => 300 ),
                'can_be_multiple' => FALSE,
                'initial_value'   => DEFAULT_N_RESULTS_PER_PAGE,
                'q_part'       => 'LIMIT',
                'q_contrib'    => '{VALUE}',
            )),
        );
    }

    public function render($page)
    {
        echo "<form method='get' action='$page'>";
        echo "<input type='hidden' name='show' value='search'>";
        echo "<table>";

        foreach ( $this->widgets as $widget )
        {
            if ( @$widget->can_be_multiple )
            {
                if ( $widget->type == 'text' )
                {
                    $help = _('list ok');
                }
                elseif ( $widget->type == 'select' )
                {
                    $help = _('multi-select');
                }
                $help = "<br>($help)";
            }
            else
            {
                $help = '';
            }
            echo "
                <tr>
                    <td align='right'>{$widget->label}$help</td>
                    <td>".$widget->get_html_control()."</td>
                </tr>
            ";
        }

        echo "
            <tr>
                <td></td>
                <td>
                    <table width='100%'>
                    <tr>
                        <td align='left'><input type='submit' value='", attr_safe(_("Search")), "'></td>
                        <td align='right'><input type='reset' value='", attr_safe(_("Clear form")), "'></td>
                    </tr>
                    </table>
                </td>
            </tr>
            </table>
            </form>
        ";

        echo _("For terms that you type in, matching is case-insensitive and unanchored; so, for instance, 'jim' matches both 'Jimmy Olsen' and 'piggyjimjams'. This doesn't apply to PG etext numbers, for which you should type in the complete number.");
        echo "<br><br>";
        echo _('"(list ok)": You can search by multiple ProjectIDs or PG etext numbers at once: enter the list of ProjectIDs or PG etext numbers, separated by commas, semicolons, or spaces.');
        echo "<br><br>";
        echo _('"(multi-select)": If desired, you should be able to select multiple values for Language, Difficulty, Special Day, or State (e.g., by holding down Ctrl).');
    }

    public function get_widget_contribution()
    {
        $condition = '1';
        foreach ( $this->widgets as $widget )
        {
            $contribution = $widget->get_sql_contribution();
            if ( $contribution == '' ) continue;

            if ( $widget->q_part == 'WHERE' )
            {
                $condition .= "\nAND $contribution";
            }
            else if ( $widget->q_part == 'LIMIT' )
            {
                // n_results_per_page is handled below
            }
            else
            {
                assert(FALSE);
            }
        }
        return $condition;
    }

    public function get_page_size()
    {
        $n_results_per_page = intval(@$_GET['n_results_per_page']);
        if ( $n_results_per_page == 0 ) $n_results_per_page = DEFAULT_N_RESULTS_PER_PAGE;
        return $n_results_per_page;
    }
}

// -----------------------------------------------------------------------------

if (user_is_PM() && empty($_GET['show'])) {
    if ($userP['i_pmdefault'] == 0) {
        metarefresh(0,"projectmgr.php?show=user_all","","");
        exit();
    } elseif ($userP['i_pmdefault'] == 1) {
        metarefresh(0,"projectmgr.php?show=user_active","", "");
        exit();
    }
}

output_header(_("Project Search"), NO_STATSBAR);

$search_form = new ProjectSearchForm();

$PROJECT_IS_ACTIVE_sql = "(state NOT IN ('".PROJ_SUBMIT_PG_POSTED."','".PROJ_DELETE."'))";

if (!isset($_GET['show']) || $_GET['show'] == 'search_form') {

    echo_manager_header();

    // New proofreaders are having a hard time finding stuff because they
    // end up on the Project Search page instead of the starting round page.
    // See if we can't help them out by pointing them to the starting
    // round page.
    $pagesproofed = get_pages_proofed_maybe_simulated();
    if($pagesproofed < 100)
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Looking for projects to proofread?");
        echo "</div>";

        echo "<p>" . sprintf(_("If you're looking for projects to proofread, consider using the list on the <a href='%1\$s'>%2\$s</a> round page instead of this search form."), "$code_url/{$ELR_round->relative_url}#{$ELR_round->id}", $ELR_round->id) . "</p>";
        echo "</p>";

        echo "<p><small>";
        echo _("After a period of time, this message will no longer appear.");
        echo "</small></p>";
        echo "</div>";
    }

    echo "
        <center>
        <h1>", _("Search for Projects"), "</h1>
        "._("Search for projects matching the following criteria:")."<br>";

    $search_form->render('projectmgr.php');

    echo "</center>";
} else {
    echo_manager_header();

    // Construct and submit the search query.

    if ($_GET['show'] == 'search') {
        $condition = $search_form->get_widget_contribution();
    } elseif ($_GET['show'] == "site_active") {
        $condition = $PROJECT_IS_ACTIVE_sql;
    } elseif ($_GET['show'] == "user_all") {
        $condition = "username = '$pguser'";
    } else {
        // ($_GET['show'] == "user_active")
        // plus some corner cases
        $condition = "$PROJECT_IS_ACTIVE_sql AND username = '$pguser'";
    }

    $n_results_per_page = $search_form->get_page_size();

    $results_offset = intval(@$_GET['results_offset']);

    $state_collater = sql_collater_for_project_state('state');
    $sql = "
        SELECT SQL_CALC_FOUND_ROWS *
        FROM projects
        WHERE $condition
        ORDER BY $state_collater, nameofwork asc
        LIMIT $n_results_per_page OFFSET $results_offset
    ";
    // echo "<pre>\n$sql\n</pre>\n";
    $result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

    $numrows = mysqli_num_rows($result);

    $res_found = mysqli_query(DPDatabase::get_connection(), "SELECT FOUND_ROWS()");
    $row = mysqli_fetch_row($res_found);
    $num_found_rows = $row[0];

    echo "<h1>", _("Search Results"), "</h1>\n";

    if ( $numrows == 0 )
    {
        echo _("<b>No projects matched the search criteria.</b>");
        return;
    }

    // -------------------------------------------------------------

    // Present the results of the search query.

    function results_navigator()
    {
        global $n_results_per_page, $results_offset, $numrows, $num_found_rows;

        // The REQUEST_URI must have at least one query-string parameter,
        // otherwise the response would have been just the search form,
        // and this function wouldn't have been called.
        $url_base = $_SERVER['REQUEST_URI'] . '&';
        $url_base = preg_replace('/results_offset=[^&]*&/', '', $url_base);

        if ( $results_offset > 0 )
        {
            $t = _('Previous');
            $prev_offset = max(0, $results_offset - $n_results_per_page );
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
            $next_offset = $results_offset + $n_results_per_page;
            $url = $url_base . "results_offset=$next_offset";
            echo " | <a href='$url'>$t</a>";
        }
    }

    results_navigator();

    $user_can_see_download_links = user_can_work_in_stage($pguser, 'PP');
    $show_options_column = $user_can_see_download_links || user_is_PM();

    echo "<center><table border=1 width='99%' cellpadding=0 cellspacing=0 style='border: 1px solid #111; border-collapse: collapse'>";

    function echo_header_cell( $width, $text )
    {
        global $theme;
        echo "<td width='$width' align='center' bgcolor='{$theme['color_headerbar_bg']}'>";
        echo "<font color='{$theme['color_headerbar_font']}'>";
        echo "<b>$text</b>";
        echo "</font>";
        echo "</td>";
        echo "\n";
    }

    echo "<tr>";
    echo_header_cell( 175, _("Title") );
    echo_header_cell( 100, _("Author") );
    // TRANSLATORS: Abbreviation for difficulty
    echo_header_cell( 25, _("Diff.") );
    echo_header_cell( 50, _("Avail. Pages") );
    echo_header_cell( 50, _("Total Pages") );
    echo_header_cell(  75, pgettext("project manager", "PM") );
    echo_header_cell(  75, _("Checked Out By") );
    echo_header_cell( 180, _("Project Status") );
    if ( $show_options_column )
    {
        echo_header_cell(  30, _("Options") );
    }
    echo "</tr>";

    // Determine whether to use special colors or not
    // (this does not affect the alternating between two
    // background colors) in the project listing.
    $userSettings =& Settings::get_Settings($pguser);
    $show_special_colors = !$userSettings->get_boolean('hide_special_colors');

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
        echo_project_state_changer($project);
        echo "</td>\n";

        // Options
        if ( $show_options_column )
        {
            echo "<td align=center>";
            if ( user_is_a_sitemanager() || user_is_proj_facilitator() || $project->username == $pguser )
            {
                echo _("Edit") . ":";
                echo " ";
                echo "<a href=\"editproject.php?action=edit&project=$projectid\">" . _("Info") . "</a>";
                echo " | ";
                echo "<a href=\"edit_project_word_lists.php?projectid=$projectid\">" . _("Word&nbsp;Lists") . "</a>";

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

    results_navigator();

    // special colours legend
    // Don't display if the user has selected the
    // setting "Show Special Colors: No".
    // The legend has been put at the bottom of the page
    // because the use of colors is presumably mostly
    // useful as a check that no typo was made. The
    // exact color probably doesn't matter and,
    // furthermore, the PM 'knows' the project and
    // what's so special about it.
    if (!$userSettings->get_boolean('hide_special_colors')) {
        echo_special_legend(" 1 = 1");
    }
}
echo "<br>";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_project_state_changer($project)
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

        echo_project_state_option( $project->state, 1 );

        foreach ( $transitions as $transition )
        {
            echo_project_state_option( $transition->to_state, 0 );
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

function echo_project_state_option($project_state,$selected)
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

// -----------------------------------------------------------------------------

function escape_value($value)
{
    return mysqli_real_escape_string(DPDatabase::get_connection(), $value);
}

// vim: sw=4 ts=4 expandtab
?>
