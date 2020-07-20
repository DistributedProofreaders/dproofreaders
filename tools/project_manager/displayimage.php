<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'Project.inc');

require_login();

$default_percent = array_get( @$_SESSION["displayimage"], 'percent', 100 );

// get variables passed into page
$projectid      = get_projectID_param($_GET, 'project');
$imagefile      = get_page_image_param($_GET, 'imagefile', true);
$percent        = get_integer_param($_GET, 'percent', $default_percent, 1, 999);
$showreturnlink = get_integer_param($_GET, 'showreturnlink', 1, 0, 1);
$preload        = get_enumerated_param($_GET, 'preload', '', array('', 'prev', 'next'));

$width = 10 * $percent;

$_SESSION["displayimage"]["percent"]=$percent;

$project = new Project($projectid);


// Get a list of images in the project so we can populate the prev and
// next <link rel=... href=...> tags in <head> if needed.
// NB The query results are used later to populate a popup menu too.
$images = $project->get_page_names_from_db();
$prev_image = "";
$next_image = "";
$num_rows = count($images);
for ($row=0; $row<$num_rows; $row++)
{
    $this_val = $images[$row];
    if ($this_val == $imagefile) {
        if ( $row != 0 ) $prev_image = $images[$row-1];
        if ( $row != $num_rows-1 ) $next_image = $images[$row+1];
    }
}
$link_tags = "";
if ($prev_image != "" && $preload == "prev")
    $link_tags .= "<link rel=\"prefetch prev\" href=\"$project->url/$prev_image\">\n";
if ($next_image != "" && $preload == "next")
    $link_tags .= "<link rel=\"prefetch next\" href=\"$project->url/$next_image\">\n";

$title = sprintf(_("Display Image: %s"),$imagefile);
slim_header($title, array("head_data" => $link_tags));
?>

<form method="get" action="displayimage.php">
<input type="hidden" name="project" value="<?php echo $projectid; ?>">
<input type="hidden" name="imagefile" value="<?php echo $imagefile; ?>">
<input type="hidden" name="showreturnlink" value="<?php echo $showreturnlink; ?>">
<input type="hidden" name="preload" value="<?php echo $preload; ?>">

<?php echo _("Resize"); ?>:
<input type="number" name="percent" min="1" max="999" value="<?php echo $percent; ?>">%
<input type="submit" value="<?php echo attr_safe(_("Resize")); ?>" size="3">

<?php echo _("Jump to"); ?>:
<select name="jumpto" onChange="this.form.imagefile.value=this.form.jumpto[this.form.jumpto.selectedIndex].value; this.form.submit();">
<?php
// Populate the options in the popup menu based on the database query earlier
for ($row=0; $row<$num_rows; $row++)
{
    $this_val = $images[$row];
    echo "<option value=\"$this_val\"";
    if ($this_val == $imagefile) echo " selected";
    echo ">".$this_val."</option>\n";
}
?>
</select>
<?php
function prevnext_buttons()
{
    global  $prev_image, $next_image;
    echo "<input type='button' value='" . attr_safe(_("Previous")) . "' onClick=\"this.form.imagefile.value='$prev_image'; this.form.preload.value='prev'; this.form.submit();\"";
    if ( $prev_image == "" ) echo " disabled";
    echo ">\n";
    echo "<input type='button' value='" . attr_safe(_("Next")) . "' onClick=\"this.form.imagefile.value='$next_image'; this.form.preload.value='next'; this.form.submit();\"";
    if ( $next_image == "" ) echo " disabled";
    echo ">\n";
}

prevnext_buttons();
if($showreturnlink) {
    $label = sprintf(_("Return to Project Page for %s"), html_safe($project->nameofwork));

    echo "<br>\n";
    echo "<a href='$code_url/project.php?id=$projectid'>$label</a>";
}
echo "<br>\n";
echo "<img src='$projects_url/$projectid/$imagefile' style='width: ${width}px; border: solid thin black;'>";

echo "<p class='center-align'>";
prevnext_buttons();
echo "</p>";
echo "</form>";
// vim: sw=4 ts=4 expandtab
