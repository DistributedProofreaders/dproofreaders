<?PHP
// The following variables must be defined by the file that includes this one:
// $relPath
// $ppage
// $userP

include_once($relPath.'http_headers.inc');
include_once($relPath.'slim_header.inc');

if ($userP['i_layout']=='1')
{
    $rows_or_cols = "COLS='*,{$userP['v_tframe']}%'";
}
else
{
    $rows_or_cols = "ROWS='*,{$userP['h_tframe']}%'";
}

$params = $ppage->urlencoded();

if ( ($userP['i_layout']=='1' && $userP['v_tscroll']==0)
    ||
     ($userP['i_layout']=='0' && $userP['h_tscroll']==0) )
{
    $text_scrolling = "SCROLLING='no'";
}
else
{
    $text_scrolling = "";
}

$margins = "marginwidth='2' marginheight='2'";

// -------------------------------------

echo "
    <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Frameset//EN'
    'http://www.w3.org/TR/html4/frameset.dtd'>
";
slim_header($ppage->projectid(),FALSE,FALSE);
echo "
    </HEAD>
    <FRAMESET $rows_or_cols>
    <FRAME NAME='imageframe' SRC='image_frame_std.php?$params' $margins>
    <FRAME NAME='textframe' SRC='text_frame_std.php?$params' $margins $text_scrolling>
    </FRAMESET>
    <NOFRAMES>Your browser is not frames capable!</NOFRAMES>
    </HTML>
";

// vim: sw=4 ts=4 expandtab
?>
