<?php
$relPath="./../../pinc/";
include($relPath.'base.inc');
include($relPath.'theme.inc');
include($relPath.'Project.inc');

require_login();

$projectid = validate_projectID('project', @$_GET['project']);

$zip_type = get_enumerated_param( $_GET, 'zip_type', NULL, array('pages', 'illos'), TRUE );

$project = new Project($projectid);

$image_index_str = _('Image Index');

// Suppress display if we're streaming back a zipfile
// so that we can control the http headers
if (is_null($zip_type))
{
theme("$image_index_str: {$project->nameofwork}", 'header');

echo "
    <h1>{$project->nameofwork}</h1>
    <p>$projectid</p>
    <p><a href='$code_url/project.php?id=$projectid'>", _('Return to project page'), "</a></p>
    <h2>$image_index_str</h2>
    <p>" . _('Below are the individual images for this project.') . "</p>
";
}

$page_image_names = array();
$res = mysql_query("
    SELECT image
    FROM $projectid
    ORDER BY image
") or die(mysql_error());
while ( list($image) = mysql_fetch_row($res) )
{
    $page_image_names[] = $image;
}

chdir("$projects_dir/$projectid");
$existing_image_names = glob("*.{png,jpg}", GLOB_BRACE);
// That returns a sorted list of the .png files
// followed by a sorted list of the .jpg files,
// but we want the two lists interleaved...
sort($existing_image_names);

$nonpage_image_names = array_diff($existing_image_names, $page_image_names);

if (!is_null($zip_type))
{
    chdir("$projects_dir/$projectid");
    $list_name = $projectid."_".$zip_type."_flist.txt";
    switch($zip_type)
    {
    case "illos":
	$files_list=$nonpage_image_names;
	break;
    default:
    case "pages":
	$files_list=$page_image_names;
    }
    $files=implode(chr(10),$files_list);
    file_put_contents( $list_name, $files );

    // Create the zip on-the-fly and stream it back
    $zipfile=$projectid . "_" . $zip_type . ".zip";
    header('Content-type: application/zip');
    header('Content-Disposition: attachment; filename="'.$zipfile.'"');
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    passthru("cat $list_name |zip -@ -");
    unlink($list_name);
    exit();
}
else
{
    echo "<table border='1'>\n";
    echo "<tr>\n";
    echo "<td valign='top'>\n";
    list_images( $page_image_names, TRUE );
    echo "</td>\n";
    echo "<td valign='top'>\n";
    list_images( $nonpage_image_names, FALSE );
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td align='center'>\n";
    show_dl_link( $page_image_names, TRUE );
    echo "</td>\n";
    echo "<td align='center'>\n";
    show_dl_link( $nonpage_image_names, FALSE );
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
}

theme("", 'footer');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function list_images( $image_names, $these_are_page_images )
{
    global $project, $existing_image_names;

    if ( $these_are_page_images )
    {
        $header = _('Page Images');
    }
    else
    {
        $header = _('Non-Page Images (Illustrations)');
    }
    echo "<h4 align='center'>$header</h4>";

    $show_replace_links = $project->can_be_managed_by_current_user;

    echo "<table>\n";

    {
        echo "<tr>\n";
        echo "<th>", _('name'), "</th>\n";
        echo "<th>", _('size (bytes)'), "</th>\n";
        if ( $show_replace_links )
        {
            echo "<th>", _('replace'), "</th>\n";
        }
        echo "</tr>\n";
    }

    foreach ( $image_names as $image_name )
    {
        echo "<tr>\n";

        if ( $these_are_page_images && !in_array( $image_name, $existing_image_names ) )
        {
            echo "<td>$image_name</td>\n";
            echo "<td bgcolor='#ff9999'>" . _("missing") . "</td>\n";
        }
        else
        {
            global $projects_url, $projectid;
            $encoded_url = "$projects_url/$projectid/" . rawurlencode($image_name);
            echo "<td><a href='$encoded_url'><b>$image_name</b></a></td>\n";

            $size = filesize($image_name);
            echo "<td align='right'>$size</td>\n";
        }

        if ( $show_replace_links )
        {
            global $code_url;
            if ( $these_are_page_images)
            {
                $replace_url = "$code_url/tools/project_manager/handle_bad_page.php?projectid=$projectid&amp;image=$image_name&amp;modify=image";
            }
            else
            {
                $replace_url = "$code_url/tools/project_manager/replace_image.php?projectid=$projectid&amp;image=$image_name";
            }
            echo "<td><a href='$replace_url'>", _('replace'), "</a></td>\n";
        }

        echo "</tr>\n";
    }

    echo "</table>\n";

}

function show_dl_link( $image_names, $these_are_page_images )
{
    global $projectid, $code_url;

    if ( $these_are_page_images )
    {
	$tvalue=_("Download Page Images");
	$ztype="pages";
    }
    else
    {
	$tvalue=_("Download Illustrations");
	$ztype="illos";
    }

    // Show a download button for non-empty images list
    if(!empty($image_names))
    {
	$form_target="$code_url/tools/proofers/images_index.php?project=$projectid&zip_type=$ztype";
	echo "<form name='idl' id='idl' action='$form_target' method='POST'>\n";
        echo "<input id='zip_type' type='submit' value='$tvalue'>";
        echo "</form>";
    }
}

// vim: sw=4 ts=4 expandtab
