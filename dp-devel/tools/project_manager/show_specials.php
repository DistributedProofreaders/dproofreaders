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
?>

<b>Birthdays</b><br>
<br>
To set the book aside to release on the author's birthday, start the project comments with<br>
<br>
SPECIAL: Birthday mmdd<br>
<br>
where mmdd is the month and date, so 1109 is November 9th, 0120 January 20th, etc.<br>
<br>
<b>"Otherdays"</b><br>
<br>
An "Otherday" is some date of significance to the book or author apart from the author's birthday. Examples might be: date is birthday of subject of a biography, or date is anniversary of author's death, or a million other possibililties. To set the book aside to release on a given "Otherday", start the project comments with<br>
<br>
SPECIAL: Otherday mmdd<br>
<br>
where mmdd is the month and date, so 1109 is November 9th, 0120 January 20th, etc.<br>
<br>
All other Special Days are set using the drop down list on the Project Creation page.<br>
<br>
<?
theme("","footer");
?>
