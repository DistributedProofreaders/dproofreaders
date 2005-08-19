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

    if (user_is_PM()) {
        echo "<a href='projectmgr.php'>"._("Back to your PM page")."</a><br><br>";
    }


    dpsql_dump_query("
        SELECT
                concat('<a href=\"show_image_sources.php?name=',code_name,'\" >',full_name,'</a>') as 'Full Name',
                display_name as 'Name in<br>Dropdown',
                public_comment as 'Description',
                internal_comment as 'Notes',
                CASE ok_show_images WHEN 1 THEN 'yes' WHEN 0 THEN 'no' ELSE 'unknown' END as 'OK to<br>Publish?',
                concat('<a href=\"',url,'\">',url,'</a>') as 'More Info'
        FROM image_sources
        WHERE enable = 1
        ORDER BY full_name

    ");

} else {

    $imso_code = $_GET['name'];

    $imso = mysql_fetch_assoc( mysql_query("
        SELECT
                full_name,
                display_name,
                public_comment,
                concat('<a href=\"',url,'\">',url,'</a>') as 'more_info'
        FROM image_sources
        WHERE code_name = '$imso_code'
       "));


    $title = _("Completed Ebooks produced by scans provided by ")." ".$imso['full_name'];
    $sub_title = $imso['public_comment'];
    $more_info = $imso['more_info'];

    theme($title, "header");

    echo "<br><h2>$title</h2>\n";

    echo "<br><h3>$sub_title</h3><h4>$more_info</h4>\n\n";

    echo  "<a href='show_image_sources.php'>"._("Back to the full listing of Image Providers")."</a><br><br>";

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

}

echo "<br>\n";

theme("","footer");
?>
