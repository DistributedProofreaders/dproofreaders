<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
    $imagefile = ptog('imagefile');
    $fileid = ptog('fileid');
    $prooflevel = ptog('prooflevel');
    $button1 = ptog('button1');
    $button2 = ptog('button2');
    $button3 = ptog('button3');
    $button4 = ptog('button4');
    $project = ptog('projectname');
    $text_data = ptog('text_data');
    $text_data = strip_tags($text_data, '<i>');
    $orient = ptog('orient');
    $lang=ptog('lang');
    $js = ptog('js');
    $button1_x = ptog('button1_x');
    $button2_x = ptog('button2_x');
    $button3_x = ptog('button3_x');
    $button4_x = ptog('button4_x');

if ($js)
{
$fntF=ptog('fntF');
$fntS=ptog('fntS');
$sTags=ptog('sTags');
$zmSize=ptog('zmSize');
$fntF=isset($fntF)? $fntF:'0';
$fntS=isset($fntS)? $fntS:'0';
$sTags=isset($sTags)? $sTags:'1';
$zmSize=isset($zmSize)? $zmSize:'100';
//echo $fntF;
$prefTags="&fntF=$fntF&fntS=$fntS&sTags=$sTags&zmSize=$zmSize";
}

    $result = mysql_query("SELECT state FROM projects WHERE projectid = '$project'");
    $state = mysql_result($result, 0, "state");

    if ((($prooflevel == 0) && (($state == 2) || ($state != 8))) || 
        (($prooflevel == 2) && (($state == 12) || ($state == 18)))) {

    $timestamp = time();

    $newprooflevel = $prooflevel+1;

    $delay = 0;

    if (($button1 != "") || ($button2 != "") || isset($button1_x) || isset($button2_x)) {
        $sql = "SELECT fileid FROM $project WHERE state='";
        $sql.=$prooflevel==0? "2'":"12'";
        $sql.=" LIMIT 1";

        $result = mysql_query($sql);
        $numrows=mysql_num_rows($result);

        if ($numrows == 0) {
            if ($prooflevel == 0) { $newstate = 8; } else { $newstate = 18; }
            $result = mysql_query("UPDATE projects SET state = $newstate WHERE projectid = '$project'");
        }
        // update user page count in user database
        $sql = "UPDATE users SET pagescompleted = pagescompleted+1 WHERE username = '$pguser'";
        $result = mysql_query($sql);
 
    // save
     $dbQuery="UPDATE $project SET state='";
     if ($prooflevel==2)
     {$dbQuery.="18', round2_text='$text_data', round2_time='$timestamp', round2_user='$pguser'";}
     else {$dbQuery.="8', round1_text='$text_data', round1_time='$timestamp', round1_user='$pguser'";}
     $dbQuery.=" WHERE fileid='$fileid'";
        $result = mysql_query($dbQuery);
    } // end  button1 & button 2 saves, etc.

    // save and restore image to edit view
    if ((@$button1 != "") || isset($button1_x) || isset($button4_x) || (@$button4 !="")) {
            $project = 'project='.$project;
            $fileid = '&fileid='.$fileid;
            $imagefile = '&imagefile='.$imagefile;
            $prooflevel = '&prooflevel='.$prooflevel;
            $js='&js='.$js;
            $lang='&lang='.$lang;
            $saved='&saved=1';
    if ($button4 != "" || isset($button4_x)) {
          $orient=$orient=='vert'? $orient='hrzn':$orient='vert';
    } // end change layout
            $orient = '&orient='.$orient;
     $frame1 = 'proof.php?'.$project.$fileid.$imagefile.$prooflevel.$orient.$lang.$js.$saved;
     if ($js) {$frame1=$frame1.$prefTags;}
     echo "<HTML><HEAD><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=$frame1\"></HEAD><BODY>"; 
    } // end save and continue same page button 1



    // save and do another send back to proof.php for a new page
    if ($button2 != "" || isset($button2_x)) {
            $project = 'project='.$project;
            $prooflevel = '&prooflevel='.$prooflevel;
            $js='&js='.$js;
            $lang='&lang='.$lang;
            $orient = '&orient='.$orient;
     $frame1 = 'proof.php?'.$project.$prooflevel.$orient.$lang.$js;
     if ($js) {$frame1=$frame1.$prefTags;}
     echo "<HTML><HEAD><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=$frame1\" TARGET=\"_top\"></HEAD><BODY>"; 
    }
    // if quit without saving send back to projects page
    if ($button3 != "") {
// is this still needed? or just let the server-side stuff realize it's a missing file?
     $dbQuery="UPDATE $project SET state='";
     $dbQuery.=$prooflevel==2?"12":"2";
     $dbQuery.="WHERE fileid = '$fileid'";
     $result = mysql_query($dbQuery);
     echo "<HTML><HEAD><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=proof_per.php\" TARGET=\"_top\"></HEAD><BODY>"; 
    } // end button 3 quit

    } else {
     echo "<HTML><HEAD><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=proof_per.php\" TARGET=\"_top\"></HEAD><BODY>";
     echo "No more files available for proofing for this project.<BR> You will be taken back to the project page in 4 seconds.";
    }
?>
<script language="JavaScript">
<!--
  javascript:window.history.forward(1);
//-->
</script>
</BODY></HTML>
