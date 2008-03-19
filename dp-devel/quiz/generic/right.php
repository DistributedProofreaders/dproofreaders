<?
$relPath='../../pinc/';
include_once('../small_theme.inc');

$page_id = get_enumerated_param($_REQUEST, 'type', NULL, $valid_page_ids);

include "./data/qd_${page_id}.inc";
?>

<?php echo $welcome; ?></body>
</html>
