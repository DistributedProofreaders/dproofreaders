<?
include($relPath.'v_site.inc');

/* dpRandomRule - Select and format a random rule from the database
 *
 * Note:  Must already be connected to database (e.g., already include
 *        connect.php)
 */

function dpRandomRule () {
	global $code_url;

    $query = "SELECT count(*) AS numrules FROM rules";
    $result = mysql_query($query);
    $num_rules = mysql_result($result,0,"numrules");

    $randid  = rand(0,$num_rules); 

    $query = "SELECT subject,rule,doc FROM rules WHERE id=$randid";
    $result = mysql_query($query);
    $rule = mysql_fetch_assoc($result);

    return <<<EOT

<p><strong>$rule[subject]</strong></p>

<p>$rule[rule]</p>

<p>See the <a href="$code_url/faq/document.php#$rule[doc]">$rule[subject]</a> section of the <a href="$code_url/faq/document.php">Proofreading Guidelines</a></p>
EOT;
}
?>
