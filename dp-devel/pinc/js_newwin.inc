<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!-- 
function newProofWin(winURL)
{
<?PHP 
$i_r= array('640x480','800x600','1024x768','1152x864','1280x1024','1600x1200');
$wSize=explode("x",$i_r[$userP['i_res']*1]);
echo "newFeatures='toolbar={$userP['i_toolbar']},status={$userP['i_statusbar']},location=0,directories=0,menubar=0,scrollbars=1,resizable=1,width=".($wSize[0]-20).",height=".($wSize[1]-80).",top=0,left=5';\r\n";
?>
nwWin=window.open(winURL,"prooferWin",newFeatures);}
// -->
</SCRIPT>