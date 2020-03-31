<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'site_vars.php'); // $utf8_site
include_once($relPath.'misc.inc'); // array_get()

if(!$user_is_logged_in)
{
    $data = _("You must be logged in to do this");
}
else
{
    $projectid = array_get($_GET,"projectid","");
    $page = array_get($_GET,"page","");
    $text_column_name = array_get($_GET,"text_column","");

    $result = mysqli_query(DPDatabase::get_connection(), sprintf("SELECT $text_column_name FROM $projectid WHERE image = '%s'",mysqli_real_escape_string(DPDatabase::get_connection(), $page)));
    if($result)
    {
        $row = mysqli_fetch_assoc($result);
        $data = $row[$text_column_name];
    }
    else
    {
        $data = mysqli_error(DPDatabase::get_connection());
    }
}

header('Content-Type: application/json');

if(!$utf8_site)
{
    $data = utf8_encode($data);
}
echo json_encode($data);
