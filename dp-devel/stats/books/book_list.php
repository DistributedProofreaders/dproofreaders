<?php
$relPath="../../pinc/";
include($relPath.'connect.inc');
$db_Connection=new dbConnect();
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'showtexts.inc');

$metal_map = array(
    'bronze' => _('Bronze'),
    'silver' => _('Silver'),
    'gold'   => _('Gold'),
    'all'    => _('All'),
);

$type        = get_enumerated_param($_GET, 'type', 'all', array_keys($metal_map));
$etext_limit = get_integer_param($_GET, 'etext_limit', 0, 0, NULL);
$orderby     = get_enumerated_param($_GET, 'orderby', 'asc', array('asc', 'desc'));
$field_name  = @$_GET['field_name'];
$search_char = @$_GET['search_char'];
$show_total  = @$_GET['show_total'];

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
<?php

$i = 0;
foreach ( array('gold','silver','bronze') as $metal )
{
    if ( $metal != $type )
    {
        $i++;
        if ( $i > 1 ) echo " | ";
        echo_other_type( $metal );
    }
}

?>
</center><br>

<center>
<?php

$field_map = array(
    'nameofwork'   => _('Title'),
    'author'       => _('Author'),
    'modifieddate' => _('Submitted Date'),
);

$i = 0;
foreach ( array('nameofwork', 'author', 'modifieddate') as $other_field_name )
{
    $i++;
    if ( $i > 1 ) echo " | ";

    $field_name_t = $field_map[$other_field_name];
    echo "<i>$field_name_t:</i>\n";

    echo_other_order( $other_field_name, 'asc' );
    echo " or ";
    echo_other_order( $other_field_name, 'desc' );
}

?>
</center>
<hr width="75%" align="center"><br>

<?php

showtexts($etext_limit, $type, $orderby, $field_name, $search_char, $show_total);

theme("", "footer");
?>
