<?

// This is an ad hoc file for testing things on the server,
// for developers who don't have shell accounts on it.

echo "include_path = "
ini_get('include_path');
echo "<BR>\n";

ini_set('include_path', './pinc');

include('v_site.inc');
echo "site_dir = $site_dir<BR>\n";

include('txt/v_ereg_latin1.inc');
echo "regLatin1 = $regLatin1<BR>\n";

echo "<pre>\n";
include('txt/readme.txt');
echo "</pre>\n";

?>
