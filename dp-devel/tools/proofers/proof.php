<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
    $project = gtog('project');
    $prooflevel =gtog('prooflevel');
    $orient = gtog('orient');
    $text_data = gtog('text_data');
    $fileid = gtog('fileid');
    $imagefile = gtog('imagefile');
    $js=gtog('js');
    $saved=gtog('saved');
    $editone=gtog('editone');


if (!isset($saved))
{
    //Make sure project is still available
    $sql = "SELECT * FROM projects WHERE projectid = '$project' LIMIT 1";
    $result = mysql_query($sql);
    $state = mysql_result($result, 0, "state");
    if ((($prooflevel == 0) && ($state != 2)) || (($prooflevel == 2) && ($state != 12))) {

        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"4 ;URL=proof_per.php\"></head></body>";

        echo "No more files available for proofing for this project.<BR> You will be taken back to the project page in 4 seconds.</body></html>";
    exit;}

        $timestamp = time();
        //find page to be proofed.
          $dbQuery="SELECT fileid, image FROM $project WHERE state='";
          if ($prooflevel==2) {$dbQuery.="12' AND round1_user != '$pguser'";}
          else {$dbQuery.="2'";}
          $dbQuery.=" ORDER BY image ASC";
        $result=mysql_query($dbQuery);
        $numrows = mysql_num_rows($result);

        if ($numrows == 0) {
            echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"2 ;URL=proof_per.php\"></head><body>";
            echo "No more files available for proofing for this project.<BR> You will be taken back to the project page in 2 seconds.</body></html>";
        } else {
            $fileid = mysql_result($result, 0, "fileid");
            $imagefile = mysql_result($result, 0, "image");
            $dbQuery="UPDATE $project SET state='";
            if ($prooflevel==2)
            {$dbQuery.="15', round2_time='$timestamp', round2_user='$pguser'";}
            else {$dbQuery.="5', round1_time='$timestamp', round1_user='$pguser'";}
            $dbQuery.="  WHERE fileid='$fileid'";
            $update = mysql_query($dbQuery);
        }

}

            $pageNum=substr($imagefile,0,-4);
            $fileid = '&fileid='.$fileid;
            $imagefile = '&imagefile='.$imagefile;
            $newprooflevel = '&prooflevel='.$prooflevel;
            $newjs='&js='.$js;
// will need to add a true language option to this in future
            $lang=gtog('lang');
            $lang=isset($lang)? $lang:'1';
            $lang="&lang=$lang";
$frame1=isset($saved) && (!isset($editone))? 'saved':'imageframe';
            $frame1 = $frame1.'.php?project='.$project.$imagefile.$newjs;
            $frame3 = 'textframe.php?project='.$project.$imagefile.$fileid.$newprooflevel.$lang.$newjs;
if (isset($orient)) {$neworient="&orient=$orient"; $frame1.=$neworient;$frame3.=$neworient;}
if (isset($editone)) {$editone="&editone=$editone"; $frame3.=$editone;}

if ($js)
{
$fntF=gtog('fntF');
$fntS=gtog('fntS');
$sTags=gtog('sTags');
$zmSize=gtog('zmSize');
$fntF=isset($fntF)? $fntF:'0';
$fntS=isset($fntS)? $fntS:'0';
$sTags=isset($sTags)? $sTags:'1';
$zmSize=isset($zmSize)? $zmSize:'100';
$prefTags="&fntF=$fntF&fntS=$fntS&sTags=$sTags&zmSize=$zmSize";
$frame3=$frame3.$prefTags;
}
            //print $sql;
            include('frameset.inc');

?>
