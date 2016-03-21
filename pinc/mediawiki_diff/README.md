Files in this directory are lifted from MediaWiki 1.18.6 (04657f)
and support PHP 5.

They are sourced from the following directories in the mediawiki git repo:
    mediawiki/includes/diff
        DairikiDiff.php
        DifferenceEngine.php
    mediawiki/resources/mediawiki.action
        mediawiki.action.history.diff.css

The only modification made was to TableDiffFormatter::deletedLine() in
DairikiDiff.php to change the UTF-8 endash to the ASCII hyphen-minus.

To use them, see ../DifferenceEngineWrapper.inc
