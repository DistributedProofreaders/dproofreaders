# Page Compare Test

'manual_page_compare_test.php' is a development tool to test the functions to remove formatting (in PageUnformatter.inc) which are used in page_compare.php and diff.php. The purpose of removing formatting markup is to detect differences between P and F (or F1 and F2) rounds other than formatting; these could be either proofing corrections or inadvertent changes which are made in the F round. It is difficult to test using normal round pages because the test texts need to be in two different rounds. Two texts can be pasted into the upper two boxes in 'page_compare_test.php' and processed. The results are shown in the two lower boxes. The same processing is applied to each box.

The "unwrap" check box controls the 2nd parameter of remove_formatting($text, $unwrap).

The "Ignore case" checkbox is an experimental feature to explore whether ignoring case would be useful in connection with small caps markup. Since changing the text to the appropriate case is done in formatting rounds differences can show up which are just case chamges. This is not at present used in diff.php or page_compare.php.
