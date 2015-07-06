<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');

require_login();

output_header("Random Rule Database Validation");

$query = "SELECT count(*) AS numrules FROM rules";
$result = mysql_query($query);
$num_rules = mysql_result($result,0,"numrules");

echo "<p>There are $num_rules Random Rules in the database...</p>";

for ($i=1;$i<=$num_rules;$i++)
{
    $query = "SELECT document,anchor,subject,rule FROM rules WHERE id = '$i'";
    $result = mysql_query($query);
    $rule = mysql_fetch_assoc($result);
    echo "<hr>\n";
    echo "<div><b>ID:</b> $i &mdash; $rule[subject] (anchored as \"#$rule[anchor]\" in $rule[document])</div>\n";
    echo "<div>$rule[rule]</div>\n";
}

// vim: sw=4 ts=4 expandtab
