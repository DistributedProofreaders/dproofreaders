<?PHP
include_once($relPath.'v_resolution.inc');

$i_r = $i_resolutions;
$wSize = explode("x",$i_r[$userP['i_res']*1]);
$window_width  = $wSize[0] - 20;
$window_height = $wSize[1] - 80;

$window_name_base = 
    preg_replace(
        array( "'^[a-z]+:/+'i", "'/.*$'", "'[.]'" ),
        array( "",              "",       "_" ),
        $code_url );

return "
function newProofWin(winURL)
{
    newFeatures='toolbar={$userP['i_toolbar']},status={$userP['i_statusbar']},location=0,directories=0,menubar=0,scrollbars=1,resizable=1,width=$window_width,height=$window_height,top=0,left=5';
    nwWin=window.open(winURL,'prooferWin_$window_name_base',newFeatures);
}
";

// vim: sw=4 ts=4 expandtab
?>
