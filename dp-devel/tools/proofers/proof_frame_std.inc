<?PHP
// The following variables must be defined by the file that includes this one:
// $relPath
// $project
// $userP

include_once($relPath.'http_headers.inc');
include_once($relPath.'slim_header.inc');

echo "
    <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Frameset//EN'
    'http://www.w3.org/TR/html4/frameset.dtd'>
";
slim_header($project,FALSE,FALSE);
echo "
    <HTML>
    <HEAD>
    <TITLE>$project</TITLE>
    </HEAD>
";

echo "<FRAMESET ";
if ($userP['i_layout']=='1')
    {echo "COLS='*,{$userP['v_tframe']}%'";}
else
    {echo "ROWS='*,{$userP['h_tframe']}%'";}
echo ">\n";

$margins = "marginwidth='2' marginheight='2'";
echo "
    <FRAME NAME='imageframe' SRC='image_frame.php' $margins>
    <FRAME NAME='textframe' SRC='text_frame.php' $margins
";
if ($userP['i_layout']=='1' && $userP['v_tscroll']==0)
    {echo "    SCROLLING='no'";}
if ($userP['i_layout']=='0' && $userP['h_tscroll']==0)
    {echo "    SCROLLING='no'";}
echo ">\n";

echo "
    </FRAMESET>
    <NOFRAMES>Your browser is not frames capable!</NOFRAMES>
    </HTML>
";

// vim: sw=4 ts=4 expandtab
?>
