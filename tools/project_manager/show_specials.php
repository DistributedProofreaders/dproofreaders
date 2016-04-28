<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Details of Special Days/Weeks/Months");
output_header($title, NO_STATSBAR);

echo "<br><h2>$title</h2>\n";
echo _("The Name column shows what the colour looks like with a link on top, the Comment with ordinary text")."<br><br>";

if (user_is_PM()) {
    echo "<a href='projectmgr.php'>"._("Back to your PM page")."</a><br><br>";
}


dpsql_dump_query("
        SELECT
                      concat('<span style=\"background-color: #',
                      color,
                      '\"><a href=\"projectmgr.php?show=search&special_day[]=',spec_code,'&n_results_per_page=100\" title=\"',display_name,'\">',
                display_name,
                      '</a></span>') as '" . mysql_real_escape_string(_("Name")) . "',
                      concat('<span style=\"background-color: #',
                      color,
                      '\" title=\"',comment,'\">',
                comment,
                      '</a></span>') as '" . mysql_real_escape_string(_("Comment")) . "',
                concat(' ',DATE_FORMAT(concat('2000-',open_month,'-',open_day),'%b %e')) as '" . mysql_real_escape_string(_("Start Date")) . "',
                concat('<a href=\"',info_url,'\">',info_url,'</a>') as '" . mysql_real_escape_string(_("More Info")) . "'
        FROM special_days
        WHERE enable = 1
        ORDER BY open_month, open_day

");


echo "<br>\n";
?>
