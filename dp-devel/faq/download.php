<?
$relPath="./../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');

include($dynstats_dir."/faq_data.inc");

if(isset($_GET['id'])) {
	if($faq_data[$_GET['id']]) {
		$f=$code_dir."/faq/".$faq_data[$_GET['id']];

		header("Content-Type: text/x-php; charset=$charset");
		header("Content-Length: ".filesize($f));
		header("Content-Disposition: attachment; filename=".basename($f));

		readfile($f);
	}
} else {
	theme(_("Download FAQs"),"header");

	echo "<center><h1>"._("Download FAQs")."</h1></center>\n";

	echo "<ul>\n";
	foreach($faq_data as $k=>$v) {
		echo "<li><a href='download.php?id=$k'>$v</a></li>\n";
	}
	echo "</ul>\n";

	theme("","footer");
}
?>
