<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');

require_login();

output_header("Random Rule Database Validation");

$query = "SELECT * FROM rules ORDER BY id";
$result = mysqli_query(DPDatabase::get_connection(), $query);
$num_rules = mysqli_num_rows($result);

echo "<p>There are $num_rules Random Rules in the database...</p>";

$rule_number = 1;
while ($rule = mysqli_fetch_assoc($result))
{
    echo "<hr>\n";
    echo "<div><b>ID:</b> $rule_number &mdash; $rule[subject] (anchored as \"#$rule[anchor]\" in $rule[document])</div>\n";
    echo "<div>$rule[rule]</div>\n";
    $rule_number++;
}

// vim: sw=4 ts=4 expandtab
