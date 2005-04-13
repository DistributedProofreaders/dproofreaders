<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'RoundDescriptor.inc');

    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $round_num = $_GET['round_num'];

    if ($round_num == 0) {
        $text_column_name = 'master_text';
    } else {
        $round = get_Round_for_round_number($round_num);
        if ( is_null($round) )
        {
            die("downloadproofed.php: unexpected parameter round_num = '$round_num'");
        }
        $text_column_name = $round->text_column_name;
    }

    $result = mysql_query("SELECT $text_column_name FROM $project WHERE fileid = '$fileid'"); 
    $data = mysql_result($result, 0, $text_column_name);

    header("Content-type: text/plain; charset=$charset");
    // SENDING PAGE-TEXT TO USER
    // It's a text/plain document, so no encoding is necessary.
    echo $data;

// vim: sw=4 ts=4 expandtab
?> 
