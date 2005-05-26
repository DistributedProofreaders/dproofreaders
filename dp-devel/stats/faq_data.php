<?
$relPath="./../pinc/";
include_once($relPath.'v_site.inc');

$faq_file=array(
	"ProoferFAQ.php"=>TRUE,
	"doc-copy.php"=>TRUE,
	"doc-ency.php"=>TRUE,
	"doc-poet.php"=>TRUE,
	"document.php"=>TRUE,
	"faq_central.php"=>TRUE,
	"pm-faq.php"=>TRUE,
	"post_proof.php"=>TRUE,
	"ppv.php"=>TRUE,
	"privacy.php"=>TRUE,
	"scanning.php"=>TRUE,
	"cp.php"=>TRUE,
);

if($f=fopen("$dynstats_dir/faq_data.inc","w")) {
	fwrite($f,"<?\n\$faq_data=array(\n");
	read_dir("");
	fwrite($f,");\n?>");

	fclose($f);
} else {
	echo "File could not be opened for writing!";
}

function read_dir($a)
{
	global $f,$faq_file,$code_dir;

	$d=opendir("$code_dir/faq/$a");

	while(($n=readdir($d))!==FALSE) {
		if(preg_match("/^([a-z][a-z])$/",$n)) {
			read_dir("$a/$n");
		} else if($faq_file[$n] && filesize("$code_dir/faq/$a/$n")>1024) {
			fwrite($f,"'".substr("$a/",1).$n."',\n");
		}
	}

	closedir($d);
}

?>
