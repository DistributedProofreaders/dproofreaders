# MediaWiki

Files in this directory are lifted from MediaWiki 1.31.1 (a4c806563)
and support PHP 7.0 and up.

They are sourced from the following directories in the mediawiki git repo:
```
    mediawiki/includes/diff
        ComplexityException.php
        DairikiDiff.php
        DiffEngine.php
        DifferenceEngine.php
        DiffFormatter.php
        TableDiffFormatter.php
        WordAccumulator.php
        WordLevelDiff.php
    mediawiki/resources/src/mediawiki
        mediawiki.diff.styles.css
```

## Modifications

The following modifications were made to the files because the DP code does
not support UTF-8:
* WordAccumulator.php:
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
