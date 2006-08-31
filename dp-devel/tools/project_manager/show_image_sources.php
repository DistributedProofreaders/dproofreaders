<?PHP
// Display lists of image sources, or lists of projects that used image sources
// List contents vary with user permissions

$relPath='../../pinc/';
include_once($relPath.'dpsession.inc');
include_once($relPath.'maintenance_mode.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'dpsql.inc');

// the user_is functions don't work unless this has been executed previously!
// it's in dp_main.inc, but we also want this page to be accessible to 
// people who aren't logged in, so we can't include that file,
// which requries a visitor to log in
dpsession_resume();

//Check to see if we are in a maintenance mode
abort_if_in_maintenance_mode();

$locuserSettings = Settings::get_Settings($pguser);

$no_stats = 1;

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
    theme($header_text, "header");
    echo "<h1>{$header_text}</h1>\n";


    if (user_is_PM()) {
        echo "<a href='projectmgr.php'>"._("Back to your PM page")."</a><br><br>";
    }

    if ($logged_in) {
        $query = "SELECT
                    display_name as '"._("Name in<br>Dropdown")."',
                    case when length(url) > 5 then concat('<a href=\"',url,'\">',full_name,'</a>') 
                        else full_name end as '"._("Full Name")."',
                    public_comment as '"._("Description")."',
                    case when count(distinct  projectid) = 0 then '<center>0</center>' 
                        else concat('<center><a href=\"show_image_sources.php?name=',code_name,'&which=ALL\" >',count(distinct projectid),'</a></center>') end as '"._("Works In Progress")."', 
                    case when isnull(sum(".SQL_CONDITION_GOLD.")) then '<center>0</center>' 
                         when sum(".SQL_CONDITION_GOLD.") = 0 then '<center>0</center>' 
                        else concat('<center><a href=\"show_image_sources.php?name=',code_name,'\" >',sum(".SQL_CONDITION_GOLD."),'</a></center>') end as '"._("Works Completed")."',
                    internal_comment as '"._("Notes")."'
                FROM image_sources i left join projects p on i.code_name = p.image_source
                WHERE info_page_visibility >= $min_vis_level
                GROUP BY 1,2,3,6
                ORDER BY display_name";
    } else {

        $query =  " SELECT
                       case when length(url) > 5 then concat('<a href=\"',url,'\">',full_name,'</a>') 
                          else full_name end as '"._("Image Source")."',
                       public_comment as '"._("Description")."',
                       case when count(distinct  projectid) = 0 then '<center>0</center>' 
                          else concat('<center><a href=\"show_image_sources.php?name=',code_name,'&which=ALL\" >',count(distinct projectid),'</a></center>') end as '"._("Works In Progress")."', 
                       case when isnull(sum(".SQL_CONDITION_GOLD.")) then '<center>0</center>' 
                          when sum(".SQL_CONDITION_GOLD.") = 0 then '<center>0</center>' 
                          else concat('<center><a href=\"show_image_sources.php?name=',code_name,'\" >',sum(".SQL_CONDITION_GOLD."),'</a></center>') end as '"._("Works Completed")."'
                    FROM image_sources i left join projects p on i.code_name = p.image_source
                    WHERE info_page_visibility >= $min_vis_level
                    GROUP BY 1,2
                    ORDER BY full_name";
   }

    dpsql_dump_query( $query);

} else {

    $imso_code = $_GET['name'];

    $imso = mysql_fetch_assoc( mysql_query("
        SELECT
                full_name,
                display_name,
                public_comment,
                internal_comment,
                info_page_visibility,
                concat('<a href=\"',url,'\">',url,'</a>') as 'more_info'
        FROM image_sources
        WHERE code_name = '$imso_code'
       "));

    $visibility = $imso['info_page_visibility'];


    // info page visibility
    //  0 = Image Source Managers and SAs
    //  1 = also any PM
    //  2 = also any logged-in user
    //  3 = anyone

	// layout below intended to make logic easier to follow:
	// see it as a tree lying on it's side, the root node the "or"
	// on the third line

    $can_see = 	(
				($visibility == 3) 
			or 
				($logged_in 
					and 	
							(	$visibility == 2 
							   or 
								(user_is_PM() and $visibility == 1 ) 
							   or 
								user_is_image_sources_manager()
							)
				)
			);
                                         
    if ($can_see) {

        if (isset($_GET['which']))
        {
            $which = $_GET['which'];

            switch ($which)
            {
               case 'ALL':
                   $adjective = _("All");
                   $where_cls = " ";
                   $other_label = _("Completed");
                   $other_which = 'DONE';
                   $tense = _("being produced");
                   $other_tense = _("produced");
                   break;
               case 'DONE':
               default:
                   $adjective = _("Completed");
                   $where_cls = " AND  ".SQL_CONDITION_GOLD." ";
                   $other_label = _("All");
                   $other_which = 'ALL';
                   $tense = _("produced");
                   $other_tense = _("being produced");
            }
                  
        } else {
            $adjective = _("Completed");
            $where_cls = " AND  ".SQL_CONDITION_GOLD." ";
            $other_label = _("All");
            $other_which = 'ALL';
            $tense = _("produced");
        }

        $title = $adjective." "._("Ebooks")." ".$tense." "._("from scans from")." ".$imso['full_name'];
        $sub_title  = $imso['public_comment'];
        $details    = $imso['internal_comment'];
        $more_info  = $imso['more_info'];

        theme($title, "header");

        echo "<br><h2>$title</h2>\n";

        echo "<br><h3>$sub_title</h3>\n";

        if ($logged_in) {
		echo "<br><h4>$details</h4>\n";
	 }
       echo "\n<h4>$more_info</h4>\n\n";



        echo  "<a href='show_image_sources.php?name=".$imso_code.
              "&which=".$other_which."'>"._("See list of")." ".$other_label." ".
              _("Ebooks")." ".$other_tense." "._("from scans from")." ".$imso['full_name']."</a><br><br>";

        echo  "<a href='show_image_sources.php'>"._("Back to the full listing of all Image Sources")."</a><br><br>";

        dpsql_dump_query("
            SELECT
               concat('<a href=\"$code_url/project.php?id=',projectid,'\">',nameofwork,'</a>') as "._('Title').",
               authorsname as "._('Author').",
               genre as "._('Genre').",
               language as "._('Language').",
               IF(postednum, concat('<a href=\"http://www.gutenberg.net/etext/',postednum,'\">',postednum,'</a>'),'In Progress') as '"._('PG Number<br>and Link')."'
            FROM projects
            WHERE image_source = '".$imso_code."' ".$where_cls."
            ORDER BY nameofwork
        ");

    } else {

        $title = _("Unknown Image Source Code");
        theme($title, "header");

        echo "<br><h2>$title</h2>\n";

        echo  "<a href='show_image_sources.php'>"._("Back to the full listing of Image Sources")."</a><br><br>";

    }
}

echo "<br>\n";

theme("","footer");
?>