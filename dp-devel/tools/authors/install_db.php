<?php
  $relPath = '../../pinc/';
  include_once($relPath.'theme.inc');
  include_once($relPath.'user_is.inc');
?>
<html>
<head><title>Installing tables</title></head>
<body>
<?php

 if (!user_is_a_sitemanager()) {
  echo _('You are not permitted to execute this script.');
  exit;
}

$lines = file('schema.sql');

$query = '';

foreach ($lines as $line) {
  if (substr($line, 0, 1) == '#')
    continue;
  else if (rtrim($line) == '') {
    if ($query != '') {
      do_query($query);
      $query = '';
    }
  }
  else
    $query .= $line;
}
if ($query != '')
  do_query($query);

echo _('OK!');

function do_query($query) {
  $result = mysql_query($query);

  if (!$result) {
    echo _('Error!');
    exit;
  }
}
?>
</body>
</html>