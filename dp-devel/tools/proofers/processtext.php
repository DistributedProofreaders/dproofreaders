<?
if ($_COOKIE['pguser']) {
    // can only come from a cookie, forged or otherwise
    $good_login = 1;
    $pguser = $_COOKIE['pguser'];
}

if ($good_login != 1) {
    echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=../../accounts/signin.php\"></head><body></body></html>";
} else {

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

    import '../../connect.php';

    $result = mysql_query("SELECT state FROM projects WHERE projectid = '$project'");
    $state = mysql_result($result, 0, "state");

    if ((($prooflevel == 0) && (($state == 2) || ($state != 8))) || 
        (($prooflevel == 2) && (($state == 12) || ($state == 18)))) {

    $timestamp = time();

    $newprooflevel = $prooflevel+1;

    $delay = 0;

    if (($button1 != "") || ($button2 != "")) {

        $uniqueid = uniqid("fileID");
        $sql = "SELECT fileid FROM $project WHERE checkedout = 'no' AND prooflevel = '$prooflevel' LIMIT 1";

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

    // if save and quit selected save the file and redirect back to projects page
    if ($button1 != "") {
        $result = mysql_query("INSERT INTO $project (prooflevel, text_data, image_Filename, 
         fileid, proofedby, timestamp) VALUES ('$newprooflevel','$text_data','$imagefile',  
         '$uniqueid','$pguser', '$timestamp')");
        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"2 ;URL=proof_per.php\"></head><body>";
        echo "<p>File being saved. You will be taken to the projects page after save is complete.";
    }

    // if save and do another than save file and send back to proof.php for a new page
    if ($button2 != "") {
        
        $sql = "INSERT INTO $project (prooflevel, text_data, image_Filename,
         fileid, proofedby, timestamp) VALUES ('$newprooflevel','$text_data','$imagefile',  
         '$uniqueid','$pguser','$timestamp')";
        $result = mysql_query($sql);
        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=proof.php?project=$project&prooflevel=$prooflevel&orient=$orient\"></head><body>"; 
    }

    // if quit without saving send back to projects page
    if ($button3 != "") {
        $result = mysql_query("UPDATE $project SET checkedout = 'no' WHERE fileid = '$fileid'");
        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=proof_per.php\"></head><body>"; 
    }
    } else {

        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"4 ;URL=proof_per.php\"></head></body>";
        echo "No more files available for proofing for this project.<BR> You will be taken back to the project page in 4 seconds.</body></html>";
    }
?>
<script language="JavaScript">
<!--
  javascript:window.history.forward(1);
//-->
</script>
</body></html>
<?
}
?>
