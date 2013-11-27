<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

$page_id = get_enumerated_param($_REQUEST, 'type', NULL, $valid_page_ids);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo "$charset";?>">
<title></title></head>
<body bgcolor='#ffffff'>
<img src="./images/qi_<?php echo $page_id; ?>.png" alt="">
</body>
</html>
