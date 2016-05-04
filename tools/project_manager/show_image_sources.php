<?php
// Display lists of image sources, or lists of projects that used image sources
// List contents vary with user permissions

$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'pg.inc');

require_login();

$which = get_enumerated_param($_GET, 'which', 'DONE', array('ALL', 'DONE'));

$locuserSettings =& Settings::get_Settings($pguser);

// ---------------------------------------
// Page construction varies with whether the user is logged in or out
if (isset($GLOBALS['pguser'])) { $logged_in = TRUE;} else { $logged_in = FALSE;}

if ($logged_in)
{
    if  ( user_is_image_sources_manager() ) {
        $min_vis_level = 0;
    } else if (user_is_PM()) {
        $min_vis_level = 1;
    } else {
        $min_vis_level = 2;
    }
}
else
{
    $min_vis_level = 3;
}


if (!isset($_GET['name']))
{

    $header_text = _("Image Sources");
    output_header($header_text, NO_STATSBAR);
    echo "<h1>{$header_text}</h1>\n";


    if (user_is_PM()) {
        echo "<a href='projectmgr.php'>"._("Back to your PM page")."</a><br><br>";
    }

    if ($logged_in) {
        $query = "
            SELECT
                display_name as '".mysql_real_escape_string(_("Name in<br>Dropdown"))."',
                case when length(url) > 5 then concat('<a href=\"',url,'\">',full_name,'</a>')
                    else full_name end as '".mysql_real_escape_string(_("Full Name"))."',
                public_comment as '".mysql_real_escape_string(_("Description"))."',
                CASE ok_show_images
                    WHEN  1 THEN '<center>".mysql_real_escape_string(_('Yes'))    ."</center>'
                    WHEN  0 THEN '<center>".mysql_real_escape_string(_('No'))     ."</center>'
                    WHEN -1 THEN '<center>".mysql_real_escape_string(_('Unknown'))."</center>'
                END AS '".mysql_real_escape_string(_('OK to publish images?'))."',
                case when count(distinct  projectid) = 0 then '<center>0</center>'
                    else concat('<center><a href=\"show_image_sources.php?name=',code_name,'&which=ALL\" >',count(distinct projectid),'</a></center>') end as '".mysql_real_escape_string(_("Works In Progress"))."',
                case when isnull(sum(".SQL_CONDITION_GOLD.")) then '<center>0</center>'
                     when sum(".SQL_CONDITION_GOLD.") = 0 then '<center>0</center>'
                    else concat('<center><a href=\"show_image_sources.php?name=',code_name,'\" >',sum(".SQL_CONDITION_GOLD."),'</a></center>') end as '".mysql_real_escape_string(_("Works Completed"))."',
                internal_comment as '".mysql_real_escape_string(_("Notes"))."'
            FROM image_sources i left join projects p on i.code_name = p.image_source
            WHERE info_page_visibility >= $min_vis_level
            GROUP BY code_name
            ORDER BY display_name";
    } else {

        $query = "
            SELECT
                case when length(url) > 5 then concat('<a href=\"',url,'\">',full_name,'</a>')
                    else full_name end as '".mysql_real_escape_string(_("Image Source")). "',
                public_comment as '".mysql_real_escape_string(_("Description"))."',
                CASE ok_show_images
                    WHEN  1 THEN '<center>".mysql_real_escape_string(_('Yes'))    ."</center>'
                    WHEN  0 THEN '<center>".mysql_real_escape_string(_('No'))     ."</center>'
                    WHEN -1 THEN '<center>".mysql_real_escape_string(_('Unknown'))."</center>'
                END AS '".mysql_real_escape_string(_('OK to publish images?'))."',
                case when count(distinct  projectid) = 0 then '<center>0</center>'
                    else concat('<center><a href=\"show_image_sources.php?name=',code_name,'&which=ALL\" >',count(distinct projectid),'</a></center>') end as '".mysql_real_escape_string(_("Works In Progress"))."',
                case when isnull(sum(".SQL_CONDITION_GOLD.")) then '<center>0</center>'
                    when sum(".SQL_CONDITION_GOLD.") = 0 then '<center>0</center>'
                    else concat('<center><a href=\"show_image_sources.php?name=',code_name,'\" >',sum(".SQL_CONDITION_GOLD."),'</a></center>') end as '".mysql_real_escape_string(_("Works Completed"))."'
            FROM image_sources i left join projects p on i.code_name = p.image_source
            WHERE info_page_visibility >= $min_vis_level
            GROUP BY code_name
            ORDER BY full_name";
    }

    dpsql_dump_query( $query);

} else {

    $imso_code = $_GET['name'];

    $imso = mysql_fetch_assoc( mysql_query( sprintf("
        SELECT
            full_name,
            display_name,
            public_comment,
            internal_comment,
            info_page_visibility,
            concat('<a href=\"',url,'\">',url,'</a>') as 'more_info'
        FROM image_sources
        WHERE code_name = '%s'
    ", mysql_real_escape_string($imso_code))));

    $visibility = $imso['info_page_visibility'];


    // info page visibility
    //  0 = Image Source Managers and SAs
    //  1 = also any PM
    //  2 = also any logged-in user
    //  3 = anyone

    // layout below intended to make logic easier to follow:
    // see it as a tree lying on it's side, the root node the "or"
    // on the third line

    $can_see = (
                ($visibility == 3)
            or
                ($logged_in
                    and
                    (
                        $visibility == 2
                        or
                        (user_is_PM() and $visibility == 1)
                        or
                        user_is_image_sources_manager()
                    )
                )
            );

    if ($can_see) {

        switch ($which)
        {
            case 'ALL':
                $where_cls = " ";
                $other_which = 'DONE';
                $title = sprintf( _("All Ebooks being produced from scans from %s"), $imso['full_name'] );
                $link_text = sprintf( _("See list of Completed Ebooks produced from scans from %s"), $imso['full_name'] ); 
                break;
            case 'DONE':
            default:
                $where_cls = " AND  ".SQL_CONDITION_GOLD." ";
                $other_which = 'ALL';
                $title = sprintf( _("Completed Ebooks produced from scans from %s"), $imso['full_name'] );
                $link_text = sprintf( _("See list of All Ebooks being produced from scans from %s"), $imso['full_name'] ); 
        }

        $sub_title  = $imso['public_comment'];
        $details    = $imso['internal_comment'];
        $more_info  = $imso['more_info'];

        output_header($title, NO_STATSBAR);

        echo "<br><h2>$title</h2>\n";

        echo "<br><h3>$sub_title</h3>\n";

        if ($logged_in) {
            echo "<br><h4>$details</h4>\n";
        }
        echo "\n<h4>$more_info</h4>\n\n";



        echo  "<a href='show_image_sources.php?name=".$imso_code.
            "&which=".$other_which."'>" . $link_text . "</a><br><br>";

        echo  "<a href='show_image_sources.php'>"._("Back to the full listing of all Image Sources")."</a><br><br>";

        dpsql_dump_query("
            SELECT
                concat('<a href=\"$code_url/project.php?id=',projectid,'\">',nameofwork,'</a>') as '".mysql_real_escape_string(_('Title'))."',
                authorsname as '".mysql_real_escape_string(_('Author'))."',
                genre as '".mysql_real_escape_string(_('Genre'))."',
                language as '".mysql_real_escape_string(_('Language'))."',
                IF(postednum, concat('<a href=\"{$PG_home_url}ebooks/',postednum,'\">',postednum,'</a>'),'".mysql_real_escape_string(_('In Progress'))."') as '".mysql_real_escape_string(_('PG Number<br>and Link'))."'
            FROM projects
            WHERE image_source = '".mysql_real_escape_string($imso_code)."' ".$where_cls."
            ORDER BY nameofwork
        ");

    } else {

        $title = _("Unknown Image Source Code");
        output_header($title, NO_STATSBAR);

        echo "<br><h2>$title</h2>\n";

        echo  "<a href='show_image_sources.php'>"._("Back to the full listing of Image Sources")."</a><br><br>";

    }
}

echo "<br>\n";

// vim: sw=4 ts=4 expandtab
