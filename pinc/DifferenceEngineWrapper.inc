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
include_once($relPath."3rdparty/mediawiki/ComplexityException.php");
include_once($relPath."3rdparty/mediawiki/WordAccumulator.php");
include_once($relPath."3rdparty/mediawiki/DairikiDiff.php");
include_once($relPath."3rdparty/mediawiki/WordLevelDiff.php");
include_once($relPath."3rdparty/mediawiki/DiffEngine.php");
include_once($relPath."3rdparty/mediawiki/DiffFormatter.php");

abstract class DifferenceEngineWrapper
{
    private $oldText;
    private $newText;
    private $formatter;

    protected function __construct(DiffFormatter $formatter = null)
    {
        if ($formatter === null) {
            $formatter = new DiffFormatter();
        }
        $this->formatter = $formatter;
    }

    public function setText($oldText, $newText)
    {
        $this->oldText = $oldText;
        $this->newText = $newText;
    }

    public function getDiff($oldTitle, $newTitle)
    {
        $otext = $this->oldText;
        $ntext = $this->newText;

        // from DifferenceEngine::getDiffBody()
        $difftext = $this->generateTextDiffBody($otext, $ntext);
        $difftext = $this->localiseLineNumbers($difftext);

        // from DifferenceEngine::getDiff()
        return $this->addHeader($difftext, $oldTitle, $newTitle);
    }

    private function generateTextDiffBody($otext, $ntext)
    {
        // lifted from DifferenceEngine::generateTextDiffBody()
        global $wgContLang;

        $otext = str_replace("\r\n", "\n", $otext);
        $ntext = str_replace("\r\n", "\n", $ntext);

        // ripped out the $wgExternalDiffEngine code

        // Native PHP diff
        $ota = explode("\n", $wgContLang->segmentForDiff($otext));
        $nta = explode("\n", $wgContLang->segmentForDiff($ntext));
        $diffs = new Diff($ota, $nta);
        $difftext = $wgContLang->unsegmentForDiff($this->formatter->format($diffs));

        return $difftext;
    }

    // Default implementation adds nothing
    protected function addHeader($diff, $otitle, $ntitle, $multi = '', $notice = '')
    {
        return $diff;
    }

    private function localiseLineNumbers($text)
    {
        // lifted from DifferenceEngine::localiseLineNumbers()
        return preg_replace_callback(
            '/<!--LINE (\d+)-->/',
            [&$this, 'localiseLineNumbersCb'],
            $text
        );
    }

    private function localiseLineNumbersCb($line_numbers)
    {
        // DP-specific function
        return sprintf(_("Line %d"), $line_numbers[1]);
    }
}

// stub classes and global instances
class ContLang
{
    public function segmentForDiff($string)
    {
        return $string;
    }

    public function unsegmentForDiff($string)
    {
        return $string;
    }
}

$wgContLang = new ContLang();

// DifferenceEngine uses the Xml::tags function
// so we will define a rough simulation to satisfy
// the requirements
if (!class_exists("Xml")) {
    class Xml
    {
        public static function tags($tagName, $className, $contents)
        {
            return "<$tagName>$contents</$tagName>";
        }
    }
}

// MW code references wfDebug() in some places
if (!function_exists("wfDebug")) {
    function wfDebug($text, $dest = 'all', array $context = [])
    {
        // don't log any debug messages
    }
}
