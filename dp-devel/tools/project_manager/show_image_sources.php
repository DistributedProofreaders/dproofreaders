<?PHP
// Display lists of image sources, or lists of projects that used image sources
// List contents vary with user permissions

$relPath='../../pinc/';
include_once($relPath.'dpsession.inc');
include_once($relPath.'maintenance_mode.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'f_dpsql.inc');

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
    if  ( user_is_a_sitemanager() || user_is_image_sources_manager() ) {
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
                    concat('<a href=\"show_image_sources.php?name=',code_name,'\" >',full_name,'</a>') as 'Full Name',
                    display_name as 'Name in<br>Dropdown',
                    public_comment as 'Description',
                    internal_comment as 'Notes', CASE ok_show_images WHEN 1 THEN 'yes' WHEN 0 THEN 'no' ELSE 'unknown' END as 'OK to<br>Publish?',
                    concat('<a href=\"',url,'\">',url,'</a>') as 'More Info'
                FROM image_sources
                WHERE info_page_visibility >= $min_vis_level
                ORDER BY full_name";
    } else {

        $query =  " SELECT
                       concat('<a href=\"show_image_sources.php?name=',code_name,'\" >',full_name,'</a>') as 'Full Name',
                       public_comment as 'Description',
                       concat('<a href=\"',url,'\">',url,'</a>') as 'More Info'
                    FROM image_sources
                    WHERE info_page_visibility >= $min_vis_level
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
                info_page_visibility,
                concat('<a href=\"',url,'\">',url,'</a>') as 'more_info'
        FROM image_sources
        WHERE code_name = '$imso_code'
       "));

    $visibility = $imso['info_page_visibility'];

    if ($visibility = 3) {

        $title = _("Completed Ebooks produced by scans provided by ")." ".$imso['full_name'];
        $sub_title  = $imso['public_comment'];
        $more_info  = $imso['more_info'];

        theme($title, "header");

        echo "<br><h2>$title</h2>\n";

        echo "<br><h3>$sub_title</h3><h4>$more_info</h4>\n\n";

        echo  "<a href='show_image_sources.php'>"._("Back to the full listing of Image Sources")."</a><br><br>";

        dpsql_dump_query("
            SELECT
               nameofwork as "._('Title').",
               authorsname as "._('Author').",
               year as "._('Year').",
               language as "._('Language').",
               concat('<a href=\"http://www.gutenberg.net/etext/',postednum,'\">',postednum,'</a>') as '"._('PG Number<br>and Link')."'
            FROM projects
            WHERE image_source = '".$imso_code."'
               AND  ".SQL_CONDITION_GOLD."
            ORDER BY 1
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