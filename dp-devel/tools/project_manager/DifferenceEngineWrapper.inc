<?
// This file allows us (PGDP) to use DifferenceEngine.php from WikiMedia
// without any* content modification, which should make it easier
// to upgrade when WikiMedia publishes a new version of the file.
//
// When upgrading the version of DifferenceEngine.php, ensure that the
// stylesheet doesn't also need to be upgraded.
//
// Note that the WikiMedia version of the file has a .php extension
// whereas the PGDP version has an .inc extension.

// * This file was originally designed to work with DifferenceEngine.php
// rev 33518 but that version requires PHP 5 and we rolled back to
// rev 15532 as the current codebase should work on PHP 4.
// When minimum level of PHP is brought back to 5 for the PGDP codebase,
// rev 33518, and possibly later, can be used. Until then
// DifferenceEngine.inc has to be updated and the include("Revisin.inc")
// commented out.

// DifferenceEngine lifted from WikiMedia:
//   mediawiki/trunk/phase3/includes/DifferenceEngine.php rev 15532
include_once("DifferenceEngine.inc");

// non-object global variables
$wgExternalDiffEngine = FALSE;

// wrapper function for the primary DifferenceEngine interface
class DifferenceEngineWrapper extends DifferenceEngine {
    function __construct() {
        parent::__construct();
    }

    function showDiff($L_text, $R_text, $L_label, $R_label) {
        $this->setText($L_text, $R_text);
        parent::showDiff($L_label, $R_label);
    }

    function getMultiNotice() {
        return '';
    }

    function localiseLineNumbersCb($line_numbers) {
        return sprintf(_("Line %d"), $line_numbers[1]);
    }
}

// stub functions
function wfDebug($string) {
}

function wfProfileIn($string) {
}

function wfProfileOut($string) {
}

function wfIncrStats($string) {
}

// stub classes and global instances
class OutputPage {
    function addHTML($text) {
        echo $text;
    }

    function addWikiMsg($text) {
    }

    function addStyle($text) {
    }

    function addScript($text) {
    }
}

$wgOut = new Outputpage();

class ContLang {
    function segmentForDiff($string) {
        return $string;
    }

    function unsegmentForDiff($string) {
        return $string;
    }
}

$wgContLang = new ContLang;

// DifferenceEngine uses the Xml::tags function
// so we will define a rough simulation to satisfy
// the requirements
if(!class_exists("Xml")) {
    class Xml {
        function tags( $tagName, $className, $contents ) {
            return "<$tagName>$contents</$tagName>";
        }
    }
}

// stylesheet lifted from WikiMedia:
//   mediawiki/trunk/phase3/skins/common/diff.css rev 33518
// and customized for PGDP
function get_DifferenceEngine_stylesheet() {
    return "
table.diff, td.diff-otitle, td.diff-ntitle {
    background-color: white;
}
td.diff-otitle,
td.diff-ntitle {
    text-align: center;
    /* added for PGDP */
    font-weight: bold;
}
td.diff-marker {
    text-align: right;
}
.rtl td.diff-marker {
    text-align: left;
}
td.diff-lineno {
    font-weight: bold;
}
td.diff-addedline {
    background: #cfc;
}
td.diff-deletedline {
    background: #ffa;
}
td.diff-context {
    background: #eee;
}
/* added for PGDP */
td.diff-marker,
td.diff-addedline,
td.diff-deletedline,
td.diff-context {
    font-family: DPCustomMono2, monospace;
    font-size: smaller;
}
.diffchange {
    color: red;
    font-weight: bold;
    text-decoration: none;
    white-space: pre-wrap;
    white-space: -moz-pre-wrap;
}
.diffchange-inline {
    border: 1px dotted red;
}
table.diff {
    border: none;
    border-spacing: 4px;
}
table.diff td {
    padding: 0;
}
table.diff col.diff-marker {
}
table.diff col.diff-content {
}
table.diff td div {
    /* Force-wrap very long lines such as URLs or page-widening char strings.
       CSS 3 draft..., but Gecko doesn't support it yet:
       https://bugzilla.mozilla.org/show_bug.cgi?id=99457 */
    word-wrap: break-word;
    
    /* As fallback, scrollbars will be added for very wide cells
       instead of text overflowing or widening */
    overflow: auto;
    
    /* The above rule breaks on very old versions of Mozilla due
       to a bug which collapses the table cells to a single line.
       
       In Mozilla 1.1 and below with JavaScript enabled, the rule
       will be overridden with this by diff.js; wide cell contents
       then spill horizontally without widening the rest of the
       table: */
    /* overflow: visible; */
}
";
}

// vim: sw=4 ts=4 expandtab
?>
