<?PHP
function metarefresh($sec,$url,$title,$body)
{
$relPath=$GLOBALS['relPath'];
include($relPath.'doctype.inc');
echo $docType."\r\n";
echo "<HTML><HEAD><TITLE>$title</TITLE>\r\n";
echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"$sec ;URL=$url\">\r\n";
echo "</HEAD><BODY>\r\n";
echo $body, "\r\n";
echo "</BODY></HTML>";
}
?>
