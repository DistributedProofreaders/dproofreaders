<?
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once('projectmgr.inc');
include_once('projectmgr_select.inc');



if (empty($_GET['show']) && empty($_GET['up_projectid'])) {
    if ($userP['i_pmdefault'] == 0) {
        metarefresh(0,"projectmgr.php?show=all","","");
        exit();
    } elseif ($userP['i_pmdefault'] == 1) {
        metarefresh(0,"projectmgr.php?show=user_active","", "");
        exit();
    }
}

$can_see_all = user_is_a_sitemanager() || user_is_proj_facilitator();

theme(_("Project Managers"), "header");

abort_if_not_manager();



if ((!isset($_GET['show']) && (!isset($_GET['up_projectid']))) ||
    $_GET['show'] == 'search_form' ||
    ($_GET['show'] == '' && $_GET['up_projectid'] == '' )) {

    echo_manager_header('project_search_page');

    echo "
        <center>
        "._("Search for projects matching the following criteria:")."<br>
        <form method=get action='projectmgr.php'>
        <input type='hidden' name='show' value='search'>
        <table>
        <tr>
            <td>"._("Title")."</td>
            <td><input type='text' name='title'></td>
        </tr>
        <tr>
            <td>"._("Author")."</td>
            <td><input type='text' name='author'></td>
        </tr>
        <tr>
            <td>"._("Language")."</td>
            <td>
            <select name='language'>
                <option value='' selected>"._("Any")."</option>
    ";
    foreach($lang_list as $k=>$v)
        echo "<option value='{$v['lang_name']}'>{$v['lang_name']}</option>\n";
    echo "
            </select>
            </td>
        </tr>
    ";
    if ($can_see_all)
    {
        echo "
        <tr>
            <td>"._("Project Manager")."</td>
            <td><input type='text' name='project_manager'></td>
        </tr>
        ";
    }
    echo "
        <tr>
            <td>"._("Project ID")."</td>
            <td><input type='text' name='projectid'></td>
        </tr>
    ";
    // In the <select> tag, we set the name attribute to 'state[]'.
    // I'm pretty sure this doesn't mean anything to HTML/HTTP,
    // but PHP takes it as a cue to make the multiple values of
    // the select control available as an array.
    // That is, $_GET['state'] will be an array containing
    // all selected values.
    echo "
        <tr>
            <td>"._("State")."</td>
            <td>
            <select name='state[]' multiple>
            <option value=''>any state</option>
    ";
    foreach ($PROJECT_STATES_IN_ORDER as $proj_state_in_order)
    {
        echo "<option value='$proj_state_in_order'>";
        echo project_states_text($proj_state_in_order);
        echo "</option>\n";
    }
    echo "
            </select>
            </td>
        </tr>
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
        "._("Matching [except for State] is case-insensitive and unanchored;<br>
        so, for instance, 'jim' matches both 'Jimmy Olsen' and 'piggyjimjams'.<br>
        <br>
        If desired, you should be able to select<br>
        multiple values for State (e.g., by holding down Ctrl).")."
        </center>
    ";
} else {
    echo_manager_header('project_listings_page');

    // Construct and submit the search query.

    if ($_GET['show'] == "site" && $can_see_all) {
        $condition = "state != '".PROJ_SUBMIT_PG_POSTED."'";
    } elseif ($_GET['show'] == "allfor" && $can_see_all && isset($_GET['up_projectid'])) {
        $condition = " 1 ";
    } elseif ($_GET['show'] == "all") {
        $condition = "username = '$pguser'";
    } elseif ($_GET['show'] == 'search') {
        $condition = '1';
        if ( $_GET['title'] != '' )
        {
            $condition .= " AND nameofwork LIKE '%{$_GET['title']}%'";
        }
        if ( $_GET['author'] != '' )
        {
            $condition .= " AND authorsname LIKE '%{$_GET['author']}%'";
        }
        if ( $_GET['language'] != '' )
        {
            $condition .= " AND language LIKE '%{$_GET['language']}%'";
        }
        if ($can_see_all)
        {
            if ( $_GET['project_manager' ] != '' )
            {
                $condition .= " AND username LIKE '%{$_GET['project_manager']}%'";
            }
        }
        else
        {
            // The user is a project manager, not a site admin or project facilitator
            // so they can only see their own projects.
            $condition .= " AND username='$pguser'";
        }
        if ( $_GET['projectid'] != '' )
        {
            $condition .= " AND projectid LIKE '%{$_GET['projectid']}%'";
        }
        if ( isset($_GET['state']) && count($_GET['state']) > 0 )
        {
            $condition .= " AND (0";
            foreach( $_GET['state'] as $state )
            {
                if ( $state == '' )
                {
                    $condition .= " OR 1";
                }
                else
                {
                    $condition .= " OR state='$state'";
                }
            }
            $condition .= ")";
        }
    } else {
        $condition = "state != '".PROJ_SUBMIT_PG_POSTED."' AND username = '$pguser'";
    }


    if (isset($_GET['up_projectid'])) {
        $up_projectid = $_GET['up_projectid'];
        $can_see_this_uber = $can_see_all;
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

    $result = mysql_query("
        SELECT *
        FROM projects
        WHERE $condition
        ORDER BY nameofwork asc
    ") or die(mysql_error());

    $numrows = mysql_num_rows($result);
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
        while ( $project = mysql_fetch_assoc($result) )
        {
            if ( $project['state'] == $curr_state )
            {
                $projectids[] = $project['projectid'];
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
        }
    }

    option_to_move( PROJ_NEW, PROJ_P1_UNAVAILABLE );
    option_to_move( PROJ_P1_UNAVAILABLE, PROJ_P1_WAITING_FOR_RELEASE );

    // -------------------------------------------------------------

    // Present the results of the search query.

    $show_pages_total = 1;

    echo "<center><table border=1 width=630 cellpadding=0 cellspacing=0 style='border-collapse: collapse' bordercolor=#111111>";

    function echo_header_cell( $width, $text )
    {
        global $theme;
        echo "<td width='$width' align='center' bgcolor='{$theme['color_headerbar_bg']}'>";
        echo "<font color='{$theme['color_headerbar_font']}'>";
        echo "<b>$text</b>";
        echo "</font>";
        echo "</td>";
    }

    echo "<tr>";
    echo_header_cell( 175, _("Title") );
    echo_header_cell( 100, _("Author") );
    echo_header_cell( 25, _("Diff.") );
    if ( $show_pages_total )
    {
        echo_header_cell( 50, _("Total") );
    }
    echo_header_cell(  75, ($_GET['show'] == "site" ? _("PM") : _("Owner") ) );
    echo_header_cell( 180, _("Project Status") );
    echo_header_cell(  50, _("Options") );
    echo "</tr>";

    // Determine whether to use special colors or not
    // (this does not affect the alternating between two
    // background colors) in the project listing.
    $userSettings = Settings::get_Settings($pguser);
    $show_special_colors = !$userSettings->get_boolean('hide_special_colors');

    $tr_num = 0;
    foreach ($PROJECT_STATES_IN_ORDER as $proj_state_in_order)
    {
        // Reset internal row pointer (we know that $numrows > 0 so this is ok)
        mysql_data_seek($result, 0);
        while ($project = mysql_fetch_assoc($result)) {
            $state = $project['state'];
            if ($state == $proj_state_in_order)
            {
                $name = $project['nameofwork'];
                $author = $project['authorsname'];
                $diff = strtoupper(substr($project['difficulty'],0,1));
                $projectid = $project['projectid'];
                $outby = $project['checkedoutby'];
                $comments = $project['comments'];

                if ($tr_num % 2 ) {
                    $bgcolor = $theme['color_mainbody_bg'];
                } else {
                    $bgcolor = $theme['color_navbar_bg'];
                }

                // Special colours for special books of various types
                if ($show_special_colors)
                {
                    $special_color = get_special_color_for_project($project);
                    if (!is_null($special_color)) {
                        $bgcolor = "'$special_color'";
                    }
                }

                echo "<tr bgcolor=$bgcolor>\n";

                // Title
                echo "<td><a href='$code_url/project.php?id=$projectid&amp;detail_level=3'>$name</a></td>\n";

                // Author
                echo "<td>$author</td>\n";

                // Difficulty
                echo "<td align=\"center\">$diff</td>\n";


                // Total
                if ( $show_pages_total )
                {
                    $totpag = $project['n_pages'];

                    echo "<td align=\"center\">$totpag</td>\n";
                }


                // Owner
                echo "<td align=\"center\">";
                if ($_GET['show'] == 'site') {
                    print $project['username'];
                } else if ($outby != "") {
                    // Maybe we should get this info via a
                    // left outer join in the big select query.
                    $tempsql = mysql_query("SELECT email FROM users WHERE username = '$outby'");
                    $outbyemail = mysql_result($tempsql, 0, "email");
                    print "<a href=mailto:$outbyemail>$outby</a>";
                }
                echo "</td>\n";

                // Project Status

                if (user_is_a_sitemanager() or ($project['username']==$pguser) or user_is_proj_facilitator()) {

                    echo "
                        <td valign=center>
                        <form
                            name='$projectid'
                            method='get'
                            action='changestate.php'>
                            <input
                            type='hidden'
                            name='project'
                            value='$projectid'>
                            <select
                            name='state'
                            onchange='this.form.submit()'>
                    ";
                    getSelect($state);
                    echo "</select></form></td>\n";
                } else {
                    echo "<td valign=center>$state</td>\n";
                }

                // Options
                echo "<td align=center>";
                print "<a href=\"editproject.php?project=$projectid\">Edit</a>";
                if ($state==PROJ_POST_FIRST_UNAVAILABLE || $state==PROJ_POST_FIRST_AVAILABLE || $state==PROJ_POST_FIRST_CHECKED_OUT) print " <a href = \"$projects_url/$projectid/$projectid.zip\">D/L</A>";
                if (($state == PROJ_POST_SECOND_CHECKED_OUT) || ($state == PROJ_POST_COMPLETE)) print " <a href=\"$projects_url/$projectid/".$projectid."_second.zip\">D/L</A>";
                echo "</td>\n";

                echo "</tr>\n";

                $tr_num++;
            }
        }
    }
    echo "<tr><td colspan=7 bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td></tr></table></center>";

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
    // list_uber_projects( $can_see_all );
}
echo "<br>";
theme("","footer");

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function list_uber_projects( $can_see_all )
{
    global $pguser, $theme;

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
                AND state != '".PROJ_SUBMIT_PG_POSTED."'
                AND username = '".$pguser."'
            "));
            $num_active_proj = $num_active_proj_res['num'];

            $num_all_active_proj_res = mysql_fetch_assoc(mysql_query("
                SELECT count(*) as num
                FROM projects WHERE up_projectid = '$up_projid'
                AND state != '".PROJ_SUBMIT_PG_POSTED."'
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
            echo "<td align=\"center\"><a href='projectmgr.php?show=all&up_projectid=$up_projid'>$num_proj</a></td>\n";

            // Number of ALL active related projects
            // For SA/PFs this is a link to them, others just see the total
            if ($can_see_all) {
                $link_top = "<a href='projectmgr.php?show=site&up_projectid=".$up_projid."'>";
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
