<?php
// This file allows us (PGDP) to use DifferenceEngine.php from WikiMedia
// without any* content modification, which should make it easier
// to upgrade when WikiMedia publishes a new version of the file.
//
// When upgrading the version of DifferenceEngine.php, ensure that the
// stylesheet doesn't also need to be upgraded.
//
// See mediawiki_diff/README.md for which MediaWiki version these map to.
include_once($relPath."mediawiki_diff/DifferenceEngine.php");
include_once($relPath."mediawiki_diff/DairikiDiff.php");

// non-object global variables
$wgExternalDiffEngine = FALSE;

// DifferenceEngine derives from ContextSource
class ContextSource {
    function getTitle() {
        return new MWTitle();
    }
}

// wrapper function for the primary DifferenceEngine interface
class DifferenceEngineWrapper extends DifferenceEngine {
    function __construct() {
        parent::__construct();
    }

    function showDiff($left_label, $right_label, $notice='') {
        echo $this->getDiff($left_label, $right_label, $notice);
    }

    function getMultiNotice() {
        return '';
    }

    function localiseLineNumbersCb($line_numbers) {
        return sprintf(_("Line %d"), $line_numbers[1]);
    }
}

// stub functions
function wfRunHooks($event, $args = array()) {
    return TRUE;
}

function wfDebug($string) {
}

function wfProfileIn($string) {
}

function wfProfileOut($string) {
}

function wfIncrStats($string) {
}

// stub classes and global instances
class MWLang {
    function alignStart() {
        return 'left';
    }
}

class MWTitle {
    function getPageLanguage() {
        $lang = new MWLang();
        return $lang;
    }
}

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
        static function tags( $tagName, $className, $contents ) {
            return "<$tagName>$contents</$tagName>";
        }
    }
}

// Return URL to main CSS file
function get_DifferenceEngine_css_files() {
    global $code_url;
    return array("$code_url/pinc/mediawiki_diff/mediawiki.action.history.diff.css");
}

// Override default css for DP customizations
function get_DifferenceEngine_css_data() {
    return "
td.diff-otitle,
td.diff-ntitle {
    font-weight: bold;
}
td.diff-marker,
td.diff-addedline,
td.diff-deletedline,
td.diff-context {
    font-family: DPCustomMono2, monospace;
    font-size: smaller;
}
";
}

// vim: sw=4 ts=4 expandtab
?>
