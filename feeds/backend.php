<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // xmlencode()

$content = get_enumerated_param($_GET, 'content', 'posted', array('posted', 'postprocessing', 'proofing', 'smoothreading', 'news')); // Which feed the user wants
$refreshDelay = 30 * 60; // Time in seconds for how often the feeds get refreshed
$refreshAge = time()-$refreshDelay; // How long ago $refreshDelay was in UNIX time

// Determine if we should display a 0.91 compliant RSS feed or our own feed
$intlang = get_desired_language();
if (isset($_GET['type'])) {
    $xmlfile = "$xmlfeeds_dir/${content}_rss.$intlang.xml";
} else {
    $xmlfile = "$xmlfeeds_dir/${content}.$intlang.xml";
}

// If the file does not exist or is stale, let's (re)create it
if(!file_exists($xmlfile) || filemtime($xmlfile) < $refreshAge) {
    $relPath="./../pinc/";
    include_once($relPath.'pg.inc');
    include_once($relPath.'project_states.inc');

    $absolute_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
    $absolute_url .= $_SERVER['HTTP_HOST'];
    $absolute_url .= $_SERVER['REQUEST_URI'];
    $encoded_url = xmlencode($absolute_url);

    if ($content == "posted" || $content == "postprocessing" || $content == "proofing" || $content == "smoothreading") {
        switch($content) {
            case "posted":
                $condition = sprintf("state='%s'", PROJ_SUBMIT_PG_POSTED);
                break;
            case "postprocessing":
                $condition = sprintf("state='%s'", PROJ_POST_FIRST_AVAILABLE);
                break;
            case "proofing":
                $condition = sprintf("state='%s'", PROJ_P1_AVAILABLE);
                break;
            case "smoothreading":
                $condition = "
                    state = 'proj_post_first_checked_out' AND
                    smoothread_deadline > UNIX_TIMESTAMP()";
                break;
        }
        $data = '';
        $result = mysqli_query(DPDatabase::get_connection(), "SELECT * FROM projects WHERE $condition ORDER BY modifieddate DESC LIMIT 10");
        while ($row = mysqli_fetch_array($result)) {
            $posteddate = date("r",($row['modifieddate']));
            if (isset($_GET['type'])) {
                $data .= "<item>
                <title>".xmlencode($row['nameofwork'])." - ".xmlencode($row['authorsname'])."</title>
                <link>".xmlencode("$code_url/project.php?id=".$row['projectid'])."</link>
                <guid>".xmlencode("$code_url/project.php?id=".$row['projectid'])."</guid>
                <description>".xmlencode(sprintf(_("Language: %1\$s - Genre: %2\$s"), $row['language'], $row['genre']))."</description>
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
                <PG_catalog>".xmlencode(get_pg_catalog_url_for_etext($row['postednum']))."</PG_catalog>
                <library>".xmlencode("$code_url/project.php?id=".$row['projectid'])."</library>
                </links>
                </project>
                ";
            }
        }

        $lastupdated = date("r");
        if (isset($_GET['type'])) {
            $xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"$charset\" ?".">
                <rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
                <channel>
                <atom:link href=\"$encoded_url\" rel=\"self\" type=\"application/rss+xml\" />
                <title>".xmlencode($site_name)." - " . _("Latest Releases") . "</title>
                <link>".xmlencode($code_url)."</link>
                <description>".xmlencode(sprintf( _("The latest releases posted to Project Gutenberg from %1\$s."), $site_name))."</description>
                <webMaster>".xmlencode($site_manager_email_addr)." (" . xmlencode(_("Site Manager")) . ")</webMaster>
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
        $result = mysqli_query(DPDatabase::get_connection(), "SELECT * FROM news_items ORDER BY date_posted DESC LIMIT 10");
        while ($news_item = mysqli_fetch_array($result)) {
            $posteddate = date("l, F jS, Y",($news_item['date_posted']));
            $data .= "<item>
                <title>".xmlencode(sprintf( _("News Update for %1\$s."), $posteddate))."</title>
                <link>".xmlencode("$code_url/pastnews.php?#".$news_item['id'])."</link>
                <guid>".xmlencode("$code_url/pastnews.php?#".$news_item['id'])."</guid>
                <description>".xmlencode(strip_tags($news_item['content']))."</description>
                </item>
                ";
        }
        $lastupdated = date("r");
        $xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"$charset\" ?".">
                <rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
                <channel>
                <atom:link href=\"$encoded_url\" rel=\"self\" type=\"application/rss+xml\" />
                <title>".xmlencode($site_name) . " - " . _("Latest News") . "</title>
                <link>".xmlencode($code_url)."</link>
                <description>".xmlencode(sprintf( _("The latest news related to %1\$s."), $site_name))."</description>
                <webMaster>".xmlencode($site_manager_email_addr)." (" . xmlencode(_("Site Manager")) . ")</webMaster>
                <pubDate>".xmlencode($lastupdated)."</pubDate>
                <lastBuildDate>".xmlencode($lastupdated)."</lastBuildDate>
                $data
                </channel>
                </rss>";
    }

    $file = fopen($xmlfile,"w");
    fwrite($file,$xmlpage);
    $file = fclose($file);
}

// If we're here, the file exists and is fresh, output it

$fileModifiedTime=filemtime($xmlfile);
$secondsOfFreshnessRemaining=$fileModifiedTime + $refreshDelay - time();

// Let the browser cache it until the local cache becomes stale
header("Content-Type: text/xml; charset=$charset");
header("Expires: " . gmdate("D, d M Y H:i:s",$fileModifiedTime + $refreshDelay) . " GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s", $fileModifiedTime) . " GMT");
header("Cache-Control: max-age=$secondsOfFreshnessRemaining, public, must-revalidate");

readfile($xmlfile);

// vim: sw=4 ts=4 expandtab
