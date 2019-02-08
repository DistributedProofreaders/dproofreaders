<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'ThemedTable.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'misc.inc');

$title = _("Statistics Central");
output_header($title);

echo "<h1>" . _("Statistics Central") . "</h1>\n";

show_news_for_page("STATS");

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//Member/team stats searches and listings
?>

<table style='width: 95%; margin: 2em auto;'>
<tr>
    <td>
    <form action='<?php echo $code_url; ?>/stats/members/mbr_list.php' method='post'>
        <input type='text' name='uname' size='20' style='margin-left: 0;' required>
        <input type='submit' value='<?php echo attr_safe(_("Member Search")); ?>'>
        <br>
        <input type='checkbox' name='uexact' value='yes' style='margin-left: 0;'> <?php echo _("Exact match"); ?>
        <br><br>
        <a href='<?php echo $code_url; ?>/stats/members/mbr_list.php'><?php echo _("Member List"); ?></a>
    </form>
    </td>
    <td>
    <form action='<?php echo $code_url; ?>/stats/teams/tlist.php' method='post'>
        <input type='text' name='tname' size='20' style='margin-left: 0;' required>
        <input type='submit' value='<?php echo attr_safe(_("Team Search")); ?>'>
        <br>
        <input type='checkbox' name='texact' value='yes' style='margin-left: 0;'> <?php echo _("Exact match"); ?>
        <br><br>
        <a href='<?php echo $code_url; ?>/stats/teams/tlist.php'><?php echo _("Team List"); ?></a>
    </form>
    </td>
</tr>
</table>

<?php
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//General site stats with links to view the queue's

$table = new ThemedTable(
    3,
    _('General Site Statistics'),
    array( 'width' => 'auto' )
);

$table->set_column_alignments( 'left', 'right', 'left' );

   //get total users active in the last 7 days
    $begin_time = time() - 604800; // in seconds
    $users = mysqli_query(DPDatabase::get_connection(), "SELECT count(*) AS numusers FROM users
                          WHERE t_last_activity > $begin_time");
    $row = mysqli_fetch_assoc($users);
    $totalusers = $row["numusers"];

    $table->row(
        _("Proofreaders active in the last 7 days:"),
        $totalusers,
        ""
    );

  //get total books posted  in the last 7 days

    $books = mysqli_query(DPDatabase::get_connection(), "SELECT count(*) AS numbooks FROM projects
                          WHERE modifieddate >= $begin_time AND state = '".PROJ_SUBMIT_PG_POSTED."'");
    $row = mysqli_fetch_assoc($books);
    $totalbooks = $row["numbooks"];

    $table->row(
        _("Books posted in the last 7 days:"),
        $totalbooks,
        ""
    );



    $view_books=_("(View)");
  //get total first round books waiting to be released
    $firstwaitingbooks = mysqli_query(DPDatabase::get_connection(), "SELECT count(*) AS numbooks FROM projects WHERE state = '".PROJ_P1_WAITING_FOR_RELEASE."'");
    $row = mysqli_fetch_assoc($firstwaitingbooks);
    $totalfirstwaiting = $row["numbooks"];

    $table->row(
        _("Books waiting to be released for first round:"),
        $totalfirstwaiting,
        "&nbsp;<a href ='to_be_released.php?order=default'>$view_books</a>"
    );

  //get total non-English books waiting to be released
    $nonwaitingbooks = mysqli_query(DPDatabase::get_connection(), "SELECT count(*) AS numbooks FROM projects
                                    WHERE state = '".PROJ_P1_WAITING_FOR_RELEASE."' AND language != 'English'");
    $row = mysqli_fetch_assoc($nonwaitingbooks);
    $totalnonwaiting = $row["numbooks"];

    $table->row(
        _("Non-English Books waiting to be released for first round:"),
        $totalnonwaiting,
        ""
    );

  //get total books waiting to be post processed
    $waitingpost = mysqli_query(DPDatabase::get_connection(), "SELECT count(*) AS numbooks FROM projects
                                WHERE state = '".PROJ_POST_FIRST_AVAILABLE."'");
    $row = mysqli_fetch_assoc($waitingpost);
    $totalwaitingpost = $row["numbooks"];

    $table->row(
        _("Books waiting for post processing:"),
        $totalwaitingpost,
        ""
    );

  //get total books being post processed
    $inpost = mysqli_query(DPDatabase::get_connection(), "SELECT count(*) AS numbooks FROM projects
                           WHERE state = '".PROJ_POST_FIRST_CHECKED_OUT."'");
    $row = mysqli_fetch_assoc($inpost);
    $totalinpost = $row["numbooks"];

    $table->row(
        _("Books being post processed:"),
        $totalinpost,
        "&nbsp;<a href ='checkedout.php?state=".PROJ_POST_FIRST_CHECKED_OUT."'>$view_books</a>"
    );

  //get total books in verify
    $verifybooks = mysqli_query(DPDatabase::get_connection(), "SELECT count(*) AS numbooks FROM projects
                                WHERE state = '".PROJ_POST_SECOND_AVAILABLE."'");
    $row = mysqli_fetch_assoc($verifybooks);
    $totalverify = $row["numbooks"];

    $table->row(
        _("Books waiting to be verified:"),
        $totalverify,
        "&nbsp;<a href ='PPV_avail.php'>$view_books</a>"
    );

  //get total books in verifying
    $verifyingbooks = mysqli_query(DPDatabase::get_connection(), "SELECT count(*) AS numbooks FROM projects
                                   WHERE state = '".PROJ_POST_SECOND_CHECKED_OUT."'");
    $row = mysqli_fetch_assoc($verifyingbooks);
    $totalverifying = $row["numbooks"];

    $table->row(
        _("Books being verified:"),
        $totalverifying,
        "&nbsp;<a href ='checkedout.php?state=".PROJ_POST_SECOND_CHECKED_OUT."'>$view_books</a>"
    );

$table->end();

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Miscellaneous Statistics

$table = new ThemedTable(
    2,
    _("Miscellaneous Statistics")
);

$table->row(
    "<a href='release_queue.php'>" . _("See All Waiting Queues") . "</a>",
    "<a href='requested_books.php'>" . _("Most Requested Books") . "</a>"
);

$table->row(
    "<a href='user_logon_stats.php'>" . _("User Logon Statistics") . "</a>",
    "<a href='pm_stats.php'>" . _("Project Management Statistics") . "</a>"
);

$table->row(
    "<a href='pp_stats.php'>" . _("Post-Processing Statistics") . "</a>",
    "<a href='ppv_stats.php'>" . _("Post-Processing Verification Statistics") . "</a>"
);

$table->end();

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Pages in Rounds

$table = new ThemedTable(
    4,
    _("Pages in Rounds")
);

foreach ( $page_tally_names as $tally_name => $tally_title )
{
    $qs = "tally_name=$tally_name";
    $table->row(
        $tally_name,
        "<a href='pages_proofed_graphs.php?$qs'>" . _("Pages Proofread Graphs") . "</a>",
        "<a href='misc_stats1.php?$qs'>" . _("Top Proofreading Days and Months, etc.") . "</a>",
        "<a href='proof_stats.php?$qs'>" . _("Top Proofreaders") . "</a>"
    );
}

$table->end();

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Projects by Status

$table = new ThemedTable(
    3,
    _("Projects by Status")
);

$table->column_headers(
    '',
    _('Number So Far'),
    _('Graphed Over Time')
);

foreach ( array('created','proofed','PPd','posted') as $which )
{
    $psd = get_project_status_descriptor( $which );

    $res = mysqli_query(DPDatabase::get_connection(), "
        SELECT CAST(SUM(num_projects) AS SIGNED)
        FROM project_state_stats
        WHERE $psd->state_selector
        GROUP BY date
        ORDER BY date DESC
        LIMIT 1
    ");
    $row = mysqli_fetch_row($res);
    $num_so_far = number_format($row[0]);

    $table->row(
        $psd->projects_Xed_title,
        $num_so_far,
        "<a href='projects_Xed_graphs.php?which=$which'>$psd->graphs_title</a>"
    );
}

$table->end();

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Total Projects Created, Proofread, Post-Processed and Posted

$table = new ThemedTable(
    1, 
    _("Total Projects Created, Proofread, Post-Processed and Posted")
);

$table->row(
    '&nbsp;'
);

$img_url = "jpgraph_files/cumulative_total_proj_summary_graph.php";
$alt_text = _("Total Projects Created, Proofread, Post-Processed and Posted");

$table->row(
    "<img src='$img_url' alt='" . attr_safe($alt_text) . "'>"
);

$table->end();

// vim: sw=4 ts=4 expandtab
