<?
$relPath='../../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');
$no_stats = 1;

$title = _("Details of Special Days/Weeks/Months");
theme($title, "header");

echo "<br><h2>$title</h2>\n";
echo _("The Name column shows what the colour looks like with a link on top, the Comment with ordinary text")."<br><br>";

if (user_is_PM()) {
    echo "<a href='projectmgr.php'>"._("Back to your PM page")."</a><br><br>";
}


dpsql_dump_query("
        SELECT
                      concat('<span style=\"background-color: #',
                      color,
                      '\"><a href=\"show_specials.php?null=',now(),'\">',
                display_name,
                      '</a></span>') as 'Name',
                      concat('<span style=\"background-color: #',
                      color,
                      '\">',
                comment,
                      '</a></span>') as 'Comment',
                concat(' ',DATE_FORMAT(concat('2000-',open_month,'-',open_day),'%e %b')) as 'Start Date',
                concat('<a href=\"',info_url,'\">',info_url,'</a>') as 'More Info'
        FROM special_days
        WHERE enable = 1
        ORDER BY open_month, open_day

");


echo "<br>\n";
theme("","footer");
?>
