<?php
$relPath = "pinc/";
include_once($relPath."base.inc");
include_once($relPath."project_states.inc");

// fixed, non-project pages to include in the listing with their
// change frequency
$fixed_pages = [
    "default.php" => "monthly",
];

header('Content-type: application/xml; charset=utf-8');

// see https://en.wikipedia.org/wiki/Sitemaps#File_format
echo <<<XML_HEADER
    <?xml version="1.0" encoding="utf-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    XML_HEADER;

// sitemaps are limited to 50k URLs, so we need to keep track of how many
// we output and stop when we get to that number
$MAX_URLS = 50000;
$url_count = 0;

foreach ($fixed_pages as $page => $frequency) {
    if ($url_count >= $MAX_URLS) {
        break;
    }

    $url = "$code_url/$page";
    $lastmod = date("Y-m-d", filemtime("$code_dir/$page"));
    echo <<<URL
            <url>
                <loc>$url</loc>
                <lastmod>$lastmod</lastmod>
                <changefreq>$frequency</changefreq>
                <priority>1.0</priority>
            </url>

        URL;
    $url_count += 1;
}

// now project pages

// Order the projects in the order they go through the system. This is only
// relevant if there are more than 50k of them, in which case we drop off
// projects on the tail end (deleted projects get dropped first, posted ones
// get dropped second, etc).
$order_by = sql_collater_for_project_state("state");
$sql = "
    SELECT projectid, modifieddate
    FROM projects
    ORDER BY $order_by
";
$result = DPDatabase::query($sql);
while ($row = mysqli_fetch_assoc($result)) {
    if ($url_count >= $MAX_URLS) {
        break;
    }

    // skip entries with no modifieddate
    if ($row["modifieddate"] == 0) {
        continue;
    }

    $url = "$code_url/project.php?id=" . $row["projectid"];
    $lastmod = date("Y-m-d", $row["modifieddate"]);

    echo <<<URL
            <url>
                <loc>$url</loc>
                <lastmod>$lastmod</lastmod>
                <priority>0.5</priority>
            </url>

        URL;
    $url_count += 1;
}

echo <<<XML_FOOTER
    </urlset>

    XML_FOOTER;
