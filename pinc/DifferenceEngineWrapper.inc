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
    private string $oldText;
    private string $newText;
    private DiffFormatter $formatter;

    protected function __construct(?DiffFormatter $formatter = null)
    {
        if ($formatter === null) {
            $formatter = new DiffFormatter();
        }
        $this->formatter = $formatter;
    }

    public function setText(string $oldText, string $newText): void
    {
        $this->oldText = $oldText;
        $this->newText = $newText;
    }

    public function getDiff(string $oldTitle, string $newTitle): string
    {
        $otext = $this->oldText;
        $ntext = $this->newText;

        // from DifferenceEngine::getDiffBody()
        $difftext = $this->generateTextDiffBody($otext, $ntext);
        $difftext = $this->localiseLineNumbers($difftext);

        // from DifferenceEngine::getDiff()
        return $this->addHeader($difftext, $oldTitle, $newTitle);
    }

    private function generateTextDiffBody(string $otext, string $ntext): string
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
    protected function addHeader(string $diff, string $otitle, string $ntitle, string $multi = '', string $notice = ''): string
    {
        return $diff;
    }

    private function localiseLineNumbers(string $text): string
    {
        // lifted from DifferenceEngine::localiseLineNumbers()
        return preg_replace_callback(
            '/<!--LINE (\d+)-->/',
            fn ($match) => sprintf(_("Line %d"), $match[1]),
            $text
        );
    }
}

// stub classes and global instances
class ContLang
{
    public function segmentForDiff(string $string): string
    {
        return $string;
    }

    public function unsegmentForDiff(string $string): string
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
        public static function tags(string $tagName, ?string $className, string $contents): string
        {
            return "<$tagName>$contents</$tagName>";
        }
    }
}

// MW code references wfDebug() in some places
if (!function_exists("wfDebug")) {
    /** @param array<string, string> $context */
    function wfDebug(string $text, string $dest = 'all', array $context = []): void
    {
        // don't log any debug messages
    }
}
