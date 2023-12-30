<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');

$content = get_enumerated_param($_GET, 'content', 'posted', ['posted', 'postprocessing', 'proofing', 'smoothreading']); // Which feed the user wants
// Time in seconds for how often the feeds get refreshed
// Disable delay if we are on the test server
$refreshDelay = $testing ? 0 : 30 * 60;
$refreshAge = time() - $refreshDelay; // How long ago $refreshDelay was in UNIX time
$limit = 20; // Number of rows we query from the table, number of items in RSS feed

// Determine if we should display a 0.91 compliant RSS feed or our own feed
$intlang = get_desired_language();
if (isset($_GET['type'])) {
    $xmlfile = "$xmlfeeds_dir/{$content}_rss.$intlang.xml";
} else {
    $xmlfile = "$xmlfeeds_dir/{$content}.$intlang.xml";
}

// If the file does not exist or is stale, let's (re)create it
if (!file_exists($xmlfile) || filemtime($xmlfile) < $refreshAge) {
    $relPath = "./../pinc/";
    include_once($relPath.'pg.inc');
    include_once($relPath.'project_states.inc');

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
                <links>";
                if ($row['postednum']) {
                    $data .= "<PG_catalog>".xmlencode(get_pg_catalog_url_for_etext($row['postednum']))."</PG_catalog>";
                }
                $data .= "<library>".xmlencode("$code_url/project.php?id=".$row['projectid'])."</library>
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
                <link>".xmlencode($link)."</link>
                <description>".xmlencode($desc)."</description>
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

    $file = fopen($xmlfile, "w");
    fwrite($file, $xmlpage);
    $file = fclose($file);
}

// If we're here, the file exists and is fresh, output it

$fileModifiedTime = filemtime($xmlfile);
$secondsOfFreshnessRemaining = $fileModifiedTime + $refreshDelay - time();

// Let the browser cache it until the local cache becomes stale
header("Content-Type: text/xml; charset=$charset");
header("Expires: " . gmdate("D, d M Y H:i:s", $fileModifiedTime + $refreshDelay) . " GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s", $fileModifiedTime) . " GMT");
header("Cache-Control: max-age=$secondsOfFreshnessRemaining, public, must-revalidate");

readfile($xmlfile);
