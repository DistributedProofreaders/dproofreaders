<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'misc.inc'); // attr_safe()
include_once($relPath.'metarefresh.inc');

require_login();

undo_all_magic_quotes();

$show_image_size = '';

$projectid = validate_projectID('projectid', @$_GET['projectid']);

if (!$site_supports_metadata)
{
	echo _("md_phase1.php: \$site_supports_metadata is false, so exiting.");
	exit();
}


if (isset($_POST['done']))
{
    $badmetadata = handle_page_params();
    if ($badmetadata == 1) {
        $result = mysql_query("UPDATE projects SET state = 'project_md_bad' WHERE projectid = '$projectid'");
        metarefresh(0,'md_available.php', _("Image Metadata Collection"),"");
    } else {
        $result = mysql_query("UPDATE projects SET state = 'project_md_second' WHERE projectid = '$projectid'");
        $result = mysql_query("UPDATE $projectid SET state = 'avail_md_second'");
        metarefresh(0,'md_available.php', _("Image Metadata Collection"),"");
    }
    exit;
}

if(isset($_POST['return']))
{
    //they don't want to save so clean it up and return them to md_available
    metarefresh(0,'md_available.php', _("Image Metadata Collection"),"");
    exit;
}


if(isset($_POST['continue']))
{
    handle_page_params();
    // ignore the return value
}

function handle_page_params()
// Handle the $_POST parameters that give info about individual pages.
{
    global $projectid;

    foreach ( $_POST['orig_page_num_'] as $image => $orig_page_num )
    {
        $result = mysql_query(sprintf("
            UPDATE $projectid
            SET orig_page_num = '%s'
            WHERE image = '%s'
        ", mysql_real_escape_string($orig_page_num),
            mysql_real_escape_string($image)));
    }

    $badmetadata = 0;
    foreach ( $_POST['metadata_'] as $image => $metadata )
    {
        $result = mysql_query(sprintf("
            UPDATE $projectid
            SET metadata = '%s'
            WHERE image = '%s'
        ", mysql_real_escape_string($metadata),
            mysql_real_escape_string($image)));
        if ($metadata == 'badscan' || $metadata == 'missing' || $metadata == 'sequence') {
            $badmetadata = 1;
        }
    }

    return $badmetadata;
}

// Finished dealing with input data.
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Now (if we're still here) echo the page.

$result = mysql_query("SELECT nameofwork, authorsname, language, username, state FROM projects WHERE projectid = '$projectid'");

$manager = mysql_result($result, 0, "username");
$state = mysql_result($result, 0, "state");
$name = mysql_result($result, 0, "nameofwork");
$author = mysql_result($result, 0, "authorsname");
$language = mysql_result($result, 0, "language");


$numpages = Project_getNumPages( $projectid );

output_header(_("Image Metadata Phase1"));

echo "<center><table border=1>";

echo "<tr><td bgcolor='" . $theme['color_headerbar_bg'] . "' colspan=6><b><font color='" . $theme['color_headerbar_font'] . "' size=+1>" . sprintf(_("Project Name: %s"),$name) . "</b></td></tr>";

echo "<tr><td bgcolor='" . $theme['color_navbar_bg'] . "'><b>" . _("Author") . ":</b></td><td>$author</td><td bgcolor='" . $theme['color_navbar_bg'] . "'><b>" . _("Total Number of Master Pages") . ":</b></td><td>$numpages</td>";
echo "<td bgcolor='" . $theme['color_navbar_bg'] . "'><b>" . _("Language") . ":</b></td><td>$language</td></tr><tr></tr>";
echo "</table>";

//---------------------------------------------------------------------------------------------------


//echo "<h3>Per-Page Info</h3>\n";


echo "<form method ='post'><table border=1>\n";


    // Top header row
    echo "<tr>\n";
    echo "    <td align='center' colspan='1'><b>" . _("Index") . "</b></td>\n";
    echo "    <td align='center' colspan='1'><b>" . _("Image Name") . "</b></td>\n";
    echo "    <td align='center' colspan='1'><b>" . _("Original Page #") . "</b></td>\n";
    echo "    <td align='center' colspan='1'><b>" . _("Page Metadata") . "</b></td>\n";
    echo "    <td align='center' colspan='1'><b>" . _("Thumbnail") . "</b></td>\n";
    echo "</tr>\n";

    // Image rows
    $path = "$projects_dir/$projectid/";

    $fields_to_get = 'image, state, metadata';

    $res = mysql_query( "SELECT image, state, metadata, orig_page_num FROM $projectid ORDER BY image ASC");
    $num_rows = mysql_num_rows($res);

    for ( $rownum=0; $rownum < $num_rows; $rownum++ )
    {
        $page_res = mysql_fetch_array( $res, MYSQL_ASSOC );

        $image = $page_res['image'];
        $metadata = $page_res['metadata'];
        $orig_page_num = $page_res['orig_page_num'];

        if ($rownum % 2 ) {
            $row_color = $theme['color_mainbody_bg'];
        } else {
            $row_color = $theme['color_navbar_bg'];
        }
        echo "<tr bgcolor='$row_color'>";

        // --------------------------------------------
        // Index
        $index = $rownum+1;
        echo "<td align='right'>$index</td>\n";

        if (file_exists($path.$image)) {
            $bgcolor = $row_color;
            if ($show_image_size) $imagesize = filesize(realpath($path.$image));
        } else {
            $bgcolor = "#FF0000";
            if ($show_image_size) $imagesize = 0;
        }
        echo "<td bgcolor='$bgcolor'><a href=../project_manager/displayimage.php?project=$projectid&imagefile=$image>$image</a></td>\n";

        // Original Page Number   
        echo "<td bgcolor='$bgcolor'><input type ='textbox' name='orig_page_num_[$image]' value = $orig_page_num></td>";


        // Set up existing page metadata if there is any, page defaults to nonblank
        $metadata_possibles = array(
            'illustration' => _("Illustration"),
            'blank'        => _("Blank"),
            'missing'      => _("Page Missing After This One"),
            'badscan'      => _("Bad Scan"),
            'sequence'     => _("Page Out of Sequence"),
            'nonblank'     => _("Non-Blank"),
        );

        if ( !array_key_exists($metadata, $metadata_possibles) )
        {
            // e.g., $metadata == ''
            $metadata = 'nonblank';
        }

        echo "<td bgcolor='$bgcolor' align='left'>\n";
        foreach ( $metadata_possibles as $code => $label )
        {
            $checked = ($code == $metadata ? 'checked' : '');
            echo "<input type='radio' name='metadata_[$image]' value='$code' $checked>$label<br>\n";
        }
        echo "</td>";

        // Show Thumbnail
        echo "<td bgcolor='$bgcolor' align='right'>
                <a href=\"../project_manager/displayimage.php?project=$projectid&imagefile=$image\"><img src =\"$projects_url/$projectid/thumbs/$image\" alt = \"$image\" border =\"0\"></a>
            </td>";

        echo "</tr>";
    }
    echo "</table>";

    echo "<INPUT TYPE=SUBMIT VALUE=\"" . attr_safe(_("Save and Continue Working")) . "\" NAME =\"continue\">
        <INPUT TYPE=SUBMIT VALUE=\"" . attr_safe(_("Save as Done")) . "\" NAME =\"done\">
        <INPUT TYPE=SUBMIT VALUE=\"" . attr_safe(_("Leave As-Is and Quit")) . "\" NAME =\"return\">";

echo "</form></center>";
echo "<br>";

// vim: sw=4 ts=4 expandtab
