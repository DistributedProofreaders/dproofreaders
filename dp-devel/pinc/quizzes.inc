<?
// This file creates quiz objects for each quiz with data in CVS
include_once($relPath."Quiz.inc");

$defined_quizzes = array('PQ');

$PQ = new Quiz(
 "PQ",
 "Proofreading Quiz",
 array('step1','step2','step3','step4','step5'),
 array()
);

# $PT = new Quiz(
#  "PT",
#  "Test Quiz",
#  array('step1','step3'),
#  array()
# );
?>
