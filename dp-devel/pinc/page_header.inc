<?PHP
include_once($relPath.'site_vars.php');

function page_header( $img_base, $title )
// Display a page-header, either an image (if available) or a textual title.
{
    global $dyn_dir, $dyn_url;

    $header_images_dir = "$dyn_dir/header_images";
    $header_images_url = "$dyn_url/header_images";

    if ( file_exists("$header_images_dir/$img_base.jpg") )
    {
        echo "<br>";
        echo "<center>";
        echo "<img src='$header_images_url/$img_base.jpg' title='$title' alt='$title'>";
        echo "</center>";
    }
    else
    {
        echo "<h1 align='center'>$title</h1>";
    }
    echo "\n";
}

// vim: sw=4 ts=4 expandtab
?>
