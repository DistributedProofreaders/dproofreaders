<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
include_once($relPath.'ThemedTable.inc');
include_once($relPath.'site_news.inc');
new dbConnect();

$title = _("Statistics Central");
theme($title,'header');

echo "<br><h2>" . _("Statistics Central") . "</h2>";

show_site_news_for_page("stats_central.php");
random_news_item_for_page("stats_central.php");

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//Member/team stats searches and listings

echo "<table border='0' align='center' width='95%' cellspacing='2' cellpadding='2'>\n";
echo "<tr><td align='left'><form action='$code_url/stats/members/mbr_list.php' method='post'>";
echo "<font color='".$theme['color_headerbar_font']."'><input type='text' name='uname' size='20'>&nbsp;<input type='submit' value='"._("Member Search")."'></font></form></td>\n";
echo "<td align='right'><form action='$code_url/stats/teams/tlist.php' method='post'>";
echo "<font color='".$theme['color_headerbar_font']."'><input type='text' name='tname' size='20'>&nbsp;<input type='submit' value='"._("Team Search")."'></font></form></td>\n";
echo "</tr><tr><td align='center'><a href='$code_url/stats/members/mbr_list.php'>"._("Member List")."</a></td><td align='center'><a href='$code_url/stats/teams/tlist.php'>"._("Team List")."</a></td></tr></table>\n<br>\n";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//General site stats with links to view the queue's

echo "<center>";
$table = new ThemedTable(
    3,
    _('General Site Statistics')
);

$table->set_column_alignments( 'left', 'right', 'left' );

   //get total users active in the last 7 days
    $begin_time = time() - 604800; // in seconds
    $users = mysql_query("SELECT count(*) AS numusers FROM users
                          WHERE last_login > $begin_time");
    $totalusers = (mysql_result($users,0,"numusers"));

    $table->row(
        _("Proofreaders active in the last 7 days:"),
        $totalusers,
	""
    );

  //get total books posted  in the last 7 days

    $books = mysql_query("SELECT count(*) AS numbooks FROM projects
                          WHERE modifieddate >= $begin_time AND state = '".PROJ_SUBMIT_PG_POSTED."'");
    $totalbooks = (mysql_result($books,0,"numbooks"));

    $table->row(
        _("Books posted in the last 7 days:"),
        $totalbooks,
	""
    );



    $view_books=_("(View)");
  //get total first round books waiting to be released
    $firstwaitingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects WHERE state = '".PROJ_P1_WAITING_FOR_RELEASE."'");
    $totalfirstwaiting = (mysql_result($firstwaitingbooks,0,"numbooks"));

    $table->row(
        _("Books waiting to be released for first round:"),
        $totalfirstwaiting,
	"&nbsp;<a href ='to_be_released.php?order=default'>$view_books</a>"
    );

  //get total non-English books waiting to be released
    $nonwaitingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                    WHERE state = '".PROJ_P1_WAITING_FOR_RELEASE."' AND language != 'English'");
    $totalnonwaiting = (mysql_result($nonwaitingbooks,0,"numbooks"));

    $table->row(
        _("Non-English Books waiting to be released for first round:"),
        $totalnonwaiting,
	""
    );

  //get total books waiting to be post processed
    $waitingpost = mysql_query("SELECT count(*) AS numbooks FROM projects
                                WHERE state = '".PROJ_POST_FIRST_AVAILABLE."'");
    $totalwaitingpost = (mysql_result($waitingpost,0,"numbooks"));

    $table->row(
        _("Books waiting for post processing:"),
        $totalwaitingpost,
	""
    );

  //get total books being post processed
    $inpost = mysql_query("SELECT count(*) AS numbooks FROM projects
                           WHERE state = '".PROJ_POST_FIRST_CHECKED_OUT."'");
    $totalinpost = (mysql_result($inpost,0,"numbooks"));

    $table->row(
        _("Books being post processed:"),
        $totalinpost,
	"&nbsp;<a href ='checkedout.php?state=".PROJ_POST_FIRST_CHECKED_OUT."'>$view_books</a>"
    );

  //get total books in verify
    $verifybooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                WHERE state = '".PROJ_POST_SECOND_AVAILABLE."'");
    $totalverify = (mysql_result($verifybooks,0,"numbooks"));

    $table->row(
        _("Books waiting to be verified:"),
        $totalverify,
	"&nbsp;<a href ='PPV_avail.php'>$view_books</a>"
    );

  //get total books in verifying
    $verifyingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                   WHERE state = '".PROJ_POST_SECOND_CHECKED_OUT."'");
    $totalverifying = (mysql_result($verifyingbooks,0,"numbooks"));

    $table->row(
        _("Books being verified:"),
        $totalverifying,
	"&nbsp;<a href ='checkedout.php?state=".PROJ_POST_SECOND_CHECKED_OUT."'>$view_books</a>"
    );

$table->end();
echo "</center>";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Miscellaneous Statistics

$table = new ThemedTable(
    2,
    _("Miscellaneous Statistics"),
    array( 'width' => 99 )
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
    _("Pages in Rounds"),
    array( 'width' => 99 )
);

foreach ( $page_tally_names as $tally_name => $tally_title )
{
    $qs = "tally_name=$tally_name";
    $table->row(
        $tally_name,
        "<a href='pages_proofed_graphs.php?$qs'>" . _("Pages Proofread Graphs") . "</a>",
        "<a href='misc_stats1.php?$qs'>" . _("Top Proofreading Days and Months, etc") . "</a>",
        "<a href='proof_stats.php?$qs'>" . _("Top Proofreaders") . "</a>"
    );
}

$table->end();

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Projects by Status

$table = new ThemedTable(
    3,
    _("Projects by Status"),
    array( 'width' => 99 )
);

$table->column_headers(
    '',
    _('Number So Far'),
    _('Graphed Over Time')
);

foreach ( array('created','proofed','PPd','posted') as $which )
{
    $psd = get_project_status_descriptor( $which );

    $res = mysql_query("
        SELECT SUM(num_projects)
        FROM project_state_stats
        WHERE $psd->state_selector
        GROUP BY date
        ORDER BY date DESC
        LIMIT 1
    ");
    $num_so_far = mysql_result($res,0);

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
    _("Total Projects Created, Proofread, Post-Processed and Posted"),
    array( 'width' => 99 )
);

$table->row(
    '&nbsp;'
);

$img_url = "jpgraph_files/cumulative_total_proj_summary_graph.php";
$alt_text = _("Total Projects Created, Proofed, PPd and Posted");

$table->row(
    "<img src='$img_url' alt='$alt_text'>"
);

$table->end();

theme('','footer');

// vim: sw=4 ts=4 expandtab
?>
