<?PHP
function metarefresh($seconds,$url,$title,$body)
{
$relPath=$GLOBALS['relPath'];
include('doctype.inc');
include('v_site.inc');
include('gettext_setup.inc');

if ($testing)
{
    $sec = $seconds + 15;
    // That may not be long enough to read everything,
    // but it should be long enough to Select All + Copy.
}
else
{
    $sec = $seconds;
}

echo $docType."\n";
echo "<html".lang_html_header()."><head><title>$title</title>\n";
echo "<meta http-equiv=\"refresh\" content=\"$sec ;URL=$url\">\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\" />\n";
echo "</head><body>\n";
echo $body, "\n";

if ($testing)
{
	echo "\n<hr>\n<i>";
	echo _("Normally, you would be directed to the next page in $seconds seconds.
However, as we are in testing mode, this has been increased to $sec seconds.
If you don't want to wait that long,
or if you want this page inserted into your browser history,
<a href='$url'>click here</a>.");
	echo "</i>";
}

echo "</body></html>";
}
?>
