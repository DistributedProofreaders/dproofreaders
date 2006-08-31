<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!-- 
function newHelpWin(wFile)
{
<?PHP 
include_once($relPath.'resolution.inc');
$i_r= $i_resolutions;
$wSize=explode("x",$i_r[$userP['i_res']*1]);
echo "newFeatures='toolbar=0,status=0,location=0,directories=0,menubar=0,scrollbars=1,resizable=1,width=400,height=300,top=".(($wSize[1]-300)/2).",left=".(($wSize[0]-400)/2)."';\r\n";
echo "wDir='$popHelpDir';\r\n";
?>
nwWin=window.open(wDir+wFile+'.html',"popHelp",newFeatures);}
// -->
</SCRIPT>