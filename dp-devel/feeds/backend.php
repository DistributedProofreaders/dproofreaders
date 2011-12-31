<?php
$relPath="./../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'misc.inc');
include_once($relPath.'xml.inc');

//Try our best to make sure no browser caches the page
header("Content-Type: text/xml");
header("Expires: Sat, 1 Jan 2000 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$content = get_enumerated_param($_GET, 'content', 'posted', array('posted', 'postprocessing', 'proofing', 'news')); //Which feed the user wants
$refreshdelay = 30; //Time in minutes for how often the feeds get refreshed
$refreshdelay = time()-($refreshdelay*60); //Find out how long ago $refreshdelay was in UNIX time

//Determine if we should display a 0.91 compliant RSS feed or our own feed
if (isset($_GET['type'])) {
    $xmlfile = $xmlfeeds_dir."/".$content."_rss.xml";
} else {
    $xmlfile = $xmlfeeds_dir."/".$content.".xml";
}

//If the file does not exist let's create it and then set the refresh delay to now so it updates
if (!file_exists($xmlfile)) {
    touch($xmlfile);
    $refreshdelay = time()+100;
}

//Determine if the feed needs to be updated.  If not display feed to user
if (filemtime($xmlfile) > $refreshdelay) {
    readfile($xmlfile);
} else {
    $relPath="./../pinc/";
    include($relPath.'site_vars.php');
    include($relPath.'pg.inc');
    include($relPath.'connect.inc');
    include($relPath.'project_states.inc');
    $db_Connection=new dbConnect();

    if ($content == "posted" || $content == "postprocessing" || $content == "proofing") {
        switch($content) {
            case "posted":
                $state=PROJ_SUBMIT_PG_POSTED;
                $x="g";
                break;
            case "postprocessing":
                $state=PROJ_POST_FIRST_AVAILABLE;
                $x="s";
                break;
            case "proofing":
                $state=PROJ_P1_AVAILABLE;
                $x="b";
                break;
        }
        $data = '';
        $result = mysql_query("SELECT * FROM projects WHERE state='$state' ORDER BY modifieddate DESC LIMIT 10");
        while ($row = mysql_fetch_array($result)) {
            $posteddate = date("r",($row['modifieddate']));
            if (isset($_GET['type'])) {
                $data .= "<item>
                <title>".xmlencode($row['nameofwork'])." - ".xmlencode($row['authorsname'])."</title>
                <link>$code_url/project.php?id=".$row['projectid']."</link>
                <description>" . sprintf(_("Language: %1\$s - Genre: %2\$s"), xmlencode($row['language']), xmlencode($row['genre'])) . "</description>
                </item>
                ";
            } else {
                $data .= "<project id=\"".$row['projectid']."\">
                <nameofwork>".xmlencode($row['nameofwork'])."</nameofwork>
                <authorsname>".xmlencode($row['authorsname'])."</authorsname>
                <language>".xmlencode($row['language'])."</language>
                <posteddate>".$posteddate."</posteddate>
                <genre>".xmlencode($row['genre'])."</genre>
                <links>
                <PG_catalog>".get_pg_catalog_url_for_etext($row['postednum'])."</PG_catalog>
                <library>$code_url/project.php?id=".$row['projectid']."</library>
                </links>
                </project>
                ";
            }
        }

        $lastupdated = date("r");
        if (isset($_GET['type'])) {
            $xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"$charset\" ?".">
                <!DOCTYPE rss SYSTEM \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">
                <rss version=\"0.91\">
                <channel>
                <title>".xmlencode($site_name)." - " . _("Latest Releases") . "</title>
                <link>".xmlencode($code_url)."</link>
                <description>" . sprintf( _("The latest releases posted to Project Gutenberg from %1\$s."), xmlencode($site_name)) . "</description>
                <language>" . _("en-us") . "</language>
                <webMaster>".xmlencode($site_manager_email_addr)."</webMaster>
                <pubDate>".xmlencode($lastupdated)."</pubDate>
                <lastBuildDate>".xmlencode($lastupdated)."</lastBuildDate>
                $data
                </channel>
                </rss>";
        } else {
            $xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"$charset\" ?".">
                <!-- Last Updated: $lastupdated -->
                <projects xmlns:xsi=\"http://www.w3.org/2000/10/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"projects.xsd\">
                $data
                </projects>";
        }
    }

    if ($content == "news") {
        $data = '';
        $result = mysql_query("SELECT * FROM news_items ORDER BY date_posted DESC LIMIT 10");
        while ($news_item = mysql_fetch_array($result)) {
            $posteddate = date("l, F jS, Y",($news_item['date_posted']));
            $data .= "<item>
                <title>" . sprintf( _("News Update for %1\$s."), xmlencode($posteddate)) . "</title>
    <description>" . sprintf( _("The latest news related to %1\$s."), xmlencode($site_name)) . "</description>
                <link>".xmlencode("$code_url/pastnews.php?#".$news_item['id'])."</link>
                <description>".xmlencode(strip_tags($news_item['content']))."</description>
                </item>
                ";
        }
        $lastupdated = date("r");
        $xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"$charset\" ?".">
                <!DOCTYPE rss SYSTEM \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">
                <rss version=\"0.91\">
                <channel>
                <title>".xmlencode($site_name) . " - " . _("Latest News") . "</title>
                <link>".xmlencode($code_url)."</link>
                <description>" . sprintf( _("The latest news related to %1\$s."), xmlencode($site_name)) . "</description>
                <language>en-us</language>
                <webMaster>".xmlencode($site_manager_email_addr)."</webMaster>
                <pubDate>".xmlencode($lastupdated)."</pubDate>
                <lastBuildDate>".xmlencode($lastupdated)."</lastBuildDate>
                $data
                </channel>
                </rss>";
    }

    $file = fopen($xmlfile,"w");
    fwrite($file,$xmlpage);
    $file = fclose($file);
    readfile($xmlfile);
}
// vim: sw=4 ts=4 expandtab
