<?PHP

// Download a generated-on-demand zip of the
// image files in a given project directory.

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

$zipfile_path = "$dyn_dir/download_tmp/{$projectid}_images.zip";
$zipfile_url  = "$dyn_url/download_tmp/{$projectid}_images.zip";

if (file_exists($zipfile_path))
{
    header( "Location: $zipfile_url" );
    exit;
}

$projectpath = "$projects_dir/$projectid";

if (!is_dir($projectpath))
{
    echo "download_images.php: no project directory named '$projectid'.";
    exit;
}

mkdir_recursive( dirname($zipfile_path), 0777 );

exec(
    "zip -q -j -o $zipfile_path $projectpath/*.png $projectpath/*.jpg",
    $output,
    $return_code );
if ($return_code != 0)
{
    echo "download_images.php: the zip command failed.";
    exit;
}

header( "Location: $zipfile_url" );

// vim: sw=4 ts=4 expandtab
?>
