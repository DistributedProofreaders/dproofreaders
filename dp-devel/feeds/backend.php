<?
$relPath="./../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'xml.inc');

//Try our best to make sure no browser caches the page
header("Content-Type: text/xml");
header("Expires: Sat, 1 Jan 2000 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$content = $_GET['content']; //Which feed the user wants
$refreshdelay = 30; //Time in minutes for how often the feeds get refreshed
$refreshdelay = time()-($refreshdelay*60); //Find out how long ago $refreshdelay was in UNIX time

//If the user did not specify a xml feed set posted as the default
if (($content != "posted") & ($content != "proofing") & ($content != "news")) { $content = "posted"; }

//Determine if we should display a 0.91 compliant RSS feed or our own feed
if (isset($_GET['type'])) { $xmlfile = $xmlfeeds_dir."/".$content."_rss.xml"; } else { $xmlfile = $xmlfeeds_dir."/".$content.".xml"; }

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
include($relPath.'v_site.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'project_states.inc');
$db_Connection=new dbConnect();

	if ($content == "posted") {
	$result = mysql_query("SELECT * FROM projects WHERE state='".PROJ_SUBMIT_PG_POSTED."' ORDER BY modifieddate DESC LIMIT 10");
		while ($row = mysql_fetch_array($result)) {
		$posteddate = date("r",($row['modifieddate']));
			if (isset($_GET['type'])) {
				$data .= "<item>
				<title>".xmlencode($row['nameofwork'])." - ".xmlencode($row['authorsname'])."</title>
				<link>$code_url/list_etexts.php?x=g".xmlencode("&")."sort=5#".$row['projectid']."</link>
				<description>Language: ".xmlencode($row['language'])." - Genre: ".xmlencode($row['genre'])."</description>
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
				<library>$code_url/list_etexts.php?x=g".xmlencode("&")."sort=5#".$row['projectid']."</library>
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
				<title>Distributed Proofreaders - Latest Releases</title>
				<link>$code_url</link>
				<description>The latest releases from Distributed Proofreaders posted to Project Gutenberg</description>
				<language>en-us</language>
				<webMaster>$site_manager_email_addr</webMaster>
				<pubDate>$lastupdated</pubDate>
				<lastBuildDate>$lastupdated</lastBuildDate>
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
	$result = mysql_query("SELECT * FROM news_items ORDER BY date_posted DESC LIMIT 10");
		while ($news_item = mysql_fetch_array($result)) {
		$posteddate = date("l, F jS, Y",($news_item['date_posted']));
				$data .= "<item>
				<title>Distributed Proofreaders News Update for $posteddate</title>
				<link>$code_url/pastnews.php?#".$news_item['uid']."</link>
				<description>".xmlencode(strip_tags($news_item['content']))."</description>
				</item>
				";
		}
		$lastupdated = date("r");
				$xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"$charset\" ?".">
				<!DOCTYPE rss SYSTEM \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">
				<rss version=\"0.91\">
				<channel>
				<title>Distributed Proofreaders - Latest Releases</title>
				<link>$code_url</link>
				<description>The latest news related to Distributed Proofreaders</description>
				<language>en-us</language>
				<webMaster>$site_manager_email_addr</webMaster>
				<pubDate>$lastupdated</pubDate>
				<lastBuildDate>$lastupdated</lastBuildDate>
				$data
				</channel>
				</rss>";
	}

$file = fopen($xmlfile,"w");
fwrite($file,$xmlpage);
$file = fclose($file);
readfile($xmlfile);
}
?>
