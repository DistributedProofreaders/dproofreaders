<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!-- 
function newProofWin(winURL)
{
<?PHP 
include_once($relPath.'v_resolution.inc');
$i_r= $i_resolutions;
$wSize=explode("x",$i_r[$userP['i_res']*1]);
echo "newFeatures='toolbar={$userP['i_toolbar']},status={$userP['i_statusbar']},location=0,directories=0,menubar=0,scrollbars=1,resizable=1,width=".($wSize[0]-20).",height=".($wSize[1]-80).",top=0,left=5';\r\n";
echo "nwWin=window.open(winURL,\"prooferWin.".preg_replace(array("'^[a-z]+:/+'i","'/.*$'"),"",$code_url)."\",newFeatures);}";
?>
// -->
</SCRIPT>
