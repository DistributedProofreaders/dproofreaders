<?
$relPath="./../../pinc/";
include($relPath.'cookiecheck.inc');
include($relPath.'connect.inc');
$dbC=new dbConnect();

    php_track_vars;

    $project = $_GET['project'];
    $prooflevel = $_GET['prooflevel'];
    $orient = $_GET['orient'];
    $text_data = $_GET['text_data'];
    $fileid = $_GET['fileid'];
    $imagefile = $_GET['imagefile'];

    echo "<HTML><HEAD><TITLE>Proofers Page</TITLE></HEAD><font color=\"#000f00\">";

    //Make sure project is still available

    $sql = "SELECT * FROM projects WHERE projectid = '$project' LIMIT 1";
    $result = mysql_query($sql);
    $state = mysql_result($result, 0, "state");

    if ((($prooflevel == 0) && ($state != 2)) || ((prooflevel == 2) && ($state != 12))) {

        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"4 ;URL=proof_per.php\"></head></body>";

        echo "No more files available for proofing for this project.<BR> You will be taken back to the project page in 4 seconds.</body></html>";
    } else {

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
            $dbQuery="UPDATE $project SET state='";
            if ($prooflevel==2)
            {$dbQuery.="15' round2_time='$timestamp' round2_user='$pguser'";}
            else {$dbQuery.="5' round1_time='$timestamp' round1_user='$pguser'";}
            $dbQuery.="  WHERE fileid='$fileid'";
            $update = mysql_query($dbQuery);
            $imagefile = mysql_result($result, 0, "image");

            $fileid = '&fileid='.$fileid;
            $imagefile = '&imagefile='.$imagefile;
            $newprooflevel = '&prooflevel='.$prooflevel;

// will need to add a true language option to this in future
            $lang='&lang=1';
            $frame1 = 'imageframe.php?project='.$project.$imagefile;
            $frame3 = 'textframe.php?project='.$project.$imagefile.$fileid.$newprooflevel.$lang."&orient=$orient";

            //print $sql;
            include('frameset.php');

        }
    }//end project available loop
?>
