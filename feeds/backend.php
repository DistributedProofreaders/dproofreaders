<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'pg.inc');

function generateRssFeed($content, $site_name, $code_url, $charset, $site_manager_email_addr)
{
    $limit = 20; // Number of rows we query from the table, number of items in RSS feed

    $absolute_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
    $absolute_url .= $_SERVER['HTTP_HOST'];
    $absolute_url .= $_SERVER['REQUEST_URI'];
    $encoded_url = xmlencode($absolute_url);

    if ($content == "posted" || $content == "postprocessing" || $content == "proofing" || $content == "smoothreading") {
        switch ($content) {
            case "posted":
                $condition = sprintf("state='%s'", PROJ_SUBMIT_PG_POSTED);
                $desc = sprintf(_("The latest releases posted to Project Gutenberg from %1\$s."), $site_name);
                $link = "$code_url/list_etexts.php?x=g";
                break;
            case "postprocessing":
                $condition = sprintf("state='%s'", PROJ_POST_FIRST_AVAILABLE);
                $desc = sprintf(_("The latest releases available at %1\$s for post-processing."), $site_name);
                $link = "$code_url/list_etexts.php?x=s";
                break;
            case "proofing":
                $condition = sprintf("state='%s'", PROJ_P1_AVAILABLE);
                $desc = sprintf(_("The latest releases available at %1\$s for proofreading."), $site_name);
                $link = "$code_url/list_etexts.php?x=b";
                break;
            case "smoothreading":
                // Query for SR projects which have been moved into SR in the last 30 days (30 days * 24 hours * 60 minutes * 60 seconds)
                $query = "
                    SELECT 
                        projectid, nameofwork, authorsname, genre, language, postednum, e.timestamp AS modifieddate
                    FROM projects
                    JOIN project_events e USING (projectid)
                    WHERE
                        e.event_type = 'smooth-reading' AND
                        e.details1 = 'text available' AND
                        e.timestamp > UNIX_TIMESTAMP() - (30*24*60*60)
                    ORDER BY e.timestamp DESC
                    LIMIT $limit
                ";
                $desc = sprintf(_("The latest releases available at %1\$s for Smooth Reading."), $site_name);
                $link = "$code_url/tools/post_proofers/smooth_reading.php";
                break;
        }

        // If $query is not set, use the default query.
        if (!isset($query)) {
            $query = "
                SELECT * 
                FROM projects 
                WHERE $condition 
                ORDER BY modifieddate DESC 
                LIMIT $limit
            ";
        }

        $data = '';
        $result = DPDatabase::query($query);
        while ($row = mysqli_fetch_array($result)) {
            $posteddate = date("r", ($row['modifieddate']));
            $data .= "<item>
            <title>".xmlencode($row['nameofwork'])." - ".xmlencode($row['authorsname'])."</title>
            <link>".xmlencode("$code_url/project.php?id=".$row['projectid'])."</link>
            <guid>".xmlencode("$code_url/project.php?id=".$row['projectid'])."</guid>
            <description>".xmlencode(sprintf(_("Language: %1\$s - Genre: %2\$s"), $row['language'], $row['genre']))."</description>
            </item>
            ";
        }
    
        $lastupdated = date("r");
        $rssfeed = "<"."?"."xml version=\"1.0\" encoding=\"$charset\" ?".">
            <rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
            <channel>
            <atom:link href=\"$encoded_url\" rel=\"self\" type=\"application/rss+xml\" />
            <title>".xmlencode($site_name)." - " . _("Latest Releases") . "</title>
            <link>".xmlencode($link)."</link>
            <description>".xmlencode($desc)."</description>
            <webMaster>".xmlencode($site_manager_email_addr)." (" . xmlencode(_("Site Manager")) . ")</webMaster>
            <pubDate>".xmlencode($lastupdated)."</pubDate>
            <lastBuildDate>".xmlencode($lastupdated)."</lastBuildDate>
            $data
            </channel>
            </rss>";
    }
    return $rssfeed;
}

$content = get_enumerated_param($_GET, 'content', 'posted', ['posted', 'postprocessing', 'proofing', 'smoothreading']); // Which feed the user wants
$rssfeed = generateRssFeed($content, $site_name, $code_url, $charset, $site_manager_email_addr);

// Let the browser cache it for $cache_duration seconds
$cache_duration = 30 * 60;
$now = time();
header("Content-Type: text/xml; charset=$charset");
header("Expires: " . gmdate("D, d M Y H:i:s", $now + $cache_duration) . " GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s", $now) . " GMT");
header("Cache-Control: max-age=$cache_duration, public, must-revalidate");

echo $rssfeed;
