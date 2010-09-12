<?php
$relPath='./pinc/';

// gettext_setup.inc has a reference (in top-level code) to $userP,
// which isn't set properly (if at all) until dpsession_resume() is called.
// Thus, we must include dpsession.inc and call dpsession_resume()
// before we include gettext_setup.inc (directly or indirectly).
// (There's probably several scripts that don't do this,
// so we need a better mechanism.)

include_once($relPath.'dpsession.inc');
$user_is_logged_in = dpsession_resume();
// If the requestor is not logged in,
// we refer to them as a "guest".

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
include_once($relPath.'user_project_info.inc');
include_once($relPath.'wordcheck_engine.inc'); // get_project_word_file
include_once($relPath.'links.inc'); // new_window_link
include_once($relPath.'project_edit.inc'); // check_user_can_load_projects
include_once($relPath.'forum_interface.inc'); // get_last_post_time_in_topic & get_url_*()


// for strftime:
$datetime_format = _("%A, %B %e, %Y at %X");
$date_format     = _("%A, %B %e, %Y");
$time_format     = _("%X");

error_reporting(E_ALL);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Usually, the user arrives here by clicking on the title of a project
// in a list of projects.
// But there are lots of other less-used pages that link here.

$MIN_DETAIL_LEVEL = 1;
$MAX_DETAIL_LEVEL = 4;

// Validate all the input
$projectid      = validate_projectID('id', @$_GET['id']);
$expected_state = get_enumerated_param($_GET, 'expected_state', null, $PROJECT_STATES_IN_ORDER, true);
$detail_level   = get_integer_param($_GET, 'detail_level', 2, $MIN_DETAIL_LEVEL, $MAX_DETAIL_LEVEL);

// -----------------------------------------------------------------------------

$project = new Project( $projectid );

// TRANSLATORS: this is the project page title.
// In a tabbed browser, the page-title passed to theme() will appear in
// the tab, which tends to be small, as soon as you have a few of them.
// So, put the distinctive part of the page-title (i.e. the name of the
// project) first.
$title_for_theme = sprintf( _('"%s" project page'), $project->nameofwork );

$title = sprintf( _("Project Page for '%s'"), $project->nameofwork );

// -----------------------------------------------------------------------------

if ( !$user_is_logged_in )
{
    // Guests see a reduced version of the project page.

    $no_stats=1;
    theme($title_for_theme, "header");

    echo "<h1>$title</h1>\n";

    list($top_blurb, $bottom_blurb) = decide_blurbs();
    do_blurb_box( $top_blurb );
    do_project_info_table();
    do_blurb_box( $bottom_blurb );

    do_smooth_reading();

    echo "<br>\n";
    theme('', 'footer');
    return;
}

if ( $user_is_logged_in )
{
    upi_set_t_latest_home_visit(
        $pguser, $project->projectid, $project->t_retrieved );
}

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
    do_event_subscriptions();
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
    global $project, $detail_level, $MIN_DETAIL_LEVEL, $MAX_DETAIL_LEVEL;

    echo sprintf(
        _('This page is being presented at detail level %d.'),
        $detail_level
    );
    echo "\n";
    echo _('Switch to:'), "\n";
    for($v = $MIN_DETAIL_LEVEL; $v <= $MAX_DETAIL_LEVEL; $v++ )
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

    $blurb = can_user_get_pages_in_project( $pguser, $project, $round );
    if ( $blurb )
        return array( $blurb, $blurb );

    {
        // If there's any proofreading to be done, this is the link to use.
        $url = url_for_pi_do_whichever_page( $projectid, $state );
        $label = _("Start Proofreading");
        $proofreading_link = "<b><a href='$url'>$label</a></b>";

        // When were the project comments last modified?
        $comments_timestamp = $project->t_last_change_comments;
        $comments_time_str = strftime($datetime_format, $comments_timestamp);
        $comments_last_modified_blurb = _("Project Comments last modified:") . " " . $comments_time_str;

        // Other possible components of blurbs:
        $please_scroll_down = _("Please scroll down and read the Project Comments for any special instructions <b>before</b> proofreading!");
        $the_link_appears_below = _("The 'Start Proofreading' link appears below the Project Comments");
        $comments_have_changed =
            "<font color='red'>"
            . "<b>"
            . _("Project Comments have changed!")
            . "</b>"
            . "</font>";

        // ---

        $bottom_blurb =
            $comments_last_modified_blurb
            . "<br>"
            . $proofreading_link;

        // Has the user saved a page of this project since the project comments were
        // last changed? If not, it's unlikely they've seen the revised comments.
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
                . $comments_last_modified_blurb
                . "<br>"
                . $the_link_appears_below;
        }
        else
        {
            // The user has saved a page for this project.
            $my_latest_save_timestamp = mysql_result($res,0,$round->time_column_name);

            if ($my_latest_save_timestamp < $comments_timestamp)
            {
                // The latest page-save was before the comments were revised.
                // The user probably hasn't seen the revised project comments.
                $top_blurb =
                    $comments_have_changed
                    . "<br>"
                    . $please_scroll_down
                    . "<br>"
                    . $comments_last_modified_blurb
                    . "<br>"
                    . $the_link_appears_below;
            }
            else
            {
                // The latest page-save was after the comments were revised.
                // We'll assume that the user has read the new comments.
                $top_blurb =
                    $please_scroll_down
                    . "<br>"
                    . $comments_last_modified_blurb
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
    global $user_is_logged_in;

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
    
    // the array below should guarantee that the strings 'beginner',
    // 'easy', 'average' and 'hard' reach the po file, so that using
    // later _($project->difficulty) should translate the project
    // difficulty, if regularly formed, or display the (irregular)
    // english project difficulty.
    if (0) 
    {
        $difficulty_labels = array(
            'beginner' => _('beginner'),
            'easy'     => _('easy'),
            'average'  => _('average'),
            'hard'     => _('hard'),
        );
    }

    echo_row_a( _("Title"),           $project->nameofwork );
    echo_row_a( _("Author"),          $project->authorsname );
    echo_row_a( _("Language"),        $project->language );
    echo_row_a( _("Genre"),           _($project->genre) );
    echo_row_a( _("Difficulty"),      _($project->difficulty) );

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

    // We choose not to show guests anything involving users' names.
    if ( $user_is_logged_in )
    {
        echo_row_a( _("Project Manager"), $project->username );

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
    }

    // -------------------------------------------------------------------------
    // Current activity

    $formatted_now = strftime($time_format, time());
    $ct = _("Current Time");
    $current_time_addition = "&nbsp;&nbsp;&nbsp;($ct: $formatted_now)";

    echo_row_a(
        _("Last Edit of Project Info"),
        strftime($datetime_format, $project->t_last_edit)
        . $current_time_addition );

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
            $lastproofed = strftime($datetime_format, $latest_save_time);
        }
        else
        {
            $lastproofed = _("Project has not been proofread in this round.");
        }
        echo_row_a( _("Last Proofread"), $lastproofed );
    }

    // -------------------------------------------------------------------------

    // We choose not to show guests the word lists.
    if ( $user_is_logged_in )
    {
        $good_bad = array(
            'good' => _("Good Words"),
            'bad'  => _("Bad Words"),
        );

        $links = '';
        foreach ( $good_bad as $gb => $label )
        {
            $f = get_project_word_file($projectid, $gb);
            if ( $f->size > 0 )
            {
                $links .= new_window_link( $f->abs_url, $label );
                $links .= " - " . _("Last modified") . ": " . strftime($datetime_format,$f->mod_time);
            }
            else
            {
                $links .= $label . " " . _("(empty)");
            }
            $links .= "<br>";
        }

        echo_row_a( _("Word Lists"), $links );
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
            $postednum . " &ndash; " .
            sprintf(_("<a href='%s'>Read this text</a> at Project Gutenberg"),
                 get_pg_catalog_url_for_etext($postednum)));
    }

    // -------------------------------------------------------------------------
    // Forum topic

    $topic_id = $project->topic_id;
    if (!empty($topic_id))
    {
        $last_post_date = get_last_post_time_in_topic($topic_id);
        $last_post_date = strftime($datetime_format, $last_post_date);
        echo_row_a( _("Last Forum Post"), $last_post_date );
    }

    // If the topic is only visible to logged-in users,
    // there's little point showing guests the link to it.
    if ( $user_is_logged_in )
    {
        if ($topic_id == "")
        {
            $blurb = _("Start a discussion about this project");
        }
        else
        {
            $blurb = _("Discuss this project");
        }
        $url = "$code_url/tools/proofers/project_topic.php?project=$projectid";
	if (($state == PROJ_DELETE) && ($topic_id == ""))
	{
	    echo_row_a( _("Forum"), _("The project has been deleted, and no discussion exists."));
	}
	else
	{
            echo_row_a( _("Forum"), "<a href='$url'>$blurb</a>" );
	}
    }

    // -------------------------------------------------------------------------

    // For now, we say that guests can't see page details.
    if ( $user_is_logged_in )
    {
        global $detail_level;
        if ($detail_level >= 4)
        {
            // We'll call do_page_table later, so we don't need the "Page Detail" link.
        }
        else
        {
            if ($project->pages_table_exists)
            {
                $url = "$code_url/tools/project_manager/page_detail.php?project=$projectid&show_image_size=0";
                $blurb = _("Images, Pages Proofread, & Differences");
                $url2 = "$url&select_by_user";
                $blurb2 = _("Just my pages");
                $detail = "<a href='$url'>$blurb</a> &gt;&gt;<a href='$url2'>$blurb2</a>&lt;&lt;";
            }
            else
            {
                if ($project->archived != 0) {
                    $detail = _("The project has been archived, so page details are not available.");
                } elseif ($project->state == PROJ_DELETE) {
                    $detail = _("The project has been deleted, so page details are not available.");
                } else {
                    $detail = _("Page details are not available for this project.");
                }
            }
            echo_row_a( _("Page Detail"), $detail);
        }
    }

    // -------------------------------------------------------------------------
    // Personal data with respect to this project

    // If you're not logged in, we certainly can't show your personal data.
    if ( $user_is_logged_in )
    {
        global $detail_level;
        if ($round && $detail_level > 1)
        {
            recentlyproofed(0);
            recentlyproofed(1);
        }
    }

    // -------------------------------------------------------------------------
    // Comments

    global $pguser;

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
        // Likewise, when it's available for PPing, a prospective PPer should be able
        // to read them
        elseif ( $project->PPer_is_current_user || 
                 $project->PPVer_is_current_user ||
                 $project->can_be_managed_by_current_user ||
                 $state==PROJ_POST_FIRST_AVAILABLE && user_can_work_in_stage($pguser,'PP') ||
                 $state==PROJ_POST_SECOND_AVAILABLE && user_can_work_in_stage($pguser,'PPV') )
        {
            echo_row_b( _("Post Processor's Comments"), '' );
            echo_row_c( $postcomments );
        }
    }

    // --------

    // For now, we suppress Project Comments for guests.
    // (They might be confused by the instructions for proofreaders.)
    if ( $user_is_logged_in )
    {
        $comments = $project->comments;

        // Automatically prepend second round comments template for Beginners Only
	// The r2 in the template name is an artifact of the old two-round structure
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

            $time_str = strftime($datetime_format, $project->t_last_change_comments );
            $c = "(" . _("last modified:") . " " . $time_str . ")";

            $comments_blurb = "$a<br>$b<br>$c";
        }
        else
        {
            $comments_blurb = "";
        }
        echo_row_b( _("Project Comments"), $comments_blurb );

        echo_row_c( $comments );
    }

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
    echo "<a href='$code_url/tools/project_manager/editproject.php?action=edit&project=$project->projectid&amp;return=" . urlencode($_SERVER["REQUEST_URI"]) . "'>";
    echo _("Edit the above information");
    echo "</a>";
    echo " | ";
    echo "<a href='$code_url/tools/project_manager/edit_project_word_lists.php?projectid=$project->projectid&amp;return=" . urlencode($_SERVER["REQUEST_URI"]) . "'>";
    echo _("Edit project word lists");
    echo "</a>";
    echo "</p>";

    if (! user_has_project_loads_disabled() )
    {
        echo "<p>";
        echo "<a href='$code_url/tools/project_manager/editproject.php?action=clone&project=$project->projectid'>";
        echo _("Clone this project");
        echo "</a>";
        echo "</p>";
    }
    // possibly print a message, which will appear where the clone project
    // link would otherwise be, and near where the add/replace files 
    // section would be.
    check_user_can_load_projects(false); // keep going, even if they can't
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
            echo "<input type='submit' value='", attr_safe(_("Add")), "'>";
            echo "<br>\n";
            echo "</form>\n";
            $add_reminder = TRUE;
        }
    }

    // Load text+images from uploads area into project.
    // Can do this if it's a new project (as measured by the state it's in)
    // If the user is disabled from uploading new projects, they can only
    // do this if the project already has some pages loaded, but there is
    // no need to display a message reminding them that they can't, as 
    // there will already be one instead of the clone project link, just above.
    if (
        ( ($state == PROJ_NEW && ! $site_supports_metadata)
          || ( $site_supports_metadata && ($state == PROJ_NEW_APPROVED || $state == PROJ_NEW_FILE_UPLOADED) )
          || $state == PROJ_P1_UNAVAILABLE )
        && ( Project_getNumPages($projectid) > 0 || ! user_has_project_loads_disabled() )
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
        echo "<p>\n";
        echo _("Remember to upload the illustration files as well as the page files!");
        echo "</p>\n";
        echo "<input type='submit' value='", attr_safe(_("Add/Replace")), "'>";
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
            _("Reminder for uploads: host=%s account=%s password=%s"),
            "<b>$uploads_host</b>", 
            "<b>$uploads_account</b>", 
            "<i><font color='#DDDDDD'>$uploads_password</font></i>" );
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
            echo "<li>" . _("(none!)") . "</li>\n";
        }
        echo "</ul>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_event_subscriptions()
{
    global $project, $code_url, $subscribable_project_events, $pguser;

    $projectid = $project->projectid;

    echo "<a name='event_subscriptions'></a>\n";
    echo "<h4>", _("Event Subscriptions"), "</h4>\n";

    echo "<div style='margin-left:3em'>\n";

    $user_email_address = get_forum_email_address($pguser);
    echo "<p>";
    echo _("Here you can sign up to be notified when certain events happen to this project.");
    echo "\n";
    echo sprintf(
        _("Notifications will be sent to your email address, which is currently &lt;%s&gt;. (If this is not correct, please visit <a href='%s'>your profile</a>.)"),
        $user_email_address,
        get_url_to_edit_profile()
    );
    echo "\n";
    echo _("Your current subscriptions are shown below with a shaded background.");
    echo "</p>\n";

    $n_users_subscribed_to_ = get_n_users_subscribed_to_events_for_project( $projectid );

    $url = "$code_url/tools/set_project_event_subs.php";
    echo "<form method='post' action='$url'>\n";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";
    echo "<input type='hidden' name='return_uri' value='{$_SERVER['REQUEST_URI']}#event_subscriptions'>\n";
    echo "<table>\n";
    echo "<tr>";
    echo "<th>", _("Event"), "</th>";
    echo "<th>", _("Subscribed?"), "</th>";
    echo "<th>", _("Users Subscribed"), "</th>";
    echo "</tr>\n";
    foreach ( $subscribable_project_events as $event => $label )
    {
        $is_subd = user_is_subscribed_to_project_event( $pguser, $projectid, $event );
        $bgcolor = ( $is_subd ? '#CFC' : '#FFF' );
        $checked = ( $is_subd ? 'checked' : '' );
        echo "<tr>";
        echo "<td>$label</td>";
        echo "<td style='text-align:center; background-color:$bgcolor;'><input type='checkbox' name='$event' $checked></td>";
        echo "<td style='text-align:center;'>{$n_users_subscribed_to_[$event]}</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "<input type='submit' value='", attr_safe(_("Update Event Subscriptions")), "'>\n";
    echo "</form>\n";

    echo "</div>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_history()
{
    global $project;

    echo "<h4>", _("Project History"), "</h4>\n";

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
    
    // The project history is only partially translated right now.
    $event_type_labels = array(
        "archive" => _("archive"),
        "creation" => _("creation"),
        "deletion" => _("deletion"),
        "edit" => _("edit"),
        "smooth-reading" => _("smoothreading"),
        "transition" => _("transition"),
        "transition(s)" => _("transition(s)")
    );

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

        echo "<td>{$event['who']}</td>\n";

        $event_type = $event['event_type'];
        echo "<td>",
             array_get($event_type_labels, $event_type, $event_type),
             "</td>\n";

        if ( $event['event_type'] == 'transition' || $event['event_type'] == 'transition(s)')
        {
            $from_state = $event['details1'];
            $from_state_t = (
                $from_state == '?'
                ? _('unknown state')
                : get_medium_label_for_project_state($from_state)
            );
            if ( $from_state_t == '' ) $from_state_t = $from_state;

            $to_state = $event['details2'];
            $to_state_t = (
                $to_state == '?'
                ? _('unknown state')
                : get_medium_label_for_project_state($to_state)
            );
            if ( $to_state_t == '' ) $to_state_t = $to_state;

            $details3 = $event['details3'];
            $details3_t = $details3; // but that can be overridden by ...
            $mappings = array(
                '/^via_q:\s*$/'     => _('via no queue'),
                '/^via_q:\s*(.*)/'  => _('via "%s" queue'),
                '/^out_to:\s*(.*)/' => _('checked out to %s'),
            );
            foreach ( $mappings as $pattern => $format )
            {
                if ( preg_match($pattern, $details3, $matches) )
                {
                    unset($matches[0]); // the (sub)string that matched the whole pattern
                    $details3_t = vsprintf($format, $matches);
                    break;
                }
            }

            echo "<td>", sprintf(_("from %s"), $from_state_t), "</td>\n";
            echo "<td>", sprintf(_("to %s"), $to_state_t), "</td>\n";
            echo "<td>$details3_t</td>\n";
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
        elseif ( $event['details1'] != '' )
        {
            echo "<td colspan='3'>{$event['details1']}</td>\n";
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

    $link_url = "$code_url/tools/proofers/images_index.php?project=$projectid";

    $link_text = (
        $project->can_be_managed_by_current_user
        ? _("View/Replace Images")
        : _("View Images Online")
    );

    echo "<h4>", _('Images'), "</h4>";
    echo "<ul>";
    echo "<li><a href='$link_url'>$link_text</a></li>\n";
    echo "</ul>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_extra_files()
{
    global $project;

    if ( !$project->can_be_managed_by_current_user
        && !$project->PPer_is_current_user
        && !$project->PPVer_is_current_user )
    {
        return;
    }

    if ( !$project->dir_exists ) return;

    echo "<h4>", _('Extra Files in Project Directory'), "</h4>";

    $saved_dir = getcwd();

    chdir($project->dir);
    $filenames = glob("*" );

    echo "<ul>";

    $n_extra_files = 0;
    foreach ($filenames as $filename)
    {
        if ( is_an_extra_file($filename) )
        {
            echo "<li><a href='$project->url/$filename'>$filename</a></li>";
            $n_extra_files += 1;
        }
    }

    if ( $n_extra_files == 0 )
    {
        echo "<li>", _("(none)"), "</li>\n";
    }

    echo "</ul>";

    chdir($saved_dir);
}

function is_an_extra_file( $filename )
{
    global $project;

    static $excluded_filenames = NULL;
    if ( is_null($excluded_filenames) )
    {
        $excluded_filenames = array();
        // A small set of filenames that we know a priori
        // will be excluded if they occur.


        // These appear at "Word Lists":
        foreach ( array('good', 'bad') as $code )
        {
            $f = get_project_word_file($project->projectid, $code);
            $excluded_filenames[$f->filename] = 1;
        }

        // These three appear under "Post Downloads":
        $excluded_filenames[$project->projectid . 'images.zip'] = 1;
        $excluded_filenames[$project->projectid . '.zip'] = 1;
        $excluded_filenames[$project->projectid . '_TEI.zip'] = 1;

        // should really exclude uploaded SR, PP and PPV files too,
        // but we can't just add them to excluded_filenames because we
        // don't know their names in advance
    }

    if ( array_key_exists( $filename, $excluded_filenames ) ) return FALSE;

    // Exclude all images (both page-images and non-page-images).
    $image_extensions = array('png','jpg');
    $extension = pathinfo($filename,PATHINFO_EXTENSION);
    if ( in_array($extension, $image_extensions) ) return FALSE;

    return TRUE;
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
        echo _("Generate Post Files (This will overwrite existing post files, if any.)"), "\n";
    }
    else
    {
        echo _("Download Concatenated Text"), "\n";
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

    echo _("For each page, use:"), "<br>\n";
    echo "<input type='radio' name='which_text' value='EQ' CHECKED>";
    echo _("the text (if any) saved in the selected round; or"), "<br>\n";
    echo "<input type='radio' name='which_text' value='LE'>";
    echo _("the latest text saved in any round up to and including the selected round."), "<br>\n";
    echo _("(If every page has been saved in the selected round, then the two choices are equivalent.)"), "<br>\n";

    // Proofreader names allowed for people who can see proofreader names
    // on the page details
    if ( $project->names_can_be_seen_by_current_user )
    {
        echo _("Include usernames?"), " &nbsp;&nbsp; ";
        echo "<input type='radio' name='include_proofers' value='1' CHECKED />";
        echo _("Yes"), " &nbsp;&nbsp; ";
        echo "<input type='radio' name='include_proofers' value='0' />";
        echo _("No"), "<br />\n";
    }
    else
    {
        echo "<input type='hidden' name='include_proofers' value='0' />";
    }

    // saving files allowed only for sitemanagers
    if (user_is_a_sitemanager())
    {
        echo _("Save file on server?"), "  &nbsp;&nbsp; ";
        echo "<input type='radio' name='save_files' value='1' CHECKED />";
        echo _("Yes"), " &nbsp;&nbsp; ";
        echo "<input type='radio' name='save_files' value='0' />";
        echo _("No"), "<br />\n";

        echo "<input type='submit' value='", attr_safe(_("(Re)generate")), "'>\n";
    }
    else
    {
        echo "<input type='hidden' name='save_files' value='0' />";
        echo "<input type='submit' value='", attr_safe(_("Download")), "'>\n";
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
      // TRANSLATORS: %s is an adjective like "partially verified" or "smoothread"
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
      // TRANSLATORS: %s is an adjective like "partially verified" or "smoothread"
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
    echo " (", sprintf(_("%d kb"), $filesize_kb), ")";
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
      echo "<br /><input type='submit' value='" . attr_safe(_('Update comment and project status')) . "'/>";
      echo "</form>\n";

    }

}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_smooth_reading()
{
    global $project, $code_url, $pguser, $date_format;

    if ( $project->state != PROJ_POST_FIRST_CHECKED_OUT ) return;

    $projectid = $project->projectid;

    $current_user_can_manage_SR_for_this_project =
        $project->PPer_is_current_user || user_is_a_sitemanager();
    // i.e., can:
    // -- make the project available for SR (initially or again),
    // -- replace the SR-able text,
    // -- see SR-commitments, and
    // -- read SR'ed texts

    echo "<h4>", _('Smooth Reading'), "</h4>";
    echo "<ul>";

    if ( $project->smoothread_deadline == 0 )
    {
        echo "<li>";
        echo _('This project has not been made available for smooth reading.');
        echo "</li>";

        if ($current_user_can_manage_SR_for_this_project)
        {
            echo "<li>";
            echo _("But you can make it available.");
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

            if ($current_user_can_manage_SR_for_this_project)
            {
                echo "<li>";
                echo "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_avail&weeks=replace'>";
                echo _("Replace the current file that's available for smooth-reading.");
                echo "</a>";
                echo "</li>";
            }

            if (!$project->PPer_is_current_user)
            {
                echo_download_zip( _("Download zipped text for smooth reading"), '_smooth_avail' );
                
                // We don't allow guests to upload the results of smooth-reading.
                global $user_is_logged_in;
                if ( $user_is_logged_in )
                {
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
                    echo _('Please note that while unregistered guests are welcome to download texts for smooth reading, only registered volunteers are able to upload annotated texts.');
                    echo "\n";
                    echo _('A registration link is available at the top of this page.');
                    echo "</li>\n";
                }
            }
        }
        else
        {
            echo "<li>";
            echo _('The deadline for smooth-reading this project has passed.');
            echo "</li>";

            if ($current_user_can_manage_SR_for_this_project)
            {
                echo "<li>";
                echo _("But you can make it available for smooth-reading for a further period.")." ";
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

        if ($current_user_can_manage_SR_for_this_project)
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
                    $user_privmsg_url = get_url_to_compose_message_to_user($sr_user);
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
    echo "<p><a href='$url'>", _("Submit a PPV Summary for this project"), "</a></p>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_change_state()
{
    global $project, $pguser, $code_url, $charset;

    $valid_transitions = get_valid_transitions( $project, $pguser );
    if (count($valid_transitions) == 0 ) return;

    echo "<h4>";
    echo _("Change Project State");
    echo "</h4>\n";

    if ( $project->state == PROJ_NEW )
    {
        echo "<p>\n";
        echo _("Check for missing pages and make sure that all illustration files have been uploaded <b>before</b> moving this project into the rounds.");
        echo "</p>\n";
    }

    // print out a message if PM has project loads disabled,
    // as they can't move a project out of the unavailable state
    if ( $project->can_be_managed_by_current_user )
    {
        check_user_can_load_projects(false);
    }

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
            $onClick_condition = "return confirm(\"" 
                . javascript_safe($question, $charset) . "\");";
        }
        $onclick_attr = "onClick='$onClick_condition'";
        echo "<input type='submit' value='", attr_safe($transition->action_name), "' $onclick_attr>";
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
