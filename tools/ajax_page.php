<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // array_get()

//include_once($relPath.'RoundDescriptor.inc');

if(!$user_is_logged_in)
{
    $data = _("You must be logged in to do this");
}
else
{
    $projectid = trim(array_get($_GET,"projectid",""));
    $page = trim(array_get($_GET,"page",""));
//    $expanded_rounds = array_keys($Round_for_round_id_);
  //  array_unshift($expanded_rounds, 'OCR');
    $text_column_name = trim(array_get($_GET,"text_column",""));

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

//$data = "test message";
$data = utf8_encode($data);
$retval = json_encode($data);
echo $retval;
