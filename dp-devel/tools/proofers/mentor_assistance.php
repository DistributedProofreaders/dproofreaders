<?php
/*
 Displays information useful to Mentors.
(i.e. those who are second-round proofreading projects with difficulty = "BEGINNER")

************************************
*/

error_reporting(E_ALL);

$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'DpTableClass.inc');

require_login();

// ---------------------------------------------------------------

// get the args
$urlself    = $_SERVER['PHP_SELF'];
$round_id   = @$_GET['round_id'] ;
$action     = @$_GET['action'] ;
$username   = @$_POST['username'] ;
$projectid  = @$_POST['projectid'] ; 

// if checking out a user/project
if(! empty( $projectid ) && ! empty( $username ) ) {

}

// Gather the valid mentoring rounds

$mentoring_rounds = get_mentoring_rounds();

if( count( $mentoring_rounds ) == 0 ) {
    die( $_( "There are no mentoring rounds." ) );
}

// ---------------------------------------------------------------

// Establish the mentoring round, and validate the user

$mentoring_round = get_mentoring_round();

if( ! user_can_mentor_in_round( $mentoring_round ) )
{
    $errmsg = sprintf(
        _( "You do not have access to 'Mentors Only' projects in %s." ),
        $mentoring_round->id ) ;
    die( $errmsg );
}



// ---------------------------------------------------------------

// We're done with validations, show the data.

// ---------------------------------------------------------------

// Display page header.
$args['css_data']   = mycss() ;
$args['js_data']    = myjs();

output_header( _("Mentoring Assistance"), SHOW_STATSBAR, $args );

// ---------------------------------------------------------------

// Are there other mentoring rounds? If so, provide mentoring links for them.
if ( count($mentoring_rounds) > 1 )
{
    show_other_mentoring_rounds();
}

// ---------------------------------------------------------------

// Collect page data for all mentorable  projects in this round
$page_rows = get_page_rows( $mentoring_round ) ;

// Collect a list of proofers and their projects for these pages
$proofers = get_proofers_and_projects();

// Create the table for display for each project for each proofer
$proofer_round_id = $mentoring_round->mentee_round->id;

$tbl = new DpTable();

$tbl->AddColumn(_("Page"), "fileid" );
$tbl->AddColumn(_("Age"), "unixtime", "_ago" );
$tbl->AddColumn(_("State"), "page_state");


echo "
      <form id='coform' method='post' action='" . htmlspecialchars($urlself, ENT_QUOTES) . "'>
      <input type='hidden' id='username' value='$username' />
      <input type='hidden' id='projectid' value='$projectid' />\n";
$strmsg = sprintf( _("%s Mentor pages"), $mentoring_round->id );
echo "<h2>$strmsg</h2>";

$tbl->SetRows( $page_rows );

$filter_proofer = null;
$filter_project = null;

$i = 0 ;
foreach( $proofers as $username => $proofer )
{
    $filter_proofer  = $username;
    $filter_project = null;
    $allcount       = $proofer['total_count'];
    $pagecount      = $proofer['proofer_page_count'];
    $mentoringcount = $proofer['mentoring_count'];
    $yourcount      = $proofer['your_count'];
    $uid            = $proofer['uid'];

    $privmsg = _("Send private message");

    echo "\n<hr>\n";
    $proofer_link = "<a href=\"{$code_url}/stats/members/mdetail.php?id={$uid}\" target=\"_blank\">$username</a>
    <a href=\"{$forums_url}/privmsg.php?mode=post&amp;u={$uid}\" target='_blank'>"
    ."<img src=\"{$forums_url}/templates/subSilver/images/lang_english/icon_pm.gif\"
    alt='{$privmsg}' title='{$privmsg}' border='0' /></a>\n";

    $pgs_to_mentor  = _("Pages to mentor");
    $being_mentored = _("being mentored");
    $by_you         = _("by you");
    $pages_total    = _("pages total");

    echo "<h3>{$proofer_link} - {$pgs_to_mentor}: $pagecount 
          {$being_mentored}: $mentoringcount  {$by_you}
          $yourcount ({$proofer_round_id} {$pages_total}: $allcount)</h3>\n";

    $projects = & $proofer['projects'];
    foreach( $projects as $proj => $info )
    {
        $title = $info['title'];
        $project_link = _project_link( $proj, $title );
        $project_page_count = $info['project_page_count'];
        $mentoring_project_count = $info['mentoring_project_count'];
        $your_project_count = $info['your_project_count'];
        $filter_project = $proj;
        $btnid = "btn" . $i ;
        $tblid = "tbl" . $i ;
        $divid = "div" . $i ;
        $strmsg = "$project_link - {$pgs_to_mentor}: $project_page_count  
            {$being_mentored}: $mentoring_project_count  {$by_you}: $your_project_count";
        echo "<p>
                <input type='button' id='{$btnid}' class='tinybutton' value='+' style='display: inline'
                onclick='flop(\"{$divid}\")' /> $strmsg "
                ."</p>";
        $_rows = array_filter( $page_rows, "_proofer_project_filter" );
        echo "\n<div id='div{$i}' style='display: none'>\n";
        $tbl->SetId( "tbl{$i}" ) ;
        $tbl->SetRows( $_rows );
        $tbl->EchoTable();
        echo "</div>\n";
    }
    $i++ ;
}

echo "
      </form>\n";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// -------------------------------------------------------------------

function get_mentoring_projects($mentoring_round)
{
    $sql = "
        SELECT
            projectid,
            nameofwork AS title,
            authorsname AS author
        FROM
            projects
        WHERE
            difficulty = 'BEGINNER'
        AND
            state='{$mentoring_round->project_available_state}'
    ";
    return _sql_objects( $sql ) ;
}

function get_mentoring_rounds()
{
    global $Round_for_round_id_;

    $_rounds = array();
    foreach ( $Round_for_round_id_ as $round )
    {
        if ( $round->is_a_mentor_round() )
        {
            $_rounds[] = $round;
        }
    }
    return $_rounds;
}

function get_mentoring_round()
{
    global $mentoring_rounds;
    $round_id = @$_GET['round_id'];

    if ( $round_id != '' )
    {
        $round = get_Round_for_round_id( $round_id ) ;
        if (! $round->is_a_mentor_round() )
        {
            die("{$mentoring_round->id} is not a mentoring round!");
        }
        return $round;
    }

    // Consider the page they came from.
    $referer = @$_SERVER['HTTP_REFERER'];

    // If they're coming to this page from a MENTORS ONLY book in X2, 
    // referrer should contain &expected_state=X2.proj_avail.
    
    foreach ( $mentoring_rounds as $mround )
    {
        if ( strpos($referer, $mround->project_available_state) )
        {
            return $mround;
        }
    }

    // Just take the first.
    return $mentoring_rounds[0];
}

function show_other_mentoring_rounds()
{
    global $code_url, $mentoring_rounds, $mentoring_round;

    echo "<p class='rtext'>(" . _('Mentor Round');

    foreach( $mentoring_rounds as $other_round )
    {
        if($other_round != $mentoring_round)
        {
            $url = "$code_url/tools/proofers/for_mentors.php?round_id={$other_round->id}";
            echo " <a href=\"$url\"> {$other_round->id}</a>";
        }
    }
    echo ")</p>";
}

// -------------------------------------------------------------------

function get_page_rows( $mentoring_round )
{
    global $pguser;
    $pages = array();

    $mentored_round = $mentoring_round->mentee_round;
    
    $projects = get_mentoring_projects( $mentoring_round );

    $mentoring_user_column  = $mentoring_round->user_column_name;
    $mentored_user_column   = $mentored_round->user_column_name;
    $mentored_time_column   = $mentored_round->time_column_name;

    $round_tallyboard = new TallyBoard($mentored_round->id, 'U' );
    list($join_clause, $count_column) =
            $round_tallyboard->get_sql_joinery_for_current_tallies('u.u_id');


    foreach( $projects as $project )
    {
        $sql = "SELECT
                    p.projectid,
                    p.nameofwork AS title,
                    p.authorsname AS author,
                    pg.fileid,
                    pg.image,
                    u.u_privacy AS privacy,
                    u.u_id,
                    {$count_column} AS total_count,
                    pg.{$mentored_user_column} AS mentored_username,
                    pg.{$mentored_time_column} AS unixtime,
                    pg.{$mentoring_user_column} AS mentor_username,
                    FROM_UNIXTIME(pg.{$mentored_time_column}) AS time1,
                    pg.state AS page_state
                FROM
                    projects AS p,
                    {$project->projectid} AS pg
                JOIN users AS u ON pg.{$mentored_user_column} = u.username
                $join_clause
                WHERE 
                    p.projectid = '{$project->projectid}'
                    AND ( pg.state = '{$mentoring_round->page_avail_state}'
                          OR (
                            pg.{$mentoring_user_column} = '{$pguser}'
                            AND pg.state IN ( '{$mentoring_round->page_out_state}',
                                              '{$mentoring_round->page_temp_state}'
                                            )
                            )
                    )
                ORDER BY
                    mentored_username,
                    unixtime";

        $rows = _sql_rows( $sql );
        $pages = $pages + $rows;
    }
    return $pages;
}

function get_proofers_and_projects( )
{
    global $pguser, $page_rows;
    global $mentoring_round;
    $mlist = array();

    foreach( $page_rows as $row )
    {
        $uname       = $row['mentored_username'];
        $mentorname  = $row['mentor_username'];
        $prooftime   = $row['unixtime'];
        $projectid   = $row['projectid'];
        $title       = $row['title'];
        $page_state  = $row['page_state'];

        if( ! isset($mlist[$uname]) ) 
        {
            $projectid   = $row['projectid'];
            $total_count = $row['total_count'];
            $uid         = $row['u_id'];

            $mlist[$uname]['total_count']           = $total_count;
            $mlist[$uname]['uid']                   = $uid;
            $mlist[$uname]['usertime']              = $prooftime;
            $mlist[$uname]['proofer_page_count']    = 0;
            $mlist[$uname]['mentoring_count']       = 0;
            $mlist[$uname]['your_count']            = 0;
        }

        if( ! isset( $mlist[$uname]['projects'] )
                || ! isset( $mlist[$uname]['projects'][$projectid] ) ) 
        {
            $mlist[$uname]['projects'][$projectid]['title']  = $title;
            $mlist[$uname]['projects'][$projectid]['projtime'] = $prooftime;
            $mlist[$uname]['projects'][$projectid]['project_page_count'] = 0;
            $mlist[$uname]['projects'][$projectid]['mentoring_project_count']  = 0;
            $mlist[$uname]['projects'][$projectid]['your_project_count']       = 0;
        }
        if( ( $mentorname == $pguser ) && ( $page_state !=  $mentoring_round->page_avail_state ) )
        {
            $mlist[$uname]['your_count'] = $mlist[$uname]['your_count'] + 1;
            $mlist[$uname]['projects'][$projectid]['your_project_count'] 
                = $mlist[$uname]['projects'][$projectid]['your_project_count'] + 1;
        }
        if( ( $page_state == $mentoring_round->page_out_state )
                || ( $page_state == $mentoring_round->page_temp_state ) )
        {
            $mlist[$uname]['mentoring_count'] = $mlist[$uname]['mentoring_count'] + 1;
            $mlist[$uname]['projects'][$projectid]['mentoring_project_count'] 
                = $mlist[$uname]['projects'][$projectid]['mentoring_project_count'] + 1; 
        }
        $mlist[$uname]['proofer_page_count'] = $mlist[$uname]['proofer_page_count'] + 1;
        $mlist[$uname]['projects'][$projectid]['project_page_count']
                = $mlist[$uname]['projects'][$projectid]['project_page_count'] + 1;
    }
    return $mlist;
}


function _checkout_button( $username, $projectid )
{
    $check_out = _("check out");
    $str = "
    <input type='button' value='$check_out' onclick='checkout(\"$username\", \"$projectid\")' />\n";
    return $str;
}

function _ago($unixtime)
{
    // Display a human readable time delta from $unixtime to now
    $interval  = time() - $unixtime;
    $days_ago  = (int) ($interval / 24 / 60 / 60);
    $interval %= 24 * 60 * 60;
    $hours_ago = (int) ($interval / 60 / 60);
    $interval %= 60 * 60;
    $mins_ago  = (int) ($interval / 60);

    return sprintf(
        _('%1$d days %2$d hours %3$d minutes'),
        $days_ago, $hours_ago, $mins_ago
    );
}

function fmt_time( $val )
{
    // return $row->unixtime;
    return date('m-d h:i a', $val );
}

function toHTML( $s )
{
    return htmlspecialchars( $s ) ;
}

function _proofer_project_filter( $row )
{
    global $filter_proofer, $filter_project;
    $ok = ( $row['mentored_username'] == $filter_proofer 
            && $row['projectid'] == $filter_project ) ;
    return $ok;
}

function _project_link( $projectid, $title ) 
{
    global $code_url;
    return "<a href=\"$code_url/project.php"
            . "?id={$projectid}\" target='_blank'>".toHTML( $title )."</a>\n";
}

function _sql_rows( $sql )
{
    $a = array();
    $result = mysql_query($sql);
    if(mysql_error()) {
        die("<pre>MySQL Error in $sql</pre>");
    }
    while($r = mysql_fetch_array($result)) {
        $a[] = $r;
    }
    return $a;
}

function _sql_objects( $sql ) 
{
    $a = array();
    $result = mysql_query($sql);
    if(mysql_error()) {
        die("<pre>MySQL Error in $sql</pre>");
    }

    while($o = mysql_fetch_object($result)) {
        $a[] = $o;
    }

    mysql_free_result($result);
    return $a;
}

function mycss()
{
return "
h2 {
    text-align: center;
}

body {
   width: 100%;
   margin: 0;
   padding: 0;
   font-size: normal;
   color: #4F5155;
   background-color: #FFFFFF;
   line-height: 1.4em;
}

.rtext {
    text-align: right;
}

.content {
    margin: 0 2% 0 2%;
    width: 100%;
    min-height: 2em;
}

.dptable {
    font-size: .95em;
    border-width: 1px;
    border-color: #CCC;
    border-collapse: collapse;
    margin-bottom: 2em;
    height: 10%;
    width: 96%;
}

td.dptable {
    padding: 1px 3px;
}

.tinybutton {
    border-width: 0;
    margin-right: 0;
    offset-right: 1em;
    padding: 0;
}

th {
    background-color: #e0e8dd;
    color: #000000;
    padding: 3px 3px;
    text-align: left;
    border-color: gray;
    border-width: 1px;
}

tr.even td {
}

tr.odd td {
   background-color: #e0e8dd;
}\n";
}
function myjs()
{
    return "

    window.onload = function()
    {
        if( $('username').length > 0 || $('projectid').length > 0 ) {
            alert( $('username').value + ' ' + $( 'projectid' ).value );
        }
    }

    function $(name)
    {
        return document.getElementById(name);
    }

    function coform()
    {
        return $('coform');
    }

    function submitform()
    {
        coform().submit() ;
    }

    function flop( divid )
    {
        var div = $( divid );
        div.style.display = ( div.style.display == 'none' ? 'block' : 'none' ) ;
    }

    function checkout(username, projectid)
    {
        $('username').value = username;
        $('projectid').value = projectid;
        submitform();
        // alert( 'checkout ' + $('username') + $('projectid') );
    }\n" ;
}

// vim: sw=4 ts=4 expandtab
