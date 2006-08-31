<?
$relPath='../pinc/';
include($relPath.'site_vars.php');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme("Beginners' Simple Proofreading Rules",'header');
echo "<br><br>";
include($relPath.'simple_proof_text.inc');
echo "<br><br>";
theme('','footer');
?>
