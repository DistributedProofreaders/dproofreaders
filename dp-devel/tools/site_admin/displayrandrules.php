<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');

theme("Random Rule Database Validation", "header");

    $query = "SELECT count(*) AS numrules FROM rules";
    $result = mysql_query($query);
    $num_rules = mysql_result($result,0,"numrules");

    echo "<p>There are $num_rules Random Rules in the database...</p>";

for ($i=1;$i<=$num_rules;$i++)
    {
    $query = "SELECT document,anchor,subject,rule FROM rules WHERE id = '$i'";
    $result = mysql_query($query);
    $rule = mysql_fetch_assoc($result);
    echo "<hr>";
    echo "<p><b>ID:</b> $i &mdash; $rule[subject] (anchored as \"#$rule[anchor]\" in $rule[document])</p>";
    echo "<p>$rule[rule]</p>";
    }

theme("","footer");
?>
