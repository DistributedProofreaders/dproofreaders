<?php
// This file allows us (PGDP) to use DairikiDiff.php from WikiMedia
// without any content modification, which should make it easier
// to upgrade when WikiMedia publishes a new version of the file.
//
// When upgrading the version of DairikiDiff.php, ensure that the
// stylesheet doesn't also need to be upgraded.
//
// We are pulling content out of the MediaWiki DifferenceEngine class
// and including it here instead of inheriting from it because starting
// in MediaWiki 1.20 the DifferenceEngine class begins being inherited
// from other classes. By version 1.21 the class is so intertwined with
// dependencies that it became unwieldy. We don't need most of what
// the DifferenceEngine class does, so we pull the relevant bits out
// here. This means that when updating the diff code from MediaWiki one
// needs to look at the contents of several DifferenceEngine methods
// and see if they need updating in the DifferenceEngineWrapper class.
//
// See 3rdparty/mediawiki/README.md for which MediaWiki version this maps to.
include_once($relPath."misc.inc"); // html_safe() in following includes
include_once($relPath."prefs_options.inc"); // get_user_proofreading_font()
include_once($relPath."3rdparty/mediawiki/ComplexityException.php");
include_once($relPath."3rdparty/mediawiki/WordAccumulator.php");
include_once($relPath."3rdparty/mediawiki/DairikiDiff.php");
include_once($relPath."3rdparty/mediawiki/WordLevelDiff.php");
include_once($relPath."3rdparty/mediawiki/DiffEngine.php");
include_once($relPath."3rdparty/mediawiki/DiffFormatter.php");
include_once($relPath."3rdparty/mediawiki/TableDiffFormatter.php");

class DifferenceEngineWrapper {
    private $oldText;
    private $newText;

    public function setText($oldText, $newText) {
        $this->oldText = $oldText;
        $this->newText = $newText;
    }

    public function showDiff($oldTitle, $newTitle) {
        $otext = $this->oldText;
        $ntext = $this->newText;

        // from DifferenceEngine::getDiffBody()
        $difftext = $this->generateTextDiffBody($otext, $ntext);
        $difftext = $this->localiseLineNumbers($difftext);

        // from DifferenceEngine::getDiff()
        echo $this->addHeader($difftext, $oldTitle, $newTitle);
    }

    private function generateTextDiffBody($otext, $ntext) {
        // lifted from DifferenceEngine::generateTextDiffBody()
        global $wgContLang;

        $otext = str_replace( "\r\n", "\n", $otext );
        $ntext = str_replace( "\r\n", "\n", $ntext );

        // ripped out the $wgExternalDiffEngine code

        # Native PHP diff
        $ota = explode( "\n", $wgContLang->segmentForDiff( $otext ) );
        $nta = explode( "\n", $wgContLang->segmentForDiff( $ntext ) );
        $diffs = new Diff( $ota, $nta );
        $formatter = new TableDiffFormatter();
        $difftext = $wgContLang->unsegmentForDiff( $formatter->format( $diffs ) );

        return $difftext;
    }

    private function addHeader($diff, $otitle, $ntitle, $multi='', $notice='') {
        // lifted from DifferenceEngine::addHeader()
        // but without any of the $userLang bits

        $tableClass = 'diff';
        $header = "<table class='$tableClass'>";

        if ( !$diff && !$otitle ) {
            $header .= "
            <tr class='diff-title'>
            <td class='diff-ntitle'>{$ntitle}</td>
            </tr>";
            $multiColspan = 1;
        } else {
            if ( $diff ) { // Safari/Chrome show broken output if cols not used
                $header .= "
                <col class='diff-marker' />
                <col class='diff-content' />
                <col class='diff-marker' />
                <col class='diff-content' />";
                $colspan = 2;
                $multiColspan = 4;
            } else {
                $colspan = 1;
                $multiColspan = 2;
            }
            $header .= "
            <tr class='diff-title'>
            <td colspan='$colspan' class='diff-otitle'>{$otitle}</td>
            <td colspan='$colspan' class='diff-ntitle'>{$ntitle}</td>
            </tr>";
        }

        if ( $multi != '' ) {
            $header .= "<tr><td colspan='{$multiColspan}' class='diff-multi'>{$multi}</td></tr>";
        }
        if ( $notice != '' ) {
            $header .= "<tr><td colspan='{$multiColspan}' class='diff-notice'>{$notice}</td></tr>";
        }

        return $header . $diff . "</table>";
    }

    private function localiseLineNumbers( $text ) {
        // lifted from DifferenceEngine::localiseLineNumbers()
        return preg_replace_callback(
            '/<!--LINE (\d+)-->/',
            array( &$this, 'localiseLineNumbersCb' ),
            $text
        );
    }

    private function localiseLineNumbersCb($line_numbers) {
        // DP-specific function
        return sprintf(_("Line %d"), $line_numbers[1]);
    }
}

// stub classes and global instances
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

// MW code references wfDebug() in some places
if(!function_exists("wfDebug")) {
    function wfDebug($text, $dest='all', array $context=[]) {
        // don't log any debug messages
        return;
    }
}

// Return URL to main CSS file
function get_DifferenceEngine_css_files() {
    global $code_url;
    return array("$code_url/pinc/3rdparty/mediawiki/mediawiki.diff.styles.css");
}

// Override default css for DP customizations
function get_DifferenceEngine_css_data() {
    list( , $font_size, $font_family) = get_user_proofreading_font();
    if($font_size != '')
        $font_size = "font-size: $font_size;";

    return "
.diff-otitle,
.diff-ntitle {
    font-weight: bold;
}
.diff-marker,
.diff-addedline,
.diff-deletedline,
.diff-context {
    font-family: $font_family;
    $font_size
}
.diff-deletedline .diffchange,
.diff-addedline .diffchange {
    padding-top: 0;
}
";
}

// vim: sw=4 ts=4 expandtab
