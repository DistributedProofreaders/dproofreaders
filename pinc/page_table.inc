<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'theme.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'project_states.inc'); // get_phase_containing_project_state
include_once($relPath.'page_tally.inc');
include_once($relPath.'../tools/proofers/PPage.inc'); // url_for_pi_do_particular_page
include_once($relPath.'misc.inc'); // surround_and_join, attr_safe
include_once($relPath.'links.inc'); // private_message_link

// Given a project, return the rounds that contain useful data
function get_rounds_to_display($project)
{
    $project_round = get_Round_for_project_state($project->state);

    // This project may have skipped some rounds, and/or it may have rounds
    // yet to do, so there may be some round-columns with no data in them.
    // Figure out which ones to display.
    //
    $rounds_to_display = get_rounds_with_data($project->projectid);

    // If the project is in a round, then users expect that round to appear here,
    // even if there's no data in it.
    //
    if ( !is_null($project_round) )
    {
        // Look for $project_round in $rounds_to_display.
        // If it does appear, it will normally be the last item,
        // but check everywhere, just in case.
        $found = FALSE;
        foreach ( $rounds_to_display as $round )
        {
            if ( $round->id == $project_round->id )
            {
                $found = TRUE;
                break;
            }
        }
        if ( !$found )
        {
            $rounds_to_display[] = $project_round;
        }
    }

    return $rounds_to_display;
}

/*
Generator to return page table data for a project, one page at a time.
Data is returned in an multi-level associative array that abstracts away
the lower-level database structure. The returned format looks like:

{
    "image": "018.png",
    "image_url": "https://www.pgdp.net/projects/projectID460978cb8e116/018.png",
    "state": "P3.page_saved",
    "image_size": 45541,
    "pagerounds": {
        "OCR": {
            "page_size": "1445"
        },
        "P1": {
            "page_size": "1446",
            "is_diff": "0",
            "username": "username",
            "user_page_tally": "117"
            "wordcheck_ran": "0",
            "modified_timestamp": "1175026200",
        },
        "P2": {
            ...
        },
        ...
    }
}
*/
function fetch_page_table_data($project, $page_selector = NULL, $proofed_in_round = NULL)
{
    $rounds_to_display = get_rounds_to_display($project);

    $fields_to_get = "{$project->projectid}.image AS image, length(master_text), state";
    $tables = $project->projectid;
    $prev_text_column_name = 'master_text';
    foreach ( $rounds_to_display as $round )
    {
        $rn = $round->round_number;

        $tallyboard = new TallyBoard( $round->id, 'U' );
        list($joined_with_user_page_tallies,$user_page_tally_column) =
            $tallyboard->get_sql_joinery_for_current_tallies( "users$rn.u_id" );

        if(is_formatting_round($round))
        {
            $wordcheck_query = "NULL as wordcheck_status$rn";
        }
        else
        {
            $wordcheck_query = sprintf("
                (
                    SELECT count(*)
                    FROM wordcheck_events
                    WHERE projectid = '%s' AND
                        image = {$project->projectid}.image AND
                        username = $round->user_column_name AND
                        round_id = '%s'
                ) as wordcheck_status$rn
            ", DPDatabase::escape($project->projectid),
                DPDatabase::escape($round->id));
        }
        $fields_to_get .= ",
            $prev_text_column_name = BINARY $round->text_column_name AS $round->textdiff_column_name,
            $round->time_column_name,
            $round->user_column_name,
            $user_page_tally_column AS `$rn.user_page_tally`,
            length($round->text_column_name),
            $wordcheck_query
        ";
        $tables .= "
            LEFT OUTER JOIN users AS users$rn
                ON (users$rn.username = $round->user_column_name)
            $joined_with_user_page_tallies
        ";
        $prev_text_column_name = $round->text_column_name;
    }

    if ( is_null($page_selector) )
    {
        // Select all pages
        $where_condition = "1";
    }
    elseif ( is_string($page_selector) )
    {
        // Select all pages of a particular user.
        $username_for_selecting_pages = $page_selector;

        $where_condition = "0";
        $work_round = get_Round_for_round_id($proofed_in_round);
        if (is_null($proofed_in_round) || is_null($work_round))
        {
            // want pages that the specified user did in any round
            foreach ( $rounds_to_display as $round )
            {
                $where_condition .= sprintf("
                    OR $round->user_column_name = '%s'
                ", DPDatabase::escape($username_for_selecting_pages));
            }
        }
        else
        {
            // want only pages that the specified user did in the specified round
            $where_condition .= sprintf("
                OR $work_round->user_column_name = '%s'
            ", DPDatabase::escape($username_for_selecting_pages));

        }
        $page_selector = NULL;
    }
    else
    {
        // $page_selector is an array whose keys are imagenames of the pages to select
        $imagenames_str = surround_and_join( array_keys($page_selector), "'", "'", "," );
        $where_condition = "image IN ($imagenames_str)";
    }

    $sql = "
        SELECT $fields_to_get
        FROM $tables
        WHERE $where_condition
        ORDER BY image ASC
    ";
    $res = DPDatabase::query($sql);
    while($row = mysqli_fetch_assoc($res))
    {
        // reconstruct the row data into a structured format
        $data = [
            "image" => $row['image'],
            "image_url" => "{$project->url}/{$row['image']}",
            "image_size" => $project->get_image_file_size($row['image']),
            "state" => $row['state'],
        ];

        $pagerounds = [
            "OCR" => [
                "page_size" => $row["length(master_text)"],
            ],
        ];

        foreach ( $rounds_to_display as $round )
        {
            $rn = $round->round_number;

            $wordcheck_status = $row["wordcheck_status$rn"];
            if($wordcheck_status == NULL)
            {
                $wordcheck_ran = NULL;
            }
            else
            {
                $wordcheck_ran = $wordcheck_status > 0;
            }

            $pagerounds[$round->id] = [
                "page_size" => $row["length($round->text_column_name)"],
                "is_diff" => $row[$round->textdiff_column_name] == 1,
                "username" => $row[$round->user_column_name],
                "user_page_tally" => $row["$rn.user_page_tally"],
                "wordcheck_ran" => $wordcheck_ran,
                "modified_timestamp" => $row[$round->time_column_name],
            ];
        }
        $data['pagerounds'] = $pagerounds;
        yield $data;
    }
}

function echo_page_table(
    $project,
    $show_image_size,
    $disable_editing = FALSE,
    $page_selector = NULL ,
    $proofed_in_round = NULL)
{
    global $projects_dir, $code_url, $pguser;

    $can_edit = $project->can_be_managed_by_current_user;

    // want PPers able to see names
    $can_see_names_for_all_pages = $project->names_can_be_seen_by_current_user;

    if ( $disable_editing )
    {
        // Although the user may be allowed to edit the project,
        // this particular page should not let them do so.
        $can_edit = FALSE;
        // But they still get to see names.
    }

    $projectid = $project->projectid;
    $state = $project->state;

    $project_phase = get_phase_containing_project_state($state);

    $show_delete = (($project_phase=='NEW' || $project_phase=='PR') && $can_edit) || user_is_proj_facilitator();

    $project_round = get_Round_for_project_state($project->state);

    $rounds_to_display = get_rounds_to_display($project);

    $upload_colspan = 2 + $show_image_size;

    if ( $can_edit )
    {
        echo "<form name='pagesform' method='post' action='$code_url/tools/project_manager/edit_pages.php'>\n";
        echo "<input type='hidden' name='projectid' value='$projectid'>\n";
    }

    echo "<table class='themed theme_striped pagedetail' id='page_table'>\n";

    $n_cols_per_round = 3; // Diff, Date, Text
    // Even if the user can't see names for all pages, they can see names
    // for the pages they've worked on, so we always include the User column.
    if (TRUE)           $n_cols_per_round += 1; // User

    // Top header row
    {
        echo "<tr>\n";
        if ($can_edit)
        {
            echo "    <td>&nbsp;</td>\n";
        }
        echo "    <td>&nbsp;</td>\n";
        echo "    <td colspan='$upload_colspan'>"._("Upload")."</td>\n";
        echo "    <td>&nbsp;</td>\n";
        foreach ( $rounds_to_display as $round )
        {
            if ($project_phase == 'PAGE_EDITING' && $round->id == $project_round->id)
            {
                $n_cols_per_round++;
            }

            echo "    <td colspan='$n_cols_per_round'>$round->id</td>\n";
        }
        echo "</tr>\n";
    }

    // Bottom header row
    {
        echo "<tr>\n";

        $td_start = "<th>";
        $td_end   = "</th>\n";

        if ($can_edit)
        {
            echo "{$td_start}X{$td_end}";
        }
        echo "{$td_start}I{$td_end}";

        echo "{$td_start}"._("Image")."{$td_end}";
        if ($show_image_size)
        {
            echo "{$td_start}"._("Size")."{$td_end}";
        }

        echo "{$td_start}"._("Text")."{$td_end}";

        echo "{$td_start}"._("Page State")."{$td_end}";

        foreach ( $rounds_to_display as $round )
        {
            echo "{$td_start}"._("Diff")."{$td_end}";
            echo "{$td_start}"._("Date")."{$td_end}";
            if (TRUE)
            {
                echo "{$td_start}"._("User")."{$td_end}";
            }
            echo "{$td_start}"._("Text")."{$td_end}";
        }

        if ($project_phase == 'PAGE_EDITING')
        {
            echo "{$td_start}"._("Edit")."{$td_end}";
        }

        if ($can_edit)
        {
            echo "{$td_start}"._("Clear")."{$td_end}";
            echo "{$td_start}"._("Bad/Fix")."{$td_end}";
        }

        if ($show_delete)
        {
            echo "{$td_start}"._("Delete")."{$td_end}";
        }
    }

    echo "</tr>";

    $avail_pages = [];

    $row_count = 0;
    foreach(fetch_page_table_data($project, $page_selector, $proofed_in_round) as $page_res)
    {
        $row_count += 1;

        $imagename = $page_res['image'];

        echo "<tr>";

        // --------------------------------------------
        // Selector
        if ( $can_edit )
        {
            echo "<td><input type='checkbox' name='selected_pages[$imagename]'></td>\n";
        }

        // --------------------------------------------
        // Index
        echo "<td style='text-align: right'>$row_count</td>\n";

        // --------------------------------------------
        // Upload Block

        // Image
        $cell_class_attr = '';
        $imagesize = $page_res["image_size"];
        if($imagesize === 0)
        {
            $cell_class_attr = "class='error'";
        }
        elseif($imagesize === NULL)
        {
            $cell_class_attr = "class='error'";
            $imagesize = "X";
        }

        echo "<td $cell_class_attr><a href='$code_url/tools/page_browser.php?project=$projectid&amp;imagefile=$imagename'>$imagename</a></td>\n";

        // Image Size
        if ($show_image_size)
        {
            echo "<td $cell_class_attr style='text-align: right'>$imagesize</td>";
        }

        // Master Text
        $master_text_length = $page_res["pagerounds"]["OCR"]["page_size"];
        echo "<td style='text-align: right'><a href='$code_url/tools/project_manager/downloadproofed.php?project=$projectid&amp;image=$imagename&amp;round_num=0'>$master_text_length&nbsp;b</a></td>\n";

        // --------------------------------------------

        // Page State
        $page_state = $page_res['state'];
        echo "<td>$page_state</td>\n";

        if (page_state_is_an_avail_state($page_state))
            $avail_pages[] = $row_count;

        // --------------------------------------------
        // Per-Round Info

        if ($can_see_names_for_all_pages)
        {
            $can_see_names_for_this_page = TRUE;
        }
        else
        {
            // The user can see the names of all the proofreaders for this page
            // only if they are one of those proofreaders.
            $can_see_names_for_this_page = FALSE;
            foreach ( $rounds_to_display as $round )
            {
                $proofer_name = $page_res["pagerounds"][$round->id]["username"];
                if ( $proofer_name != '' && $proofer_name == $pguser )
                {
                    $can_see_names_for_this_page = TRUE;
                    break;
                }
            }
        }

        $prev_rn = 0;
        foreach ( $rounds_to_display as $round )
        {
            $rn = $round->round_number;
            echo_cells_for_round($rn, $prev_rn, $page_res, $projectid, $can_see_names_for_this_page);
            $prev_rn = $rn;
        }

        // (It's annoying that we have to pass all those args,
        // or else make them global, which I don't want to do.)

        // Bad Page
        $page_is_in_bad_state = page_state_is_a_bad_state($page_state);

        // --------------------------------------------
        // Link to proofreading interface
        if ($project_phase == 'PAGE_EDITING')
        {
            if (!$page_is_in_bad_state
                && $page_res["pagerounds"][$project_round->id]["username"] == $pguser
                && $page_state != $project_round->page_avail_state)
            {

                $url = url_for_pi_do_particular_page(
                    $projectid, $state, $imagename, $page_state, TRUE );
                echo "<td><a href='$url'>"._("Edit")."</a></td>\n";
            }
            else
                echo "<td></td>";
        }

        // --------------------------------------------
        // PM/Admin project editing
        if ($can_edit) {

            // Clear
            $clear_string = " ";
            // Anticipate the tests in page_clear()
            // and only make a link if page_clear will do something
            if (
                $project_phase=='PAGE_EDITING' &&
                $page_state == $project_round->page_save_state
            )
            {
                $clear_string = "<a href='$code_url/tools/project_manager/edit_pages.php?projectid=$projectid&amp;selected_pages[$imagename]=on&amp;operation=clear'>"._("Clear")."</a>";
            }
            echo "<td>$clear_string</td>";

            echo "<td>";
            if ($page_is_in_bad_state) {
                echo "<b>";
                echo "<a href='$code_url/tools/project_manager/handle_bad_page.php?projectid=$projectid&amp;image=$imagename'>" . _("Bad") . "</a>";
                echo "</b>";
            } else {
                echo "<a href='$code_url/tools/project_manager/handle_bad_page.php?projectid=$projectid&amp;image=$imagename'>" . _("Fix") . "</a>\n";
            }
            echo "</td>\n";

            // Delete
            if ($show_delete)
            {
                echo "<td><a href='$code_url/tools/project_manager/edit_pages.php?projectid=$projectid&amp;selected_pages[$imagename]=on&amp;operation=delete'>" . _("Delete") . "</a></td>\n";
            }
        }

        echo "</tr>";
    }
    echo "</table>";

    if ( $can_edit )
    {
?>
<script><!--
let avail_pages = <?php echo json_encode($avail_pages); ?>;
let num_rows = <?php echo $row_count; ?>;

function changeSelection(sel) {
    switch(sel) {
        case "all":
            for(i=1; i<=num_rows; i++)
                document.pagesform.elements[i].checked=true;
            break;
        case "unproofed":
            changeSelection("clear");
            for(i in avail_pages)
                document.pagesform.elements[avail_pages[i]].checked=true;
            break;
        case "invert":
            for(i=1; i<=num_rows; i++)
                document.pagesform.elements[i].checked=!document.pagesform.elements[i].checked;
            break;
        case "clear":
            for(i=1; i<=num_rows; i++)
                document.pagesform.elements[i].checked=false;
            break;
        default:
            break;
    }
}
// --></script>
<?php
        echo "<br>\n";
        echo "<select name='select' onChange='changeSelection(this.options[this.selectedIndex].value)'>\n";
        echo "<option value='0'>"._("Select...")."</option>";
        echo "<option value='all'>"._("Select all")."</option>";
        echo "<option value='unproofed'>"._("Select all unproofread")."</option>";
        echo "<option value='invert'>"._("Invert selection")."</option>";
        echo "<option value='clear'>"._("Clear selection")."</option>";
        echo "</select>. ";
        echo _("For each selected page:") . "\n";
        echo "<select name='operation'>\n";
        // echo "  <option value='bad'   >" . _("Mark as bad") . "</option>\n";
        echo "  <option value='clear' >" . _("Clear effects of current round") . "</option>\n";
        if($show_delete)
        {
            echo "  <option value='delete'>" . _("Delete") . "</option>\n";
        }
        echo "</select>\n";
        echo "<input type='submit' value='" . attr_safe(_("Go")) . "'>\n";
        echo "</form>\n";

        // We can't easily do a multipage mark-as-bad,
        // because it requires a per-page reason.
    }

}

// -----------------------------------------------------------------------------

function get_rounds_with_data( $projectid )
// Returns an array of Rounds representing those rounds
// for which the given project has some useful data.
{
    $rounds_with_data = array();

    // Currently, when a project skips a round, the time and text of the
    // corresponding slot are still set. It's this data that we exclude by
    // talking about "useful data" above.
    // So a column of empty users is the best indication of a skipped round.
    // (And also works for a not-yet-reached round.)

    global $Round_for_round_id_;

    $sums = array();
    foreach ( $Round_for_round_id_ as $round_id => $round )
    {
        $sums[] = "SUM({$round->user_column_name} != '') AS $round_id";
    }
    $body = join($sums,',');

    validate_projectid($projectid);
    $sql = "
        SELECT $body
        FROM $projectid
    ";
    $res = DPDatabase::query($sql);
    $num_filled_user_fields_for_round_ = mysqli_fetch_assoc($res);

    foreach ( $Round_for_round_id_ as $round_id => $round )
    {
        if ( $num_filled_user_fields_for_round_[$round_id] == 0 )
        {
            // no real info in this slot
            // (skipped round or not-yet-reached round)
        }
        else
        {
            // There's some real info in this slot
            $rounds_with_data[] = $round;
        }
    }

    return $rounds_with_data;
}

// -----------------------------------------------------------------------------

function echo_cells_for_round( $round_num, $diff_round_num, // <- These are the only "real" params.
    $page_res,
    $projectid, $can_see_names_for_this_page)
{
    global $pguser, $code_url;

    $imagename  = $page_res['image'];
    $page_state = $page_res['state'];

    $round = get_Round_for_round_number($round_num);
    assert( !is_null($round) );

    $round_data = $page_res["pagerounds"][$round->id];
    $R_username = $round_data["username"];

    $cell_class_attr = "";

    // colour-code pages proofread by the user
    if ($R_username == $pguser) {

        // a page saved as DONE by the user has a greenish background
        if ($page_state == $round->page_save_state) {
            $cell_class_attr = "class='done_current'";
        }

        // a page saved as IN PROGRESS by the user has an orangey background
        else if ($page_state == $round->page_temp_state
            || $page_state == $round->page_out_state) {
            $cell_class_attr = "class='in_progress'";
        }

        // a page by the user but not available for editing has a reddish background
        else if ($page_state != $round->page_avail_state)
        {
            $cell_class_attr = "class='done_previous'";
        }
    }

    // ------------------------------
    // Diff

    echo "<td>";
    if ($page_state != $round->page_avail_state) {
        if($round_data["is_diff"]) {
            echo _("No diff");
        } else {
            echo "<a href='$code_url/tools/project_manager/diff.php?project=$projectid&amp;image=$imagename&amp;L_round_num=$diff_round_num&amp;R_round_num=$round_num'>"._("Diff")."</a>";
        }
    }
    echo "</td>\n";


    // ------------------------------
    // Date

    $R_time = $round_data["modified_timestamp"];
    if ($R_time == 0)
    {
        $R_time_str = '';
    }
    else
    {
        // TRANSLATORS: This is an strftime-formatted string
        $R_time_str = strftime(_("%Y %b %e %H:%M"), $R_time);
    }
    echo "<td>$R_time_str</td>\n";

    // ------------------------------
    // User

    if ($R_username == '')
    {
        echo "<td></td>\n";
    }
    else if ($can_see_names_for_this_page)
    {
        $R_pages_completed = $round_data["user_page_tally"];

        if ($round_data["wordcheck_ran"] === NULL)
        {
            $wordcheck_status = '';
        }
        elseif ($round_data["wordcheck_ran"])
        {
            $wordcheck_status = '&nbsp;<span title="' . _('This page was WordChecked.') . '">&check;</span>';
        }
        else
        {
            $wordcheck_status = '&nbsp;<span title="' . _('This page was not WordChecked.') . '">&#x2717;</span>';
        }

        echo "<td $cell_class_attr>" . private_message_link($R_username,null) . " ($R_pages_completed)$wordcheck_status</td>\n";
    }
    else
    {
        echo "<td></td>\n";
    }

    // ------------------------------
    // Text

    $R_text_length = $round_data["page_size"];

    if ( $R_text_length == 0 )
    {
        echo "<td $cell_class_attr> </td>\n";
    }
    else
    {
        echo "<td style='text-align: right' $cell_class_attr><a href='$code_url/tools/project_manager/downloadproofed.php?project=$projectid&amp;image=$imagename&amp;round_num=$round_num'>$R_text_length&nbsp;b</a></td>\n";
    }
}

// -----------------------------------------------------------------------------

function page_state_is_an_avail_state($page_state)
{
    for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
    {
        $round = get_Round_for_round_number($rn);
        if ( $page_state == $round->page_avail_state )
        {
            return TRUE;
        }
    }
    return FALSE;
}

function page_state_is_a_bad_state($page_state)
{
    for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
    {
        $round = get_Round_for_round_number($rn);
        if ( $page_state == $round->page_bad_state )
        {
            return TRUE;
        }
    }
    return FALSE;
}

// -----------------------------------------------------------------------------

function echo_detail_legend()
{
?>
<table class='pagedetail'>
    <tr>
        <td colspan='3'><?php echo _("Pages proofread by you and..."); ?></td>
    </tr>
    <tr>
        <td class='in_progress'><?php echo _("IN PROGRESS (awaiting completion this round)"); ?></td>
        <td class='done_current'><?php echo _("DONE (still available for editing this round)"); ?></td>
        <td class='done_previous'><?php echo _("DONE in a previous round (no longer available for editing)"); ?></td>
    </tr>
</table>
<?php
}

// vim: sw=4 ts=4 expandtab
