<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');

if ($mode != "upload") {
    $project = $_GET['projectid'];
    $todaysdate = time();

    $sql = mysql_query("SELECT nameofwork, authorsname, language, scannercredit, clearance, username FROM projects WHERE projectid = '$projectid'");
    $NameofWork = mysql_result($sql, 0, "nameofwork");
    $AuthorsName = mysql_result($sql, 0, "authorsname");
    $Language = mysql_result($sql, 0, "language");
    $scannercredit = mysql_result($sql, 0, "scannercredit");
    $clearance = mysql_result($sql, 0, "clearance");
    $username = mysql_result($sql, 0, "username");

    $result = mysql_query("SELECT email, real_name FROM users WHERE userrname = '$username'");
    $email = mysql_result($result, 0, "email");
    $real_name = mysql_result($result, 0, "real_name");

    $result = mysql_query("SELECT real_name FROM users WHERE username = '$pguser'");
    $post_proofer = mysql_result($result, 0, "real_name");

    // mark the project as completed post-processing
    $sql = mysql_query("UPDATE projects SET state='29', modifieddate = '$todaysdate' WHERE projectid = '$project'");
?>

<html><head><title>Completed Post-Processing</title></head>
<body>
<P>This project has been checked in as completed and <? echo $real_name; ?> has been e-mailed. Make sure the file you uploaded is a zip file.
<P>Credit line: <? echo $real_name, $scannercredit;?> and The Online Distributed Proofreading Team.
<P>Completed Zip File: <FORM ENCTYPE="multipart/form-data" ACTION=postcompleted.php METHOD="POST"><input name=zipfile TYPE="file" SIZE=25>
<input type="hidden" name=mode value="upload">
<?
    print "<input type=\"hidden\" name=projectid value=\"$project\"><INPUT TYPE=\"submit\" VALUE=\"Upload\"></FORM>";

    // NOTE: Future Suggestions For This Area:
    // - Attach to P.G. submission page

//    exec("perl mailcomplete.pl '$project' '$email'");

} else {
    $project = $_GET['projectid'];
    if ($file != "none") {
        copy ($file, "/home/charlz/dproofreaders/projects/$project/post.zip");
        echo "Project has been uploaded. Back to <a href=\"post_proofers.php\">Post-Processing</a>.";
    } else echo "No file was submitted. Please e-mail the project manager with your file. Back to <a href=\"post_proofers.php\">Post-Processing</a>.";
}
?>
