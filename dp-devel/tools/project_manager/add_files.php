<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');

    $project = $_GET['project'];

    $sql = mysql_query("SELECT username FROM projects WHERE projectid = '$project' LIMIT 1");
    $username = mysql_result($sql, 0, "username");

    $sql = mysql_query("SELECT sitemanager FROM users WHERE username = '$pguser' LIMIT 1");
    $sitemanager = mysql_result($sql, 0, "sitemanager");

    if (($sitemanager != 'yes') && ($pguser != $username)) {
        echo "<P>You are not allowed to change this project. If this message is an error, contact the <a href=\"mailto:$site_manager_email_addr\">site manager</a>.";
        echo "<P>Back to <a href=\"projectmgr.php\">project manager</a> page.";
    } else {
        if ($sitemanager == 'yes') {
            $string = "perl add_files.pl $project $projects_dir/";
        } else $string = "perl add_files.pl $project /home/dpscans/";
        exec($string);
        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=projectmgr.php?project=$project\"></head><body></body></html>";
    }
?>

