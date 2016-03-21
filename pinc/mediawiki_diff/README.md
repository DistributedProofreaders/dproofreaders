Files in this directory are lifted from MediaWiki 1.23.13 (8a017f)
and support PHP 5.

They are sourced from the following directories in the mediawiki git repo:
    mediawiki/includes/diff
        DairikiDiff.php
        DifferenceEngine.php
    mediawiki/resources/src/mediawiki.action
        mediawiki.action.history.diff.css

The only modification made was to TableDiffFormatter::deletedLine() in
TableDiffFormatter.php to change the UTF-8 endash to the ASCII hyphen-minus.

To use them, see ../DifferenceEngineWrapper.inc
