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

if(empty($percent))
    $percent=100;

$width = 10 * $percent;

$_SESSION["displayimage"]["percent"]=$percent;

$title = sprintf(_("Display Image: %s"),$imagefile);
slim_header($title);
?>

<form method="get" action="displayimage.php">
<input type="hidden" name="project" value="<?echo $project;?>">
<input type="hidden" name="imagefile" value="<?echo $imagefile;?>">
<input type="hidden" name="showreturnlink" value="<?echo $showreturnlink;?>">

Resize:
<input type="text" maxlength="3" name="percent" size="3" value="<?echo $percent;?>">%
<input type="submit" value="Resize" size="3">

Jump to:
<select name="jumpto" onChange="this.form.imagefile.value=this.form.jumpto[this.form.jumpto.selectedIndex].value; this.form.submit();">
<?
new dbConnect();
$res = mysql_query( "SELECT image FROM $project ORDER BY image ASC") or die(mysql_error());
$num_rows = mysql_num_rows($res);
$prev_image = "";
$next_image = "";
for ($row=0; $row<$num_rows;$row++)
{
    $this_val = mysql_result($res, $row, "image");
    echo "<option value=\"$this_val\"";
    if ($this_val == $imagefile)
        {
        echo " selected";
        if ( $row != 0 ) $prev_image = mysql_result($res, $row-1, "image");
        if ( $row != $num_rows-1 ) $next_image = mysql_result($res, $row+1, "image");
        }
    echo ">".$this_val."</option>\n";
}
?>
</select>
<?
echo "<input type='button' value='" . _("Previous") . "' onClick=\"this.form.imagefile.value='$prev_image'; this.form.submit();\"";
if ( $prev_image == "" ) echo " disabled";
echo ">\n";
echo "<input type='button' value='" . _("Next") . "' onClick=\"this.form.imagefile.value='$next_image'; this.form.submit();\"";
if ( $next_image == "" ) echo " disabled";
echo ">\n";
?>

</form>

<?
if($showreturnlink) {
    $myresult = mysql_query("SELECT nameofwork FROM projects WHERE projectid = '$project'");
    $row = mysql_fetch_assoc($myresult);
    $title = $row['nameofwork'];

    $label = sprintf(_("Return to Project Page for %s"),$title);

    echo "<a href='$code_url/project.php?id=$project'>$label</a>";
    echo "<br>\n";
}

echo "<img src='$projects_url/$project/$imagefile' width='$width' border='1'>";

// vim: sw=4 ts=4 expandtab
?>
</body></html>
