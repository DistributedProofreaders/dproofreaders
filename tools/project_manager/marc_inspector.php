<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

$rec = array_get($_REQUEST, 'rec', null);
if (!$rec) {
    throw new UnexpectedValueException("Unexpected rec `$rec`");
}

$record = unserialize(gzdecode(base64url_decode($rec)));

$title = _("MARC record inspector");
output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>\n";
echo "<a href='https://www.loc.gov/marc/bibliographic/'>MARC 21 Format for Bibliographic Data</a>\n";
echo "<p>\n";

$loc_bib = "https://www.loc.gov/marc/bibliographic";
// NB The tooltips below are taken verbatim from the titles in the MARC 21 specification,
// so we haven't bothered to translate them because the spec is only available in English.
$field_info = [
    "leader" => ["tooltip" => "Leader",  "url" => "$loc_bib/bdleader.html"],
    "001" => ["tooltip" => "Control Number", "url" => "$loc_bib/bd001.html"],
    "003" => ["tooltip" => "Control Number Identifier", "url" => "$loc_bib/bd003.html"],
    "005" => ["tooltip" => "Date and Time of Latest Transaction", "url" => "$loc_bib/bd005.html"],
    "006" => ["tooltip" => "Fixed-Length Data Elements - Additional Material Characteristics", "url" => "$loc_bib/bd006.html"],
    "007" => ["tooltip" => "Physical Description Fixed Field", "url" => "$loc_bib/bd007.html"],
    "008" => ["tooltip" => "Fixed-Length Data Elements-General Information", "url" => "$loc_bib/bd008.html"],
    "035" => ["tooltip" => "System Control Number", "url" => "$loc_bib/bd035.html"],
    "010" => ["tooltip" => "Library of Congress Control Number", "url" => "$loc_bib/bd010.html"],
    "013" => ["tooltip" => "Patent Control Information", "url" => "$loc_bib/bd013.html"],
    "015" => ["tooltip" => "National Bibliography Number", "url" => "$loc_bib/bd015.html"],
    "016" => ["tooltip" => "National Bibliographic Agency Control Number ", "url" => "$loc_bib/bd016.html"],
    "017" => ["tooltip" => "Copyright or Legal Deposit Number", "url" => "$loc_bib/bd017.html"],
    "018" => ["tooltip" => "Copyright Article-Fee Code", "url" => "$loc_bib/bd018.html"],
    "020" => ["tooltip" => "International Standard Book Number", "url" => "$loc_bib/bd020.html"],
    "024" => ["tooltip" => "Other Standard Identifier", "url" => "$loc_bib/bd024.html"],
    "028" => ["tooltip" => "Publisher or Distributor Number", "url" => "$loc_bib/bd028.html"],
    "037" => ["tooltip" => "Source of Acquisition", "url" => "$loc_bib/bd037.html"],
    "040" => ["tooltip" => "Cataloging Source", "url" => "$loc_bib/bd040.html"],
    "041" => ["tooltip" => "Language Code", "url" => "$loc_bib/bd041.html"],
    "042" => ["tooltip" => "Authentication Code", "url" => "$loc_bib/bd042.html"],
    "043" => ["tooltip" => "Geographic Area Code", "url" => "$loc_bib/bd043.html"],
    "046" => ["tooltip" => "Special Coded Dates", "url" => "$loc_bib/bd046.html"],
    "050" => ["tooltip" => "Library of Congress Call Number", "url" => "$loc_bib/bd050.html"],
    "082" => ["tooltip" => "Dewey Decimal Classification Number", "url" => "$loc_bib/bd082.html"],
    "100" => ["tooltip" => "Main Entry - Personal Name", "url" => "$loc_bib/bd100.html"],
    "110" => ["tooltip" => "Main Entry - Corporate Name", "url" => "$loc_bib/bd110.html"],
    "111" => ["tooltip" => "Main Entry - Meeting Name", "url" => "$loc_bib/bd111.html"],
    "130" => ["tooltip" => "Main Entry - Uniform Title", "url" => "$loc_bib/bd130.html"],
    "210" => ["tooltip" => "Abbreviated Title", "url" => "$loc_bib/bd210.html"],
    "222" => ["tooltip" => "Key Title", "url" => "$loc_bib/bd222.html"],
    "240" => ["tooltip" => "Uniform Title", "url" => "$loc_bib/bd240.html"],
    "242" => ["tooltip" => "Translation of Title by Cataloging Agency", "url" => "$loc_bib/bd242.html"],
    "243" => ["tooltip" => "Collective Uniform Title", "url" => "$loc_bib/bd243.html"],
    "245" => ["tooltip" => "Title Statement", "url" => "$loc_bib/bd245.html"],
    "246" => ["tooltip" => "Varying Form of Title", "url" => "$loc_bib/bd246.html"],
    "247" => ["tooltip" => "Former Title", "url" => "$loc_bib/bd247.html"],
    "250" => ["tooltip" => "Edition Statement", "url" => "$loc_bib/bd250.html"],
    "257" => ["tooltip" => "Edition Statement", "url" => "$loc_bib/bd250.html"],
    "260" => ["tooltip" => "Country of Producing Entity", "url" => "$loc_bib/bd257.html"],
    "264" => ["tooltip" => "Production, Publication, Distribution, Manufacture, and Copyright Notice", "url" => "$loc_bib/bd264.html"],
    "300" => ["tooltip" => "Physical Description", "url" => "$loc_bib/bd300.html"],
    "336" => ["tooltip" => "Content Type", "url" => "$loc_bib/bd336.html"],
    "337" => ["tooltip" => "Media Type", "url" => "$loc_bib/bd337.html"],
    "338" => ["tooltip" => "Carrier Type", "url" => "$loc_bib/bd338.html"],
    "340" => ["tooltip" => "Physical Medium", "url" => "$loc_bib/bd340.html"],
    "344" => ["tooltip" => "Sound Characteristics", "url" => "$loc_bib/bd344.html"],
    "347" => ["tooltip" => "Notated Music Characteristics", "url" => "$loc_bib/bd347.html"],
    "380" => ["tooltip" => "Form of Work", "url" => "$loc_bib/bd380.html"],
    "440" => ["tooltip" => "Series Statement/Added Entry-Title", "url" => "$loc_bib/bd440.html"],
    "490" => ["tooltip" => "Series Statement", "url" => "$loc_bib/bd490.html"],
    "500" => ["tooltip" => "General Note", "url" => "$loc_bib/bd500.html"],
    "533" => ["tooltip" => "Reproduction Note", "url" => "$loc_bib/bd533.html"],
    "504" => ["tooltip" => "Bibliography, Etc. Note", "url" => "$loc_bib/bd504.html"],
    "505" => ["tooltip" => "Formatted Contents Note", "url" => "$loc_bib/bd505.html"],
    "520" => ["tooltip" => "Summary, Etc.", "url" => "$loc_bib/bd520.html"],
    "530" => ["tooltip" => "Additional Physical Form available Note", "url" => "$loc_bib/bd530.html"],
    "538" => ["tooltip" => "System Details Note", "url" => "$loc_bib/bd538.html"],
    "600" => ["tooltip" => "Subject Added Entry - Personal Name", "url" => "$loc_bib/bd600.html"],
    "610" => ["tooltip" => "Subject Added Entry - Corporate Name", "url" => "$loc_bib/bd610.html"],
    "611" => ["tooltip" => "Subject Added Entry - Meeting Name", "url" => "$loc_bib/bd611.html"],
    "630" => ["tooltip" => "Subject Added Entry - Uniform Title", "url" => "$loc_bib/bd630.html"],
    "647" => ["tooltip" => "Subject Added Entry - Named Event", "url" => "$loc_bib/bd647.html"],
    "648" => ["tooltip" => "Subject Added Entry - Chronological Term", "url" => "$loc_bib/bd648.html"],
    "650" => ["tooltip" => "Subject Added Entry - Topical Term", "url" => "$loc_bib/bd650.html"],
    "651" => ["tooltip" => "Subject Added Entry - Geographic Name", "url" => "$loc_bib/bd651.html"],
    "653" => ["tooltip" => "Index Term - Uncontrolled", "url" => "$loc_bib/bd653.html"],
    "654" => ["tooltip" => "Subject Added Entry - Faceted Topical Terms", "url" => "$loc_bib/bd654.html"],
    "655" => ["tooltip" => "Index Term-Genre/Form", "url" => "$loc_bib/bd655.html"],
    "656" => ["tooltip" => "Index Term - Occupation", "url" => "$loc_bib/bd656.html"],
    "657" => ["tooltip" => "Index Term - Function", "url" => "$loc_bib/bd657.html"],
    "658" => ["tooltip" => "Index Term - Curriculum Objective", "url" => "$loc_bib/bd658.html"],
    "662" => ["tooltip" => "Subject Added Entry - Hierarchical Place Name", "url" => "$loc_bib/bd662.html"],
    "688" => ["tooltip" => "Subject Added Entry - Type of Entity Unspecified", "url" => "$loc_bib/bd688.html"],
    "700" => ["tooltip" => "Added Entry - Personal Name", "url" => "$loc_bib/bd700.html"],
    "710" => ["tooltip" => "Added Entry - Corporate Name", "url" => "$loc_bib/bd710.html"],
    "711" => ["tooltip" => "Added Entry - Meeting Name", "url" => "$loc_bib/bd711.html"],
    "720" => ["tooltip" => "Added Entry - Uncontrolled Name", "url" => "$loc_bib/bd720.html"],
    "730" => ["tooltip" => "Added Entry - Uniform Title", "url" => "$loc_bib/bd730.html"],
    "740" => ["tooltip" => "Added Entry - Uncontrolled Related/Analytical Title", "url" => "$loc_bib/bd740.html"],
    "800" => ["tooltip" => "Series Added Entry - Personal Name", "url" => "$loc_bib/bd800.html"],
    "856" => ["tooltip" => "Electronic Location and Access", "url" => "$loc_bib/bd856.html"],
];

echo "<table class='basic striped'>\n";
foreach ($record as $r) {
    if (count($r) == 1) {
        $r[] = "";
    }
    [$label, $value] = $r;
    if (preg_match('/^(\(3,)([^)]*)(\).*)$/', $label, $match)) {
        $field = $match[2];
        if (array_key_exists($field, $field_info)) {
            $info = $field_info[$field];
            $attr = " class='bold' title='" . attr_safe($info["tooltip"]) . "'";
            if (array_key_exists("url", $info)) {
                $match[2] = "<a href='{$info['url']}'{$attr}>{$field}</a>";
            } else { /** @phpstan-ignore-line */
                $match[2] = "<span {$attr}>{$field}</span>";
            }
            $label = $match[1] . $match[2] . $match[3];
        }
    }
    echo "<tr><td style='white-space:nowrap'>$label</td><td>$value</td></tr>\n";
}
echo "</table>\n";
