<?php
$relPath="../../pinc/";
include($relPath.'connect.inc');
$db_Connection=new dbConnect();
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'showtexts.inc');


$type = $_GET['type'];
$etext_limit = $_GET['etext_limit'];
$type = $_GET['type'];
$orderby = $_GET['orderby'];
$field_name = $_GET['field_name'];
$search_char = $_GET['search_char'];
$show_total = $_GET['show_total'];

$metal_map = array(
    'bronze' => _('Bronze'),
    'silver' => _('Silver'),
    'gold'   => _('Gold'),
);

function echo_other_type( $other_type )
{
    global $code_url, $etext_limit, $orderby, $field_name, $search_char, $show_total, $metal_map;
    $metal_name = $metal_map[$other_type];
    echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=$other_type&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total=$show_total'>$metal_name</a>";
}

function echo_other_order( $other_field_name, $other_direction )
{
    global $code_url, $etext_limit, $type, $search_char, $show_total;
    echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=$type&orderby=$other_direction&field_name=$other_field_name&search_char=$search_char&show_total=$show_total'>$other_direction</a>";
}

theme("$type E-Texts", "header");
?>

<center><font face="Verdana" size="6"><b>E-Texts</b></font></center>

<center>
<?
if ($type == "gold") {
    echo_other_type( 'silver' );
    echo " | ";

    echo_other_type( 'bronze' );

} elseif ($type == "silver") {
    echo_other_type( 'bronze' );
    echo " | ";

    echo_other_type( 'gold' );

} elseif ($type == "bronze") {

    echo_other_type( 'gold' );
    echo " | ";

    echo_other_type( 'silver' );

} else {

    echo_other_type( 'bronze' );
    echo " | ";

    echo_other_type( 'silver' );
    echo " | ";

    echo_other_type( 'gold' );
}
?>
</center><br>

<center>
<i>Title:</i> 
<?

    echo_other_order( 'nameofwork', 'asc' );
    echo " or ";

    echo_other_order( 'nameofwork', 'desc' );
    echo " | ";

?>
<i>Author:</i> 
<?

    echo_other_order( 'author', 'asc' );
    echo " or ";

    echo_other_order( 'author', 'desc' );
    echo " | ";

?>
<i>Submitted Date:</i> 
<?

    echo_other_order( 'modifieddate', 'asc' );
    echo " or ";

    echo_other_order( 'modifieddate', 'desc' );

?>
</center>
<hr width="75%" align="center"><br>

<?

showtexts($etext_limit, $type, $orderby, $field_name, $search_char, $show_total);

theme("", "footer");
?>
