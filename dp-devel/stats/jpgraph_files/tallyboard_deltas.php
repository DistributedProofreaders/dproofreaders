<?
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'TallyBoard.inc');
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();

$tally_name  = array_get( $_GET, 'tally_name',  '' );
$holder_type = array_get( $_GET, 'holder_type', '' );
$holder_id   = array_get( $_GET, 'holder_id',   '' );
$days_back   = array_get( $_GET, 'days_back', '30' );

if ( $tally_name  == '' ) die( "tally_name is empty" );
if ( $holder_type == '' ) die( "holder_type is empty" );
if ( $holder_id   == '' ) die( "holder_id is empty" );
if ( $days_back   == '' ) die( "days_back is empty" );

if ($days_back == "all")
{
    $min_timestamp = 0;
}
else
{
    $min_timestamp = time() - ($days_back * 86400);
}

$tallyboard = new TallyBoard( $tally_name, $holder_type );
$deltas = $tallyboard->get_deltas( $holder_id, $min_timestamp );

$datax = array();
$datay = array();
foreach ( $deltas as $timestamp => $tally_delta )
{
    $datax[] = date("n/j/Y", ($timestamp-1));
    $datay[] = $tally_delta;
}

if ( $days_back >= 700 )
{
    $x_tli = 30;
}
else if ( $days_back >= 365 )
{
    $x_tli = 15;
}
else if ( $days_back >= 60)
{
    $x_tli = 2;
}
else
{
    $x_tli = 1;
}

draw_simple_bar_graph(
    $datax,
    $datay,
	$x_tli,
    'Pages Completed per Day',
    'Pages',
    600, 300,
    180
);

// vim: sw=4 ts=4 expandtab
?>
