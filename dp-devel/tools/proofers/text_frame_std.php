<?
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');

//Find out how many pages have been proofread already
    $pagessql = "SELECT pagescompleted FROM users WHERE username = '$pguser' LIMIT 1";
    $pages = mysql_query($pagessql);
    $totalpages = mysql_result($pages, 0, "pagescompleted");

if ($userP['i_type'] != 1)
  {include('textframe_njs.inc');}
else
  {include('textframe_js.inc');}
?>