<?PHP

// Download a dynamically-generated zip of the
// image files in a given project directory.
//
// (By generating it on-the-fly, we don't need it
// taking up space in the filesytstem.)

$relPath='../pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');

$projectid = array_get( $_GET, 'projectid', '' );
if (empty($projectid))
{
    echo "download_images.php: missing or empty 'projectid' parameter.";
    exit;
}

if (strpos($projectid,'/') !== FALSE )
{
    echo "download_images.php: bad 'projectid' parameter: '$projectid'.";
    exit;
}

$projectpath = "$projects_dir/$projectid";

if (!is_dir($projectpath))
{
    echo "download_images.php: no project directory named '$projectid'.";
    exit;
}

header( "Content-type: application/zip");
header( "Content-Disposition: filename='{$projectid}images.zip'" );

passthru( "zip -q -j -o - $projectpath/*.png $projectpath/*.jpg" );

// vim: sw=4 ts=4 expandtab
?>
