<?
$relPath="./../../pinc/";
include($relPath.'echo_project_info.inc');


?>
<table border=1 cellpadding=0 cellspacing=0 style='border-collapse: collapse' 
bordercolor='#111111' width=630>
<tr>
<td width=126 bgcolor ="#CCCCCC" align=center><a href ="<? echo $forums_url ?>/index.php">Forums</a></td>
<?
    // If Project Manager give link back to project manager page.

    $manager = $userP['manager'];
    if ($manager == "yes") {
        echo "<td width=126 bgcolor='#CCCCCC' align=center><a href = \"../project_manager/projectmgr.php\">Manage 
Projects</a></td>\n";
    } else {
        echo "<td width=126 bgcolor='#CCCCCC' align=center>&nbsp;</td>\n";
    }

?>
<td width=126 bgcolor='#CCCCCC' align=center>&nbsp;</td>
<?
    $postprocessor = $userP['postprocessor'];
    if ($postprocessor == "yes" || $postprocessorpages >= 400) {
  //  if ($postprocessor == "yes") {
        echo "<td width=126 bgcolor='#CCCCCC' align=center><a href =\"../post_proofers/post_proofers.php\">Post-Processing</a></td>";
    } else {
        echo "<td width=126 bgcolor='#CCCCCC' align=center>&nbsp;</td>\n";
    }
?>

<td width=126 bgcolor='#CCCCCC' align=center><a href ="../logout.php">Logout</a></td>
</tr></table>
<?

/* $_GET $project, $proofstate, $proofing */

include($relPath.'doctype.inc');
echo "$docType\r\n<HTML><HEAD><TITLE> Project Comments</TITLE>";

include($relPath.'js_newwin.inc');
echo "</HEAD><BODY>";

    echo_project_info( $project, 'proj_post', 0 );
    echo "<BR>";


?>
</BODY></HTML>
