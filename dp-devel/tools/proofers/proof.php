<?
if ($_COOKIE['pguser']) {
    // can only come from a cookie, forged or otherwise
    $good_login = 1;
    $pguser = $_COOKIE['pguser'];
}

if ($good_login != 1) {
    echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=../../accounts/signin.php\"></head><body></body></html>"; 
} else {

    php_track_vars;

    $project = $_GET['project'];
    $prooflevel = $_GET['prooflevel'];
    $orient = $_GET['orient'];
    $text_data = $_GET['text_data'];
    $fileid = $_GET['fileid'];
    $imagefile = $_GET['imagefile'];

    echo "<HTML><HEAD><TITLE>Proofers Page</TITLE></HEAD><font color=\"#000f00\">";

    //Make sure project is still available
    include '../../connect.php';

    $sql = "SELECT * FROM projects WHERE projectid = '$project' LIMIT 1";
    $result = mysql_query($sql);
    $state = mysql_result($result, 0, "state");

    if ((($prooflevel == 0) && ($state != 2)) || ((prooflevel == 2) && ($state != 12))) {

        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"4 ;URL=proof_per.php\"></head></body>";

        echo "No more files available for proofing for this project.<BR> You will be taken back to the project page in 4 seconds.</body></html>";
    } else {

        $timestamp = time();
        //find page to be proofed.
#        $sqlupdate = mysql_query("UPDATE $project SET checkedout = 'yes', timestamp = '$timestamp', proofedby = '$pguser' WHERE checkedout = 'no' AND prooflevel = '$prooflevel' ORDER BY Image_Filename LIMIT 1") or die ("No project available.");
#        $numrows = mysql_affected_rows();
        $result = mysql_query("SELECT fileid, Image_Filename FROM $project WHERE checkedout = 'no' AND prooflevel = '$prooflevel'");
        $numrows = mysql_num_rows($result);

        if ($numrows == 0) {

            echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"2 ;URL=proof_per.php\"></head><body>";
            echo "No more files available for proofing for this project.<BR> You will be taken back to the project page in 2 seconds.</body></html>";
        } else {
#            $result = mysql_query("SELECT fileid, Image_Filename FROM $project WHERE timestamp = '$timestamp' AND proofedby = '$pguser'");
#            $numrows=mysql_num_rows($result);
            $fileid = mysql_result($result, 0, "fileid");
            $update = mysql_query("UPDATE $project SET checkedout = 'yes', timestamp = '$timestamp', proofedby = '$pguser' WHERE fileid = '$fileid'");
            $imagefile = mysql_result($result, 0, "Image_Filename");

            $fileid = '&fileid='.$fileid;
            $imagefile = '&imagefile='.$imagefile;
            $newprooflevel = '&prooflevel='.$prooflevel;

            $frame1 = 'imageframe.php?project='.$project.$imagefile;
            $frame3 = 'textframe.php?project='.$project.$imagefile.$fileid.$newprooflevel;

            //print $sql;
            if ($orient == "vert") {
               $frame1 = 'imageframe2.php?project='.$project.$imagefile;
               echo "<FRAMESET COLS=\"*,600\"><FRAME NAME=\"imageframe\" SRC=\"$frame1\"><FRAME NAME=\"text\" SRC=\"$frame3&orient=$orient\">";
            } else {
                echo"<FRAMESET ROWS=\"*,170\">";
                echo"<FRAME NAME=\"imageframe\" SRC=\"$frame1\"><FRAME NAME=\"text\" SRC=\"$frame3\">";
            }
            echo"</FRAMESET><NOFRAMES><BODY>You're browser is not frames capable!</BODY></NOFRAMES></HTML>";

        }
    }//end project available loop
}
?>
