<?php
// Convert Big Bill's list of stealth scannos into .rc files for PPing tools on-the-fly
// Bill's lists are in CVS in the format scanno\tscanno\r\n (two words per line, tab separated)
// Guiguts' .rc files should be in the format 'scanno' => 'scanno',\n
// enclosed in %scannoslist = ( );.

$relPath = "../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param(), html_safe()

require_login();


$lang = get_enumerated_param($_GET, 'language', null, ['eng', 'es', 'fr', 'ger']);
$flavour = get_enumerated_param($_GET, 'type', null, ['common', 'suspect', 'rare']);
$filename = "$code_dir/faq/stealth_scannos_".$lang."_".$flavour.".txt";
$this_url = html_safe($_SERVER['PHP_SELF']);

if (!file_exists($filename)) {
    output_header(_("Download Stealth Scannos"));
    echo "<h1>"._("Download Stealth Scannos")."</h1>
          <p>"._("The following scanno lists are available in .rc format:")."</p>
          <table class='basic striped'>
          <tr><th>"._("Language")."</th><th></th><th></th><th></th></tr>
          <tr><td>"._("English")."</td><td><a href='$this_url?language=eng&amp;type=common'>"._("Common")."</a></td>
                                       <td><a href='$this_url?language=eng&amp;type=suspect'>"._("Suspect")."</a></td>
                                       <td><a href='$this_url?language=eng&amp;type=rare'>"._("Rare")."</a></td></tr>
          <tr><td>"._("French"). "</td><td><a href='$this_url?language=fr&amp;type=common'>"._("Common")."</a></td>
                                       <td><a href='$this_url?language=fr&amp;type=suspect'>"._("Suspect")."</a></td>
                                       <td><a href='$this_url?language=fr&amp;type=rare'>"._("Rare")."</a></td></tr>
          <tr><td>"._("German"). "</td><td><a href='$this_url?language=ger&amp;type=common'>"._("Common")."</a></td>
                                       <td><a href='$this_url?language=ger&amp;type=suspect'>"._("Suspect")."</a></td>
                                       <td><a href='$this_url?language=ger&amp;type=rare'>"._("Rare")."</a></td></tr>
          <tr><td>"._("Spanish")."</td><td><a href='$this_url?language=es&amp;type=common'>"._("Common")."</a></td>
                                       <td><!-- <a href='$this_url?language=es&amp;type=suspect'>"._("Suspect")."</a> --></td>
                                       <td><!-- <a href='$this_url?language=es&amp;type=rare'>"._("Rare")."</a></td> --></tr>
          </table>
          ";

    exit();
}

$output = "%scannoslist = (\n";

$raw_scannos = fopen($filename, "r");
while (!feof($raw_scannos)) {
    $trans_scannos = fscanf($raw_scannos, "%[^\t]\t%[^\r\n]\n");
    if ($trans_scannos) {
        [$scanno1, $scanno2] = $trans_scannos;
        // Escape 's to avoid messing up the file
        $sc1 = str_replace("'", "\'", $scanno1);
        $sc2 = str_replace("'", "\'", $scanno2);
        $output .= "'$sc1' => '$sc2',\r\n";
    }
    $trans_scannos = null;
}
fclose($raw_scannos);

$output .= ");";


header("Content-Type: text/plain; charset=$charset");
header("Content-Disposition: attachment; filename=\"".$lang."_".$flavour.".rc\"");
header("Content-Length: ".strlen($output));

echo $output;
