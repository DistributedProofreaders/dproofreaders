<?
$relPath="./../../pinc/";
include($relPath.'cookiecheck.inc');
include($relPath.'connect.inc');
$dbC=new dbConnect();

    $projectname = $_POST['projectname'];
    $imagefile = $_POST['imagefile'];
    $fileid = $_POST['fileid'];
    $prooflevel = $_POST['prooflevel'];
    $button1 = $_POST['button1'];
    $button2 = $_POST['button2'];
    $button3 = $_POST['button3'];
    $project = $_POST['projectname'];
    $text_data = $_POST['text_data'];
    $text_data = strip_tags($text_data, '<i>');
    $orient = $_POST['orient'];

    $result = mysql_query("SELECT state FROM projects WHERE projectid = '$project'");
    $state = mysql_result($result, 0, "state");

    if ((($prooflevel == 0) && (($state == 2) || ($state != 8))) || 
        (($prooflevel == 2) && (($state == 12) || ($state == 18)))) {

    $timestamp = time();

    $newprooflevel = $prooflevel+1;

    $delay = 0;

    if (($button1 != "") || ($button2 != "")) {
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
    }

    // save
    if ($button1 != "" || $button2 !="") {
     $dbQuery="UPDATE $project SET state='";
     if ($prooflevel==1)
     {$dbQuery.="18' round2_text='$text_data' round2_time='$timestamp' round2_user='$pguser'";}
     else {$dbQuery.="8' round1_text='$text_data' round1_time='$timestamp' round1_user='$pguser'";}

        $result = mysql_query($dbQuery);
    }

    // save and restore image to edit view
    if ($button1 != "") {
     $frame1 = 'imageframe.php?project='.$project.$imagefile;
     echo "<HTML><HEAD><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=$frame1\"></HEAD><BODY>"; 
    }
    // save and do another send back to proof.php for a new page
    if ($button2 != "") {
     echo "<HTML><HEAD><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=proof.php?project=$project&prooflevel=$prooflevel&orient=$orient\" TARGET=\"_top\"></HEAD><BODY>"; 
    }

    // if quit without saving send back to projects page
    if ($button3 != "") {
// is this still needed? or just let the server-side stuff realize it's a missing file?
//        $result = mysql_query("UPDATE $project SET checkedout = 'no' WHERE fileid = '$fileid'");
//        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=proof_per.php\"></head><body>"; 
     echo "<HTML><HEAD><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=proof_per.php\" TARGET=\"_top\"></HEAD><BODY>"; 
    }
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
