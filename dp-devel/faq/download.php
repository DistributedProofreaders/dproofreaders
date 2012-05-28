<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // undo_all_magic_quotes()

undo_all_magic_quotes();

include_once($dynstats_dir."/faq_data.inc");

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
// vim: sw=4 ts=4 expandtab
