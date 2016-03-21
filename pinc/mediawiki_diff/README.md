Files in this directory are lifted from MediaWiki 1.26.2 (f46552)
and support PHP 5.3 through 5.6.

They are sourced from the following directories in the mediawiki git repo:
    mediawiki/includes/diff
        DairikiDiff.php
        DifferenceEngine.php
    mediawiki/resources/src/mediawiki.action
        mediawiki.action.history.diff.css

The only modification made was to TableDiffFormatter::deletedLine() in
TableDiffFormatter.php to change the UTF-8 endash to the ASCII hyphen-minus.

To use them, see ../DifferenceEngineWrapper.inc

To update the files from MediaWiki:
1. Check out MediaWiki code from git
  `git clone https://gerrit.wikimedia.org/r/p/mediawiki/core.git mediawiki`
2. Show list of release tags
  `git tags`
3. Change to the release you want to use
  `git checkout <tag_name>`
4. Copy updated files from locations mentioned above to
   dproofreaders/pinc/mediawiki_diff
5. Update TableDiffFormatter.php::deletedLine() to change the endash to
   the ASCII hyphen-minus (unless the code has already been fully converted
   to UTF-8).
5. Compare the methods in DifferenceEngineWrapper and DifferenceEngine to
   see if any of those in the latter were changed, and then reflect those
   changes in the methods in the former.
6. Update README.md (this file) with the new version information
7. Commit the changes
