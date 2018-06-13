# Manual Web Tests

Files in this directory are expected to be run manually via the web.

'page_compare_test.php' tests the functions to remove formatting (in PageUnformatter.inc) which are used in page_compare.php and diff.php. The purpose of removing formatting markup is to detect differences between P and F (or F1 and F2) rounds other than formatting; these could be either proofing corrections or inadvertent changes which are made in the F round. It is difficult to test using normal round pages because the test texts need to be in two different rounds. Two texts can be pasted into the upper two boxes in 'page_compare_test.php' and processed. The results are shown in the two lower boxes. The same processing is applied to each box. The use of the check boxes is described below.

## Types of formatting

No attempt is made to remove some formatting such as moving illustrations or sidenotes to different parts of the page. The following types of formatting can be removed:

* User notes. These are removed completely.

* Inline and out-of-line markup and [Illustration] are removed.

* Capital letter footnote anchors are replaced by *.

* For footnotes with a reference "[Footnote" and ":" and "]" are removed. If the reference is a letter it is replaced it by *. Any asterisk follwing the footnote is removed.

* For a continuation footnotes without a reference "[Footnote:" and "]" are removed, and any preceding of following asterisk. Similarly for sidenotes and illustrations with text.

* multiple spaces and newlines are dealt with in two different ways:

If "unwrap" is not checked then leading and trailing spaces on each line are removed, multiple spaces changed to a single space and blank lines removed. Formatting involving adding blank lines, adding spaces and indenting will be removed. This is what is done in diff.php when "remove formatting" is selected.

If "unwrap" is checked then multiple spaces and newlines are converted to a single space and then any space at the beginning or end is removed. Thus any formatting involving unwrapping lines will be undone in addition. This is what is done in page_compare.php.

The "Ignore case" checkbox is an experimental feature to explore whether ignoring case would be useful in connection with small caps markup. Since changing the text to the appropriate case is done in formatting rounds differences can show up which are just case chamges. This is not at present used in diff.php or page_compare.php.

Some texts are provided in this directory to test these features. The names begin with P or F: P_footnotes.txt and F_footnotes.txt test processing of fotnotes, P_poem1.txt should give no differences with F_poem1.txt, but P_poem2.txt will only give no differences with F_poem1.txt if unwrap is checked.
