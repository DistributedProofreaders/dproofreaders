<?PHP
$relPath='../../pinc/';
include($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'dpsession.inc');
include_once($relPath.'slim_header.inc');

$user_is_logged_in = dpsession_resume();

// get variables passed into page
$project   = $_GET['project'];
$imagefile = $_GET['imagefile'];
$percent   = array_get($_GET,'percent',$_SESSION["displayimage"]["percent"]);
$showreturnlink = array_get($_GET,'showreturnlink',1);
$preload   = array_get($_GET, "preload", "");

if(empty($percent))
    $percent=100;

$width = 10 * $percent;

$_SESSION["displayimage"]["percent"]=$percent;

$title = sprintf(_("Display Image: %s"),$imagefile);
slim_header($title, TRUE, FALSE);

// Get a list of images in the project so we can populate the prev and
// next <link rel=... href=...> tags in <head> if needed.
// NB The query results are used later to populate a popup menu too.
new dbConnect();
$res = mysql_query( "SELECT image FROM $project ORDER BY image ASC") or die(mysql_error());
$num_rows = mysql_num_rows($res);
$prev_image = "";
$next_image = "";
for ($row=0; $row<$num_rows;$row++)
{
    $this_val = mysql_result($res, $row, "image");
    if ($this_val == $imagefile) {
        if ( $row != 0 ) $prev_image = mysql_result($res, $row-1, "image");
        if ( $row != $num_rows-1 ) $next_image = mysql_result($res, $row+1, "image");
    }
}
if ($prev_image != "" && $preload == "prev")
    echo "    <link rel=\"prefetch prev\" href=\"$projects_url/$project/$prev_image\">\n";
if ($next_image != "" && $preload == "next")
    echo "    <link rel=\"prefetch next\" href=\"$projects_url/$project/$next_image\">\n";
echo "</head>\n\n<body onLoad=\"self.focus()\">\n";
?>

<form method="get" action="displayimage.php">
<input type="hidden" name="project" value="<?echo $project;?>">
<input type="hidden" name="imagefile" value="<?echo $imagefile;?>">
<input type="hidden" name="showreturnlink" value="<?echo $showreturnlink;?>">
<input type="hidden" name="preload" value="<?echo $preload;?>">

Resize:
<input type="text" maxlength="3" name="percent" size="3" value="<?echo $percent;?>">%
<input type="submit" value="Resize" size="3">

Jump to:
<select name="jumpto" onChange="this.form.imagefile.value=this.form.jumpto[this.form.jumpto.selectedIndex].value; this.form.submit();">
<?
// Populate the options in the popup menu based on the database query earlier
for ($row=0; $row<$num_rows;$row++)
{
    $this_val = mysql_result($res, $row, "image");
    echo "<option value=\"$this_val\"";
    if ($this_val == $imagefile) echo " selected";
    echo ">".$this_val."</option>\n";
}
?>
</select>
<?
function prevnext_buttons()
{
    global  $prev_image, $next_image;
    echo "<input type='button' value='" . _("Previous") . "' onClick=\"this.form.imagefile.value='$prev_image'; this.form.preload.value='prev'; this.form.submit();\"";
    if ( $prev_image == "" ) echo " disabled";
    echo ">\n";
    echo "<input type='button' value='" . _("Next") . "' onClick=\"this.form.imagefile.value='$next_image'; this.form.preload.value='next'; this.form.submit();\"";
    if ( $next_image == "" ) echo " disabled";
    echo ">\n";
}

prevnext_buttons();
if($showreturnlink) {
    $myresult = mysql_query("SELECT nameofwork FROM projects WHERE projectid = '$project'");
    $row = mysql_fetch_assoc($myresult);
    $title = $row['nameofwork'];

    $label = sprintf(_("Return to Project Page for %s"),$title);

    echo "<br>\n";
    echo "<a href='$code_url/project.php?id=$project'>$label</a>";
    echo "<br>\n";
}

echo "<img src='$projects_url/$project/$imagefile' width='$width' border='1'>";
?>
<center>
<?
prevnext_buttons();
// vim: sw=4 ts=4 expandtab
?>
</center>
</form>
</body></html>
