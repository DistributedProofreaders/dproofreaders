<?
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');

if ($userP['i_type'] != 1)
  {include('textframe_std.inc');}
else
  {include('textframe_enh.inc');}
?>
