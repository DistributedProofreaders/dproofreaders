<?
$relPath='../../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');
$no_stats = 1;

$title = _("Details of Special Days/Weeks/Months");
theme($title, "header");

echo "<br><h2>$title</h2>\n";


dpsql_dump_query("
        SELECT
                concat('<span style=\"background-color: #',color,'\">',display_name,'</span>') as 'Name',
                comment as 'Comment',
                concat(' ',DATE_FORMAT(concat('2000-',open_month,'-',open_day),'%e %b')) as 'Start Date',
                concat('<a href=\"',info_url,'\">',info_url,'</a>') as 'More Info'
        FROM special_days
        WHERE enable = 1
        ORDER BY open_month, open_day

");


echo "<br>\n";

theme("","footer");
?>
