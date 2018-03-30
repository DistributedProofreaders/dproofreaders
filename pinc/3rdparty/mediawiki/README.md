# MediaWiki

Files in this directory are lifted from MediaWiki 1.26.2 (f46552)
and support PHP 5.3 through 5.6.

They are sourced from the following directories in the mediawiki git repo:
    mediawiki/includes/diff
        DairikiDiff.php
        DifferenceEngine.php
    mediawiki/resources/src/mediawiki.action
        mediawiki.action.history.diff.css

## Modifications

The following modifications were made to the files because the DP code does
not support UTF-8:
* DairikiDiff.php:
  * Instances of htmlspecialchars() changed to html_safe().
* TableDiffFormatter.php:
  * TableDiffFormatter::deletedLine() changed the UTF-8 endash to the
    ASCII hyphen-minus.
  * Instances of htmlspecialchars() changed to html_safe().

To use them, see ../DifferenceEngineWrapper.inc

## Updating

To update the files from MediaWiki:
1. Check out MediaWiki code from git
  `git clone https://gerrit.wikimedia.org/r/p/mediawiki/core.git mediawiki`
2. Show list of release tags
  `git tags`
3. Change to the release you want to use
  `git checkout <tag_name>`
4. Copy updated files from locations mentioned above to this directory
5. Unless the code has been fully converted to UTF-8:
   1. Update TableDiffFormatter.php::deletedLine() to change the endash to
      the ASCII hyphen-minus.
   2. Change all instances of htmlspecialchars() to html_safe().
6. Compare the methods in DifferenceEngineWrapper and DifferenceEngine to
   see if any of those in the latter were changed, and then reflect those
   changes in the methods in the former.
7. Update README.md (this file) with the new version information and
   changes made.
8. Commit the changes
