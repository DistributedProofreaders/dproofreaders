<?PHP
$relPath='./pinc/';

include_once($relPath.'dp_main.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'projectinfo.inc'); // project_getnumavailablepagesinround()
include_once($relPath.'comment_inclusions.inc'); // parse_project_comments()
include_once($relPath.'../tools/project_manager/page_table.inc'); // echo_page_table
include_once($relPath.'user_is.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'pg.inc');          // get_pg_catalog_link...
include_once($relPath.'theme.inc');
include_once($relPath.'../tools/project_manager/projectmgr.inc'); // echo_manager_header
include_once($relPath.'postcomments.inc'); // get_formatted_postcomments(...)
include_once($relPath.'../tools/proofers/PPage.inc'); // url_for_pi_*
include_once($relPath.'smoothread.inc');           // functions for smoothreading
include_once($relPath.'release_queue.inc'); // cook_project_selector

// for strftime:
$datetime_format = _("%A, %B %e, %Y at %X");
$date_format     = _("%A, %B %e, %Y");
$time_format     = _("%X");

error_reporting(E_ALL);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Usually, the user arrives here by clicking on the title of a project
// in a list of projects.
// But there are lots of other less-used pages that link here.

$projectid      = @$_GET['id'];
$expected_state = @$_GET['expected_state'];
$detail_level   = @$_GET['detail_level'];

$VALID_DETAIL_LEVELS = array('1','2','3','4');
if ( is_null($detail_level) )
{
    // unspecified
    $detail_level = 2;
}
elseif ( in_array($detail_level, $VALID_DETAIL_LEVELS ) )
{
    // fine
    $detail_level = intval($detail_level);
}
else
{
    die("bad 'detail_level' parameter: '$detail_level'");
}

// -----

$project = new Project( $projectid );

// -----------------------------------------------------------------------------

// In a tabbed browser, the page-title passed to theme() will appear in
// the tab, which tends to be small, as soon as you have a few of them.
// So, put the distinctive part of the page-title (i.e. the name of the
// project) first.
$title_for_theme = sprintf( _('"%s" project page'), $project->nameofwork );

$title = sprintf( _("Project Page for '%s'"), $project->nameofwork );

do_update_pp_activity();

if ($detail_level==1)
{
    echo "<h1>$title</h1>\n";

    do_expected_state();
    do_detail_level_switch();

    do_project_info_table();

    /*
    echo "<BR>";

    echo "<p><b>";
    echo _("This information has been opened in a separate browser window, feel free to leave it open for reference or close it.");
    echo "</b></p>";
    */
}
else
{
    // Detail level 2 (the default) should show the information
    // that is usually wanted by the people who usually work with
    // the project in its current state.

    // don't show the stats column
    $no_stats=1;
    theme($title_for_theme, "header");

    do_pm_header();

    echo "<h1>$title</h1>\n";

    do_detail_level_switch();
    do_expected_state();

    list($top_blurb, $bottom_blurb) = decide_blurbs();

    do_blurb_box( $top_blurb );
    do_project_info_table();
    do_edit_above();
    do_blurb_box( $bottom_blurb );

    do_early_uploads();
    do_waiting_queues();
    do_post_downloads();
    do_postcomments();
    do_smooth_reading();
    do_ppv_report();
    do_change_state();

    if ($detail_level >= 3)
    {
        // Stuff that's (usually) only of interest to
        // PMs/PFs/SAs and curious others.
        do_history();
        do_images();
        do_extra_files();
        do_page_summary();
        if ($detail_level >= 4)
        {
            do_page_table();
        }
    }

    do_detail_level_switch();
    theme('', 'footer');
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_update_pp_activity()
{
// If the project has been checked out for more than 90 days and
// the pp-er loads the page, it's interpreted as a sign of
// activity. Update the database accordingly.

    global $project;

    if ( $project->state != PROJ_POST_FIRST_CHECKED_OUT ) return;

    if (! $project->PPer_is_current_user) return;

    // 7776000 = 90*24*60*60 seconds
    if ($project->modifieddate < time()-7776000) {
      // Modified more than 90 days ago. The PP-er,
      // by loading this page, is reporting
      // activity.

      $projectid = $project->projectid;
      $now = time();
      mysql_query("UPDATE projects SET modifieddate=$now WHERE projectid='$projectid'");
      $project->modifieddate = $now;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_pm_header()
{
    global $project;
    if (!$project->can_be_managed_by_current_user) return;

    echo_manager_header( 'project_detail_page' );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_detail_level_switch()
{
    global $project, $detail_level, $VALID_DETAIL_LEVELS;

    echo sprintf(
        _('This page is being presented at detail level %d.'),
        $detail_level
    );
    echo "\n";
    echo _('Switch to:'), "\n";
    foreach( $VALID_DETAIL_LEVELS as $v )
    {
        if ( $v != $detail_level )
        {
            $url = "project.php?id={$project->projectid}&amp;expected_state={$project->state}&amp;detail_level=$v";
            echo "<a href='$url'>$v</a>\n";
        }
    }
    echo "<br>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_expected_state()
{
    global $project, $expected_state;

    if ( !empty($expected_state) && $expected_state != $project->state )
    {
        echo "<font color='red'>";
        echo sprintf(
            _("Warning! The project is no longer in '%s'. It is now in '%s'."),
            project_states_text($expected_state),
            project_states_text($project->state)
        );
        echo "</font>";
        echo "<br>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function decide_blurbs()
{
    global $project, $pguser, $datetime_format;

    list($code,$msg) = $project->can_be_proofed_by_current_user();
    if ( $code != $project->CBP_OKAY )
    {
        if ( $code == $project->CBP_PROJECT_NOT_IN_ROUND )
        {
            // Rather than blurbs that say it's not in a round,
            // just don't have any blurbs.
            $msg = NULL;
        }
        return array( $msg, $msg );
    }

    $projectid = $project->projectid;
    $state = $project->state;
    $round = get_Round_for_project_state($state);

    $num_pages_available =
        Project_getNumPagesInState( $projectid, $round->page_avail_state );

    if ( $num_pages_available == 0 )
    {
        $blurb =
            _("Round Complete")
            . "<br>"
            . _("There are no pages available for proofreading.");
        return array( $blurb, $blurb );
    }

    // (This code has parallels to code in get_available_page().)
    if ( $project->difficulty == 'beginner' )
    {
        if ( $round->is_a_mentee_round() )
        {
            if ( !user_can_work_on_beginner_pages_in_round($round) )
            {
                $blurb =
                    _("You have reached your quota of pages from 'Beginners Only' projects in this round.")
                    . " "
                    . _("Perhaps you could try working on an EASY project.");
                return array( $blurb, $blurb );
            }
        }
        else if ( $round->is_a_mentor_round() )
        {
            if ( !user_can_work_on_beginner_pages_in_round($round) )
            {
                $blurb =
                    _("You do not have access to difficulty='beginner' (Mentors Only) projects in this round.");
                return array( $blurb, $blurb );
            }
        }
        else
        {
            // difficulty='beginner' projects aren't handled differently
        }
    }

    {
        // If there's any proofreading to be done, this is the link to use.
        $url = url_for_pi_do_whichever_page( $projectid, $state );
        $label = _("Start Proofreading");
        $proofreading_link = "<b><a href='$url'>$label</a></b>";

        // When was the project info last modified?
        $info_timestamp = $project->t_last_edit;
        $info_time_str = strftime($datetime_format, $info_timestamp);
        $info_last_modified_blurb = _("Project information last modified:") . " " . $info_time_str;

        // Other possible components of blurbs:
        $please_scroll_down = _("Please scroll down and read the Project Comments for any special instructions <b>before</b> proofreading!");
        $the_link_appears_below = _("The 'Start Proofreading' link appears below the Project Comments");
        $info_have_changed =
            "<font color='red'>"
            . "<b>"
            . _("Project information has changed!")
            . "</b>"
            . "</font>";

        // ---

        $bottom_blurb =
            $info_last_modified_blurb
            . "<br>"
            . $proofreading_link;

        // Has the user saved a page of this project since the project info was
        // last changed? If not, it's unlikely they've seen the revised info.
        $res = mysql_query("
            SELECT {$round->time_column_name}
            FROM $projectid
            WHERE state='{$round->page_save_state}' AND {$round->user_column_name}='$pguser'
            ORDER BY {$round->time_column_name} DESC
            LIMIT 1
        ");
        if (mysql_num_rows($res) == 0)
        {
            // The user has not saved any pages for this project.
            $top_blurb =
                $please_scroll_down
                . "<br>"
                . $info_last_modified_blurb
                . "<br>"
                . $the_link_appears_below;
        }
        else
        {
            // The user has saved a page for this project.
            $my_latest_save_timestamp = mysql_result($res,0,$round->time_column_name);

            if ($my_latest_save_timestamp < $info_timestamp)
            {
                // The latest page-save was before the info was revised.
                // The user probably hasn't seen the revised project info.
                $top_blurb =
                    $info_have_changed
                    . "<br>"
                    . $please_scroll_down
                    . "<br>"
                    . $info_last_modified_blurb
                    . "<br>"
                    . $the_link_appears_below;
            }
            else
            {
                // The latest page-save was after the info was revised.
                // We'll assume that the user has read the new info.
                $top_blurb =
                    $please_scroll_down
                    . "<br>"
                    . $info_last_modified_blurb
                    . "<br>"
                    . $proofreading_link;
            }
        }
    }

    return array( $top_blurb, $bottom_blurb );
}

// -----------------------------------------------

function do_blurb_box( $blurb )
{
    if ( is_null($blurb) ) return;

    echo "<br>";
    echo "<table width='630' bgcolor='DDDDDD'>";
    echo "<tr><td align='center'>";
    echo $blurb;
    echo "</td></tr>";
    echo "</table>";
    echo "<br>";
    echo "\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_project_info_table()
{
    global $project, $code_url, $datetime_format, $date_format, $time_format;

    $projectid = $project->projectid;
    $state = $project->state;

    $round = get_Round_for_project_state($state);
    // Note that $round may be NULL;

    echo "<table id='project_info_table' border=1 width=630>";

    // -------------------------------------------------------------------------
    // The state of the project

    $available_for_SR = (
        $project->state == PROJ_POST_FIRST_CHECKED_OUT &&
        $project->smoothread_deadline > time() );

    $right = project_states_text($project->state);
    if ($round)
    {
        $extra = $round->description;
        $right = "$right ($extra)";
    }
    elseif ($available_for_SR)
    {
        $sr_deadline_str = strftime(
            $date_format, $project->smoothread_deadline );
        $sr_sentence = sprintf(
            _('This project has been made available for smooth reading until %s.'),
            "<b>$sr_deadline_str</b>"
        );
        $extra2 = _('See below.');
        $right = "$right<br>$sr_sentence $extra2";
    }
    echo_row_a( _("Project State"), $right );

    if ( $project->state == PROJ_DELETE )
    {
        // Change anything that looks like a projectid into a link.
        $cooked_reason = preg_replace(
            '/\b(projectID[0-9a-f]{13})\b/',
            '<a href="project.php?id=\1">\1</a>',
            $project->deletion_reason );
        echo_row_a( _("Reason for Deletion"), $cooked_reason );
    }

    // -------------------------------------------------------------------------
    // Information about the work itself (independent of DP)

    echo_row_a( _("Title"),           $project->nameofwork );
    echo_row_a( _("Author"),          $project->authorsname );
    echo_row_a( _("Language"),        $project->language );
    echo_row_a( _("Genre"),           $project->genre );
    echo_row_a( _("Difficulty"),      $project->difficulty );

    // -------------------------------------------------------------------------
    // Basic DP info

    if (!empty($project->special_code))
    {
        $spec_code = $project->special_code;
        if (
            (strncmp($spec_code,'Birthday',8) == 0 ) or
            (strncmp($spec_code,'Otherday',8) == 0 )
        )
        {
            $spec_display = $spec_code;
        }
        else
        {
            $spec_res = mysql_fetch_assoc(mysql_query("
                SELECT display_name
                FROM special_days
                WHERE spec_code = '$spec_code'
            "));
            if ($spec_res)
            {
                $spec_display = $spec_res['display_name'];
            }
            else
            {
                $spec_display = "($spec_code)";
            }
        }

        echo_row_a( _("Special Day"), $spec_display );
    }

    // -------

    echo_row_a( _("Project ID"), $project->projectid );

    // The clearance line normally contains the email address of the
    // person who submitted the clearance request. Since this is
    // private information, we restrict who can see it.
    if ( $project->PPVer_is_current_user || $project->can_be_managed_by_current_user )
    {
        echo_row_a( _("Clearance Line"), $project->clearance, TRUE );
    }

    // -------------------------------------------------------------------------
    // People who have certain roles with respect to the project

    if (isset($project->image_source_name))
    {
        echo_row_a( _("Image Source"), $project->image_source_name, TRUE );
    }

    echo_row_a( _("Project Manager"), $project->username );

    {
        if ( !empty($project->postproofer) )
        {
            $PPer = $project->postproofer;
        }
        else if ( !empty($project->checkedoutby) &&
            !in_array(
                $project->state,
                array(
                    PROJ_POST_SECOND_CHECKED_OUT,
                    PROJ_POST_COMPLETE,
                    PROJ_SUBMIT_PG_POSTED,
                    PROJ_CORRECT_CHECKED_OUT,
                )
            )
        )
        {
            $PPer = $project->checkedoutby;
        }
        if ( isset($PPer) )
        {
            echo_row_a( _("Post Processor"), $PPer );
        }
    }

    {
        if ( !empty($project->ppverifier) )
        {
            $PPVer = $project->ppverifier;
        }
        else if ( !empty($project->checkedoutby) &&
            in_array(
                $project->state,
                array(
                    PROJ_POST_SECOND_CHECKED_OUT,
                    PROJ_POST_COMPLETE,
                    PROJ_SUBMIT_PG_POSTED,
                )
            )
        )
        {
            $PPVer = $project->checkedoutby;
        }
        if ( isset($PPVer) )
        {
            echo_row_a( _("PP Verifier"), $PPVer );
        }
    }

    global $site_supports_corrections_after_posting;
    if ($site_supports_corrections_after_posting)
    {
        // included for completeness
        if ( !empty($project->checkedoutby) &&
            $project->state == PROJ_CORRECT_CHECKED_OUT
        )
        {
            $CorrectionsReviewer = $project->checkedoutby;
        }
        if ( isset($CorrectionsReviewer) )
        {
            echo_row_a( _("Corrections Reviewer"), $CorrectionsReviewer );
        }
    }

    echo_row_a( _("Credits line so far"), $project->credits_line, TRUE );

    // -------------------------------------------------------------------------
    // Current activity

    echo_row_a(
        _("Last Edit of Project Info"),
        strftime($datetime_format, $project->t_last_edit) );

    echo_row_a(
        _("Last State Change"),
        strftime($datetime_format, $project->modifieddate) );

    if ($round)
    {
        $proofdate = mysql_query("
            SELECT {$round->time_column_name}
            FROM $projectid
            WHERE state='{$round->page_save_state}'
            ORDER BY {$round->time_column_name} DESC
            LIMIT 1
        ");
        if (mysql_num_rows($proofdate)!=0)
        {
            $latest_save_time = mysql_result($proofdate,0,$round->time_column_name);
            $formatted_lst = strftime($datetime_format, $latest_save_time);
            $formatted_now = strftime($time_format, time());
            $lastproofed = "$formatted_lst&nbsp;&nbsp;&nbsp; ("._("Current Time:")." $formatted_now)";
        }
        else
        {
            $lastproofed = _("Project has not been proofread in this round.");
        }
        echo_row_a( _("Last Proofread"), $lastproofed );
    }

    // -------------------------------------------------------------------------

    $state = $project->state;
    if ( $state == PROJ_SUBMIT_PG_POSTED
      || $state == PROJ_CORRECT_AVAILABLE
      || $state == PROJ_CORRECT_CHECKED_OUT )
    {
        $postednum = $project->postednum;
        echo_row_a(
            _("PG etext number"),
            get_pg_catalog_link_for_etext($postednum, $postednum) );
    }

    // -------------------------------------------------------------------------
    // Forum topic

    $topic_id = $project->topic_id;
    if (!empty($topic_id))
    {
        $last_post = mysql_query("SELECT post_time FROM phpbb_posts WHERE topic_id = $topic_id ORDER BY post_time DESC LIMIT 1");
        $last_post_date = mysql_result($last_post,0,"post_time");
        $last_post_date = strftime($datetime_format, $last_post_date);
        echo_row_a( _("Last Forum Post"), $last_post_date );
    }

    if ($topic_id == "")
    {
        $blurb = _("Start a discussion about this project");
    }
    else
    {
        $blurb = _("Discuss this project");
    }
    $url = "$code_url/tools/proofers/project_topic.php?project=$projectid";
    echo_row_a( _("Forum"), "<a href='$url'>$blurb</a>" );

    // -------------------------------------------------------------------------

    global $detail_level;
    if ($detail_level >= 4)
    {
        // We'll call do_page_table later, so we don't need the "Page Detail" link.
    }
    else
    {
        $url = "$code_url/tools/project_manager/page_detail.php?project=$projectid&show_image_size=0";
        $blurb = _("Images, Pages Proofread, & Differences");

        $url2 = "$url&select_by_user";
        $blurb2 = _("Just my pages");

        echo_row_a( _("Page Detail"), "<a href='$url'>$blurb</a> &gt;&gt;<a href='$url2'>$blurb2</a>&lt;&lt;");
    }

    // -------------------------------------------------------------------------
    // Personal data with respect to this project
    // (This is the only section that uses $pguser and $userP.)

    global $pguser, $userP;

    $temp = mysql_query("
        SELECT *
        FROM usersettings
        WHERE username = '$pguser' AND setting = 'posted_notice' AND value = '$projectid'
    ");
    if (mysql_num_rows($temp) == 0)
    {
        $blurb = _("Click here to register for automatic email notification of when this has been posted to Project Gutenberg.");
    }
    else
    {
        $blurb = _("Click here to <b>undo</b> registration for automatic email notification of when this has been posted to Project Gutenberg.");
    }
    $url = "$code_url/tools/proofers/posted_notice.php?project=$projectid&proofstate=$state";
    echo_row_a( _("Book Completed:"), "<a href='$url'>$blurb</a>" );

    global $detail_level;
    if ($round && $detail_level > 1)
    {
        recentlyproofed(0);
        recentlyproofed(1);
    }

    // -------------------------------------------------------------------------
    // Comments

    $postcomments = get_formatted_postcomments($project->projectid);

    if ($postcomments != '')
    {
        if ( $available_for_SR )
        {
            echo_row_b( _("Instructions for Smooth Reading"), '' );
            echo_row_c( $postcomments );
        }
        // Postcomments should always be shown to those directly involved with the project (the
        // first three conditions). However, when the project is available for PPVing, the prospective
        // PPVer should be able to read the PPer's comments without checking out the project.
        elseif ( $project->PPer_is_current_user || $project->PPVer_is_current_user
            || $project->can_be_managed_by_current_user ||
            $state==PROJ_POST_SECOND_AVAILABLE && user_can_work_in_stage($pguser,'PPV') )
        {
            echo_row_b( _("Post Processor's Comments"), '' );
            echo_row_c( $postcomments );
        }
    }

    // --------

    $comments = $project->comments;

    // automatically prepend R2 intro for Beginners Only
    if ($project->difficulty == "beginner")
    {
        if ($round && $round->is_a_mentor_round() )
        {
            $comments = "[template=BGr2.txt]".$comments;
        }
    }

    // insert e.g. templates and biographies
    $comments = parse_project_comments($comments);

    if ( $comments == '' )
    {
        // Put in *something*, otherwise it'll probably look odd.
        $comments = '&nbsp;';
    }

    if ($round)
    {
        $a = sprintf(
                _("The <a href='%s'>Guidelines</a> give detailed instructions for working in this round."),
                "$code_url/faq/{$round->document}"
            );
        $b = _('The instructions below are particular to this project, and <b>take precedence over those guidelines</b>.');
        $comments_blurb = "$a<br>$b";
    }
    else
    {
        $comments_blurb = "";
    }
    echo_row_b( _("Project Comments"), $comments_blurb );

    echo_row_c( $comments );

    // -------------------------------------------------------------------------

    echo "</table>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_row_a( $left, $right, $escape_right=FALSE )
{
    if ($escape_right) $right = htmlspecialchars( $right, ENT_NOQUOTES );
    echo "<tr>";
    echo "<td bgcolor='CCCCCC' align='center'><b>$left</b></td>";
    echo "<td colspan='4'>$right</td>";
    echo "</tr>\n";
}

function echo_row_b( $top, $bottom, $bgcolor = 'CCCCCC' )
{
    echo "<tr>";
    echo "<td colspan='5' bgcolor='$bgcolor' align='center'>";
    echo "<font size='+1'><b>$top</b></font>";
    if ($bottom)
    {
        echo "<br>$bottom";
    }
    echo "</td>";
    echo "</tr>\n";
}

function echo_row_c( $content )
{
    echo "<tr>";
    echo "<td colspan=5>";
    echo $content;
    echo "</td>";
    echo "</tr>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function recentlyproofed( $wlist )
{
    global $project, $pguser, $userP;

    $projectid = $project->projectid;
    $state = $project->state;

    $round = get_Round_for_project_state($state);
    assert($round);

    if ($wlist==0)
    {
        $top = _("DONE");
        $bottom = "(<b>"._("My Recently Completed")."</b> - "._("pages I've finished proofreading, that are still available for correction)");
        $state_condition = "state='{$round->page_save_state}'";
        $bg_color = '99FF66';
    }
    else
    {
        $top = _("IN PROGRESS");
        $bottom = "(<b>"._("My Recently Proofread")."</b> - "._("pages I haven't yet completed)");
        $state_condition = "(state='{$round->page_temp_state}' OR state='{$round->page_out_state}')";
        $bg_color = 'FFCC66';
    }

    echo_row_b( $top, $bottom, $bg_color );

    $recentNum=5;

    $sql = "
        SELECT image, state, {$round->time_column_name}
        FROM $projectid
        WHERE {$round->user_column_name}='$pguser' AND $state_condition
        ORDER BY {$round->time_column_name} DESC
        LIMIT $recentNum
    ";
    $result = mysql_query($sql);
    $rownum = 0;
    $numrows = mysql_num_rows($result);

    while (($rownum < $recentNum) && ($rownum < $numrows))
    {
        $imagefile = mysql_result($result, $rownum, "image");
        $timestamp = mysql_result($result, $rownum, $round->time_column_name);
        $pagestate = mysql_result($result, $rownum, "state");

        $eURL = url_for_pi_do_particular_page(
            $projectid, $state, $imagefile, $pagestate );

        if (($rownum % 5) ==0) {echo "</tr><tr>";}
        echo "<TD ALIGN=\"center\">";
        echo "<A HREF=\"$eURL\">";
        echo strftime(_("%b %d"), $timestamp).": ".$imagefile."</a></td>\r\n";
        $rownum++;
    }

    while (($rownum % 5) !=0) {echo "<td>&nbsp;</td>"; $rownum++;}
    echo "</tr>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_edit_above()
{
    global $project, $code_url;
    if (!$project->can_be_managed_by_current_user) return;

    echo "<p>";
    echo "<a href='$code_url/tools/project_manager/editproject.php?action=edit&project=$project->projectid'>";
    echo _("Edit the above information");
    echo "</a>";
    echo "</p>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_early_uploads()
{
    global $project, $code_url, $uploads_account, $pguser;
    if (!$project->can_be_managed_by_current_user) return;

    $projectid = $project->projectid;
    $state = $project->state;

    $add_reminder = FALSE;

    $user_dir = str_replace( ' ', '_', $pguser );

    // Load TP&V images
    global $site_supports_metadata;
    if ($site_supports_metadata)
    {
        if ($state == PROJ_NEW)
        {
            echo "<br>\n";
            echo "<form method='get' action='$code_url/tools/project_manager/add_files.php'>\n";
            echo "<input type='hidden' name='project' value='$projectid'>\n";
            echo "<input type='hidden' name='tpnv' value='1'>\n";
            echo "<b>", _("Add title page and verso from directory or zip file:"), "</b>";
            echo "<br>\n";
            $initial_rel_source = "$user_dir/";
            echo "~$uploads_account/ <input type='text' name='rel_source' size='50' value='$initial_rel_source'>";
            echo "<br>\n";
            echo "<input type='submit' value='Add'>";
            echo "<br>\n";
            echo "</form>\n";
            $add_reminder = TRUE;
        }
    }

    // Load text+images from uploads area into project.
    if (
        ($state == PROJ_NEW && ! $site_supports_metadata)
        || ( $site_supports_metadata && ($state == PROJ_NEW_APPROVED || $state == PROJ_NEW_FILE_UPLOADED) )
        || $state == PROJ_P1_UNAVAILABLE
    )
    {
        echo "<br>\n";
        echo "<form method='get' action='$code_url/tools/project_manager/add_files.php'>\n";
        echo "<input type='hidden' name='project' value='$projectid'>\n";
            echo _("Add/Replace text and images from directory or zip file:");
            echo "<br>\n";
            $initial_rel_source = "$user_dir/";
            echo "~$uploads_account/ <input type='text' name='rel_source' size='50' value='$initial_rel_source'>";
        echo "<br>\n";
        echo "<input type='submit' value='Add/Replace'>";
        echo "<br>\n";
        echo "</form>\n";
        $add_reminder = TRUE;
    }

    if ($add_reminder)
    {
        // remind where/how to ftp projects.
        global $uploads_host,$uploads_account,$uploads_password;
        echo "<p>";
        echo sprintf(
            _("Reminder for uploads: host=<b>%s</b> account=<b>%s</b> password=<i><font color='#DDDDDD'>%s</font></i>"),
            $uploads_host, $uploads_account, $uploads_password );
        echo "</p>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_waiting_queues()
{
    global $project, $code_url;

    $round = get_Round_for_project_state($project->state);

    if ( is_null($round) ) return;
    if ( $project->state != $round->project_waiting_state ) return;

    // Okay, so the project is in some round's waiting state.
    // What queues is it in, if any?

    echo "<h4>";
    echo _("Queues");
    echo "</h4>\n";

    $res = mysql_query("
        SELECT name, project_selector
        FROM queue_defns
        WHERE round_id='{$round->id}'
        ORDER BY ordering
    ") or die(mysql_error());
    if ( mysql_num_rows($res) == 0 )
    {
        // No queues defined for this round.
        echo sprintf(
            _('There are no queues defined for round %s, so this project should be automatically released within a few minutes.'),
            $round->id
        );
    }
    else
    {
        echo sprintf(
            _('This project is in the following round %s queues:'),
            $round->id
        );
        echo "<br>\n";

        echo "<ul>\n";
        $n_queues = 0;
        while ( list($q_name,$q_project_selector) = mysql_fetch_row($res) )
        {
            $cooked_project_selector = cook_project_selector($q_project_selector);

            $res2 = mysql_query("
                SELECT projectid
                FROM projects
                WHERE projectid='{$project->projectid}' AND ($cooked_project_selector)
            ") or die(mysql_error());
            if ( mysql_num_rows($res2) > 0 )
            {
                $n_queues += 1;
                $enc_q_name = urlencode($q_name);
                $url = "$code_url/stats/release_queue.php?round_id={$round->id}&name=$enc_q_name";
                $enc_url = htmlspecialchars( $url, ENT_QUOTES );
                echo "<li><a href='$enc_url'>$q_name</a></li>\n";
            }
        }
        if ( $n_queues == 0 )
        {
            echo "<li>(none!)</li>\n";
        }
        echo "</ul>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_history()
{
    global $project;

    echo "<h4>Project History</h4>\n";

    $res = mysql_query("
        SELECT timestamp, who, event_type, details1, details2, details3
        FROM project_events
        WHERE projectid = '{$project->projectid}'
        ORDER BY event_id
    ") or die(mysql_error());

    $events = array();
    while ( $event = mysql_fetch_assoc($res) )
    {
        $events[] = $event;
    }

    $events2 = fill_gaps_in_events( $events );

    echo "<table border='1'>\n";
    foreach ( $events2 as $event )
    {
        echo "<tr>\n";

        echo "<td>";
        echo (
            $event['timestamp'] == '?'
            ? '?'
            : strftime('%Y-%m-%d %H:%M:%S', $event['timestamp'])
        );
        echo "</td>\n";

        echo "<td align-='center'>{$event['who']}</td>\n";

        echo "<td>{$event['event_type']}</td>\n";

        if ( $event['event_type'] == 'transition' || $event['event_type'] == 'transition(s)')
        {
            $from_state = $event['details1'];
            $from_state_t = (
                $from_state == '?'
                ? _('unknown state')
                : project_states_text($from_state)
            );
            if ( $from_state_t == '' ) $from_state_t = $from_state;

            $to_state = $event['details2'];
            $to_state_t = (
                $to_state == '?'
                ? _('unknown state')
                : project_states_text($to_state)
            );
            if ( $to_state_t == '' ) $to_state_t = $to_state;

            $queue_name = $event['details3'];
            $queue_name_t = (
                $queue_name == ''
                ? ''
                : sprintf( _('via "%s" queue'), $queue_name )
            );

            echo "<td>from $from_state_t</td>\n";
            echo "<td>to $to_state_t</td>\n";
            echo "<td>$queue_name_t</td>\n";
        }
        elseif ( $event['event_type'] == 'smooth-reading' )
        {
            echo "<td>{$event['details1']}</td>\n";
            if ( $event['details1'] == 'text available' )
            {
                $deadline_f = strftime('%Y-%m-%d %H:%M:%S', $event['details2']);
                echo "<td>until $deadline_f</td>\n";
            }
        }

        echo "</tr>\n";
    }
    echo "</table>\n";
}

function fill_gaps_in_events( $in_events )
// If the project's event-history has gaps, fill them with pseudo-events.
{
    $out_events = array();

    // Creation at the start
    if ( count($in_events) == 0 || $in_events[0]['event_type'] != 'creation' )
    {
        $pseudo_event = array(
            'timestamp'  => '?',
            'who'        => '?',
            'event_type' => 'creation',
        );
        $out_events[] = $pseudo_event;
    }

    // Continuity of state
    $running_state = NULL;
    foreach ( $in_events as $event )
    {
        switch ( $event['event_type'] )
        {
            case 'creation':
                $running_state = PROJ_NEW;
                break;

            case 'transition':
                $from_state = $event['details1'];
                $to_state   = $event['details2'];
                if ( $running_state != $from_state )
                {
                    $pseudo_event = array(
                        'timestamp'  => '?',
                        'who'        => '?',
                        'event_type' => 'transition(s)',
                        'details1'   => $running_state,
                        'details2'   => $from_state,
                        'details3'   => '',
                    );
                    $out_events[] = $pseudo_event;
                }
                $running_state = $to_state;
                break;

            case 'deletion':
                $running_state = NULL;
                break;
        }
        $out_events[] = $event;
    }

    global $project;
    if ( $running_state != $project->state )
    {
        $pseudo_event = array(
            'timestamp'  => '?',
            'who'        => '?',
            'event_type' => 'transition(s)',
            'details1'   => $running_state,
            'details2'   => $project->state,
            'details3'   => '',
        );
        $out_events[] = $pseudo_event;
    }
    return $out_events;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_images()
{
    global $project;
    global $code_url;

    if ( !$project->dir_exists ) return;

    $projectid = $project->projectid;

    echo "<h4>", _('Images'), "</h4>";
    echo "<ul>";

    echo "<li>";
    echo "<a href='$code_url/tools/proofers/images_index.php?project=$projectid'>";
    echo _("View Images Online");
    echo "</a>";
    echo "</li>\n";

    echo "</ul>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_extra_files()
{
    global $project;

    if ( !$project->can_be_managed_by_current_user
        && !$project->PPer_is_current_user )
    {
        return;
    }

    if ( !$project->dir_exists ) return;

    echo "<h4>", _('Extra Files in Project Directory'), "</h4>";

    $saved_dir = getcwd();

    chdir($project->dir);
    $filenames = glob("*" );

    echo "<ul>";

    if ( count($filenames) == 0 )
    {
        echo "<li>(none)</li>\n";
    }
    else
    {
        $res = mysql_query("
            SELECT image
            FROM $project->projectid
        ") or die(mysql_error());
        $excluded_filenames = array();
        while ( list($excluded_filename) = mysql_fetch_row($res) )
        {
                $excluded_filenames[$excluded_filename] = 1;
        }
        // These three appear under "Post Downloads":
        $excluded_filenames[$project->projectid . 'images.zip'] = 1;
        $excluded_filenames[$project->projectid . '.zip'] = 1;
        $excluded_filenames[$project->projectid . '_TEI.zip'] = 1;

        // should really exclude uploaded SR, PP and PPV files too,
        // but we can't just add them to excluded_filenames because we
        // don't know their names in advance

        foreach ($filenames as $filename)
        {
            if ( !array_key_exists( $filename, $excluded_filenames ) )
            {
                echo "<li><a href='$project->url/$filename'>$filename</a></li>";
            }
        }
    }

    echo "</ul>";

    chdir($saved_dir);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_post_downloads()
{
    global $project, $pguser;

    if ( !$project->dir_exists && !$project->pages_table_exists ) return;

    $projectid = $project->projectid;
    $state = $project->state;

    if ( user_can_work_in_stage($pguser, 'PP') )
    {
        echo "<h4>";
        echo _("Post Downloads");
        echo "</h4>\n";
        
        echo "<ul>";

        echo_download_zip( _("Download Zipped Images"), 'images' );

        if ($state==PROJ_POST_FIRST_AVAILABLE || $state==PROJ_POST_FIRST_CHECKED_OUT)
        {
            echo_download_zip( _("Download Zipped Text"), '' );

            echo_download_zip( _("Download Zipped TEI Text"), '_TEI' );

            echo "<li>";
            echo_uploaded_zips('_first_in_prog_', _('partially post-processed'));
            echo "</li>";


        }
        elseif ($state==PROJ_POST_SECOND_AVAILABLE || $state==PROJ_POST_SECOND_CHECKED_OUT)
        {
            echo_download_zip( _("Download Zipped Text"), '_second' );

            echo "<li>";
            echo_uploaded_zips('_second_in_prog_', _('partially verified'));
            echo "</li>";
        }
        elseif ($state==PROJ_CORRECT_AVAILABLE || $state==PROJ_CORRECT_CHECKED_OUT)
        {
            echo_download_zip( _("Download Zipped Text"), '_corrections' );
        }
        echo "<br>";
    }
    else
    {
        echo "<h4>";
        echo _("Concatenated Text Files");
        echo "</h4>\n";

        echo "<ul>";
    }
    // regenerate post files. Only for site managers.
    // or download concatenated text, for all. Do we want to limit
    // this to people allowed to work in PP? If so, we should definitely 
    // include the PM of this project.

    global $Round_for_round_id_, $code_url;

    $sums_str = "";
    foreach ( $Round_for_round_id_ as $round )
    {
        if ( !empty($sums_str) ) $sums_str .= ', ';
        $sums_str .= "SUM( $round->text_column_name != '' ) AS $round->id";
    }
    $res = mysql_query("
            SELECT $sums_str
            FROM $projectid
        ") or die(mysql_error());
    $sums = mysql_fetch_assoc($res);

    foreach ( $Round_for_round_id_ as $round )
    {
        if ( intval($sums[$round->id]) != 0 )
        {
            $highest_round_id = $round->id;
        }
    }

    echo "<li>";
    if ( user_is_a_sitemanager() )
    {
        echo "Generate Post Files (This will overwrite existing post files, if any.)\n";
    }
    else
    {
        echo "Download Concatenated Text\n";
    }
    echo "<form method='post' action='$code_url/tools/project_manager/generate_post_files.php'>\n";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";

    if ( isset($highest_round_id) )
    {
        echo "<input type='radio' name='round_id' value='[OCR]'>[OCR]&nbsp;\n";
        foreach ( $Round_for_round_id_ as $round )
        {
            $checked = ( $round->id == $highest_round_id ? 'CHECKED' : '');
            echo "<input type='radio' name='round_id' value='$round->id' $checked>$round->id&nbsp;\n";
            if ( $round->id == $highest_round_id ) break;
        }
    }
    else
    {
        echo "<input type='radio' name='round_id' value='[OCR]' CHECKED>[OCR]&nbsp;\n";
    }
    echo "<br>";

    echo "For each page, use:<br>\n";
    echo "<input type='radio' name='which_text' value='EQ' CHECKED>";
    echo "the text (if any) saved in the selected round; or<br>\n";
    echo "<input type='radio' name='which_text' value='LE'>";
    echo "the latest text saved in any round up to and including the selected round.<br>\n";
    echo "(If every page has been saved in the selected round, then the two choices are equivalent.)<br>\n";

    // proofer names allowed for people who can see proofer names
    // on the page details
    if ( $project->names_can_be_seen_by_current_user )
    {
        echo "Include proofer names? &nbsp;&nbsp; ";
        echo "<input type='radio' name='include_proofers' value='1' CHECKED />";
        echo "Yes &nbsp;&nbsp; ";
        echo "<input type='radio' name='include_proofers' value='0' />";
        echo "No<br />\n";
    }
    else
    {
        echo "<input type='hidden' name='include_proofers' value='0' />";
    }

    // saving files allowed only for sitemanagers
    if (user_is_a_sitemanager())
    {
        echo "Save file on server?  &nbsp;&nbsp; ";
        echo "<input type='radio' name='save_files' value='1' CHECKED />";
        echo "Yes &nbsp;&nbsp; ";
        echo "<input type='radio' name='save_files' value='0' />";
        echo "No<br />\n";

        echo "<input type='submit' value='(Re)generate'>\n";
    }
    else
    {
        echo "<input type='hidden' name='save_files' value='0' />";
        echo "<input type='submit' value='Download'>\n";
    }

    echo "</form>\n";
    echo "</li>";

    echo "</ul>\n";
}

// -----------------------------------------------------------------------------
function echo_uploaded_zips($discriminator, $upload_type)
{
  global $project;

  $done_files = glob("$project->dir/*".$discriminator."*.zip");
  if ($done_files)
    {
      echo sprintf( _("Download %s file uploaded by:"), $upload_type);
      echo "<ul>";
      foreach ($done_files as $filename)
        {
          $showname = basename($filename,".zip");
          $showname = substr($showname, strpos($showname,$discriminator) + strlen($discriminator));
          echo_download_zip( $showname, $discriminator.$showname );
        }
      echo "</ul>";
    }
  else
    {
      echo sprintf( _("No %s results have been uploaded."), $upload_type);
    }

}
// -----------------------------------------------------------------------------

function echo_download_zip( $link_text, $discriminator )
{
    global $project, $code_url;

    $projectid = $project->projectid;
    if ( $discriminator == 'images' )
    {
        // Generate images zip on the fly,
        // so it's not taking up space on the disk.

        $url = "$code_url/tools/download_images.php?projectid=$projectid&amp;dummy={$projectid}images.zip";
        // The 'dummy' parameter is for the benefit of download-software that
        // names the resulting file after the last component of the request URL.

        // Images don't compress much in a zip,
        // so the sum of their individual filesizes
        // is a fair approximation (and hopefully an upper bound)
        // of the size of the resulting zip.
        $filesize_b = 0;
        foreach( glob("$project->dir/*.{png,jpg}", GLOB_BRACE) as $image_path )
        {
            $filesize_b += filesize($image_path);
        }
        $filesize_kb = round( $filesize_b / 1024 );
    }
    else
    {
        $p = "$projectid$discriminator.zip";

        $url = "$project->url/$p";
        $filesize_kb = round( filesize( "$project->dir/$p") / 1024 );
    }

    echo "<li>";
    echo "<a href='$url'>";
    echo $link_text;
    echo "</a>";
    echo " ($filesize_kb kb)";
    echo "</li>";
    echo "\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_postcomments()
{
    global $project, $code_url, $forums_url;

    if ( $project->state != PROJ_POST_FIRST_CHECKED_OUT ) return;

    $projectid = $project->projectid;

    if ($project->PPer_is_current_user)
    {

      echo "<h4>" . _("Post-Processor's Comments") . "</h4>";

      // Give the PP-er a chance to update the project record
      // (limit of 90 days is mentioned below).
      echo '<p>' . sprintf(_("You can use this text area to enter comments on how you're
                     doing with the post-processing, both to keep track for yourself
                     and so that we will know that there's still work in progress.
                     You will not receive an e-mail reminder about this project for at
                     least another %1\$d days.") .
                     _("You can use this feature to keep track of your progress,
                     missing pages, etc. (if you are waiting on missing images or page
                     scans, please add the details to the <a href='%2\$s'>Missing Page
                     Wiki</a>)."),
                     90, "$forums_url/viewtopic.php?t=7584") . ' ' .
                     _("Note that your old comments will be replaced by those you enter here.") . '</p>';

      echo "<form name='pp_update' method='post' action='$code_url/tools/post_proofers/postcomments.php'>\n";
      echo "<textarea name='postcomments' cols='60' rows='6'>\n";
      echo htmlspecialchars($project->postcomments);
      echo "</textarea>\n";
      echo "<input type='hidden' name='projectid' value='$projectid' />\n";
      echo "<br /><input type='submit' value='" . _('Update comment and project status') . "'/>";
      echo "</form>\n";

    }

}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_smooth_reading()
{
    global $project, $code_url, $pguser, $forums_url, $date_format;

    if ( $project->state != PROJ_POST_FIRST_CHECKED_OUT ) return;

    $projectid = $project->projectid;

    echo "<h4>", _('Smooth Reading'), "</h4>";
    echo "<ul>";

    if ( $project->smoothread_deadline == 0 )
    {
        echo "<li>";
        echo _('This project has not been made available for smooth reading.');
        echo "</li>";

        if ($project->PPer_is_current_user)
        {
            echo "<li>";
            echo _("But as the project's PPer, you can make it available.");
            echo _('Choose how long you want to make it available for.');
            $link_start = "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_avail&weeks";
            echo "<ul>";
            echo "<li>$link_start=1'>"._("one week")."</a>";
            echo "<li>$link_start=2'>"._("two weeks")."</a>";
            echo "<li>$link_start=4'>"._("four weeks")."</a>";
            echo "</ul>";
            echo "</li>\n";
        }

    }
    else
    {
        // Project has been made available for SR

        if ( time() < $project->smoothread_deadline )
        {
            $sr_deadline_str = strftime(
                $date_format, $project->smoothread_deadline );
            $sr_sentence = sprintf(
                _('This project has been made available for smooth reading until %s.'),
                "<b>$sr_deadline_str</b>"
            );

            echo "<li>";
            echo $sr_sentence;
            echo "</li>\n";

            if (!$project->PPer_is_current_user)
            {
                echo "<li>";
                echo "<a href='$project->url/{$projectid}_smooth_avail.zip'>";
                echo _('Download zipped text for smooth reading');
                echo "</a>";
                echo "</li>\n";

                echo "<li>";
                echo "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_done'>";
                echo _("Upload a smooth-read text") ;
                echo "</a>";
                echo "</li>\n";
                // The upload does not cause the project to change state --
                // it's still checked out to PPer.

                if (!sr_user_is_committed($projectid, $pguser))
                {
                    echo "<li>";
                    echo _('If you want, you can indicate your commitment to smoothread this project to the PP by pressing:');
                    sr_echo_commitment_form($projectid);
                    echo "</li>\n";
                }
                else
                {
                    echo "<li>";
                    echo _('You have committed to smoothread this project.');
                    echo "<br />";
                    echo _('If you want to withdraw your commitment, please press:');
                    sr_echo_withdrawal_form($projectid);
                    echo "</li>";
                }

            }
            else
            {
                echo "<li>";
                echo "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_avail&weeks=replace'>";
                echo _("Replace the current file that's available for smooth-reading.");
                echo "</a>";
                echo "</li>";
            }
        }
        else
        {
            echo "<li>";
            echo _('The deadline for smooth-reading this project has passed.');
            echo "</li>";

            if ($project->PPer_is_current_user)
            {
                echo "<li>";
                echo _("But as the project's PPer, you can make it available for smooth-reading for a further period.")." ";
                echo _('Choose how long you want to make it available for.');
                $link_start = "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_avail&weeks";
                echo "<ul>";
                echo "<li>$link_start=1'>"._("one week")."</a>";
                echo "<li>$link_start=2'>"._("two weeks")."</a>";
                echo "<li>$link_start=4'>"._("four weeks")."</a>";
                echo "</ul>";
                echo "</li>\n";
            }


        }

        if ($project->PPer_is_current_user)
        {

            $sr_list = sr_get_committed_users($projectid);

            echo "<li>";
            if (count($sr_list) == 0)
            {
                echo _('Nobody has committed to smoothread this project.');
            }
            else
            {
                echo _('The following users have committed to smoothread this project:');
                echo "<ul>";
                foreach ($sr_list as $sr_user)
                {
                    $user_privmsg_url = sprintf("%s/privmsg.php?mode=post&u=%d", $forums_url, get_bb_user_id($sr_user));
                    echo "<li>";
                    echo "<a href=$user_privmsg_url>$sr_user</a>";
                    echo "</li>\n";
                }
                echo "</ul>\n";
            }
            echo "</li>\n";

            echo "<li>";
            echo_uploaded_zips('_smooth_done_', _('smoothread'));
            echo "</li>";
        }
    }

    echo "</ul>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_ppv_report()
{
    global $project, $code_url;

    if ( $project->state != PROJ_POST_SECOND_CHECKED_OUT ) return;

    if ( !$project->PPVer_is_current_user ) return;

    $url = "$code_url/tools/post_proofers/ppv_report.php?project={$project->projectid}";
    echo "<p><a href='$url'>Submit a PPV Report Card for this project</a></p>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_change_state()
{
    global $project, $pguser, $code_url;

    $valid_transitions = get_valid_transitions( $project, $pguser );
    if (count($valid_transitions) == 0 ) return;

    echo "<h4>";
    echo _("Change Project State");
    echo "</h4>\n";

    $here = $_SERVER['REQUEST_URI'];
    // If the request URI included an 'expected_state' parameter, there's a wrinkle:
    // If the user clicks on this button, the project's state will (normally) change.
    // So if we then returned the user to exactly this URI, they'd get a warning:
    // "The project is no longer in 'this state'. It is now in 'that state'.
    // So we suppress the 'expected_state' parameter from the request URI.
    $here = preg_replace('/expected_state=[A-Za-z._0-9]+/', '', $here );
    // This can leave an extra &, but I suspect browsers can handle it.

    foreach ( $valid_transitions as $transition )
    {
        echo "<form method='POST' action='$code_url/tools/changestate.php'>";
        echo "<input type='hidden' name='projectid'  value='{$project->projectid}'>\n";
        echo "<input type='hidden' name='curr_state' value='{$project->state}'>\n";
        echo "<input type='hidden' name='next_state' value='{$transition->to_state}'>\n";
        echo "<input type='hidden' name='confirmed'  value='yes'>\n";
        echo "<input type='hidden' name='return_uri' value='$here'>\n";

        $question = $transition->confirmation_question;
        if ( is_null($question) )
        {
            $onClick_condition = "return true;";
        }
        else
        {
            $onClick_condition = "return confirm(\"$question\");";
        }
        $onclick_attr = "onClick='$onClick_condition'";
        echo "<input type='submit' value='{$transition->action_name}' $onclick_attr>";
        if (1)
        {
            // Say who is allowed to do this transition.
            echo " [$transition->who_restriction]";
        }

        echo "</form>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_page_summary()
{
    global $project;
    $projectid = $project->projectid;

    if ( !$project->pages_table_exists ) return;

    echo "<center>";
    echo "<h3>"._("Page Summary")."</h3>\n";

    // page counts by state.
    $total_num_pages = Project_getNumPages($projectid);

    echo "<table border=0>\n";
    global $PAGE_STATES_IN_ORDER;
    foreach ($PAGE_STATES_IN_ORDER as $page_state)
    {
        $num_pages = Project_getNumPagesInState($projectid,$page_state);
        if ( $num_pages != 0 )
        {
            echo "<tr><td align='right'>$num_pages</td><td>".sprintf(_("in %s"),$page_state)."</td></tr>\n";
        }
    }
    echo "<tr><td colspan='2'><hr></td></tr>\n";
    echo "<tr><td align='right'>$total_num_pages</td><td align='center'>"._("pages total")."</td></tr>\n";
    echo "</table>\n";
    echo "</center>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_page_table()
{
    global $project;

    if ( !$project->pages_table_exists ) return;

    {
        global $relPath;
        include($relPath.'../tools/project_manager/detail_legend.inc');
        echo _("N.B. It is <b>strongly</b> recommended that you view page differentials by right-clicking on a diff link and opening the link in a new window or tab.")."<br>\n";

        // second arg. indicates to show size of image files.
        echo_page_table($project, 1);
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// vim: sw=4 ts=4 expandtab
?>
