<?
$relPath='../../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
$no_stats = 1;


if (!isset($_GET['name']))
{

    $title = _("Further Information on Image Providers");
    theme($title, "header");

    echo "<br><h2>$title</h2>\n";

    dpsql_dump_query("
        SELECT
                concat('<a href=\"show_image_providers.php?name=',image_provider,'\" >',full_name,'</a>') as 'Full Name',
                display_name as 'Name in<br>Dropdown',
                public_comment as 'Description',
                internal_comment as 'Notes',
                CASE ok_show_images WHEN 1 THEN 'yes' WHEN 0 THEN 'no' ELSE 'unknown' END as 'OK to<br>Publish?',
                concat('<a href=\"',url,'\">',url,'</a>') as 'More Info'
        FROM image_providers
        WHERE enable = 1
        ORDER BY full_name

    ");

} else {

    $ip_to_show = $_GET['name'];

    $ip = mysql_fetch_assoc( mysql_query("
        SELECT
                full_name,
                display_name,
                public_comment,
                concat('<a href=\"',url,'\">',url,'</a>') as 'more_info'
        FROM image_providers
        WHERE image_provider = '$ip_to_show'
       "));


    $title = _("Completed Ebooks produced by scans provided by ")." ".$ip['full_name'];
    $sub_title = $ip['public_comment'];
    $more_info = $ip['more_info'];

    theme($title, "header");

    echo "<br><h2>$title</h2>\n";

    echo "<br><h3>$sub_title</h3><h4>$more_info</h4>\n\n";


    dpsql_dump_query("
        SELECT
           nameofwork as "._('Title').",
           authorsname as "._('Author').",
           year as "._('Year').",
	     language as "._('Language').",
           concat('<a href=\"http://www.gutenberg.net/etext/',postednum,'\">',postednum,'</a>') as '"._('PG Number<br>and Link')."'
        FROM projects
        WHERE image_provider = '".$ip_to_show."'
           AND  ".SQL_CONDITION_GOLD."
        ORDER BY 1
    ");

}

echo "<br>\n";

theme("","footer");
?>
