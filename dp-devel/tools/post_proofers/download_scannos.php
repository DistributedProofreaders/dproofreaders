<?
// Convert Big Bill's list of stealth scannos into .rc files for PPing tools on-the-fly
// Bill's lists are in CVS in the format scanno\tscanno\r\n (two words per line, tab separated)
// Guiguts' .rc files should be in the format 'scanno' => 'scanno',\n
// enclosed in %scannoslist = ( );.

// Currently valid languages: eng, fr, ger
$lang = $_GET['language'];
// Currently valid types: common, rare, suspect
$flavour = $_GET['type'];

$filename = "stealth_scannos_".$lang."_".$flavour.".txt";

if (!file_exists($filename)) {
    $relPath="../pinc/";
    include($relPath.'v_site.inc');
    include($relPath.'theme.inc');

    theme(_("Download Stealth Scannos"),"header");
    echo "<h1>"._("Download Stealth Scannos")."</h1>
          <p>"._("The following scanno lists are available in .rc format:")."</p>
          <table border='1'>
          <tr><th>"._("Language")."</th><th></th><th></th><th></th></tr>
          <tr><td>"._("English")."</td><td><a href='$_SERVER[PHP_SELF]?language=eng&amp;type=common'>"._("Common")."</a></td>
                                       <td><a href='$_SERVER[PHP_SELF]?language=eng&amp;type=suspect'>"._("Suspect")."</a></td>
                                       <td><a href='$_SERVER[PHP_SELF]?language=eng&amp;type=rare'>"._("Rare")."</a></td></tr>
          <tr><td>"._("French")."</td><td><a href='$_SERVER[PHP_SELF]?language=fr&amp;type=common'>"._("Common")."</a></td>
                                       <td><a href='$_SERVER[PHP_SELF]?language=fr&amp;type=suspect'>"._("Suspect")."</a></td>
                                       <td><a href='$_SERVER[PHP_SELF]?language=fr&amp;type=rare'>"._("Rare")."</a></td></tr>
          <tr><td>"._("German")."</td><td><a href='$_SERVER[PHP_SELF]?language=ger&amp;type=common'>"._("Common")."</a></td>
                                       <td><a href='$_SERVER[PHP_SELF]?language=ger&amp;type=suspect'>"._("Suspect")."</a></td>
                                       <td><a href='$_SERVER[PHP_SELF]?language=ger&amp;type=rare'>"._("Rare")."</a></td></tr>
          </table>
          ";

    theme("","footer");
    exit();
}

$output = "%scannoslist = (\n";

$raw_scannos = fopen($filename,"r");
while (!feof($raw_scannos)) {
   $trans_scannos = fscanf($raw_scannos, "%[^\t]\t%[^\r\n]\n");
   if ($trans_scannos) {
     list($scanno1, $scanno2) = $trans_scannos;
     // Escape 's to avoid messing up the file
     $sc1 = str_replace("'", "\'", $scanno1);
     $sc2 = str_replace("'", "\'", $scanno2);
     $output .= "'$sc1' => '$sc2',\r\n";
   }
   $trans_scannos=NULL;
}
fclose($raw_scannos);

$output .= ");";


header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename=\"".$lang."_".$flavour.".rc\"");
header("Content-Length: ".strlen($output));

echo $output;
?>
