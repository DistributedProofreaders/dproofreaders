<?
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'ProjectTransition.inc');
include_once($relPath.'genres.inc');
include_once('projectmgr.inc');

class Widget
{
    function Widget( $properties )
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
                $value_attr = "value='{$_GET[$this->id]}'";
            }
            else if ( isset($this->initial_value) )
            {
                $value_attr = "value='{$this->initial_value}'";
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
                $r .= "<option value='$option_value' $selected_attr>$option_label</option>\n";
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

                        // If the user picks the 'any' option as well as some others,
                        // it's as if they'd just picked the 'any' option.
                        if ( in_array( '', $values ) ) return NULL;
                    }

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

// -------------------------------------------------------------------

$special_day_options = array();
$special_day_options[''] = _('Any day');
$special_day_res = mysql_query("        
    SELECT
        spec_code,
        display_name,
        DATE_FORMAT(concat('2000-',open_month,'-',open_day),'%e %b')
    FROM special_days
    WHERE enable = 1
    ORDER BY open_month, open_day
");
while ( list($s_code, $s_display_name, $s_start) = mysql_fetch_row($special_day_res) )
{
    $special_day_options[$s_code] = "$s_display_name ($s_start)";
}

// ---------

$lang_options[''] = _('Any');
foreach($lang_list as $k=>$v)
{
    $lang_options[$v['lang_name']] = $v['lang_name'];
}

// ---------

$genre_options = array_merge( array( '' => _('any') ), $GENRES );

// ---------

$difficulty_options = array(
    ''         => _('any'),
    'beginner' => _('Beginner'),
    'easy'     => _('Easy'),
    'average'  => _('Average'),
    'hard'     => _('Hard'),
);

// ---------

$state_options[''] = _('any state');
foreach ($PROJECT_STATES_IN_ORDER as $proj_state)
{
    $state_options[$proj_state] = project_states_text($proj_state);
}

// ---------

define( 'DEFAULT_N_RESULTS_PER_PAGE', 100 );

$widgets = array(
    new Widget( array(
        'id'         => 'title',
        'label'      => _('Title'),
        'type'       => 'text',
        'q_part'     => 'WHERE',
        'q_contrib'  => array('nameofwork', 'LIKE'),
    )),
    new Widget( array(
        'id'         => 'author',
        'label'      => _('Author'),
        'type'       => 'text',
        'q_part'     => 'WHERE',
        'q_contrib'  => array('authorsname', 'LIKE'),
    )),
    new Widget( array(
        'id'         => 'language',
        'label'      => _('Language'),
        'type'       => 'select',
        'options'    => $lang_options,
        'can_be_multiple' => TRUE,
        'initial_value'   => '',
        'q_part'     => 'WHERE',
        'q_contrib'  => array('language', 'LIKE'),
    )),
    new Widget( array(
        'id'         => 'genre',
        'label'      => _('Genre'),
        'type'       => 'select',
        'options'    => $genre_options,
        'can_be_multiple' => TRUE,
        'q_part'     => 'WHERE',
        'q_contrib'  => array('genre', '='),
    )),
    new Widget( array(
        'id'         => 'difficulty',
        'label'      => _('Difficulty'),
        'type'       => 'select',
        'options'    => $difficulty_options,
        'can_be_multiple' => TRUE,
        'q_part'     => 'WHERE',
        'q_contrib'  => array('difficulty', '='),
    )),
    new Widget( array(
        'id'         => 'special_day',
        'label'      => _('Special day'),
        'type'       => 'select',
        'options'    => $special_day_options,
        'can_be_multiple' => TRUE,
        'initial_value'   => '',
        'q_part'     => 'WHERE',
        'q_contrib'  => array('special_code', '='),
    )),
    new Widget( array(
        'id'         => 'projectid',
        'label'      => _('Project ID'),
        'type'       => 'text',
        'size'       => 45, // big enough to show two projectids without scrolling.
        'can_be_multiple' => TRUE,
        'separator'  => '[\s,;]+',
        'q_part'     => 'WHERE',
        'q_contrib'  => array('projectid', 'LIKE'),
    )),
    new Widget( array(
        'id'         => 'project_manager',
        'label'      => _('Project Manager'),
        'type'       => 'text',
        'q_part'     => 'WHERE',
        'q_contrib'  => array('username', 'LIKE'),
    )),
    new Widget( array(
        'id'         => 'checkedoutby',
        'label'      => _('Checked out by'),
        'type'       => 'text',
        'q_part'     => 'WHERE',
        'q_contrib'  => array('checkedoutby', 'LIKE'),
    )),
    new Widget( array(
        'id'           => 'state',
        'label'        => _('State'),
        'type'         => 'select',
        'options'      => $state_options,
        'can_be_multiple' => TRUE,
        'q_part'       => 'WHERE',
        'q_contrib'    => array('state', '='),
    )),
    new Widget( array(
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

// -----------------------------------------------------------------------------

if (user_is_PM() && empty($_GET['show']) && empty($_GET['up_projectid'])) {
    if ($userP['i_pmdefault'] == 0) {
        metarefresh(0,"projectmgr.php?show=user_all","","");
        exit();
    } elseif ($userP['i_pmdefault'] == 1) {
        metarefresh(0,"projectmgr.php?show=user_active","", "");
        exit();
    }
}

theme(_("Project Search"), "header");

$PROJECT_IS_ACTIVE_sql = "(state NOT IN ('".PROJ_SUBMIT_PG_POSTED."','".PROJ_DELETE."'))";

if ((!isset($_GET['show']) && (!isset($_GET['up_projectid']))) ||
    $_GET['show'] == 'search_form' ||
    ($_GET['show'] == '' && $_GET['up_projectid'] == '' )) {

    echo_manager_header('project_search_page');
    
    echo "
        <center>
        <h1>Search for Projects</h1>
        "._("Search for projects matching the following criteria:")."<br>
        <form method=get action='projectmgr.php'>
        <input type='hidden' name='show' value='search'>
        <table>
    ";

    foreach ( $widgets as $widget )
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
                    <td align='left'><input type='submit' value='"._("Search")."'></td>
                    <td align='right'><input type='reset' value='"._("Clear form")."'></td>
                </tr>
                </table>
            </td>
        </tr>
        </table>
        </form>
        "._("For terms that you type in, matching is case-insensitive and unanchored; so, for instance, 'jim' matches both 'Jimmy Olsen' and 'piggyjimjams'.")."
        <br><br>
        "._('"(list ok)": You can search by multiple ProjectIDs at once: enter the list of ProjectIDs, separated by commas, semicolons, or spaces.')."
        <br><br>
        "._('"(multi-select)": If desired, you should be able to select multiple values for Language, Difficulty, Special Day, or State (e.g., by holding down Ctrl).')."
        </center>
    ";
} else {
    echo_manager_header('project_listings_page');

    // Construct and submit the search query.

    if ($_GET['show'] == 'search') {
        $condition = '1';
        foreach ( $widgets as $widget )
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
    } elseif ($_GET['show'] == "site_active") {
        $condition = $PROJECT_IS_ACTIVE_sql;
    } elseif ($_GET['show'] == "allfor" && isset($_GET['up_projectid'])) {
        $condition = " 1 ";
    } elseif ($_GET['show'] == "user_all") {
        $condition = "username = '$pguser'";
    } else {
        // ($_GET['show'] == "user_active")
        // plus some corner cases
        $condition = "$PROJECT_IS_ACTIVE_sql AND username = '$pguser'";
    }


    if (isset($_GET['up_projectid'])) {
        $up_projectid = $_GET['up_projectid'];
        $can_see_this_uber = TRUE;
        if (!$can_see_this_uber) {
            $UP_ok_qry = mysql_query("
            SELECT * FROM uber_projects up, usersettings us
            WHERE us.username = '$pguser' AND
                us.setting  = 'up_manager' AND
                us.value = up.up_projectid AND
                up.up_projectid > 0
            ");
            $can_see_this_uber = mysql_num_rows($UP_ok_qry);
        }
        if ($can_see_this_uber) {
            $condition .= " AND up_projectid = '$up_projectid' ";
        }
    }

    $n_results_per_page = @$_GET['n_results_per_page'];
    if ( $n_results_per_page == 0 ) $n_results_per_page = DEFAULT_N_RESULTS_PER_PAGE;

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
    $result = mysql_query($sql) or die(mysql_error());

    $numrows = mysql_num_rows($result);

    $res_found = mysql_query("SELECT FOUND_ROWS()");
    $num_found_rows = mysql_result($res_found,0);

    echo "<h1>Search Results</h1>\n";

    if ( $numrows == 0 )
    {
        echo _("<b>No projects matched the search criteria.</b>");
        theme("","footer");
        return;
    }

    // -------------------------------------------------------------

    function option_to_move( $curr_state, $new_state )
    {
        global $result;

        $projectids = array();
        while ( $project_assoc = mysql_fetch_assoc($result) )
        {
            if ( $project_assoc['state'] == $curr_state )
            {
                $projectids[] = $project_assoc['projectid'];
            }
        }
        mysql_data_seek($result, 0);

        if ( count($projectids) > 0 )
        {
            $curr_blurb = project_states_text($curr_state);
            $new_blurb  = project_states_text($new_state);
            $projectids_str = implode( ',', $projectids );

            echo "<a href='move_projects.php?curr_state=$curr_state&new_state=$new_state&projects=$projectids_str'>";
            echo _("Move all")." <b>$curr_blurb</b> "._("projects on this page to")." <b>$new_blurb</b>";
            echo "</a>";
            echo "<br>";
            echo "<br>";
            echo "\n";
        }
    }

    // Formerly, a user's search results could only contain projects
    // that the user could manage. Now that we've opened up the search page,
    // this is no longer true. E.g., the results may contain New projects
    // that the user does not have the authority to push to P1.unavail.
    // Thus, these links would be confusing/misleading. So comment them out.
    //
    // option_to_move( PROJ_NEW, PROJ_P1_UNAVAILABLE );
    // option_to_move( PROJ_P1_UNAVAILABLE, PROJ_P1_WAITING_FOR_RELEASE );

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
            _("Projects %d to %d of %d"),
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

    $show_pages_total = 1;

    $user_can_see_download_links = user_can_work_in_stage($pguser, 'PP');
    $show_options_column = $user_can_see_download_links || user_is_PM();

    echo "<center><table border=1 width=630 cellpadding=0 cellspacing=0 style='border-collapse: collapse' bordercolor=#111111>";

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
    echo_header_cell( 25, _("Diff.") );
    if ( $show_pages_total )
    {
        echo_header_cell( 50, _("Total") );
    }
    echo_header_cell(  75, _("PM") );
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
    $userSettings = Settings::get_Settings($pguser);
    $show_special_colors = !$userSettings->get_boolean('hide_special_colors');

    $tr_num = 0;
    while ($project_assoc = mysql_fetch_assoc($result)) {
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


        // Total
        if ( $show_pages_total )
        {
            $totpag = $project->n_pages;

            echo "<td align=\"center\">$totpag</td>\n";
        }


        // PM
        echo "<td align=\"center\">";
        if ( $project->username != '' )
        {
            $res_pm = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '{$project->username}'");
            $pm_user_id = mysql_result($res_pm,0);
            $contact_url = "$forums_url/privmsg.php?mode=post&amp;u=$pm_user_id";
            print "<a href='$contact_url'>{$project->username}</a>";
        }
        echo "</td>\n";

        // Checked Out By
        echo "<td align=\"center\">";
        if ($project->checkedoutby != "") {
            // Maybe we should get this info via a
            // left outer join in the big select query.
            // (Actually, I tried it in a few cases and the left outer join was always slower.)
            $tempsql = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '{$project->checkedoutby}'");
            $outby_user_id = mysql_result($tempsql, 0);
            $contact_url = "$forums_url/privmsg.php?mode=post&amp;u=$outby_user_id";
            print "<a href='$contact_url'>{$project->checkedoutby}</a>";
        }
        echo "</td>\n";

        // Project Status

        echo "<td valign=center>\n";
        echo_project_state_changer($project);
        echo "</td>\n";

        // Options
        if ( $show_options_column )
        {
            echo "<td align=center>";
            if ( user_is_a_sitemanager() || user_is_proj_facilitator() || $project->username == $pguser )
            {
                print "<a href=\"editproject.php?action=edit&project=$projectid\">Edit</a>";
            }
            if ( $user_can_see_download_links )
            {
                if ($project->state == PROJ_POST_FIRST_UNAVAILABLE ||
                    $project->state == PROJ_POST_FIRST_AVAILABLE ||
                    $project->state == PROJ_POST_FIRST_CHECKED_OUT)
                {
                    print " <a href=\"$projects_url/$projectid/$projectid.zip\">D/L</A>";
                }
                if ($project->state == PROJ_POST_SECOND_CHECKED_OUT ||
                    $project->state == PROJ_POST_COMPLETE)
                {
                    print " <a href=\"$projects_url/$projectid/".$projectid."_second.zip\">D/L</A>";
                }
            }
            echo "</td>\n";
        }

        echo "</tr>\n";

        $tr_num++;
    }

    echo "<tr><td colspan=8 bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td></tr></table></center>";
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
        echo "<p><font face='{$theme['font_mainbody']}'>\n";
        echo_special_legend(" 1 = 1");
        echo "</font></p><br>\n";
    }

    // Commented out until it's working.
    // list_uber_projects( TRUE );
}
echo "<br>";
theme("","footer");

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
        echo project_states_text($project->state), "\n";
    }
}

function echo_project_state_option($project_state,$selected)
{
    echo "<option value='$project_state'";
    if ($selected) echo " SELECTED";
    echo ">";
    if ($project_state == 'automodify')
    {
        echo 'automodify';
    }
    else
    {
        echo project_states_text($project_state);
    }
    echo "</option>\n";
}

// -----------------------------------------------------------------------------

function list_uber_projects( $can_see_all )
{
    global $pguser, $theme, $PROJECT_IS_ACTIVE_sql;

    // site managers and project facilitors can see all uber projects

    if ($can_see_all) {

        $UPs = mysql_query("
            SELECT * FROM uber_projects WHERE up_enabled = 1
        ");

    } else {

        // if the user is currently the UP_manager of any Uber Projects, display them

        // note that the Settings class can't handle lists of values, nor joins to other tables,
        // so we go directly to the user_settings table instead

        $UPs = mysql_query("
            SELECT * FROM uber_projects up, usersettings us
            WHERE us.username = '$pguser' AND
                us.setting  = 'up_manager' AND
                us.value = up.up_projectid
        ");

    }

    if (mysql_num_rows($UPs)) {

        $tr_num = 0;

        echo "<br><center><h3>"._("Uber Projects to which you have access")."</h3></center><br>";

        echo "<center><table border=1 width=630 cellpadding=0 cellspacing=0 style='border-collapse: collapse' bordercolor=#111111>";

        echo "<tr>";
        echo_header_cell( 300, _("Overall Name of Uber Project") );
        echo_header_cell( 75,  _("Your Active Related Projects") );
        echo_header_cell( 55,  _("All Your Related Projects") );
        echo_header_cell( 75,  _("All Active Related Projects") );
        echo_header_cell( 55,  _("All Related Projects") );
        echo_header_cell( 55,  _("Project Managers") );
        echo_header_cell( 30,  _("Forum Thread") );
        echo_header_cell( 30,  _("Options") );
        echo "</tr>";


        if (!$can_see_all) {
            $limit_to_user = " ";
        } else {
            $limit_to_user = " ";
        }

        while ($UPinfo = mysql_fetch_assoc($UPs)) {

            $up_projid = $UPinfo['up_projectid'];
            $up_name = $UPinfo['up_nameofwork'];
            $up_topicid = $UPinfo['up_topic_id'];

            // no one will have specific access to a large number of UPs,
            // and SA/PFs only see the list when they request it,
            // so these next few queries shouldn't be too expensive
            // in absolute terms, even though they are in a loop

            $num_active_proj_res = mysql_fetch_assoc(mysql_query("
                SELECT count(*) as num
                FROM projects WHERE up_projectid = '$up_projid'
                AND $PROJECT_IS_ACTIVE_sql
                AND username = '".$pguser."'
            "));
            $num_active_proj = $num_active_proj_res['num'];

            $num_all_active_proj_res = mysql_fetch_assoc(mysql_query("
                SELECT count(*) as num
                FROM projects WHERE up_projectid = '$up_projid'
                AND $PROJECT_IS_ACTIVE_sql
            "));
            $num_all_active_proj = $num_all_active_proj_res['num'];

            $num_proj_res = mysql_fetch_assoc(mysql_query("
                SELECT count(*) as num
                FROM projects WHERE up_projectid = '$up_projid'
                AND username = '".$pguser."'
            "));
            $num_proj = $num_proj_res['num'];

            $num_all_proj_res = mysql_fetch_assoc(mysql_query("
                SELECT count(*) as num
                FROM projects WHERE up_projectid = '$up_projid'
            "));
            $num_all_proj = $num_all_proj_res['num'];

            $num_PM_res = mysql_fetch_assoc(mysql_query("
                SELECT count(*) as num
                FROM usersettings WHERE setting = 'up_manager' and value = '$up_projid'
            "));
            $num_PM = $num_PM_res['num'];

            if ($tr_num % 2 ) {
                $bgcolor = $theme['color_mainbody_bg'];
            } else {
                $bgcolor = $theme['color_navbar_bg'];
            }

            echo "<tr bgcolor=$bgcolor>\n";

            // Name
            echo "<td>$up_name</td>\n";

            // Number of THIS USER'S active related projects  (NB SA/PFs are users too!)
            echo "<td align=\"center\"><a href='projectmgr.php?up_projectid=$up_projid'>$num_active_proj</a></td>\n";

            // Number of all of THIS USER'S related projects
            echo "<td align=\"center\"><a href='projectmgr.php?show=user_all&up_projectid=$up_projid'>$num_proj</a></td>\n";

            // Number of ALL active related projects
            // For SA/PFs this is a link to them, others just see the total
            if ($can_see_all) {
                $link_top = "<a href='projectmgr.php?show=site_active&up_projectid=".$up_projid."'>";
                $link_tail = "</a>";
            } else {
                $link_top = "";
                $link_tail = "";
            }
            echo "<td align=\"center\">".$link_top.$num_all_active_proj.$link_tail."</td>\n";

            // Number of ALL related projects
            // For SA/PFs this is a link to them, others just see the total
            if ($can_see_all) {
                $link_top = "<a href='projectmgr.php?show=allfor&up_projectid=".$up_projid."'>";
                $link_tail = "</a>";
            } else {
                $link_top = "";
                $link_tail = "";
            }
            echo "<td align=\"center\">".$link_top.$num_all_proj.$link_tail."</td>\n";

            // Number of project managers
            // could in a fancy future show SA/PFs a drop down list of PMs with
            // projects related to this UP, and let selection show the list
            // filtered by PM...
            echo "<td align=\"center\">$num_PM</td>\n";

            // link to Forum thread
            echo "<td>Click here</td>\n";

            // Options
            echo "<td>Edit / Create New</td>\n";
            echo "</tr>\n";

            $tr_num++;
        }

        echo "<tr><td colspan=8 bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td></tr></table></center>";
    }
}

// vim: sw=4 ts=4 expandtab
?>
