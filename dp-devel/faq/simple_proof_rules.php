<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'theme.inc');
$no_stats=1;
theme("Beginners' Simple Proofreading Rules",'header');
echo "<br><br>";
include($relPath.'simple_proof_text.inc');
echo "<br><br>";
theme('','footer');
?>
