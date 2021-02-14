<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'metarefresh.inc');

require_login();

$show_image_size = '';

$projectid = get_projectID_param($_GET, 'projectid');

if (!$site_supports_metadata)
{
    echo _("md_phase1.php: \$site_supports_metadata is false, so exiting.");
    exit();
}


if (isset($_POST['done']))
{
    $badmetadata = handle_page_params();
    if ($badmetadata == 1) {
        $result = mysqli_query(DPDatabase::get_connection(), "UPDATE projects SET state = 'project_md_bad' WHERE projectid = '$projectid'");
        metarefresh(0,'md_available.php', _("Image Metadata Collection"),"");
    } else {
        $result = mysqli_query(DPDatabase::get_connection(), "UPDATE projects SET state = 'project_md_second' WHERE projectid = '$projectid'");
        $result = mysqli_query(DPDatabase::get_connection(), "UPDATE $projectid SET state = 'avail_md_second'");
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
        $result = mysqli_query(DPDatabase::get_connection(), sprintf("
            UPDATE $projectid
            SET orig_page_num = '%s'
            WHERE image = '%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $orig_page_num),
            mysqli_real_escape_string(DPDatabase::get_connection(), $image)));
    }

    $badmetadata = 0;
    foreach ( $_POST['metadata_'] as $image => $metadata )
    {
        $result = mysqli_query(DPDatabase::get_connection(), sprintf("
            UPDATE $projectid
            SET metadata = '%s'
            WHERE image = '%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $metadata),
            mysqli_real_escape_string(DPDatabase::get_connection(), $image)));
        if ($metadata == 'badscan' || $metadata == 'missing' || $metadata == 'sequence') {
            $badmetadata = 1;
        }
    }

    return $badmetadata;
}

// Finished dealing with input data.
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Now (if we're still here) echo the page.

$result = mysqli_query(DPDatabase::get_connection(), "SELECT nameofwork, authorsname, language, username, state FROM projects WHERE projectid = '$projectid'");

$row = mysqli_fetch_assoc($result);
$manager = $row["username"];
$state = $row["state"];
$name = $row["nameofwork"];
$author = $row["authorsname"];
$language = $row["language"];


$project = new Project($projectid);
$numpages = $project->get_num_pages();

$title = _("Image Metadata Phase1");
output_header($title);
echo "<h1>$title</h1>";

echo "<p>";
echo "<b>" . _("Project Name") . "</b>: " . html_safe($name) . "<br>";
echo "<b>" . _("Total Number of Master Pages") . "</b>: $numpages<br>";
echo "<b>" . _("Language") . "</b>: $language<br>";
echo "</p>";

//---------------------------------------------------------------------------------------------------


//echo "<h3>Per-Page Info</h3>\n";


echo "<form method ='post'><table class='themed theme_striped'>\n";


    // Top header row
    echo "<tr>\n";
    echo "    <th>" . _("Index") . "</th>\n";
    echo "    <th>" . _("Image Name") . "</th>\n";
    echo "    <th>" . _("Original Page #") . "</th>\n";
    echo "    <th>" . _("Page Metadata") . "</th>\n";
    echo "    <th>" . _("Thumbnail") . "</th>\n";
    echo "</tr>\n";

    // Image rows
    $path = "$projects_dir/$projectid/";

    $fields_to_get = 'image, state, metadata';

    $res = mysqli_query(DPDatabase::get_connection(),  "SELECT image, state, metadata, orig_page_num FROM $projectid ORDER BY image ASC");
    $num_rows = mysqli_num_rows($res);

    for ( $rownum=0; $rownum < $num_rows; $rownum++ )
    {
        $page_res = mysqli_fetch_assoc($res);

        $image = $page_res['image'];
        $metadata = $page_res['metadata'];
        $orig_page_num = $page_res['orig_page_num'];

        echo "<tr>";

        // --------------------------------------------
        // Index
        $index = $rownum+1;
        echo "<td align='right'>$index</td>\n";

        if (file_exists($path.$image)) {
            if ($show_image_size) $imagesize = filesize(realpath($path.$image));
        } else {
            if ($show_image_size) $imagesize = 0;
        }
        echo "<td><a href=../page_browser.php?project=$projectid&imagefile=$image>$image</a></td>\n";

        // Original Page Number   
        echo "<td><input type ='textbox' name='orig_page_num_[$image]' value = $orig_page_num></td>";


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

        echo "<td align='left'>\n";
        foreach ( $metadata_possibles as $code => $label )
        {
            $checked = ($code == $metadata ? 'checked' : '');
            echo "<input type='radio' name='metadata_[$image]' value='$code' $checked>$label<br>\n";
        }
        echo "</td>";

        // Show Thumbnail
        echo "<td align='right'>
                <a href=\"../page_browser.php?project=$projectid&imagefile=$image\"><img src =\"$projects_url/$projectid/thumbs/$image\" alt = \"$image\" border =\"0\"></a>
            </td>";

        echo "</tr>";
    }
    echo "</table>";

    echo "<INPUT TYPE=SUBMIT VALUE=\"" . attr_safe(_("Save and Continue Working")) . "\" NAME =\"continue\">
        <INPUT TYPE=SUBMIT VALUE=\"" . attr_safe(_("Save as 'Done'")) . "\" NAME =\"done\">
        <INPUT TYPE=SUBMIT VALUE=\"" . attr_safe(_("Leave As-Is and Quit")) . "\" NAME =\"return\">";

echo "</form></center>";
echo "<br>";

// vim: sw=4 ts=4 expandtab
