<?

/* dpRandomRule - Select and format a random rule from the database
 *
 * Note:  Must already be connected to database (e.g., already include
 *        connect.php)
 */

function dpRandomRule () {
    $randid  = rand(0,36); // Remember to change high number after adding new rules!

    $query = "SELECT subject,rule,doc FROM rules WHERE id=$randid";
    $result = mysql_query($query);
    $rule = mysql_fetch_assoc($result);

    return <<<EOT
<p><strong>$rule[subject]</strong></p>

<p>$rule[rule]</p>

<p>See the <a href="http://texts01.archive.org/dp/faq/document.html#$rule[doc]">$rule[subject]</a> section of the <a href="http://texts01.archive.org/dp/faq/document.html">Document Guidelines</a></p>
EOT;
}
?>
